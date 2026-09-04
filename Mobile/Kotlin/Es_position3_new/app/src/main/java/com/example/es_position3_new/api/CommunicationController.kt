package com.example.es_position3_new.api


import android.content.Context
import android.net.Uri
import android.util.Log
import com.example.es_position3_new.database.SIDManager
import com.example.es_position3_new.model.Location
import com.example.es_position3_new.model.Order
import com.example.es_position3_new.model.PostOrderRequest
import com.example.es_position3_new.model.User
import com.example.es_position3_new.model.UserResponse
import io.ktor.client.HttpClient
import io.ktor.client.call.body
import io.ktor.client.engine.android.Android
import io.ktor.client.plugins.ClientRequestException
import io.ktor.client.plugins.ResponseException
import io.ktor.client.plugins.ServerResponseException
import io.ktor.client.plugins.contentnegotiation.ContentNegotiation
import io.ktor.client.request.HttpRequestBuilder
import io.ktor.client.request.delete
import io.ktor.client.request.get
import io.ktor.client.request.post
import io.ktor.client.request.put
import io.ktor.client.request.setBody
import io.ktor.client.statement.HttpResponse
import io.ktor.client.statement.bodyAsText
import io.ktor.http.ContentType
import io.ktor.http.contentType
import io.ktor.serialization.kotlinx.json.json
import kotlinx.serialization.json.Json


object CommunicationController {
    private val BASE_URL = "https://develop.ewlab.di.unimi.it/mc/2425"
    var sid: String? = null
    var uid: Int? = null

    private val TAG = CommunicationController::class.simpleName

    private val client = HttpClient(Android) {
        install(ContentNegotiation) {
            json(Json {
                ignoreUnknownKeys = true
            })
        }
    }

    enum class HttpMethod {
        GET,
        POST,
        DELETE,
        PUT
    }

    suspend fun genericRequest(
        url: String, method: HttpMethod,
        queryParameters: Map<String, Any> = emptyMap(),
        requestBody: Any? = null
    ): HttpResponse {

        val urlUri = Uri.parse(url)
        val urlBuilder = urlUri.buildUpon()
        queryParameters.forEach { (key, value) ->
            urlBuilder.appendQueryParameter(key, value.toString())
        }
        val completeUrlString = urlBuilder.build().toString()
        Log.d(TAG, completeUrlString)

        val request: HttpRequestBuilder.() -> Unit = {
            requestBody?.let {
                contentType(ContentType.Application.Json)
                setBody(requestBody)
            }
        }

        return when (method) {
            HttpMethod.GET -> client.get(completeUrlString, request)
            HttpMethod.POST -> client.post(completeUrlString, request)
            HttpMethod.DELETE -> client.delete(completeUrlString, request)
            HttpMethod.PUT -> client.put(completeUrlString, request)
        }
    }

    suspend fun initializeSID(context: Context) {
        val sidManager = SIDManager(context)

        // Verifica se il SID è già salvato
        val savedSid = sidManager.getSID()
        val savedUid = sidManager.getUID()?.toInt()

        if (savedSid != null) {
            Log.d(TAG, "SID trovato: $savedSid")
            sid = savedSid
            uid = savedUid
        } else {
            Log.d(TAG, "SID non trovato, richiedo dal server...")
            try {
                val userResponse = createUser() // Richiesta al server per ottenere un nuovo SID
                if (userResponse != null) {
                    sid = userResponse.sid
                    uid = userResponse.uid
                }
                sidManager.saveSID(sid!!)
                sidManager.saveUID(uid!!.toString())
                Log.d(TAG, "SID salvato: $sid")
                Log.d(TAG, "UID salvato: $uid")
            } catch (e: Exception) {
                Log.e(TAG, "Errore durante la creazione del SID: ${e.message}")
            }
        }
    }

    suspend fun createUser(): UserResponse? {
        Log.d(TAG, "postUser called")

        val url = "$BASE_URL/user"

        return try {
            val httpResponse = genericRequest(url, HttpMethod.POST)

            Log.d(TAG, "HTTP Response Status: ${httpResponse.status.value}")
            Log.d(TAG, "HTTP Response Body: ${httpResponse.bodyAsText()}")

            // Parsing della risposta
            val result: UserResponse = httpResponse.body()
            Log.d(TAG, "Parsed UserResponse: $result")
            result
        } catch (e: Exception) {
            Log.e(TAG, "Error during getUser: ${e.message}")
            null
        }
    }

    suspend fun getUser(): User? {
        Log.d(TAG, "getUser called")

        val sid = sid // Recupera il SID
        val uid = uid
        if (sid == null) {
            Log.e(TAG, "SID is not set.")
            return null
        }

        val url = "$BASE_URL/user/$uid"
        val queryParams = mapOf("sid" to sid)

        return try {
            // Effettua la richiesta GET
            val httpResponse = genericRequest(url, HttpMethod.GET, queryParams)

            Log.d(TAG, "HTTP Response Status: ${httpResponse.status.value}")
            Log.d(TAG, "HTTP Response Body: ${httpResponse.bodyAsText()}")

            // Parsing della risposta
            val result: User = httpResponse.body()
            Log.d(TAG, "Parsed UserResponse: $result")
            result
        } catch (e: Exception) {
            Log.e(TAG, "Error during getUser: ${e.message}")
            null
        }
    }

    suspend fun putUser(): UserResponse? {
        val uid = uid
        Log.d(TAG, "putUser called for uid: $uid")

        val user = User(
            firstName = "Mario",
            lastName = "Rossi",
            cardFullName = "Mario Rossi",
            cardNumber = "1234567812345678",
            cardExpireMonth = 12,
            cardExpireYear = 25,
            cardCVV = "123",
            uid = uid!!,
            lastOid = null,
            orderStatus = null
        )

        val sid = sid // Recupera il SID
        if (sid == null) {
            Log.e(TAG, "SID is not set.")
            return null
        }

        val url = "$BASE_URL/user/$uid"
        val bodyParams = mapOf(
            "firstName" to user.firstName,
            "lastName" to user.lastName,
            "cardFullName" to user.cardFullName,
            "cardNumber" to user.cardNumber,
            "cardExpireMonth" to user.cardExpireMonth.toString(),
            "cardExpireYear" to user.cardExpireYear.toString(),
            "cardCVV" to user.cardCVV,
            "sid" to sid
        )

        return try {
            // Effettua la richiesta PUT
            val httpResponse = genericRequest(url, HttpMethod.PUT, requestBody = bodyParams)

            Log.d(TAG, "HTTP Response Status: ${httpResponse.status.value}")
            Log.d(TAG, "HTTP Response Body: ${httpResponse.bodyAsText()}")

            // Parsing della risposta
            val result: UserResponse = httpResponse.body()
            Log.d(TAG, "Parsed UserResponse: $result")
            result
        } catch (e: Exception) {
            Log.e(TAG, "Error during putUser: ${e.message}")
            null
        }
    }

    suspend fun postOrder() :Order?{
        Log.d(TAG, "postOrder called")

        val url = "$BASE_URL/menu/5/buy"
        var lat = 45.476007
        var lng = 9.231874
        val location = Location(lat, lng)
        val bodyParams = PostOrderRequest(
            sid = sid!!,
            deliveryLocation = location
        )

        return try {
            val httpResponse = genericRequest(url, HttpMethod.POST, requestBody = bodyParams)

            Log.d(TAG, "HTTP Response Status: ${httpResponse.status.value}")
            Log.d(TAG, "HTTP Response Body: ${httpResponse.bodyAsText()}")

            // Parsing della risposta
            val result: Order = httpResponse.body()
            Log.d(TAG, "Parsed UserResponse: $result")
            result
        } catch (e: Exception) {
            Log.e(TAG, "Error during postOrder: ${e.message}")
            null
        }
    }

    suspend fun getOrder(oid: Int) :Order?{
        Log.d(TAG, "getOrder called")


        val sid = sid // Recupera il SID
        if (sid == null) {
            Log.e(TAG, "SID is not set.")
            return null
        }

        val url = "$BASE_URL/order/$oid"
        val queryParams = mapOf("sid" to sid)

        return try {
            // Effettua la richiesta GET
            val httpResponse = genericRequest(url, HttpMethod.GET, queryParams)

            Log.d(TAG, "HTTP Response Status: ${httpResponse.status.value}")
            Log.d(TAG, "HTTP Response Body: ${httpResponse.bodyAsText()}")

            // Parsing della risposta
            val result: Order = httpResponse.body()
            Log.d(TAG, "Parsed OrderResponse: $result")
            result
        } catch (e: ClientRequestException) {
            // Gestisci errore 4xx
            Log.e(TAG, "Client error: ${e.message}")
            null
        } catch (e: ServerResponseException) {
            // Gestisci errore 5xx
            Log.e(TAG, "Server error: ${e.message}")
            null
        } catch (e: ResponseException) {
            // Gestisci altri tipi di errori di risposta
            Log.e(TAG, "Response error: ${e.message}")
            null
        }
    }
}
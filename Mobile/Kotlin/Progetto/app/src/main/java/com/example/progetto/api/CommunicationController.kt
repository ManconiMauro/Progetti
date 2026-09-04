package com.example.progetto.api

import android.content.Context
import android.net.Uri
import android.util.Log
import com.example.progetto.database.DatabaseHelper
import com.example.progetto.database.dao.MenuImageDao
import com.example.progetto.database.models.MenuImage
import com.example.progetto.model.*
import com.example.progetto.repository.SIDManager
import com.example.progetto.viewModel.ViewModel
import com.google.android.gms.location.LocationServices
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
        Log.d(TAG, "getUser called for uid: $uid")

        val sid = this.sid // Recupera il SID
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

    suspend fun getMenu(context: Context): List<Menu> {
        Log.d(TAG, "getMenu called")
        val fusedLocationClient = LocationServices.getFusedLocationProviderClient(context)
        val viewModel = ViewModel(context)

        val userLocation = viewModel.calculateLocation(fusedLocationClient)
        val lat = userLocation?.lat
        var lng = userLocation?.lng

        val sid = this.sid // Recupera il SID
        if (sid == null) {
            Log.e(TAG, "SID is not set.")
            return emptyList()
        }

        val url = "$BASE_URL/menu"
        val queryParams = mapOf(
            "lat" to (lat ?: 0.0), // Valore di fallback per lat
            "lng" to (lng ?: 0.0), // Valore di fallback per lng
            "sid" to (sid ?: "")  // Valore di fallback per sid
        )

        return try {
            // Effettua la richiesta GET
            val httpResponse = genericRequest(url, HttpMethod.GET, queryParams)

            Log.d(TAG, "HTTP Response Status: ${httpResponse.status.value}")
            Log.d(TAG, "HTTP Response Body: ${httpResponse.bodyAsText()}")

            // Parsing della risposta
            val result: List<Menu> = httpResponse.body()
            Log.d(TAG, "Parsed Menu List: $result")
            result
        } catch (e: Exception) {
            Log.e(TAG, "Error during getMenu: ${e.message}")
            Log.e(TAG, "Controlla la connessione a Internet e l'URL del server.")
            emptyList()
        }
    }

    suspend fun getMenuDetails(context: Context, mid: Int): MenuDetails? {
        Log.d(TAG, "getMenuDetails called")
        val fusedLocationClient = LocationServices.getFusedLocationProviderClient(context)
        val viewModel = ViewModel(context)

        val userLocation = viewModel.calculateLocation(fusedLocationClient)
        val lat = userLocation?.lat
        var lng = userLocation?.lng

        val sid = this.sid // Recupera il SID
        if (sid == null) {
            Log.e(TAG, "SID is not set.")
            return null
        }

        val url = "$BASE_URL/menu/$mid"
        val queryParams = mapOf(
            "lat" to (lat ?: 0.0), // Valore di fallback per lat
            "lng" to (lng ?: 0.0), // Valore di fallback per lng
            "sid" to (sid ?: "")  // Valore di fallback per sid
        )

        return try {
            // Effettua la richiesta GET
            val httpResponse = genericRequest(url, HttpMethod.GET, queryParams)

            Log.d(TAG, "HTTP Response Status: ${httpResponse.status.value}")
            Log.d(TAG, "HTTP Response Body: ${httpResponse.bodyAsText()}")

            // Parsing della risposta
            val result: MenuDetails = httpResponse.body()
            Log.d(TAG, "Parsed Menu List: $result")
            result
        } catch (e: Exception) {
            Log.e(TAG, "Error during getMenu: ${e.message}")
            Log.e(TAG, "Controlla la connessione a Internet e l'URL del server.")
            null
        }
    }

    suspend fun getImageAndSaveToDB(mid: Int, imageVersion: Int, context: Context): String? {
        val sid = sid // Recupera il SID
        if (sid == null) {
            Log.e(TAG, "SID is not set.")
            return null
        }

        val url = "$BASE_URL/menu/$mid/image"
        val queryParams = mapOf("sid" to sid)

        return try {
            // Effettua la richiesta GET per ottenere l'immagine
            val httpResponse = genericRequest(url, HttpMethod.GET, queryParams)

            // Parsing della risposta
            val response = httpResponse.body<Map<String, String>>()
            val base64Image = response["base64"]

            // Salva l'immagine nel database con l'`imageVersion`
            if (base64Image != null) {
                val menuImage = MenuImage(mid = mid, imageVersion = imageVersion, base64 = base64Image)
                val menuImageDao: MenuImageDao = DatabaseHelper.getDatabase(context).menuImageDao()
                menuImageDao.insertMenuImage(menuImage)  // Usa il metodo con REPLACE
                Log.d(TAG, "Immagine salvata nel database con mid: $mid e imageVersion: $imageVersion")
            }

            base64Image
        } catch (e: Exception) {
            Log.e(TAG, "Error during getImageAndSaveToDB: ${e.message}")
            null
        }
    }

    suspend fun putUser(user: UserToSave): String? {
        Log.d(TAG, "putUser called")

        val sid = sid // Recupera il SID
        val uid = uid
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

            // Verifica che il codice di stato sia tra 200 e 299 (incluso)
            if (httpResponse.status.value in 200..299) {
                return null
            } else {
                // Parsing della risposta se il corpo è presente
                val result: String = httpResponse.bodyAsText()
                Log.d(TAG, "Parsed UserResponse: $result")
                return result
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error during putUser: ${e.message}")
            return null
        }
    }

    suspend fun postOrder(mid: Int) :Order?{
        Log.d(TAG, "postOrder called")

        val url = "$BASE_URL/menu/$mid/buy"
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
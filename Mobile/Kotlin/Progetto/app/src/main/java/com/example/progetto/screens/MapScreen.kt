package com.example.progetto.screens

import android.content.Context
import android.util.Log
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.unit.dp
import com.example.progetto.R
import com.example.progetto.api.CommunicationController
import com.example.progetto.model.Order
import com.example.progetto.repository.RestaurantLocationDataStore
import com.example.progetto.repository.ScreenDataStore
import com.example.progetto.viewModel.ViewModel
import com.google.android.gms.location.LocationServices
import com.mapbox.geojson.Point
import com.mapbox.maps.extension.compose.MapEffect
import com.mapbox.maps.extension.compose.MapboxMap
import com.mapbox.maps.extension.compose.animation.viewport.rememberMapViewportState
import com.mapbox.maps.extension.compose.annotation.generated.PointAnnotation
import com.mapbox.maps.extension.compose.annotation.rememberIconImage
import com.mapbox.maps.plugin.locationcomponent.location
import kotlinx.coroutines.delay

@Composable
fun MapScreen() {
    val context = LocalContext.current
    val fusedLocationClient = remember { LocationServices.getFusedLocationProviderClient(context) }

    // Stati per memorizzare le posizioni
    var userPosition by remember { mutableStateOf<Point?>(null) }
    var dronePosition by remember { mutableStateOf<Point?>(null) }
    var restaurantPosition by remember { mutableStateOf<Point?>(null) } // ✅ Nuovo stato per il ristorante
    var showAlert by remember { mutableStateOf(false) }

    val mapViewportState = rememberMapViewportState {
        setCameraOptions {
            center(userPosition ?: Point.fromLngLat(45.476007, 9.231874))
            zoom(14.0)
        }
    }

    // LaunchedEffect per aggiornare la mappa quando userPosition cambia
    LaunchedEffect(userPosition) {
        userPosition?.let {
            mapViewportState.setCameraOptions {
                center(it)  // ✅ Centra la mappa sulla posizione dell'utente
                zoom(12.0)  // ✅ Regola lo zoom per una migliore visualizzazione
            }
            Log.d("MapScreen", "Mappa centrata su: ${it.latitude()}, ${it.longitude()}")
        }
    }

    LaunchedEffect(Unit) {
        Log.d("MapScreen", "LaunchedEffect eseguito - salvataggio schermata Order")
        ScreenDataStore.saveLastScreen(context, "order")

        val viewModel = ViewModel(context)
        val userLocation = viewModel.calculateLocation(fusedLocationClient)
        userPosition = userLocation?.let { Point.fromLngLat(it.lng, it.lat) }
        var order: Order? = null
        try {
            val user = CommunicationController.getUser()
            if (user != null) {
                if (user.lastOid == null || user.orderStatus == "COMPLETED") {
                    Log.d("AppInitializer", "Ordine non presente o completato.")
                } else {
                    order = CommunicationController.getOrder(user.lastOid)
                    Log.d("AppInitializer", "Ordine trovato, dati ricevuti: $order")
                    val restaurantLocation = RestaurantLocationDataStore.getLocation(context)
                    if (restaurantLocation != null) {
                        restaurantPosition = Point.fromLngLat(restaurantLocation.lng!!, restaurantLocation.lat!!)
                        Log.d("MapScreen", "Posizione ristorante caricata: ${restaurantLocation.lat!!}, ${restaurantLocation.lng!!}")
                    }

                    while (order != null) {
                        try {
                            val updatedOrder = CommunicationController.getOrder(order.oid)
                            if (updatedOrder == null) {
                                // ✅ Il drone è arrivato: nascondiamo tutto
                                dronePosition = null
                                restaurantPosition = null // ✅ Nascondi il marker del ristorante
                                showAlert = true

                                // ✅ Cancella la posizione del ristorante dal DataStore
                                RestaurantLocationDataStore.clearLocation(context)

                                break
                            } else {
                                order = updatedOrder
                                Log.d("DronePosition", "Ordine aggiornato: $order")

                                val position = order.currentPosition
                                if (position != null) {
                                    dronePosition = Point.fromLngLat(position.lng, position.lat)
                                    Log.d("DronePosition", "Posizione drone aggiornata: ${position.lat}, ${position.lng}")
                                }
                            }
                        } catch (e: Exception) {
                            Log.e("DronePosition", "Errore durante l'aggiornamento dell'ordine: ${e.message}")
                            order = null
                            dronePosition = null
                            restaurantPosition = null // ✅ Nascondi il marker anche in caso di errore
                            showAlert = true

                            // ✅ Cancella la posizione del ristorante dal DataStore anche in caso di errore
                            RestaurantLocationDataStore.clearLocation(context)
                        }
                        delay(3000)
                    }
                }
            }else {
                Log.d("AppInitializer", "Cosa ci fai qui")
            }
        } catch (e: Exception) {
            Log.e("AppInitializer", "Errore durante l'inizializzazione del SID: ${e.message}")
        }
    }

    // Mostra la mappa con i marker
    MapboxMap(
        Modifier
            .fillMaxSize()
            .padding(bottom = 56.dp),
        mapViewportState = mapViewportState
    ) {
        MapEffect(Unit) { mapView ->
            mapView.location.updateSettings {
                enabled = false
            }
        }

        val userMarker = rememberIconImage(key = R.drawable.position_marker, painter = painterResource(R.drawable.position_marker))
        userPosition?.let { position ->
            PointAnnotation(point = position) {
                iconImage = userMarker
            }
        }

        val droneMarker = rememberIconImage(key = R.drawable.green_marker, painter = painterResource(R.drawable.green_marker))
        dronePosition?.let { position ->
            PointAnnotation(point = position) {
                iconImage = droneMarker
            }
        }

        // ✅ Aggiungi il marker del ristorante se esiste
        val restaurantMarker = rememberIconImage(key = R.drawable.blue_marker, painter = painterResource(R.drawable.blue_marker))
        restaurantPosition?.let { position ->
            PointAnnotation(point = position) {
                iconImage = restaurantMarker
            }
        }
    }

    if (showAlert) {
        androidx.compose.material3.AlertDialog(
            onDismissRequest = { showAlert = false },
            confirmButton = {
                androidx.compose.material3.TextButton(onClick = { showAlert = false }) {
                    androidx.compose.material3.Text("OK")
                }
            },
            title = { androidx.compose.material3.Text("Drone Arrivato") },
            text = { androidx.compose.material3.Text("Il drone è arrivato a destinazione.") }
        )
    }
}

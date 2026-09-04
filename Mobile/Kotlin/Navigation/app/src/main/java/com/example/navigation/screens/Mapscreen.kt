package com.example.navigation.screens

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
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.unit.dp
import com.example.navigation.R
import com.example.navigation.model.CommunicationController
import com.example.navigation.model.Order
import com.example.navigation.viewModel.ViewModel
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
fun MapScreen(context: Context) {
    val fusedLocationClient = remember { LocationServices.getFusedLocationProviderClient(context) }

    // Stati per memorizzare posizione utente e drone
    var userPosition by remember { mutableStateOf<Point?>(null) }
    var dronePosition by remember { mutableStateOf<Point?>(null) }
    var showAlert by remember { mutableStateOf(false) }

    // Stato della mappa
    val mapViewportState = rememberMapViewportState {
        setCameraOptions {
            center(userPosition ?: Point.fromLngLat(45.476007, 9.231874)) // Puoi sostituire con la tua posizione iniziale
            zoom(14.0)
        }
    }

    // LaunchedEffect per inizializzare la posizione dell'utente e aggiornare il drone
    LaunchedEffect(userPosition) {
        userPosition?.let {
            // Aggiorna la posizione dell'utente sulla mappa
            mapViewportState.setCameraOptions {
                center(it)  // Centra la mappa sulla posizione dell'utente
                zoom(14.0)  // Puoi regolare lo zoom
            }
        }
    }

    // LaunchedEffect per inizializzare la posizione dell'utente e aggiornare il drone
    LaunchedEffect(Unit) {
        var order: Order? = null
        try {
            val user = CommunicationController.getUser()
            if (user != null) {
                if (user.lastOid == null || user.orderStatus == "COMPLETED") {
                    Log.d("AppInitializer", "Ordine non presente o completato. Richiesto uno nuovo.")
                    order = CommunicationController.postOrder(5)
                } else {
                    order = CommunicationController.getOrder(user.lastOid)
                    Log.d("AppInitializer", "Ordine già presente, dati ricevuti: $order")
                }
            }
        } catch (e: Exception) {
            Log.e("AppInitializer", "Errore durante l'inizializzazione del SID: ${e.message}")
        }

        // Recupera la posizione dell'utente
        val viewModel = ViewModel(context)
        val userLocation = viewModel.calculateLocation(fusedLocationClient)
        userPosition = userLocation?.let { Point.fromLngLat(it.lng, it.lat) }

        // Aggiorna la posizione del drone
        while (order != null) {
            try {
                val updatedOrder = CommunicationController.getOrder(order.oid)
                if (updatedOrder == null) {
                    // Il drone è arrivato, interrompi il ciclo
                    dronePosition = null
                    showAlert = true
                    break // Esci dal ciclo
                } else {
                    order = updatedOrder
                    Log.d("DronePosition", "Ordine aggiornato: $order")

                    // Simula il recupero della posizione del drone
                    val position = order.currentPosition
                    if (position != null) {
                        dronePosition = Point.fromLngLat(position.lng, position.lat)
                        Log.d("DronePosition", "Posizione corrente del drone: ${position.lat}, ${position.lng}")
                    }
                }
            } catch (e: Exception) {
                Log.e("DronePosition", "Errore durante l'aggiornamento dell'ordine: ${e.message}")
                order = null
                dronePosition = null
                showAlert = true
            }

            // Aspetta 3 secondi prima del prossimo aggiornamento
            delay(3000)
        }
    }

    // Mostra la mappa
    MapboxMap(
        Modifier
            .fillMaxSize()
            .padding(bottom = 56.dp) // Aggiungi il padding per evitare che la mappa venga coperta dalla BottomNavigation
        , mapViewportState = mapViewportState
    ) {
        MapEffect(Unit) { mapView ->
            mapView.location.updateSettings {
                enabled = false // Disabilita il puck predefinito della posizione
            }
        }

        // Marker per la posizione dell'utente
        val userMarker = rememberIconImage(key = R.drawable.position_marker, painter = painterResource(R.drawable.position_marker))
        userPosition?.let { position ->
            PointAnnotation(point = position) {
                iconImage = userMarker
            }
        }

        // Marker per la posizione del drone
        val droneMarker = rememberIconImage(key = R.drawable.position_marker2, painter = painterResource(R.drawable.position_marker2))
        dronePosition?.let { position ->
            PointAnnotation(point = position) {
                iconImage = droneMarker
            }
        }
    }

    // Mostra l'alert se il drone è arrivato
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

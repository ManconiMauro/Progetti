package com.example.es_position3_new

import android.Manifest
import android.content.pm.PackageManager
import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.rememberLauncherForActivityResult
import androidx.activity.compose.setContent
import androidx.activity.result.contract.ActivityResultContracts
import androidx.compose.foundation.layout.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import androidx.core.content.ContextCompat
import com.example.es_position3_new.api.CommunicationController
import com.google.android.gms.location.LocationServices

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        setContent {
            MainScreen()
        }
    }
}

@Composable
fun MainScreen() {
    val context = LocalContext.current
    val fusedLocationClient = remember { LocationServices.getFusedLocationProviderClient(context) }

    // Stato per permesso e schermata attuale
    var hasPermission by remember { mutableStateOf(false) }
    var showMapScreen by remember { mutableStateOf(false) }

    // Launcher per richiedere i permessi
    val permissionLauncher = rememberLauncherForActivityResult(
        ActivityResultContracts.RequestPermission()
    ) { isGranted ->
        hasPermission = isGranted
        if (isGranted) {
            Log.d("MainScreen", "Permesso concesso")
        } else {
            Log.d("MainScreen", "Permesso negato")
        }
    }

    // Controlla se il permesso è già stato concesso
    LaunchedEffect(Unit) {
        hasPermission = checkLocationPermission(context)
        if (!hasPermission) {
            Log.d("MainScreen", "Richiesta del permesso")
            permissionLauncher.launch(Manifest.permission.ACCESS_FINE_LOCATION)
        }

        CommunicationController.initializeSID(context)
        Log.d("AppInitializer", "SID inizializzato correttamente.")
        val user = CommunicationController.getUser()
        if (user != null) {
            if (user.firstName == "") {
                CommunicationController.putUser()
                Log.d("AppInitializer", "Inseriti dati utente")
            }
        }
    }

    // Mostra la schermata mappa se i permessi sono stati concessi
    if (showMapScreen) {
        MapScreen(context)
    } else {
        // Layout dell'interfaccia iniziale
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(16.dp),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Text(
                text = if (hasPermission) {
                    "Hai il permesso per accedere alla posizione."
                } else {
                    "Non hai il permesso per accedere alla posizione."
                },
                style = MaterialTheme.typography.bodyMedium
            )
            Spacer(modifier = Modifier.height(16.dp))
            Button(onClick = {
                if (hasPermission) {
                    showMapScreen = true // Passa alla schermata della mappa
                } else {
                    permissionLauncher.launch(Manifest.permission.ACCESS_FINE_LOCATION)
                }
            }) {
                Text(text = "Vai alla Mappa")
            }
        }
    }
}

// Funzione per controllare i permessi
fun checkLocationPermission(context: android.content.Context): Boolean {
    return ContextCompat.checkSelfPermission(
        context,
        Manifest.permission.ACCESS_FINE_LOCATION
    ) == PackageManager.PERMISSION_GRANTED
}


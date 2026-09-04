package com.example.navigation

import android.Manifest
import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.rememberLauncherForActivityResult
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.activity.result.contract.ActivityResultContracts
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Button
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import com.example.navigation.model.CommunicationController
import com.example.navigation.screens.Navigation
import com.example.navigation.viewModel.ViewModel

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()  // Abilitare Edge-to-edge layout
        setContent {
            AppInizializer()
        }
    }
}

@Composable
fun AppInizializer() {
    val context = LocalContext.current

    // Stato per permesso e schermata attuale
    var hasPermission by remember { mutableStateOf(false) }
    var showApplication by remember { mutableStateOf(false) }
    var hasNavigated by remember { mutableStateOf(false) }  // Aggiungi questa variabile per tracciare la navigazione

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

    // Effetto lanciato all'inizializzazione
    LaunchedEffect(Unit) {
        try {
            val viewManager = ViewModel(context)
            hasPermission = viewManager.checkLocationPermission(context)
            if (!hasPermission) {
                Log.d("MainScreen", "Richiesta del permesso")
                permissionLauncher.launch(Manifest.permission.ACCESS_FINE_LOCATION)
            } else {
                showApplication = true // Se il permesso è già concesso, prosegui direttamente
            }

            CommunicationController.initializeSID(context) // Inizializza SID
        } catch (e: Exception) {
            Log.e("AppInizializer", "Errore durante l'inizializzazione del SID: ${e.message}")
        }
    }

    // Se il permesso è concesso o meno, mostra il contenuto
    if (showApplication || hasPermission) {
        // Se non è ancora stata eseguita la navigazione, prosegui con il caricamento della schermata
        if (!hasNavigated) {
            hasNavigated = true  // Marca che la navigazione è avvenuta
            Navigation()
        }
    } else {
        // Layout dell'interfaccia iniziale se i permessi non sono concessi
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(16.dp),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            // Mostra il testo in base al permesso
            Text(
                text = if (hasPermission) {
                    "Hai il permesso per accedere alla posizione."
                } else {
                    "Non hai il permesso per accedere alla posizione."
                },
                style = MaterialTheme.typography.bodyMedium
            )

            // Mostra il bottone solo se il permesso non è concesso
            if (!hasPermission) {
                Spacer(modifier = Modifier.height(16.dp))
                Button(onClick = {
                    permissionLauncher.launch(Manifest.permission.ACCESS_FINE_LOCATION)
                }) {
                    Text(text = "Vai alla Mappa")
                }
            }
        }
    }
}


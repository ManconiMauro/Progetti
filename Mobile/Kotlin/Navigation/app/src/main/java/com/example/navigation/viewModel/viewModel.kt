package com.example.navigation.viewModel

import android.Manifest
import android.content.Context
import android.content.pm.PackageManager
import android.util.Log
import androidx.core.content.ContextCompat
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.navigation.database.DatabaseHelper
import com.example.navigation.model.CommunicationController
import com.example.navigation.model.Location
import com.example.navigation.model.Menu
import com.google.android.gms.location.FusedLocationProviderClient
import com.google.android.gms.location.Priority
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import kotlinx.coroutines.tasks.await
import kotlinx.io.IOException
import java.util.concurrent.TimeoutException

class ViewModel(private val context: Context) : ViewModel() {
    // Funzione per controllare i permessi
    fun checkLocationPermission(context: android.content.Context): Boolean {
        return ContextCompat.checkSelfPermission(
            context,
            Manifest.permission.ACCESS_FINE_LOCATION
        ) == PackageManager.PERMISSION_GRANTED
    }

    // Funzione per calcolare la posizione
    suspend fun calculateLocation(fusedLocationClient: FusedLocationProviderClient): Location? {
        return try {
            // Ottieni la posizione dell'utente
            val location = fusedLocationClient.getCurrentLocation(Priority.PRIORITY_HIGH_ACCURACY, null).await()
            if (location != null) {
                Log.d("ViewModel", "Posizione utente trovata: ${location.latitude}, ${location.longitude}")
                Location(location.latitude, location.longitude)
            } else {
                Log.d("ViewModel", "Posizione utente non trovata")
                null
            }
        } catch (e: Exception) {
            Log.e("ViewModel", "Errore nel calcolo della posizione: ${e.message}")
            null
        }
    }
}
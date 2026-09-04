package com.example.progetto.repository

import android.content.Context
import androidx.datastore.preferences.core.doublePreferencesKey
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.preferencesDataStore
import com.example.progetto.model.Location
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.flow.map

private val Context.dataStore by preferencesDataStore(name = "restaurant_location")

object RestaurantLocationDataStore {
    private val LATITUDE_KEY = doublePreferencesKey("restaurant_latitude")
    private val LONGITUDE_KEY = doublePreferencesKey("restaurant_longitude")

    // ✅ Ora accetta direttamente un oggetto Location
    suspend fun saveLocation(context: Context, location: Location) {
        context.dataStore.edit { preferences ->
            preferences[LATITUDE_KEY] = location.lat
            preferences[LONGITUDE_KEY] = location.lng
        }
    }

    // ✅ Ora restituisce un Location? invece di Pair<Double, Double>
    suspend fun getLocation(context: Context): Location? {
        return context.dataStore.data.map { prefs ->
            val lat = prefs[LATITUDE_KEY]
            val lng = prefs[LONGITUDE_KEY]

            // Se uno dei due valori è nullo, restituisci null
            if (lat != null && lng != null) {
                Location(lat, lng) // ✅ Usa la tua data class Location
            } else {
                null
            }
        }.first()
    }

    // ✅ Cancella solo LATITUDE_KEY e LONGITUDE_KEY senza toccare altri dati
    suspend fun clearLocation(context: Context) {
        context.dataStore.edit { preferences ->
            preferences.remove(LATITUDE_KEY)
            preferences.remove(LONGITUDE_KEY)
        }
    }
}


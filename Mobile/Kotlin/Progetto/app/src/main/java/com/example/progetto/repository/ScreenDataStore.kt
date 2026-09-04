package com.example.progetto.repository

import android.content.Context
import android.util.Log
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.preferencesDataStore
import kotlinx.coroutines.flow.firstOrNull
import kotlinx.coroutines.flow.map

private val Context.dataStore by preferencesDataStore(name = "screen_prefs")

object ScreenDataStore {
    private val LAST_SCREEN_KEY = stringPreferencesKey("last_screen")

    // ✅ Salva l'ultima schermata e logga il risultato
    suspend fun saveLastScreen(context: Context, screen: String) {
        context.dataStore.edit { preferences ->
            preferences[LAST_SCREEN_KEY] = screen
            Log.d("ScreenDataStore", "Salvata ultima schermata: $screen") // ✅ LOG
        }
    }

    // ✅ Recupera l'ultima schermata e logga il risultato
    suspend fun getLastScreen(context: Context): String? {
        val screen = context.dataStore.data.map { prefs ->
            prefs[LAST_SCREEN_KEY]
        }.firstOrNull()

        Log.d("ScreenDataStore", "Ultima schermata caricata: ${screen ?: "home"}") // ✅ LOG
        return screen
    }
}

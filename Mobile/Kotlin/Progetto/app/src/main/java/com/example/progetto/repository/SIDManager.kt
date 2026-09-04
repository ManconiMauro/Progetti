package com.example.progetto.repository

import android.content.Context
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import kotlinx.coroutines.flow.first

private val Context.dataStore by preferencesDataStore(name = "settings")

class SIDManager(private val context: Context) {
    private val SID_KEY = stringPreferencesKey("sid_key")
    private val UID_KEY = stringPreferencesKey("uid_key")

    // Recupera il SID dal DataStore
    suspend fun getSID(): String? {
        val prefs = context.dataStore.data.first()
        return prefs[SID_KEY]
    }

    suspend fun getUID(): String? {
        val prefs = context.dataStore.data.first()
        return prefs[UID_KEY]
    }

    // Salva il SID nel DataStore
    suspend fun saveSID(sid: String) {
        context.dataStore.edit { preferences ->
            preferences[SID_KEY] = sid
        }
    }

    // Salva il SID nel DataStore
    suspend fun saveUID(uid: String) {
        context.dataStore.edit { preferences ->
            preferences[UID_KEY] = uid
        }
    }
}
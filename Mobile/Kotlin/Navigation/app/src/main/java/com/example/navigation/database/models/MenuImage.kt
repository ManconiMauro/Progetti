package com.example.navigation.database.models

import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "menu_images")
data class MenuImage(
    @PrimaryKey val mid: Int,            // Identificativo dell'immagine
    val imageVersion: Int,               // Versione dell'immagine
    val base64: String                   // Immagine in formato base64
)
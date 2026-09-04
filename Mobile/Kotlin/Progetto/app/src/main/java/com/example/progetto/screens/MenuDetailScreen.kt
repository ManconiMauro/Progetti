package com.example.progetto.screens

import android.content.Context
import android.graphics.Bitmap
import android.util.Log
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.asImageBitmap
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.progetto.api.CommunicationController
import com.example.progetto.database.DatabaseHelper
import com.example.progetto.database.dao.MenuImageDao
import com.example.progetto.model.Location
import com.example.progetto.model.Menu
import com.example.progetto.model.MenuDetails
import com.example.progetto.repository.RestaurantLocationDataStore
import com.example.progetto.utility.base64ToBitmap

@Composable
fun MenuDetailScreen(
    mid: Int,
    onOrderClick: (Int) -> Unit, // ✅ Ora accetta solo l'ID del menu
    onBack: () -> Unit,
    modifier: Modifier = Modifier
) {
    var imageBitmap by remember { mutableStateOf<Bitmap?>(null) }
    val context = LocalContext.current
    var menuDetails by remember { mutableStateOf<MenuDetails?>(null) }

    LaunchedEffect(Unit) {
        val menuImageDao: MenuImageDao = DatabaseHelper.getDatabase(context).menuImageDao()
        val menuImage = menuImageDao.getMenuImage(mid)
        menuDetails = CommunicationController.getMenuDetails(context, mid)
        imageBitmap = menuImage?.base64?.let { base64ToBitmap(it) }

        try {
            val user = CommunicationController.getUser() // ✅ Otteniamo i dati dell'utente
            if (user?.orderStatus == "COMPLETED") {
                // ✅ Solo se l'ordine è COMPLETED salviamo la posizione
                menuDetails?.let { menu ->
                    RestaurantLocationDataStore.saveLocation(context, menu.location)
                    Log.d("MenuDetailScreen", "Posizione ristorante salvata: ${menu.location.lat}, ${menu.location.lng}")
                }
            } else {
                Log.d("MenuDetailScreen", "Ordine attivo trovato, NON salviamo la posizione del ristorante.")
            }
        } catch (e: Exception) {
            Log.e("MenuDetailScreen", "Errore nel recupero dello stato dell'ordine: ${e.message}")
        }
    }

    Column(
        modifier = modifier
            .fillMaxSize()
            .padding(16.dp)
    ) {
        Card(
            modifier = Modifier.fillMaxSize(),
            colors = CardDefaults.cardColors(containerColor = Color(0xFFFFE4C4)),
            shape = MaterialTheme.shapes.medium
        ) {
            Column(
                modifier = Modifier
                    .padding(16.dp)
                    .fillMaxSize()
            ) {
                imageBitmap?.let {
                    Image(
                        bitmap = it.asImageBitmap(),
                        contentDescription = "Menu Image",
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(250.dp)
                            .padding(bottom = 16.dp),
                        contentScale = ContentScale.Crop
                    )
                }

                Text(
                    text = menuDetails?.name ?: "Menu non trovato",
                    style = MaterialTheme.typography.headlineLarge,
                    modifier = Modifier.padding(bottom = 8.dp),
                    color = Color.Black
                )

                Text(
                    text = menuDetails?.longDescription ?: "Nessuna descrizione disponibile",
                    style = MaterialTheme.typography.bodyMedium.copy(fontSize = 14.sp),
                    modifier = Modifier.padding(bottom = 16.dp),
                    color = Color.Gray
                )

                Text(
                    text = "Prezzo: €${menuDetails?.price ?: "Prezzo non disponibile"}",
                    style = MaterialTheme.typography.titleMedium,
                    color = Color(0xFF228B22),
                    modifier = Modifier.padding(bottom = 16.dp)
                )

                Button(
                    onClick = { menuDetails?.let { onOrderClick(it.mid) } }, // ✅ Ora onOrderClick usa solo mid
                    modifier = Modifier.fillMaxWidth()
                ) {
                    Text("Ordina questo menu")
                }

                Button(
                    onClick = { onBack() },
                    modifier = Modifier.fillMaxWidth()
                ) {
                    Text("Torna Indietro")
                }
            }
        }
    }
}

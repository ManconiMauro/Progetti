package com.example.navigation.screens

import android.graphics.Bitmap
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
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
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.asImageBitmap
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import com.example.navigation.database.DatabaseHelper
import com.example.navigation.database.dao.MenuImageDao
import com.example.navigation.model.CommunicationController
import com.example.navigation.model.Menu
import com.example.navigation.model.MenuDetails
import com.example.navigation.utility.base64ToBitmap

@Composable
fun MenuDetailScreen(
    menu: Menu,
    onOrderClick: (Menu) -> Unit, // Funzione che verrà chiamata quando si preme il pulsante "Ordina"
    modifier: Modifier = Modifier
) {
    var imageBitmap by remember { mutableStateOf<Bitmap?>(null) }
    val context = LocalContext.current
    var menuDetails by remember { mutableStateOf<MenuDetails?>(null) } // Tipo nullable per MenuDetails

    // Carica l'immagine del menu dal database e i dettagli del menu
    LaunchedEffect(menu.mid) {
        val menuImageDao: MenuImageDao = DatabaseHelper.getDatabase(context).menuImageDao()
        val menuImage = menuImageDao.getMenuImage(menu.mid)
        menuDetails = CommunicationController.getMenuDetails(context, menu.mid) // Aggiungi controllo su null
        imageBitmap = menuImage?.base64?.let { base64ToBitmap(it) }
    }

    Column(
        modifier = modifier
            .fillMaxSize()
            .padding(16.dp)
    ) {
        // Mostra l'immagine se disponibile
        imageBitmap?.let {
            Image(
                bitmap = it.asImageBitmap(),
                contentDescription = "Menu Image",
                modifier = Modifier
                    .fillMaxWidth()
                    .height(150.dp)
                    .padding(bottom = 8.dp),
                contentScale = ContentScale.Crop
            )
        }

        // Nome del menu
        Text(
            text = menu.name,
            style = MaterialTheme.typography.headlineLarge,
            modifier = Modifier.padding(bottom = 8.dp)
        )

        // Descrizione lunga del menu
        Text(
            text = menuDetails?.longDescription ?: "nessuna descrizione",
            style = MaterialTheme.typography.bodyLarge,
            modifier = Modifier.padding(bottom = 16.dp)
        )

        // Prezzo del menu
        Text(
            text = "€${menu.price}",
            style = MaterialTheme.typography.titleMedium,
            color = Color(0xFF228B22),
            modifier = Modifier.padding(bottom = 16.dp)
        )

        // Bottone per fare l'ordine
        Button(
            onClick = { onOrderClick(menu) },
            modifier = Modifier.fillMaxWidth()
        ) {
            Text("Ordina questo menu")
        }
    }
}

package com.example.progetto.screens

import android.graphics.Bitmap
import android.util.Log
import androidx.compose.foundation.Image
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.asImageBitmap
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import com.example.progetto.database.DatabaseHelper
import com.example.progetto.database.dao.MenuImageDao
import com.example.progetto.model.Menu
import com.example.progetto.repository.ScreenDataStore
import com.example.progetto.utility.base64ToBitmap
import com.example.progetto.viewModel.MenuViewModel


@Composable
fun MenuScreen(navController: NavController, viewModel: MenuViewModel) {
    val menuList by viewModel.menuList.collectAsState()
    val isLoading by viewModel.isLoading.collectAsState()
    val errorMessage by viewModel.errorMessage.collectAsState()
    val context = LocalContext.current

    LaunchedEffect(Unit){
        Log.d("MenuScreen", "LaunchedEffect eseguito - salvataggio schermata Home")
        ScreenDataStore.saveLastScreen(context, "home")
    }

    Box(modifier = Modifier.fillMaxSize()) {
        when {
            isLoading -> {
                // Mostra l'icona di caricamento al centro della pagina e con colore arancione
                CircularProgressIndicator(
                    modifier = Modifier
                        .align(Alignment.Center)
                        .padding(16.dp),
                    color = Color(0xFFFFA500) // Colore arancione
                )
            }
            errorMessage != null -> {
                // Mostra un messaggio di errore
                Text(
                    text = "Errore: $errorMessage",
                    color = Color.Red,
                    modifier = Modifier.align(Alignment.Center)
                )
            }
            else -> {
                // Visualizza la lista dei menu
                LazyColumn(modifier = Modifier.fillMaxWidth()) {
                    items(menuList) { menu ->
                        MenuItem(menu = menu, navController)
                    }
                }
            }
        }
    }
}

@Composable
fun MenuItem(menu: Menu, navController: NavController) {
    val context = LocalContext.current
    var imageBitmap by remember { mutableStateOf<Bitmap?>(null) }

    LaunchedEffect(menu.mid) {
        val menuImageDao: MenuImageDao = DatabaseHelper.getDatabase(context).menuImageDao()
        val menuImage = menuImageDao.getMenuImage(menu.mid)
        imageBitmap = menuImage?.base64?.let { base64ToBitmap(it) }
    }

    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(vertical = 8.dp, horizontal = 16.dp),
        colors = CardDefaults.cardColors(
            containerColor = Color(0xFFFFE4C4)
        ),
        elevation = CardDefaults.cardElevation(4.dp)
    ) {
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)
                .clickable {
                    // Naviga verso la schermata di dettaglio del menu, passando l'ID del menu
                    navController.navigate("menuDetail/${menu.mid}")
                }
        ) {
            Column(modifier = Modifier.fillMaxWidth()) {
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
                    fontSize = 18.sp,
                    fontWeight = FontWeight.Bold,
                    color = Color.Black,
                    modifier = Modifier.padding(bottom = 8.dp)
                )

                // Descrizione del menu
                Text(
                    text = "Descrizione: ${menu.shortDescription}",
                    fontSize = 14.sp,
                    fontWeight = FontWeight.Normal,
                    color = Color.DarkGray,
                    modifier = Modifier.padding(bottom = 8.dp)
                )

                // Latitudine e Longitudine
                Text(
                    text = "Lat: ${menu.location.lat}, Long: ${menu.location.lng}",
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Light,
                    color = Color.Gray
                )
            }

            // Prezzo in basso a destra
            Text(
                text = "€${menu.price}",
                fontSize = 16.sp,
                fontWeight = FontWeight.Bold,
                color = Color(0xFF228B22),
                modifier = Modifier
                    .align(Alignment.BottomEnd)
                    .padding(end = 8.dp, bottom = 8.dp)
            )
        }
    }
}
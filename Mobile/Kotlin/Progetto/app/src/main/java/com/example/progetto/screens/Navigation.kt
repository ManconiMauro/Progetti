package com.example.progetto.screens

import android.util.Log
import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.FmdGood
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.Person
import androidx.compose.material3.AlertDialog
import androidx.compose.material3.Icon
import androidx.compose.material3.NavigationBar
import androidx.compose.material3.NavigationBarItem
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TextButton
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.platform.LocalContext
import androidx.navigation.NavGraph.Companion.findStartDestination
import androidx.navigation.NavHostController
import androidx.navigation.NavType
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.currentBackStackEntryAsState
import androidx.navigation.compose.rememberNavController
import androidx.navigation.navArgument
import com.example.progetto.api.CommunicationController
import com.example.progetto.repository.RestaurantLocationDataStore
import com.example.progetto.repository.ScreenDataStore
import com.example.progetto.viewModel.MenuViewModel
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

@Composable
fun Navigation() {
    val navController = rememberNavController()
    val context = LocalContext.current

    // ✅ Stato per l'ultima schermata visitata
    var lastScreen = remember { mutableStateOf<String?>("home") }
    var orderError = remember { mutableStateOf<String?>(null) }

    // ✅ Recupera l'ultima schermata salvata
    LaunchedEffect(Unit) {
        val savedScreen = ScreenDataStore.getLastScreen(context)
        Log.d("Navigation", "Ultima schermata salvata: $savedScreen") // ✅ LOG
        lastScreen.value = savedScreen ?: "home"
    }

    // Bottom Navigation Routes
    val topLevelRoutes = listOf(
        TopLevelRoute("Home", "home", Icons.Default.Home),
        TopLevelRoute("Order", "order", Icons.Default.FmdGood),
        TopLevelRoute("Profile", "profile", Icons.Default.Person)
    )

    val onOrderClick: (Int) -> Unit = { menuId ->
        CoroutineScope(Dispatchers.IO).launch {
            try {
                // Verifica se l'utente ha già un ordine attivo
                val user = CommunicationController.getUser()
                if (user == null ) {
                    withContext(Dispatchers.Main) {
                        orderError.value = "Non hai inserito i dati del profilo!"
                    }
                    return@launch // Interrompi la funzione
                } else if (user.lastOid != null && user.orderStatus != "COMPLETED") {
                        // Se l'utente ha un ordine attivo, non salviamo la posizione e mostriamo un alert
                        withContext(Dispatchers.Main) {
                            orderError.value = "Hai già un ordine attivo!"
                        }
                        return@launch // Interrompi la funzione
                }

                val menu = CommunicationController.getMenuDetails(context, menuId)
                RestaurantLocationDataStore.saveLocation(context, menu!!.location!!)
                Log.d("OrderMenu", "Posizione ristorante salvata: ${menu.location}")

                CommunicationController.postOrder(menuId)
                Log.d("OrderMenu", "Menu ordinato con successo")

                withContext(Dispatchers.Main) {
                    navController.navigate("order") {
                        launchSingleTop = true
                        popUpTo("home") { inclusive = true }
                    }
                }
            } catch (e: Exception) {
                Log.e("OrderMenu", "OrderFailed: ${e.message}")
                withContext(Dispatchers.Main) {
                    orderError.value = e.message ?: "Errore Nel Ordine del Menu, controllare i propri dati del profilo"
                }
            }
        }
    }


    Scaffold(
        bottomBar = {
            BottomNavigation(navController = navController, routes = topLevelRoutes)
        }
    ) { innerPadding ->
        NavHost(
            navController = navController,
            startDestination = lastScreen.value?:"home",
            modifier = Modifier.padding(innerPadding)
        ) {
            composable("home") {
                MenuScreen(navController, viewModel = MenuViewModel(LocalContext.current))
            }
            composable("order") {
                MapScreen()
            }
            composable("profile") {
                ProfileScreen(
                    onBackClick = { navController.navigateUp() }
                )
            }
            composable("menuDetail/{menuId}", arguments = listOf(navArgument("menuId") { type = NavType.IntType })) { backStackEntry ->
                val menuId = backStackEntry.arguments?.getInt("menuId")
                if (menuId != null) {
                    MenuDetailScreen(mid = menuId, onOrderClick = { onOrderClick(menuId) }, onBack = { navController.navigate("home") })
                }
            }
        }
    }

    // ✅ Alert in caso di errore
    if (orderError.value != null) {
        AlertDialog(
            onDismissRequest = { orderError.value = null },
            confirmButton = {
                TextButton(onClick = { orderError.value = null }) {
                    Text("OK")
                }
            },
            title = { Text("Errore nell'ordine: ${orderError.value}") },
        )
    }
}

@Composable
fun BottomNavigation(
    navController: NavHostController,
    routes: List<TopLevelRoute>
) {
    NavigationBar {
        val navBackStackEntry by navController.currentBackStackEntryAsState()
        val currentDestination = navBackStackEntry?.destination
        routes.forEach { topLevelRoute ->
            val iconColor = if (currentDestination?.route == topLevelRoute.route) {
                Color(0xFFFFA500) // Colore per la voce selezionata
            } else {
                Color.Gray // Colore per le voci non selezionate
            }
            NavigationBarItem(
                icon = {
                    Icon(
                        topLevelRoute.icon,
                        contentDescription = topLevelRoute.name,
                        tint = iconColor
                    )
                },
                label = { Text(topLevelRoute.name) },
                selected = currentDestination?.route == topLevelRoute.route,
                onClick = {
                    // Navigazione alla schermata selezionata
                    navController.navigate(topLevelRoute.route) {
                        // PopUp per rimuovere le schermate precedenti
                        popUpTo(navController.graph.findStartDestination().id) {
                            saveState = true
                        }
                        launchSingleTop = true
                        restoreState = true
                    }
                }
            )
        }
    }
}

data class TopLevelRoute(val name: String, val route: String, val icon: ImageVector)
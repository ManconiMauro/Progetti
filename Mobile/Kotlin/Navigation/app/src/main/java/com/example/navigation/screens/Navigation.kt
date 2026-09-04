package com.example.navigation.screens

import android.widget.Toast
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.Menu
import androidx.compose.material.icons.filled.Person
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.NavigationBar
import androidx.compose.material3.NavigationBarItem
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.runtime.rememberCoroutineScope
import androidx.compose.ui.Alignment
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
import com.example.navigation.model.CommunicationController
import com.example.navigation.model.Menu
import com.example.navigation.viewModel.MenuViewModel
import kotlinx.coroutines.launch


@Composable
fun Navigation() {
    val navController = rememberNavController()

    // Bottom Navigation Routes
    val topLevelRoutes = listOf(
        TopLevelRoute("Home", "home", Icons.Default.Home),
        TopLevelRoute("Order", "order", Icons.Default.Menu),
        TopLevelRoute("Profile", "profile", Icons.Default.Person)
    )

    Scaffold(
        bottomBar = {
            BottomNavigation(navController = navController, routes = topLevelRoutes)
        }
    ) { innerPadding ->
        // Usa il padding calcolato per evitare che la BottomNavigation sovrascriva il contenuto
        NavHost(
            navController = navController,
            startDestination = "home",
            modifier = Modifier.padding(innerPadding)
        ) {
            composable("home") {
                MenuScreen()
            }
            composable("order") {
                MapScreen(context = LocalContext.current)
            }
            composable("profile") {
                ProfileScreen(
                    onBackClick = { navController.navigateUp() }
                )
            }
            composable("menuDetail/{menuId}", arguments = listOf(navArgument("menuId") { type = NavType.IntType })) { backStackEntry ->
                val menuId = backStackEntry.arguments?.getInt("menuId")
                if (menuId != null) {
                    // Recupera il menu dai dati esistenti
                    val context = LocalContext.current
                    val viewModel = remember { MenuViewModel(context) }
                    val menu = remember(menuId) { viewModel.getMenuById(menuId) }

                    if (menu != null) {
                        OrderHandler(menu = menu)
                    } else {
                        // Mostra un messaggio di errore se il menu non viene trovato
                        Box(
                            modifier = Modifier.fillMaxSize(),
                            contentAlignment = Alignment.Center
                        ) {
                            Text(
                                text = "Menu non trovato",
                                color = Color.Red,
                                style = MaterialTheme.typography.bodyLarge
                            )
                        }
                    }
                }
            }
        }
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

@Composable
fun OrderHandler(menu: Menu) {
    val context = LocalContext.current
    val coroutineScope = rememberCoroutineScope()

    MenuDetailScreen(
        menu = menu,
        onOrderClick = {
            coroutineScope.launch {
                try {
                    CommunicationController.postOrder(menu.mid)
                    Toast.makeText(context, "Ordine inviato con successo!", Toast.LENGTH_SHORT).show()
                } catch (e: Exception) {
                    Toast.makeText(context, "Errore: ${e.message}", Toast.LENGTH_LONG).show()
                }
            }
        }
    )
}

data class TopLevelRoute(val name: String, val route: String, val icon: ImageVector)
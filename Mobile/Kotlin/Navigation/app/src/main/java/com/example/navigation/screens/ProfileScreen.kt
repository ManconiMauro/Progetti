package com.example.navigation.screens


import android.util.Log
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material3.Button
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.unit.dp
import com.example.navigation.model.CommunicationController
import com.example.navigation.model.User
import com.example.navigation.model.UserToSave
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch


@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ProfileScreen(
    onBackClick: () -> Unit = {},
    modifier: Modifier = Modifier
) {
    // Definizione delle variabili di stato
    var nome by remember { mutableStateOf("") }
    var cognome by remember { mutableStateOf("") }
    var nomeCartaCredito by remember { mutableStateOf("") }
    var numeroCartaCredito by remember { mutableStateOf("") }
    var scadenzaMese by remember { mutableStateOf("") }
    var scadenzaAnno by remember { mutableStateOf("") }
    var cvv by remember { mutableStateOf("") }

    // Caricamento dei dati utente all'avvio
    LaunchedEffect(Unit) {
        CoroutineScope(Dispatchers.Main).launch {
            val loadedUser = CommunicationController.getUser() // Inserisci l'UID corretto
            Log.d("ProfileScreen", "dati utente trovati: $loadedUser")
            loadedUser?.let {
                nome = it.firstName
                cognome = it.lastName
                nomeCartaCredito = it.cardFullName
                numeroCartaCredito = it.cardNumber
                scadenzaMese = it.cardExpireMonth.toString()
                scadenzaAnno = it.cardExpireYear.toString()
                cvv = it.cardCVV
            }
        }
    }

    // Funzione per salvare i dati dell'utente
    fun saveUserData() {
        val user = UserToSave(firstName = nome, lastName = cognome, cardFullName = nomeCartaCredito, cardNumber = numeroCartaCredito, cardExpireMonth = scadenzaMese.toIntOrNull() ?: 0, cardExpireYear = scadenzaAnno.toIntOrNull() ?: 0, cardCVV = cvv)

        // Chiamata per aggiornare i dati utente
        CoroutineScope(Dispatchers.Main).launch {
            val result = CommunicationController.putUser(user)
            if (result != null) {
                Log.d("ProfileScreen", "User updated successfully")
                // Puoi mostrare un messaggio di successo o eseguire altre azioni
            } else {
                Log.e("ProfileScreen", "Failed to update user data")
                // Puoi gestire l'errore, ad esempio mostrando un messaggio all'utente
            }
        }
    }

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Modifica Profilo") },
                navigationIcon = {
                    IconButton(onClick = onBackClick) {
                        Icon(
                            imageVector = Icons.AutoMirrored.Filled.ArrowBack,
                            contentDescription = "Torna indietro"
                        )
                    }
                }
            )
        },
        modifier = modifier
    ) { innerPadding ->

        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(innerPadding)
                .padding(16.dp),
            verticalArrangement = Arrangement.spacedBy(8.dp)
        ) {
            Text(
                text = "Profilo",
                style = MaterialTheme.typography.headlineMedium
            )

            // Dati personali
            OutlinedTextField(
                value = nome,
                onValueChange = { if (it.length <= 15) nome = it },
                label = { Text("Nome") },
                modifier = Modifier.fillMaxWidth(),
                singleLine = true
            )

            OutlinedTextField(
                value = cognome,
                onValueChange = { if (it.length <= 15) cognome = it },
                label = { Text("Cognome") },
                modifier = Modifier.fillMaxWidth(),
                singleLine = true
            )

            // Dati carta di credito
            OutlinedTextField(
                value = nomeCartaCredito,
                onValueChange = { if (it.length <= 31) nomeCartaCredito = it },
                label = { Text("Nome sulla carta") },
                modifier = Modifier.fillMaxWidth(),
                singleLine = true
            )

            OutlinedTextField(
                value = numeroCartaCredito,
                onValueChange = { if (it.length <= 16) numeroCartaCredito = it },
                label = { Text("Numero carta (16 cifre)") },
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                modifier = Modifier.fillMaxWidth(),
                singleLine = true
            )

            // Data scadenza
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.spacedBy(8.dp)
            ) {
                OutlinedTextField(
                    value = scadenzaMese,
                    onValueChange = { if (it.length <= 2) scadenzaMese = it },
                    label = { Text("MM") },
                    keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                    modifier = Modifier.weight(1f),
                    singleLine = true
                )

                OutlinedTextField(
                    value = scadenzaAnno,
                    onValueChange = { if (it.length <= 2) scadenzaAnno = it },
                    label = { Text("YY") },
                    keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                    modifier = Modifier.weight(1f),
                    singleLine = true
                )
            }

            OutlinedTextField(
                value = cvv,
                onValueChange = { if (it.length <= 3) cvv = it },
                label = { Text("CVV") },
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                modifier = Modifier.fillMaxWidth(),
                singleLine = true
            )

            Spacer(modifier = Modifier.height(16.dp))

            Button(
                onClick = { saveUserData() },
                modifier = Modifier.fillMaxWidth()
            ) {
                Text("Salva")
            }
        }
    }
}

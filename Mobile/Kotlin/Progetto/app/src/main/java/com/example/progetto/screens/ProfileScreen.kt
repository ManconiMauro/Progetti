package com.example.progetto.screens

import android.util.Log
import androidx.compose.foundation.border
import androidx.compose.foundation.gestures.detectTapGestures
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
import androidx.compose.material3.AlertDialog
import androidx.compose.material3.Button
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TextButton
import androidx.compose.material3.TopAppBar
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.input.pointer.pointerInput
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.platform.LocalFocusManager
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.unit.dp
import com.example.progetto.api.CommunicationController
import com.example.progetto.model.UserToSave
import com.example.progetto.repository.ScreenDataStore
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun ProfileScreen(
    onBackClick: () -> Unit = {},
    modifier: Modifier = Modifier
) {
    // Variabili di stato per i dati utente
    var nome by remember { mutableStateOf("") }
    var cognome by remember { mutableStateOf("") }
    var nomeCartaCredito by remember { mutableStateOf("") }
    var numeroCartaCredito by remember { mutableStateOf("") }
    var scadenzaMese by remember { mutableStateOf("") }
    var scadenzaAnno by remember { mutableStateOf("") }
    var cvv by remember { mutableStateOf("") }

    // ✅ Stati per la validazione dei campi
    var isNomeValid by remember { mutableStateOf(true) }
    var isCognomeValid by remember { mutableStateOf(true) }
    var isNomeCartaValid by remember { mutableStateOf(true) }
    var isNumeroCartaValid by remember { mutableStateOf(true) }
    var isScadenzaMeseValid by remember { mutableStateOf(true) }
    var isScadenzaAnnoValid by remember { mutableStateOf(true) }
    var isCvvValid by remember { mutableStateOf(true) }

    // ✅ Stato per gestire l'alert di errore
    var showErrorDialog by remember { mutableStateOf(false) }

    // ✅ Stato per il messaggio di errore specifico
    var errorMessage by remember { mutableStateOf("") }
    val context = LocalContext.current
    val focusManager = LocalFocusManager.current

    // Caricamento dati utente all'avvio
    LaunchedEffect(Unit) {
        Log.d("ProfileScreen", "LaunchedEffect eseguito - salvataggio schermata Profile")
        ScreenDataStore.saveLastScreen(context, "profile")
        CoroutineScope(Dispatchers.Main).launch {
            val loadedUser = CommunicationController.getUser()
            Log.d("ProfileScreen", "Dati utente trovati: $loadedUser")
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

    // Funzione per validare i campi
    fun validateFields(): Boolean {
        isNomeValid = nome.isNotBlank()
        isCognomeValid = cognome.isNotBlank()
        isNomeCartaValid = nomeCartaCredito.isNotBlank()
        isNumeroCartaValid = numeroCartaCredito.length == 16 && numeroCartaCredito.startsWith("123")
        isScadenzaMeseValid = scadenzaMese.toIntOrNull() in 1..12
        isScadenzaAnnoValid = scadenzaAnno.toIntOrNull()?.let { it >= 2026 && scadenzaAnno.length == 4 } == true
        isCvvValid = cvv.length == 3

        return listOf(
            isNomeValid, isCognomeValid, isNomeCartaValid,
            isNumeroCartaValid, isScadenzaMeseValid,
            isScadenzaAnnoValid, isCvvValid
        ).all { it }
    }

    // Funzione per salvare i dati dell'utente
    fun saveUserData() {
        if(validateFields()){
            val user = UserToSave(
                firstName = nome,
                lastName = cognome,
                cardFullName = nomeCartaCredito,
                cardNumber = numeroCartaCredito,
                cardExpireMonth = scadenzaMese.toIntOrNull() ?: 0,
                cardExpireYear = scadenzaAnno.toIntOrNull() ?: 0,
                cardCVV = cvv
            )

            CoroutineScope(Dispatchers.Main).launch {
                try {
                    val result = CommunicationController.putUser(user)
                    if (result == null) {
                        Log.d("ProfileScreen", "User updated successfully")
                        errorMessage = "Dati Profilo salvati correttamente"
                        showErrorDialog = true
                    } else {
                        throw Exception("Errore durante l'aggiornamento dei dati utente.")
                    }
                } catch (e: Exception) {
                    Log.e("ProfileScreen", "Failed to update user data: ${e.message}")
                    errorMessage = e.message ?: "Si è verificato un errore sconosciuto."
                    showErrorDialog = true
                }
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
                .padding(16.dp)
                .pointerInput(Unit) {
                    detectTapGestures(onTap = {
                        focusManager.clearFocus() // Chiude la tastiera quando si tocca fuori dai campi di testo
                    })
                },
            verticalArrangement = Arrangement.spacedBy(8.dp)
        ) {
            Text(text = "Profilo", style = MaterialTheme.typography.headlineMedium)

            OutlinedTextField(
                value = nome,
                onValueChange = { if (it.length <= 15) nome = it },
                label = { Text("Nome") },
                modifier = Modifier.fillMaxWidth().border(2.dp, if (isNomeValid) Color.Gray else Color.Red),
                singleLine = true
            )
            OutlinedTextField(
                value = cognome,
                onValueChange = { if (it.length <= 15) cognome = it },
                label = { Text("Cognome") },
                modifier = Modifier.fillMaxWidth().border(2.dp, if (isCognomeValid) Color.Gray else Color.Red),
                singleLine = true
            )
            OutlinedTextField(
                value = nomeCartaCredito,
                onValueChange = { if (it.length <= 31) nomeCartaCredito = it },
                label = { Text("Nome sulla carta") },
                modifier = Modifier.fillMaxWidth().border(2.dp, if (isCognomeValid) Color.Gray else Color.Red),
                singleLine = true
            )
            OutlinedTextField(
                value = numeroCartaCredito,
                onValueChange = { if (it.length <= 16) numeroCartaCredito = it },
                label = { Text("Numero carta (16 cifre)") },
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                modifier = Modifier
                    .fillMaxWidth()
                    .border(2.dp, if (isNumeroCartaValid) Color.Gray else Color.Red),
                singleLine = true
            )

            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.spacedBy(8.dp)
            ) {
                OutlinedTextField(
                    value = scadenzaMese,
                    onValueChange = { if (it.length <= 2) scadenzaMese = it },
                    label = { Text("MM") },
                    keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                    modifier = Modifier
                        .weight(1f)
                        .border(2.dp, if (isScadenzaMeseValid) Color.Gray else Color.Red),
                    singleLine = true
                )
                OutlinedTextField(
                    value = scadenzaAnno,
                    onValueChange = { if (it.length <= 4) scadenzaAnno = it },
                    label = { Text("YYYY") },
                    keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                    modifier = Modifier
                        .weight(1f)
                        .border(2.dp, if (isScadenzaAnnoValid) Color.Gray else Color.Red),
                    singleLine = true
                )
            }

            OutlinedTextField(
                value = cvv,
                onValueChange = { if (it.length <= 3) cvv = it },
                label = { Text("CVV") },
                keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number),
                modifier = Modifier
                    .fillMaxWidth()
                    .border(2.dp, if (isCvvValid) Color.Gray else Color.Red),
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

    // ✅ AlertDialog che appare solo se `showErrorDialog == true`
    if (showErrorDialog) {
        if(errorMessage == "Dati Profilo salvati correttamente"){
            AlertDialog(
                onDismissRequest = { showErrorDialog = false },
                confirmButton = {
                    TextButton(onClick = { showErrorDialog = false }) {
                        Text("OK")
                    }
                },
                title = { Text("Salvataggio Completato") },
                text = { Text(errorMessage) }
            )
        }else {
            AlertDialog(
                onDismissRequest = { showErrorDialog = false },
                confirmButton = {
                    TextButton(onClick = { showErrorDialog = false }) {
                        Text("OK")
                    }
                },
                title = { Text("Errore") },
                text = { Text(errorMessage) }
            )
        }
    }
}

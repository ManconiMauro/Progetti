//funzione per visualizzare il profilo utente
  function mostraProfilo() {
    //per prima cosa nascondo gli elementi dentro la home page
    var daNascondere = document.getElementsByClassName('container');
    var daNascondere2 = document.getElementsByClassName('user-profile');
    var daMostrare = document.getElementById('profilo');

    //ciclo che nadconde tutti gli elementi dentro le classi, utilizzo container perchè ha più elementi dentro
    for (var i = 0; i < daNascondere.length; i++) {
        daNascondere[i].style.display = 'none';
        daNascondere2[i].style.display = 'none';
    }
    
    var immagineProfilo = "immagini/avatar.jfif";

    // Recupera i dati dall'archivio locale (se esistono)
    var loggedUser = localStorage.getItem("loggedInUser") // Prendo l'utente corrente
    var users = JSON.parse(localStorage.getItem('users')) // Ottieni l'array degli utenti
    var datiUtente = users.find(user => user.username === loggedUser);
    
    //controllo che l'utente abbia inserito la carta di credito
    if(!datiUtente.cartaCredito){
      var contenutoProfilo = `
        <img src="${immagineProfilo}" alt="Profilo Utente" width="60" height="60"><br>
        <h1>${datiUtente.username}</h1><br>
        <p>Email: ${datiUtente.email}</p><br>
        <p>Password: ${datiUtente.password}</p><br>
        <p>Crediti: ${datiUtente.crediti}</p><br>
        <p>Supereroe Preferito: ${datiUtente.superhero}</p>
        <p>Aggiungi la carta di credito:</p><br>
        <button onclick="aggiungiCartaCredito()">Va Bene</button><br>
        <button onclick="ModificaDati()">Modifica dati profilo</button><br>
        <button onclick="EliminaProfilo()" style="backgroung-color : red">Elimina Profilo</button><br>
        <button onclick="reverse()">Torna Indietro</button>
    `;
    }else {
      var contenutoProfilo = `
        <img src="${immagineProfilo}" alt="Profilo Utente" width="60" height="60">
        <h1>${datiUtente.username}</h1>
        <p>Email: ${datiUtente.email}</p>
        <p>Password: ${datiUtente.password}</p>
        <p>Crediti: ${datiUtente.crediti}</p>
        <p>Supereroe Preferito: ${datiUtente.superhero}</p><br>
        <button onclick="modificaDati()">Modifica dati profilo</button><br>
        <button onclick="eliminaProfilo()" style="backgroung-color : red">Elimina Profilo</button><br>
        <button onclick="reverse()">Torna Indietro</button>
    `;
    }
    // Costruzione del contenuto del profilo
    
    // Inserimento del contenuto nel div con id "profile"
    daMostrare.innerHTML = contenutoProfilo;
    daMostrare.style.display = 'block'; 
}

function aggiungiCartaCredito(){
  window.location.href = 'CartaCredito.html'
} 

function reverse() {
  var daMostrare = document.getElementsByClassName('container');
  var daMostrare2 = document.getElementsByClassName('user-profile');
  var daNascondere = document.getElementsByClassName('hidden');
  
  for (var i = 0; i < daMostrare.length; i++) {
          daMostrare[i].style.display = 'block';
  }
  for (var i = 0; i < daMostrare2.length; i++) {
    daMostrare2[i].style.display = 'block';
}
for (var i = 0; i < daNascondere.length; i++) {
  daNascondere[i].style.display = 'none';
}
   
}

function album() {
  window.location.href = 'Album.html';
}

function pacchetti(){
  window.location.href = 'Pacchetti.html'
}

function scambio(){
  window.location.href = 'Scambio.html'
}

function crediti(){
  var loggedUser = localStorage.getItem("loggedInUser") // Prendo l'utente corrente
  var users = JSON.parse(localStorage.getItem('users')) // Ottieni l'array degli utenti
  var datiUtente = users.find(user => user.username === loggedUser);
  console.log(datiUtente)
  
  if(datiUtente.cartaCredito){
    window.location.href = 'compraCrediti.html'
  }else {
    window.location.href = 'CartaCredito.html'
  }
}

function modificaDati(){
  window.location.href = "ModificaDati.html"
}

function eliminaProfilo(){
  Swal.fire({
    title: 'Eliminazione Profilo',
    text: 'Sei sicuro di voler cancellare il tuo Profilo?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sì, procedi!',
    cancelButtonText: 'no, Annulla'
  }).then((result) => {
    // Se l'utente ha cliccato su "Sì, procedi!" 
    if (result.isConfirmed) {
      // Esegue l'azione desiderata
      CancellazioneDati();
    } else {
      // Altrimenti mostra un messaggio di annullamento
      reverse()
    }
  });
}

function CancellazioneDati(){
  var users = JSON.parse(window.localStorage.getItem('users'));
  var loggedUser = localStorage.getItem("loggedInUser") // Prendo l'utente corrente
  var albums = JSON.parse(window.localStorage.getItem('albums'));
  var scambi = JSON.parse(window.localStorage.getItem('scambi'));

  for (let i = 0; i < users.length; i++) {
    if(users[i].username === loggedUser){
      users.splice(i, 1)
    }
  }
  localStorage.setItem('users', JSON.stringify(users));
  for (let j = 0; j < albums.length; j++) {
    if(albums[j].user === loggedUser){
      albums.splice(j, 1)
    }
  }
  localStorage.setItem('albums', JSON.stringify(albums));
  for (let w = 0; w < scambi.length; w++) {
    if(scambi[w].mittente === loggedUser){
      scambi.splice(w, 1)
    }else if(scambi[w].destinatario === loggedUser){
      scambi.splice(w, 1)
    }
  }
  localStorage.setItem('scambi', JSON.stringify(scambi));
  localStorage.setItem('loggedInUser', "")
  alert("Profilo cancellato con successo")
  window.location.href = "StartPage.html"
}
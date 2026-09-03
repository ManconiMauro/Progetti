//funzione per controllare i dati messi dall'utente e inserirli nel local storage
function avviaModifiche() {
    //prendo i dati inseriti dall'utente
    const nuovoUsername = document.getElementById("username1").value;
    const nuovaEmail = document.getElementById("email").value;
    const nuovaPassword = document.getElementById("password1").value;
    const nuovaConfirmPassword = document.getElementById("confirmPassword").value;
    const nuovoSuperhero = document.getElementById("superhero").value;
    //uso una variabile di controllo per vedere se l'email rispetta i parametri(generata da chatGPT)
    const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

    //controllo sull'username (se è valido il formato)
    if (!isNameValid(nuovoUsername) || nuovoUsername.length < 3) {
        alert("Username non valido");
        return;
    }
    //controllo sull'username (se è già stato usato)
    if (isNicknameAlreadyUsed(nuovoUsername)) {
        alert("Username già utilizzato");
        return;
    }

    //controllo della correttezza dell'email e che le password inserite siano uguali
    if (!nuovaEmail.match(emailRegex)) {
        alert("Inserisci un indirizzo email valido.");
        return;
    }
    
    //controllo che le password non siano uguali
    if (nuovaConfirmPassword !== nuovaPassword) {
        alert("Le password non corrispondono."); 
        return;
    }

    var users = JSON.parse(window.localStorage.getItem('users'));
    var loggedUser = localStorage.getItem("loggedInUser") // Prendo l'utente corrente
    var albums = JSON.parse(window.localStorage.getItem('albums'));
    var scambi = JSON.parse(window.localStorage.getItem('scambi'));


    for (let i = 0; i < users.length; i++) {
        const user = users[i];
        if(user.username === loggedUser){
            user.username = nuovoUsername
            user.email = nuovaEmail
            user.password = nuovaPassword
            user.superhero = nuovoSuperhero
        }
        users[i]=user
    }
    localStorage.setItem('users', JSON.stringify(users));
    if(nuovoUsername != loggedUser){
        for (let j = 0; j < albums.length; j++) {
            if(albums[j].user === loggedUser){
                albums[j].user=nuovoUsername
            }
        }
        localStorage.setItem('albums', JSON.stringify(albums));
        for (let w = 0; w < scambi.length; w++) {
            if(scambi[w].mittente === loggedUser){
                scambi[w].mittente = nuovoUsername
            }else if(scambi[w].destinatario === loggedUser){
                    scambi[w].destinatario = nuovoUsername
            }
        }
        localStorage.setItem('scambi', JSON.stringify(scambi));
        localStorage.setItem('loggedInUser', nuovoUsername)
    }
    alert("Modifiche avvenute con successo,  si prega di fare di nuovo il log In")
    window.location.href = "StartPage.html"
}

//funzione per controllare che il nickname sia valido
function isNameValid(username) {
    const nameRegex = /^[a-zA-Z\s']+$/;
    return nameRegex.test(username);
}

//funzione per controllare che l'usename non sia già salvato nel local storage
function isNicknameAlreadyUsed(username) {
    var users = JSON.parse(localStorage.getItem('users')) || []; // Ottieni l'array degli utenti

    for (var i = 0; i < users.length; i++) {
        if (users[i].username === username) {
            return true; // Il username è già in uso
        }
    }

    return false; // Il username non è in uso
}
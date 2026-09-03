//funzione che crea all'apertura della pagina un utente admin che ha crediti infiniti
function onLoad(){
    const admin1 = {
        username: "admin1",
        email: "admin@gmail.it",
        password: "admin",
        superhero: "Capitan America",
        cartaCredito: true,
        crediti: 9999,
    };

    var users =[]
    //array degli utenti registrati e salvati nel localStorage
    var savedUsers = JSON.parse(localStorage.getItem("users"));
    //controllo sugli utenti registrati, se non ci sono utenti registrati oppure esiste solo admin1 non entriamo
    if(savedUsers != null && savedUsers.length>1){
        console.log("ci sono altri utenti oltre gli admin")
        //ciclo negli utenti salvati e li aggiungo ad user
        for (let i = 0; i < savedUsers.length; i++) {
            const element = savedUsers[i];
            //controllo che l'utente non sia admin1, visto che è gia dentro users
            if(element != admin1){
                users.push(element)
            }
        }
    }else{
        users.push(admin1)
    }
    
    console.log(users)
    //salvo gli utenti nel localStorage
    localStorage.setItem('users', JSON.stringify(users));
}

//Funzione per il login della pagina web
function Login() {
    //Ottengo i valori inseriti dall'utente
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    
    var users = JSON.parse(localStorage.getItem("users"));

    var i
    //ciclo negli utenti salvati per cercare l'utente che cerca di entrare
    for (i = 0; i < users.length; i++) {
        //controllo nome utente e password
        if (users[i].username === username && password === users[i].password) {
            //se nome utente e password sono scritti correttamente salvo il nome utente come utente loggato
            localStorage.setItem("loggedInUser", username);

            //poi mi sposto alla home page
            window.location.href ='HomePage.html';
            break
        }
    }

    if(i==users.length){
        alert("nome utente o password non inseriti correttamente")
    }
};

//funzione per controllare i dati messi dall'utente e inserirli nel local storage
function register() {
    //prendo i dati inseriti dall'utente
    const username = document.getElementById("username1").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password1").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const superhero = document.getElementById("superhero").value;
    //uso una variabile di controllo per vedere se l'email rispetta i parametri(generata da chatGPT)
    const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

    //controllo sull'username (se è valido il formato)
    if (!isNameValid(username) || username.length < 3) {
        alert("Username non valido");
        return;
    }
    //controllo sull'username (se è già stato usato)
    if (isNicknameAlreadyUsed(username)) {
        alert("Username già utilizzato");
        return;
    }
    //contro sull'email se è già stata usata
    if (isEmailAlreadyUsed(email)) {
        alert("Email già utilizzata");
        return;
    }

    var users = [];
    const userData = {
        username,
        email,
        password,
        superhero,
        cartaCredito: false,
        crediti: 0,
    };

    //controllo della correttezza dell'email e che le password inserite siano uguali
    if (!email.match(emailRegex)) {
        alert("Inserisci un indirizzo email valido.");
        return;
    }
    
    //controllo che le password non siano uguali
    if (confirmPassword !== password) {
        alert("Le password non corrispondono."); 
        return;
    }
    //cerco di prendere l'array degli utenti dal localStorage(dovrebbe entrare sempre)
    if (window.localStorage.getItem('users') != null) {
        users = JSON.parse(window.localStorage.getItem('users'));
    }

    if (!controllaEsistenzaUtente(userData, users)) {
        if (Array.isArray(users)) {
            // La variabile users è un array, puoi usare push
            users.push(userData);
            console.log(users); // Output: ['Primo Utente', 'Nuovo Utente']
        } else {
            // La variabile users non è un array
            console.error('La variabile users non è un array.');
        }
        alert("Registrazione completata con successo!");
    } else {
        alert("L'utente inserito è già esistente")
    }
    //PER SALVARE NEL LOCAL STORAGE TUTTI I DATI DI UN UTENTE NELL'ARRAY DEGLI USERS
    localStorage.setItem('users', JSON.stringify(users));

    //sono invertiti daMostrare e daNascondere, ho copiato la funzione da Log
    var daNascondere = document.getElementsByClassName('navbar');
    var daMostrare = document.getElementById('registration');

    for (var i = 0; i < daNascondere.length; i++) {
    daNascondere[i].style.display = 'block';
    }
    daMostrare.style.display = 'none'; 
}

//controllo l'esistenza dell'utente tramite la mail nel localStorage
function controllaEsistenzaUtente(newUtente, users) {
    var users = JSON.parse(localStorage.getItem('users')) || []; // Ottengo l'array degli utenti

    for (var i = 0; i < users.length; i++) {
        if (users[i].email === newUtente.email) {
            return true; //l'username è già in uso
        }
    }

    return false;
}

//funzione per nascondere la barra sopra e rendere visibile il form di registrazione
function sign() {
    var daNascondere = document.getElementsByClassName('navbar');
    var daMostrare = document.getElementById('registration');
    //ho dovuto usare un for visto che document.getElementByClass name restituisce una lista di elementi(anche se ho un solo elemento)
    for (var i = 0; i < daNascondere.length; i++) {
        daNascondere[i].style.display = 'none';
    }
    daMostrare.style.display = 'block'; 
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

//funzione simile al controllo del nickname sul local storage, ma per le password
function isEmailAlreadyUsed(email) {
    var users = JSON.parse(localStorage.getItem('users')) || []; // Ottieni l'array degli utenti

    for (var i = 0; i < users.length; i++) {
        if (users[i].email === email) {
            return true; // l'email è già in uso
        }
    }

    return false; // l'email non è in uso
}

//funzione per nascondere la barra sopra e rendere visibile il login
function Log() {
    var daNascondere = document.getElementsByClassName('navbar');
    var daMostrare = document.getElementById('loginForm');

    for (var i = 0; i < daNascondere.length; i++) {
        daNascondere[i].style.display = 'none';
    }
    daMostrare.style.display = 'block'; 
}

//funzione simile a log, nasconde la barra sopra e mosta le infirmazioni sul sito web
function faq() {
    var daNascondere = document.getElementsByClassName('navbar');
    var daMostrare = document.getElementById('Faq');
    //ho dovuto usare un for visto che document.getElementByClass name restituisce una lista di elementi(anche se ho un solo elemento)
    for (var i = 0; i < daNascondere.length; i++) {
    daNascondere[i].style.display = 'none';
    }
    daMostrare.style.display = 'block'; 
}

//funzione per tornare indietro una volta che si è aperto informazioni
function reverse() {
    var daMostrare = document.getElementsByClassName('navbar');
    var daNascondere = document.getElementById('Faq');
    
    for (var i = 0; i < daMostrare.length; i++) {
    daMostrare[i].style.display = 'block';
    }
    daNascondere.style.display = 'none'; 
}
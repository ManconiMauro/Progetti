<?php
session_start();
$htmlint = <<<NOW
<HTML>
  <HEAD>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
  .menu {
  background: #333;
  padding: 1rem; 
  }
  
  .menu-item {
  background: none;
  border: none;
  color: #fff;
  cursor: pointer;
  padding: 1rem;
  }
  
  .actions {
  background: #333;
  padding: 1rem; 
  }
  
  .action-item {
  background: none;
  border: none;
  color: #fff;
  cursor: pointer;
  padding: 1rem; 
  }
    .container {
        border: 1px solid #ddd;
        padding: 9px; 
    }

    .container table {
        border-collapse: collapse;
        width: 100%;
    }

    .container th, 
    .container td {
        padding: 1px;
        border: 1px solid #ddd; 
    }

    .container th {
        background-color: #4CAF50;
        color: white; 
    }

  h1 {
  color: #2854A1;
  }
  select {
  background: #04befe;
  color: #fff;
  }
  html, body {
  margin: 0;
  min-height: 100%;
  background-color: #f2f2f2;
  }

  </style>
  </head>
<BODY> 
NOW;
//print_R($_POST);

if (isset($_POST['update']) && isset($_SESSION['tbl'])) {//sono stati passati correttamente i dati
    $tbl = $_SESSION['tbl'];

    $conn = pg_connect("host=localhost port=5432 dbname=finale user=postgres password=SQL-secret5");
    if (!$conn) {
    echo 'Connessione al database fallita.';
    exit();
    }
    else {
      switch ($tbl){
        case 'ambulatoriointerno':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Ambulatorio</th><td><input type=\"text\" name=\"codiceai\" value='" . $array['codiceai'] . "'  required readonly></td></tr>");
                print ("<tr><th>Id Stanza</th><td><input type=\"text\" name=\"stanzaid\" value='" . $array['stanzaid'] . "' required></td</tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'ambulatorioesterno':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
              $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Ambulatorio</th><td><input type=\"text\" name=\"codiceae\" value='" . $array['codiceae'] . "'  required readonly></td></tr>");
                print ("<tr><th>Indirizzo</th><td><input type=\"text\" name=\"indirizzo\" value='" . $array['indirizzo'] . "' required></td</tr>");
                print ("<tr><th>Telefono</th><td><input type=\"text\" name=\"telefono\" value='" . $array['telefono'] . "'  required></td></tr>");
                print ("<tr><th>Orario Apertura</th><td><input type=\"text\" name=\"orarioapertura\" value='" . $array['orarioapertura'] . "' required></td</tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'disponibilitaesterna':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
            print ($htmlint);
            print ("<div class=\'container\'>");
            print ("<table>");
            print ("<form action=\"modifica2.php\" method=\"POST\">");
            
            print ("<tr><th>Data</th><td><input type=\"text\" name=\"data\" value='" . $array['data'] . "' required readonly></td</tr>");
            print ("<tr><th>Ora</th><td><input type=\"text\" name=\"ora\" value='" . $array['ora'] . "' required readonly></td</tr>");
            print ("<tr><th>Codice Ambulatorio</th><td><input type=\"text\" name=\"codiceae\" value='" . $array['codiceae'] . "'  required readonly></td></tr>");
            
            print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
            print ("</form>");
            print ("</table>");
            print("</div>");
          }
        break;
        case 'disponibilitainterna':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
          $array = pg_fetch_array($result);
            print ($htmlint);
            print ("<div class=\'container\'>");
            print ("<table>");
            print ("<form action=\"modifica2.php\" method=\"POST\">");
            
            print ("<tr><th>Data</th><td><input type=\"text\" name=\"data\" value='" . $array['data'] . "' required readonly></td</tr>");
            print ("<tr><th>Ora</th><td><input type=\"text\" name=\"ora\" value='" . $array['ora'] . "' required readonly></td</tr>");
            print ("<tr><th>Codice Ambulatorio</th><td><input type=\"text\" name=\"codiceae\" value='" . $array['codiceai'] . "'  required readonly></td></tr>");
            
            print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
            print ("</form>");
            print ("</table>");
            print("</div>");
          }
        break;
        case 'esame':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
              $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice</th><td><input type=\"text\" name=\"codice\" value='" . $array['codice'] . "'  required readonly></td></tr>");
                print ("<tr><th>Descrizione</th><td><input type=\"text\" name=\"descrizione\" value='" . $array['descrizione'] . "' required></td</tr>");
                print ("<tr><th>Costo Regime Privato</th><td><input type=\"text\" name=\"costopr\" value='" . $array['costopr'] . "'  required></td></tr>");
                print ("<tr><th>Costo Assistenza Pubblica</th><td><input type=\"text\" name=\"costopu\" value='" . $array['costopu'] . "' required></td</tr>");
                print ("<tr><th>Codice Prenotazione</th><td><input type=\"text\" name=\"codiceprenotazione\" value='" . $array['codiceprenotazione'] . "'  required readonly></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'impiegato':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
              $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
                print ("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" value='" . $array['nome'] . "' required></td</tr>");
                print ("<tr><th>Cognome</th><td><input type=\"text\" name=cognome\" value='" . $array['cognome'] . "'  required></td></tr>");
                print ("<tr><th>Data Assunzione</th><td><input type=\"text\" name=\"dataassunzione\" value='" . $array['dataassunzione'] . "' required></td</tr>");
                print ("<tr><th>Nome Reparto</th><td><input type=\"text\" name=\"nomereparto\" value='" . $array['nomereparto'] . "'  required></td></tr>");
                print ("<tr><th>Codice Ospedale</th><td><input type=\"text\" name=\"codiceospedale\" value='" . $array['codiceospedale'] . "' required></td</tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
          break;
        case 'infermiere':
            $query = "SELECT * FROM $tbl;";
            $result = pg_query($conn, $query);
            if (!$result) {
                echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
                exit();
            }else{
              $array = pg_fetch_array($result);
                  print ($htmlint);
                  print ("<div class=\'container\'>");
                  print ("<table>");
                  print ("<form action=\"modifica2.php\" method=\"POST\">");
                  
                  print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
                  
                  print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                  print ("</form>");
                  print ("</table>");
                  print("</div>");
            }
        break;
        case 'letto':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Numero Letto</th><td><input type=\"text\" name=\"numero\" value='" . $array['numero'] . "'  required readonly></td></tr>");
                print ("<tr><th>Id Stanza</th><td><input type=\"text\" name=\"idstanza\" value='" . $array['idstanza'] . "'  required readonly></td></tr>");
                

                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'medico':
          $query = "SELECT * FROM $tbl;";
            $result = pg_query($conn, $query);
            if (!$result) {
                echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
                exit();
            }else{
              $array = pg_fetch_array($result);
                  print ($htmlint);
                  print ("<div class=\'container\'>");
                  print ("<table>");
                  print ("<form action=\"modifica2.php\" method=\"POST\">");
                  
                  print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
                  
                  print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                  print ("</form>");
                  print ("</table>");
                  print("</div>");
            }
        break;
        case 'ospedale':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
              $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice</th><td><input type=\"text\" name=\"codice\" value='" . $array['codice'] . "'  required readonly></td></tr>");
                print ("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" value='" . $array['nome'] . "' required></td</tr>");
                print ("<tr><th>Città</th><td><input type=\"text\" name=\"citta\" value='" . $array['citta'] . "'  required></td></tr>");
                print ("<tr><th>Indirizzo</th><td><input type=\"text\" name=\"indirizzo\" value='" . $array['indirizzo'] . "' required></td</tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'patologia':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
            print ($htmlint);
            print ("<div class=\'container\'>");
            print ("<table>");
            print ("<form action=\"modifica2.php\" method=\"POST\">");
            
            print ("<tr><th>Nome Patologia</th><td><input type=\"text\" name=\"nome\" value='" . $array['nome'] . "' required readonly></td</tr>");
            print ("<tr><th>Codice Fiscale paziente</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
            print ("<tr><th>Data Inizio Ricovero</th><td><input type=\"text\" name=\"datainizio\" value='" . $array['datainizio'] . "' required readonly></td</tr>");
            
            print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
            print ("</form>");
            print ("</table>");
            print("</div>");
          }
        break;
        case 'paziente':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
              $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codice_fiscale\" value='" . $array['codice_fiscale'] . "'  required readonly></td></tr>");
                print ("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" value='" . $array['nome'] . "' required></td</tr>");
                print ("<tr><th>Cognome</th><td><input type=\"text\" name=\"cognome\" value='" . $array['cognome'] . "'  required></td></tr>");
                print ("<tr><th>Data Nascita</th><td><input type=\"text\" name=\"datanascita\" value='" . $array['datanascita'] . "' required></td</tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'personaleamministrativo':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'primario':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'prenotazioneesame':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
              $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice</th><td><input type=\"text\" name=\"codice\" value='" . $array['codice'] . "'  required readonly></td></tr>");
                print ("<tr><th>Medico Prescrittore</th><td><input type=\"text\" name=\"medicoprescrittore\" value='" . $array['medicoprescrittore'] . "' required></td</tr>");
                print ("<tr><th>Ora</th><td><input type=\"text\" name=ora\" value='" . $array['ora'] . "'  required></td></tr>");
                print ("<tr><th>Data</th><td><input type=\"text\" name=\"data\" value='" . $array['data'] . "' required></td</tr>");
                print ("<tr><th>Regime</th><td><input type=\"text\" name=\"regime\" value='" . $array['regime'] . "'  required></td></tr>");
                print ("<tr><th>Codice Fiscale Paziente</th><td><input type=\"text\" name=\"pazientecodicefiscale\" value='" . $array['pazientecodicefiscale'] . "' required></td</tr>");
                print ("<tr><th>Tipo Esame</th><td><input type=\"text\" name=\"tipoesame\" value='" . $array['tipoesame'] . "'  required></td></tr>");
                print ("<tr><th>Avvertenze</th><td><input type=\"text\" name=\"avvertenze\" value='" . $array['avvertenze'] . "' required></td</tr>");
                print ("<tr><th>Data Prenotazione</th><td><input type=\"text\" name=dataprenotazione\" value='" . $array['dataprenotazione'] . "'  required></td></tr>");
                print ("<tr><th>Urgenza</th><td><input type=\"text\" name=\"urgenza\" value='" . $array['urgenza'] . "' required></td</tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'prontosoccorso':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" value='" . $array['nome'] . "'  required readonly></td></tr>");
                print ("<tr><th>Codice Ospedale</th><td><input type=\"text\" name=\"codiceospedale\" value='" . $array['codiceospedale'] . "'  required readonly></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'reparto':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" value='" . $array['nome'] . "'  required readonly></td></tr>");
                print ("<tr><th>Codice Ospedale</th><td><input type=\"text\" name=\"codiceospedale\" value='" . $array['codiceospedale'] . "'  required readonly></td></tr>");
                print ("<tr><th>Telefono</th><td><input type=\"text\" name=\"telefono\" value='" . $array['telefono'] . "'  required></td></tr>");
                print ("<tr><th>Orario Visite</th><td><input type=\"text\" name=\"orariovisita\" value='" . $array['orariovisita'] . "'  required></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'ricovero':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
                print ("<tr><th>Data Inizio</th><td><input type=\"text\" name=\"datainizio\" value='" . $array['datainizio'] . "'  required readonly></td></tr>");
                print ("<tr><th>Data Fine</th><td><input type=\"text\" name=\"datafine\" value='" . $array['datafine'] . "'  required></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'salaoperatoria':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Nome</th><td><input type=\"text\" name=\"nome\" value='" . $array['nome'] . "'  required readonly></td></tr>");
                print ("<tr><th>Id Stanza</th><td><input type=\"text\" name=\"idstanza\" value='" . $array['idstanza'] . "'  required readonly></td></tr>");
                

                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'sostituzione':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Codice Fiscale Primario</th><td><input type=\"text\" name=\"cfprimario\" value='" . $array['cfprimario'] . "'  required readonly></td></tr>");
                print ("<tr><th>Codice Fiscale Vice</th><td><input type=\"text\" name=\"cfvice\" value='" . $array['cfvice'] . "'  required readonly></td></tr>");
                print ("<tr><th>Data Inizio</th><td><input type=\"text\" name=\"datainizio\" value='" . $array['datainizio'] . "'  required readonly></td></tr>");
                print ("<tr><th>Data Fine</th><td><input type=\"text\" name=\"datafine\" value='" . $array['datafine'] . "'  required></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'specializzazione':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Nome Specializzazione</th><td><input type=\"text\" name=\"nomespecializzazione\" value='" . $array['nomespecializzazione'] . "'  required readonly></td></tr>");
                print ("<tr><th>Primario</th><td><input type=\"text\" name=\"primario\" value='" . $array['primario'] . "'  required readonly></td></tr>");
                

                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'stanza':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Id</th><td><input type=\"text\" name=\"id\" value='" . $array['id'] . "'  required readonly></td></tr>");
                print ("<tr><th>Numero</th><td><input type=\"text\" name=\"numero\" value='" . $array['numero'] . "'  required></td></tr>");
                print ("<tr><th>Piano</th><td><input type=\"text\" name=\"piano\" value='" . $array['piano'] . "'  required></td></tr>");
                print ("<tr><th>Nome Reparto</th><td><input type=\"text\" name=\"nomereparto\" value='" . $array['nomereparto'] . "'  required></td></tr>");
                print ("<tr><th>Codice Ospedale</th><td><input type=\"text\" name=\"codiceospedale\" value='" . $array['codiceospedale'] . "'  required></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'turnoinfermiere':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Ora Inizio</th><td><input type=\"text\" name=\"orainizio\" value='" . $array['orainizio'] . "'  required readonly></td></tr>");
                print ("<tr><th>Ora Fine</th><td><input type=\"text\" name=\"orafine\" value='" . $array['orafine'] . "'  required></td></tr>");
                print ("<tr><th>Data</th><td><input type=\"text\" name=\"data\" value='" . $array['data'] . "'  required readonly></td></tr>");
                print ("<tr><th>Codice Fiscale Infermiere</th><td><input type=\"text\" name=\"codicefiscaleinfermiere\" value='" . $array['codicefiscaleinfermiere'] . "'  required readonly></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'turnomedico':
          $query = "SELECT * FROM $tbl;";
          $result = pg_query($conn, $query);
          if (!$result) {
              echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
              exit();
          }else{
            $array = pg_fetch_array($result);
                print ($htmlint);
                print ("<div class=\'container\'>");
                print ("<table>");
                print ("<form action=\"modifica2.php\" method=\"POST\">");
                
                print ("<tr><th>Ora Inizio</th><td><input type=\"text\" name=\"orainizio\" value='" . $array['orainizio'] . "'  required readonly></td></tr>");
                print ("<tr><th>Ora Fine</th><td><input type=\"text\" name=\"orafine\" value='" . $array['orafine'] . "'  required></td></tr>");
                print ("<tr><th>Data</th><td><input type=\"text\" name=\"data\" value='" . $array['data'] . "'  required readonly></td></tr>");
                print ("<tr><th>Codice Fiscale Medico</th><td><input type=\"text\" name=\"codicefiscalemedico\" value='" . $array['codicefiscalemedico'] . "'  required readonly></td></tr>");
                
                print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                print ("</form>");
                print ("</table>");
                print("</div>");
          }
        break;
        case 'viceprimario':
          $query = "SELECT * FROM $tbl;";
            $result = pg_query($conn, $query);
            if (!$result) {
                echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
                exit();
            }else{
              $array = pg_fetch_array($result);
                  print ($htmlint);
                  print ("<div class=\'container\'>");
                  print ("<table>");
                  print ("<form action=\"modifica2.php\" method=\"POST\">");
                  
                  print ("<tr><th>Codice Fiscale</th><td><input type=\"text\" name=\"codicefiscale\" value='" . $array['codicefiscale'] . "'  required readonly></td></tr>");
                  
                  print ("<tr><td><input type=\"submit\" name=\"toupdate\" value=\"Invia\"></td></tr>");
                  print ("</form>");
                  print ("</table>");
                  print("</div>");
            }
        break;
        default:
            echo "Non dovresti entrare qui";
        break;
        };
      };
}
else {//non sono stati passati correttamente i dati
  print ($htmlint);
  echo "Non risultano dati passati<br>";
  echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
}

?>
</BODY>
</HTML>
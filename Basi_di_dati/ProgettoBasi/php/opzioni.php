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
        padding: 6px; 
    }

    .container table {
        border-collapse: collapse;
        width: 90%;
    }
 
    .container td {
        padding: 1px;
        border: 1px solid #ddd; 
    }

    .container th {
        padding: 1px;
        border: 1px solid #ddd;
        background-color: #4CAF50;
        color: white; 
    }


  .containersost {
        border: 1px solid #ddd;
        padding: 6px; 
    }

    .containersost table {
        border-collapse: collapse;
        width: 90%;
    }

    .containersost td {
        padding: 1px;
        border: 1px solid #ddd; 
    }

    .containersost th {
        padding: 1px;
        border: 1px solid #ddd;
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

if (isset($_POST['tabella']) AND isset($_POST['operazione'])){ //dati passati correttamente
    $tbl = $_POST['tabella'];
    $op = $_POST['operazione'];
    $_SESSION['tbl'] = $tbl; //inizio sessione

    $conn = pg_connect("host=localhost port=5432 dbname=cazzo user=postgres password=SQL-secret5");
    if (!$conn) {//caso connessione fallita
        echo 'Connessione al database fallita.';
        exit();
    }else{ //connessione al DB riuscita
        
        switch ($op) {//quale operazione svolggere
            case 'Inserisci':
                switch ($tbl){
                    case 'ambulatoriointerno':
                        print ($htmlint);

                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Ambulatorio Interno</h1>

                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Ambulatorio</th><td><input type='text' name='codiceai' required></td></tr>
                        <tr><th>ID Stanza Ambulatorio</th><td><input type='text' name='stanzaid' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>

                          </div>

                        ";

                    break;
                    case 'ambulatorioesterno':
                        print ($htmlint);

                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Ambulatorio Esterno</h1>

                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Ambulatorio</th><td><input type='text' name='codiceae' required></td></tr>
                        <tr><th>Indirizzo</th><td><input type='text' name='indirizzo' required></td></tr>
                        <tr><th>Telefono</th><td><input type='text' name='telefono' required></td></tr>
                        <tr><th>Orario Apertura</th><td><input type='text' name='orarioapertura' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>

                          </div>

                        ";

                    break;
                    case 'disponibilitaesterna':
                        print ($htmlint);

                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuova Disponibilità Ambulatorio Esterno</h1>

                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Ambulatorio</th><td><input type='text' name='codiceae' required></td></tr>
                        <tr><th>Data</th><td><input type='date' name='data' required></td></tr>
                        <tr><th>Ora</th><td><input type='text' name='ora' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>

                          </div>

                        ";

                    break;
                    case 'disponibilitainterna':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuova Disponibilità Ambulatorio Interno</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Ambulatorio</th><td><input type='text' name='codiceai' required></td></tr>
                        <tr><th>Data</th><td><input type='date' name='data' required></td></tr>
                        <tr><th>Ora</th><td><input type='text' name='ora' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'esame':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Esame</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice esame</th><td><input type='text' name='codice' required></td></tr>
                        <tr><th>Descrizione</th><td><input type='text' name='descrizione' required></td></tr>
                        <tr><th>Costo Regime Privato</th><td><input type='number' name='costopr' required></td></tr>
                        <tr><th>Costo Regime Assistenza Pubblica</th><td><input type='number' name='costopu' required></td></tr>
                        <tr><th>Codice prenotazione</th><td><input type='number' name='codiceprenotazione' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'impiegato':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Impiegato</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale</th><td><input type='text' name='codicefiscale' required></td></tr>
                        <tr><th>Nome</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Cognome</th><td><input type='text' name='cognome' required></td></tr>
                        <tr><th>Data Assunzione</th><td><input type='date' name='dataassunzione' required></td></tr>
                        <tr><th>Nome Reparto lavorativo</th><td><input type='text' name='nomereparto' required></td></tr>
                        <tr><th>Codice Ospedale</th><td><input type='text' name='codiceospedale' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                        break;
                    case 'infermiere':
                            print ($htmlint);
                            
                            echo " 
                            <div class='container'>
                            <h1>Inserimento Nuovo Infermiere</h1>
                            
                            <table>
                            <form action='inserimento.php' method='POST'>
                            <tr><th>Codice Fiscale</th><td><input type='text' name='cfimpiegato' required></td></tr>
                            <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                            </form>
                            </table>
                            
                              </div>
                            
                            ";
                            
                    break;
                    case 'letto':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Letto</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Numero del Letto</th><td><input type='number' name='numero' required></td></tr>
                        <tr><th>Id della Stanza</th><td><input type='number' name='idstanza' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'medico':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Medico</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale</th><td><input type='text' name='cfimpiegato' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'ospedale':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Ospedale</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice</th><td><input type='text' name='codice' required></td></tr>
                        <tr><th>Nome Ospedale</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Città</th><td><input type='text' name='citta' required></td></tr>
                        <tr><th>Indirizzo</th><td><input type='text' name='indirizzo' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'patologia':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Ospedale</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Nome Patologia</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Codice Fiscale Paziente</th><td><input type='text' name='codicefiscale' required></td></tr>
                        <tr><th>Data Ricovero</th><td><input type='date' name='datainizio' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'paziente':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Paziente</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale</th><td><input type='text' name='codice_fiscale' required></td></tr>
                        <tr><th>Nome</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Cognome</th><td><input type='text' name='cognome' required></td></tr>
                        <tr><th>Data Nascita</th><td><input type='date' name='datanascita' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'personaleamministrativo':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Impiegato Amministrativo</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale</th><td><input type='text' name='cfimpiegato' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'primario':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Primario</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale</th><td><input type='text' name='cfimpiegato' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'prenotazioneesame':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuova Prenotazione</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Prenotazione</th><td><input type='text' name='codice' required></td></tr>
                        <tr><th>Medico</th><td><input type='text' name='medicoprescrittore' required></td></tr>
                        <tr><th>Ora Prenotazione</th><td><input type='text' name='ora' required></td></tr>
                        <tr><th>Data Prenotazione</th><td><input type='date' name='data' required></td></tr>
                        <tr><th>Regime</th><td><input type='text' name='regime' required></td></tr>
                        <tr><th>Codice Fiscale Paziente</th><td><input type='text' name='pazientecodicefiscale' required></td></tr>
                        <tr><th>Tipo di Esame</th><td><input type='text' name='tipoesame' required></td></tr>
                        <tr><th>Avvertenze</th><td><input type='text' name='avvertenze' required></td></tr>
                        <tr><th>Urgenza</th><td><input type='text' name='urgenza' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'prontosoccorso':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Prontosoccorso</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Nome</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Codice Ospedale</th><td><input type='text' name='codiceospedale' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'reparto':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Reparto</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Nome Reparto</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Codice Ospedale</th><td><input type='text' name='codiceospedale' required></td></tr>
                        <tr><th>telefono</th><td><input type='text' name='telefono' required></td></tr>
                        <tr><th>Orario Visita</th><td><input type='text' name='orariovisita' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'ricovero':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Ricovero</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale Paziente</th><td><input type='text' name='codicefiscale' required></td></tr>
                        <tr><th>Data Inizio</th><td><input type='date' name='datainizio' required></td></tr>
                        <tr><th>Data Fine</th><td><input type='date' name='datafine' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'salaoperatoria':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuova Sala Operatoria</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Nome Sala</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Id della Stanza</th><td><input type='number' name='idstanza' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'sostituzione':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuova Sostituzione</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale Primario</th><td><input type='text' name='cfprimario' required></td></tr>
                        <tr><th>Codice Fiscale Viceprimario</th><td><input type='text' name='cfvice' required></td></tr>
                        <tr><th>Data Inizio</th><td><input type='date' name='datainizio' required></td></tr>
                        <tr><th>Data Fine</th><td><input type='date' name='datafine' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'specializzazione':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuova Specializzazione</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Nome Specializzazione</th><td><input type='text' name='nome' required></td></tr>
                        <tr><th>Codice Fiscale Primario</th><td><input type='text' name='primario' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'stanza':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuova Stanza</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Id Stanza</th><td><input type='number' name='id' required></td></tr>
                        <tr><th>Numero Stanza</th><td><input type='number' name='numero' required></td></tr>
                        <tr><th>piano Stanza</th><td><input type='number' name='piano' required></td></tr>
                        <tr><th>Nome Reparto</th><td><input type='text' name='nomereparto' required></td></tr>
                        <tr><th>Codice Ospedale</th><td><input type='text' name='codiceospedale' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'turnoinfermiere':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Turno Infermiere/h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Ora Inizio</th><td><input type='date' name='orainizio' required></td></tr>
                        <tr><th>Ora Fine</th><td><input type='text' name='orafine' required></td></tr>
                        <tr><th>Data</th><td><input type='date' name='data' required></td></tr>
                        <tr><th>Codice Fiscale Infermiere</th><td><input type='text' name='codicefiscaleinfermiere' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'turnomedico':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Turno Medico/h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Ora Inizio</th><td><input type='date' name='orainizio' required></td></tr>
                        <tr><th>Ora Fine</th><td><input type='text' name='orafine' required></td></tr>
                        <tr><th>Data</th><td><input type='date' name='data' required></td></tr>
                        <tr><th>Codice Fiscale Medico</th><td><input type='text' name='codicefiscalemedico' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;
                    case 'viceprimario':
                        print ($htmlint);
                        
                        echo " 
                        <div class='container'>
                        <h1>Inserimento Nuovo Viceprimario</h1>
                        
                        <table>
                        <form action='inserimento.php' method='POST'>
                        <tr><th>Codice Fiscale</th><td><input type='text' name='cfimpiegato' required></td></tr>
                        <tr><th> <input type='submit' name='inserimento' value='Inserisci'></th><tr>
                        </form>
                        </table>
                        
                          </div>
                        
                        ";
                        
                    break;

                    };
                    break;       
            case 'Mostra':
                        $query = "SELECT * FROM $tbl;";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
                            exit();
                        }else{
                            $queryc = "select column_name from information_schema.columns where table_name='" . $_POST['tabella'] . "' order by ordinal_position;";
                            $resultc = pg_query($conn, $queryc); 
                            if (!$resultc) {
                                echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
                                exit();
                            }else {
                                $columns = array(); print ($htmlint);
                                echo '<br><table class=\'container\'>';
                                echo '<tr>';
                                while ($rowc = pg_fetch_array($resultc)) {
                                    $columns[] = $rowc[0];
                                    echo '<th>' . $rowc[0] . '</th>'; 
                                };
                                echo '</tr>';
                                while ($row = pg_fetch_array($result)) {
                                    echo '<tr>';
                                    for ($i = 0;$i < count($columns);$i++) {
                                            echo '<td>' . $row[$i] . '</td>'; 
                                    };
                                    echo '</tr>'; 
                                };
                                echo '</table><br>';
                                echo "Torna al <a href='index.php'>menu</a>";
                            }
                        }
             break;
            case 'Modifica':
                    switch ($tbl){
                        case 'ambulatoriointerno':
                        print ($htmlint);
                        $query = "SELECT * FROM $tbl;";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            echo "Si è verificato un errore.<br/>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo '<br>
                            <div class=\'container\'>
                            <table>
                            <tr>
                            <th>Codice</td>
                            <th>Id Stanza</th>
                            </tr>';
                            print ("<form action=\"modifica.php\" method=\"POST\">");
                            //passo le informazioni della tupla selezionata da aggiornare
                                while ($row = pg_fetch_array($result)) {
                                    echo '<tr>
                                    <td>' . $row['codiceai'] . '</td>
                                    <td>' . $row['stanzaid'] . '</td>
                            </tr>';
                                };
                            echo '</table>';
                            echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                            echo "</form></div>";
                        }
                        break;
                        case 'ambulatorioesterno':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice</td>
                                <th>Indirizzo</th>
                                <th>Telefono</th>
                                <th>Orario Apertura</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codiceae'] . '</td>
                                        <td>' . $row['indirizzo'] . '</td>
                                        <td>' . $row['telefono'] . '</td>
                                        <td>' . $row['orarioapertura'] . '</td>
                                    </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'disponibilitaesterna':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Data</th>
                                <th>Ora</th>
                                <th>Codice Ambulatorio</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['ora'] . '</td>
                                        <td>' . $row['data'] . '</td>
                                        <td>' . $row['codiceae'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'disponibilitainterna':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Data</th>
                                <th>Ora</th>
                                <th>Codice Ambulatorio</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['ora'] . '</td>
                                        <td>' . $row['data'] . '</td>
                                        <td>' . $row['codiceai'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'esame':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice</td>
                                <th>Descrizione</th>
                                <th>Costo Privato</th>
                                <th>Costo Assistanza Pubblica</th>
                                <th>Codice Prenotazione</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codice'] . '</td>
                                        <td>' . $row['descrizione'] . '</td>
                                        <td>' . $row['costopr'] . '</td>
                                        <td>' . $row['costopu'] . '</td>
                                        <td>' . $row['codiceprenotazione'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'impiegato':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale</td>
                                <th>Nome</th>
                                <th>Cognome</td>
                                <th>Data Assunzione</th>
                                <th>Nome Reparto</th>
                                <th>Codice Ospedale</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codicefiscale'] . '</td>
                                        <td>' . $row['nome'] . '</td>
                                        <td>' . $row['cognome'] . '</td>
                                        <td>' . $row['dataassunzione'] . '</td>
                                        <td>' . $row['nomereparto'] . '</td>
                                        <td>' . $row['codiceospedale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                            break;
                        case 'infermiere':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codicefiscale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'letto':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Numero Letto</td>
                                <th>Id Stanza</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['numero'] . '</td>
                                        <td>' . $row['idstanza'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'medico':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codicefiscale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'ospedale':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice</td>
                                <th>Nome</th>
                                <th>Città</th>
                                <th>Indirizzo</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codice'] . '</td>
                                        <td>' . $row['nome'] . '</td>
                                        <td>' . $row['citta'] . '</td>
                                        <td>' . $row['indirizzo'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'patologia':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Nome Patologia</th>sss
                                <th>Codice Fiscale Paziente</td>
                                <th>Data Inizio Ricovero</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['nome'] . '</td>
                                        <td>' . $row['codicefiscale'] . '</td>
                                        <td>' . $row['datainizio'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'paziente':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice</td>
                                <th>Nome</th>
                                <th>Cognome</td>
                                <th>Data Nascita</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codice_fiscale'] . '</td>
                                        <td>' . $row['nome'] . '</td>
                                        <td>' . $row['cognome'] . '</td>
                                        <td>' . $row['datanascita'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                                }
                        break;
                        case 'personaleamministrativo':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codicefiscale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'primario':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codicefiscale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'prenotazioneesame':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice</td>
                                <th>Medico Prescrittore</th>
                                <th>Ora</td>
                                <th>Data</th>
                                <th>Regime</th>
                                <th>Codice Fiscale Paziente</th>
                                <th>Tipo Esame</td>
                                <th>Avvertenze</th>
                                <th>Data Prenotazione</th>
                                <th>Urgenza</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codice'] . '</td>
                                        <td>' . $row['medicoprescrittore'] . '</td>
                                        <td>' . $row['ora'] . '</td>
                                        <td>' . $row['data'] . '</td>
                                        <td>' . $row['regime'] . '</td>
                                        <td>' . $row['pazientecodicefiscale'] . '</td>
                                        <td>' . $row['tipoesame'] . '</td>
                                        <td>' . $row['avvertenze'] . '</td>
                                        <td>' . $row['dataprenotazione'] . '</td>
                                        <td>' . $row['urgenza'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'prontosoccorso':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Nome</td>
                                <th>Codice Ospedale</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['nome'] . '</td>
                                        <td>' . $row['codiceospedale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'reparto':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Nome</th>
                                <th>Codice Ospedale</td>
                                <th>Telefono</td>
                                <th>Orario Visite</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['nome'] . '</td>
                                        <td>' . $row['codiceospedale'] . '</td>
                                        <td>' . $row['telefono'] . '</td>
                                        <td>' . $row['orariovisita'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'ricovero':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale paziente</td>
                                <th>Data Inizio</td>
                                <th>Data Fine</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codicefiscale'] . '</td>
                                        <td>' . $row['datainizio'] . '</td>
                                        <td>' . $row['datafine'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'salaoperatoria':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Nome</td>
                                <th>Id Stanza</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['nome'] . '</td>
                                        <td>' . $row['idstanza'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'sostituzione':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale Primario</td>
                                <th>Codice Fiscale Vice</td>
                                <th>Data Inizio</td>
                                <th>Data Fine</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['cfprimario'] . '</td>
                                        <td>' . $row['cfvice'] . '</td>
                                        <td>' . $row['datainizio'] . '</td>
                                        <td>' . $row['datafine'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'specializzazione':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Nome Specializzazione</td>
                                <th>Primario</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['nomespecializzazione'] . '</td>
                                        <td>' . $row['primario'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'stanza':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Id</th>
                                <th>Numero</td>
                                <th>Piano</td>
                                <th>Nome Reparto</th>
                                <th>Codice Ospedale</td>
                                <th>Id Stanza</th>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['id'] . '</td>
                                        <td>' . $row['numero'] . '</td>
                                        <td>' . $row['piano'] . '</td>
                                        <td>' . $row['nomerepart'] . '</td>
                                        <td>' . $row['codiceospedale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'turnoinfermiere':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Ora Inizio</td>
                                <th>Ora Fine</th>
                                <th>Data</td>
                                <th>Codice Fiscale Infermiere</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['orainizio'] . '</td>
                                        <td>' . $row['orafine'] . '</td>
                                        <td>' . $row['data'] . '</td>
                                        <td>' . $row['codicefiscaleinfermiere'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'turnomedico':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Ora Inizio</td>
                                <th>Ora Fine</th>
                                <th>Data</td>
                                <th>Codice Fiscale Medico</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['orainizio'] . '</td>
                                        <td>' . $row['orafine'] . '</td>
                                        <td>' . $row['data'] . '</td>
                                        <td>' . $row['codicefiscalemedico'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        case 'viceprimario':
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo '<br>
                                <div class=\'container\'>
                                <table>
                                <tr>
                                <th>Codice Fiscale</td>
                                </tr>';
                                print ("<form action=\"modifica.php\" method=\"POST\">");
                                //passo le informazioni della tupla selezionata da aggiornare
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>
                                        <td>' . $row['codicefiscale'] . '</td>
                                </tr>';
                                    };
                                echo '</table>';
                                echo "<input type=\"submit\" name=\"update\" value=\"Modifica\">";
                                echo "</form></div>";
                            }
                        break;
                        };
                break;
            case 'Elimina':
                switch($tbl){
                            default:
                            print ($htmlint);
                            $query = "SELECT * FROM $tbl ;";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
                                exit();
                            }else{
                                
                                $queryc = "select column_name from information_schema.columns where table_name='$tbl' order by ordinal_position;";
                                $resultc = pg_query($conn, $queryc); 
                                if (!$resultc) {
                                    echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
                                    exit();
                                }else {
                                    
                                    $columns = array(); 
                                    echo '<br><table class=\'container\'>';
                                    echo '<tr>';
                                    while ($rowc = pg_fetch_array($resultc)) {
                                        $columns[] = $rowc[0];
                                        echo '<th>' . $rowc[0] . '</th>'; 
                                        };
                                    echo '</tr>';
                                    print ("<div> <form action=\"elimina.php\" method=\"POST\">");
                                    while ($row = pg_fetch_array($result)) {
                                        echo '<tr>';
                                        for ($i = 0;$i < count($columns);$i++) {
                                                echo '<td>' . $row[$i] . '</td>'; 
                                        };
                                        echo '<td><input type="radio" name="todelete" value=' .$row[0] . ' ></td>';  
                                        echo '</tr>'; 
                                    };
                                    echo '</table><br>';
                                    echo "<input type=\"submit\" name=\"delete\" value=\"Elimina\">";
                                    echo "</form></div> <br>";
                                    echo "Torna al <a href='index.php'>menu</a>";
                                    }
                                }
                            break;
                    break; 
                    }
                break; 
                case 'Vice1Sost':
                    $query = "SELECT i.nome, i.cognome
                    FROM impiegato i JOIN viceprimario v ON i.codicefiscale = v.cfimpiegato
                    JOIN (
                        SELECT cfvice
                        FROM sostituzione
                        GROUP BY cfvice
                        HAVING COUNT(cfprimario) = 1
                    ) s ON v.cfimpiegato = s.cfvice;";
                    $result = pg_query($conn, $query);
                    if (!$result) {
                        echo "Si è verificato un errore.<br/>" . pg_last_error($conn);
                        exit();
                    } else {
                        // Stampa l'header HTML per la tabella
                        echo '<br><table class=\'containersost\'>';
                        echo '<tr>';
                        echo '<th>Nome</th>';
                        echo '<th>Cognome</th>';
                        echo '</tr>';
                        
                        // Itera sui risultati e stampa i dati
                        while ($row = pg_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['nome'] . '</td>';
                            echo '<td>' . $row['cognome'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table><br>';
                        echo "Torna al <a href='index.php'>menu</a>";
                    }
                break;
                case 'Vice2Sost':
                    $query = "SELECT i.nome, i.cognome
                    FROM impiegato i JOIN viceprimario v ON i.codicefiscale = v.cfimpiegato
                    JOIN (
                        SELECT cfvice
                        FROM sostituzione
                        GROUP BY cfvice
                        HAVING COUNT(cfprimario) >= 2
                    ) s ON v.cfimpiegato = s.cfvice;";
                    $result = pg_query($conn, $query);
                    if (!$result) {
                        echo "Si è verificato un errore.<br/>" . pg_last_error($conn);
                        exit();
                    } else {
                        // Stampa l'header HTML per la tabella
                        echo '<br><table class=\'container\'>';
                        echo '<tr>';
                        echo '<th>Nome</th>';
                        echo '<th>Cognome</th>';
                        echo '</tr>';
                        
                        // Itera sui risultati e stampa i dati
                        while ($row = pg_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['nome'] . '</td>';
                            echo '<td>' . $row['cognome'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table><br>';
                        echo "Torna al <a href='index.php'>menu</a>";
                    }
                break;
                case 'Vice0Sost':
                    $query = "SELECT i.nome, i.cognome
                    FROM impiegato i JOIN viceprimario v ON i.codicefiscale = v.cfimpiegato
                    LEFT JOIN sostituzione s ON v.cfimpiegato = s.cfvice
                    WHERE s.cfvice IS NULL;";
                    $result = pg_query($conn, $query);
                    if (!$result) {
                        echo "Si è verificato un errore.<br/>" . pg_last_error($conn);
                        exit();
                    } else {
                        // Stampa l'header HTML per la tabella
                        echo '<br><table class=\'container\'>';
                        echo '<tr>';
                        echo '<th>Nome</th>';
                        echo '<th>Cognome</th>';
                        echo '</tr>';
                        
                        // Itera sui risultati e stampa i dati
                        while ($row = pg_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['nome'] . '</td>';
                            echo '<td>' . $row['cognome'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table><br>';
                        echo "Torna al <a href='index.php'>menu</a>";
                    }
            break;  
            case 'OrdineProcedure':
                $query = "SELECT p.numero, p.nome
                FROM Procedura p LEFT JOIN Trasporto t ON 
                WHERE t.id=1
                ORDER BY p.numero;";
                $result = pg_query($conn, $query);
                if (!$result) {
                    echo "Si è verificato un errore.<br/>" . pg_last_error($conn);
                    exit();
                } else {
                    // Stampa l'header HTML per la tabella
                    echo '<br><table class=\'container\'>';
                    echo '<tr>';
                    echo '<th>Ordine</th>';
                    echo '<th>Nome</th>';
                    echo '</tr>';
                    
                    // Itera sui risultati e stampa i dati
                    while ($row = pg_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['numero'] . '</td>';
                        echo '<td>' . $row['nome'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table><br>';
                    echo "Torna al <a href='index.php'>menu</a>";
                }
            break;       
        }
  }
}
else{
    print ($htmlint);
    echo "Non sono stati passati dei valori. <br>";
    echo "<a href='index.html'> Riprova <a/>";
}

//AGGIUSTARE REINSERIMENTO E METTERLO A DESTRA
?>

</body>
</html>

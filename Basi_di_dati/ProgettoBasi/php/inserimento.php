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
  background: #f2f2f2;
  border-radius: 10px;
  padding: 20px;
  width: 600px;
  margin-top: 150px; 
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
print ($htmlint);
if (isset($_POST['inserimento']) && isset($_SESSION['tbl'])) {//sono stati passati correttamente i dati
    $tbl = $_SESSION['tbl'];
    $conn = pg_connect("host=localhost port=5432 dbname=finale user=postgres password=SQL-secret5");
        if (!$conn) {
        echo 'Connessione al database fallita.';
        exit();
        }else{   
            switch ($tbl) {
                case 'ambulatoriointerno':
                    $codiceai = isset($_POST['codiceai']) ? $_POST['codiceai'] : NULL;
                    $stanzaid = isset($_POST['stanzaid']) ? $_POST['stanzaid'] : NULL;
                    
                    $query = "SELECT s.id
                    FROM stanza as s
                    WHERE s.id='$stanzaid'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "La stanza selezzionata non esiste.<br/>";
                        echo "Si prega di inserire prima i dati della stanza <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (codiceai, stanzaid) VALUES ('$codiceai','$stanzaid')";
                        echo ''.$query.'';
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'ambulatorioesterno':
                    $codiceae = isset($_POST['codiceae']) ? $_POST['codiceae'] : NULL;
                    $indirizzo = isset($_POST['indirizzo']) ? $_POST['indirizzo'] : NULL;
                    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : NULL;
                    $pattern = '/^[0-9]+$/';
                    $telefono = preg_match($pattern, $telefono) ? $telefono : 'non valido';
                    $orarioapertura = isset($_POST['orarioapertura']) ? $_POST['orarioapertura'] : NULL;

                    $query = "INSERT INTO " . $_SESSION['tbl'] . " (codiceae, indirizzo, telefono, orarioapertura) VALUES ('$codiceae', '$indirizzo', '$telefono', '$orarioapertura')";
                    if($telefono!='non valido' ){
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }   
                    }else {
                        print ($htmlint);
                        echo "I dati passati del telefono non sono conformi alle richieste.<br>";
                        echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                    }
                break;
                case 'disponibilitaesterna':
                    $codiceae = isset($_POST['codiceae']) ? $_POST['codiceae'] : NULL;
                    $data = isset($_POST['data']) ? $_POST['data'] : NULL;
                    $ora = isset($_POST['ora']) ? $_POST['ora'] : NULL;
                    
                    $query = "SELECT codiceae
                    FROM ambulatorioesterno
                    WHERE codiceae='$codiceae'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "L'ambulatorio' selezzionato non esiste.<br/>";
                        echo "Si prega di inserire prima i dati dell'ambulatorio' <a href='opzioni.php'>riprovare</a>";
                        exit();
                    }else {           
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (codiceae, data, ora) VALUES ('$codiceae','$data', '$ora')";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                print ($htmlint);
                                echo "Si è verificato un errore. <br>";
                                echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo "I dati passati salvati correttamente.<br>";
                                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                            }   
                    }
                break;
                case 'disponibilitainterna':
                    $codiceai = isset($_POST['codiceai']) ? $_POST['codiceai'] : NULL;
                    $data = isset($_POST['data']) ? $_POST['data'] : NULL;
                    $ora = isset($_POST['ora']) ? $_POST['ora'] : NULL;
                    
                    $query = "SELECT codiceai
                    FROM ambulatoriointerno
                    WHERE codiceai='$codiceai'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "L'ambulatorio' selezzionato non esiste.<br/>";
                        echo "Si prega di inserire prima i dati dell'ambulatorio' <a href='opzioni.php'>riprovare</a>";
                        exit();
                    }else {           
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (codiceai, data, ora) VALUES ('$codiceai','$data', '$ora')";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                print ($htmlint);
                                echo "Si è verificato un errore. <br>";
                                echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo "I dati passati salvati correttamente.<br>";
                                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                            }   
                    }
                break;
                case 'esame':
                    $codice = isset($_POST['codice']) ? $_POST['codice'] : NULL;
                    $descrizione = isset($_POST['descrizione']) ? $_POST['descrizione'] : NULL;
                    $costopr = isset($_POST['costopr']) ? $_POST['costopr'] : NULL;
                    $costopu = isset($_POST['costopu']) ? $_POST['costopu'] : NULL;
                    $codiceprenotazione = isset($_POST['codiceprenotazione']) ? $_POST['codiceprenotazione'] : NULL;

                    $query = "SELECT codice
                    FROM prenotazioneesame as p
                    WHERE p.codice='$codiceprenotazione'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "L'ambulatorio' selezzionato non esiste.<br/>";
                        echo "Si prega di inserire prima i dati dell'ambulatorio' <a href='opzioni.php'>riprovare</a>";
                        exit();
                    }else {         
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (codice, descrizione, costopr, costopu, codiceprenotazione) VALUES ('$codice','$descrizione','$costopr','$costopu', '$codiceprenotazione')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'impiegato':
                    $codicefiscale = isset($_POST['codicefiscale']) ? $_POST['codicefiscale'] : NULL;
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $cognome = isset($_POST['cognome']) ? $_POST['cognome'] : NULL;
                    $dataassunzione = isset($_POST['dataassunzione']) ? $_POST['dataassunzione'] : NULL;
                    $nomereparto = isset($_POST['nomereparto']) ? $_POST['nomereparto'] : NULL;
                    $codiceospedale = isset($_POST['codiceospedale']) ? $_POST['codiceospedale'] : NULL;

                    $nomereparto=strtolower($nomereparto);
                    $codicefiscale=strtolower($codicefiscale);  
                    
                    $query = "SELECT *
                    FROM reparto as r
                    WHERE nome='$nomereparto'
                    AND r.codiceospedale='$codiceospedale'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "I dati del Reparto non sono stati inseriti correttamente.<br/>";
                        echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        exit();
                    }else {         
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (codicefiscale, nome, cognome, dataassunzione, nomereparto, codiceospedale) VALUES ('$codicefiscale','$nome', '$cognome', '$dataassunzione','$nomereparto','$codiceospedale')";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                print ($htmlint);
                                echo "Si è verificato un errore. <br>";
                                echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo "I dati passati salvati correttamente.<br>";
                                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                            }   
                    }
                    break;
                case 'infermiere':
                    $cfimpiegato = isset($_POST['cfimpiegato']) ? $_POST['cfimpiegato'] : NULL;

                    $cfimpiegato=strtolower($cfimpiegato);  
                    
                    $query = "SELECT i.codicefiscale
                    FROM Impiegato as i
                    WHERE i.codicefiscale='$cfimpiegato'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun impiegato con questo codice.<br/>";
                        echo "Si prega di inserire prima i dati del impiegato <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (cfimpiegato) VALUES ('$cfimpiegato')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'letto':
                    $numero = isset($_POST['numero']) ? $_POST['numero'] : NULL;
                    $idstanza = isset($_POST['idstanza']) ? $_POST['idstanza'] : NULL;
                    
                    $query = "SELECT s.id
                    FROM stanza as s
                    WHERE s.id='$idstanza'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "La stanza selezzionata non esiste.<br/>";
                        echo "Si prega di inserire prima i dati della stanza <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (numero, idstanza) VALUES ('$numero','$idstanza')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'medico':
                    $cfimpiegato = isset($_POST['cfimpiegato']) ? $_POST['cfimpiegato'] : NULL;

                    $cfimpiegato=strtolower($cfimpiegato);  
                    
                    $query = "SELECT codicefiscale
                    FROM impiegato
                    WHERE codicefiscale='$cfimpiegato'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun impiegato con questo codice.<br/>";
                        echo "Si prega di inserire prima i dati del impiegato <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (cfimpiegato) VALUES ('$cfimpiegato')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'ospedale':
                    $codice = isset($_POST['codice']) ? $_POST['codice'] : NULL;
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $citta = isset($_POST['citta']) ? $_POST['citta'] : NULL;
                    $indirizzo = isset($_POST['indirizzo']) ? $_POST['indirizzo'] : NULL;
                    print ($htmlint);
                    
                    $query = "INSERT INTO " . $_SESSION['tbl'] . " (codice, nome, citta, indirizzo) VALUES ('$codice','$nome','$citta','$indirizzo')";
                    $result = pg_query($conn, $query);
                    if (!$result) {
                        print ($htmlint);
                        echo "Si è verificato un errore. <br>";
                        echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                        echo pg_last_error($conn);
                        exit();
                    }else {
                        print ($htmlint);
                        echo "I dati passati salvati correttamente.<br>";
                        echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                    }
                break;
                case 'patologia':
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $codicefiscale = isset($_POST['codicefiscale']) ? $_POST['codicefiscale'] : NULL;
                    $datainizio = isset($_POST['datainizio']) ? $_POST['datainizio'] : NULL;
                    print ($htmlint);

                    $nome=strtolower($nome);
                    $codicefiscale=strtolower($codicefiscale);
                    
                    $query = "SELECT *
                    FROM ricovero
                    WHERE codicefiscale='$codicefiscale'
                    AND datainizio='$datainizio'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun Ricovero con questo codice e data.<br/>";
                        echo "Si prega di inserire prima i dati del impiegato <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (codice, nome, citta, indirizzo) VALUES ('$codice','$nome','$citta','$indirizzo')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'paziente':
                    $codice_fiscale = isset($_POST['codice_fiscale']) ? $_POST['codice_fiscale'] : NULL;
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $cognome = isset($_POST['cognome']) ? $_POST['cognome'] : NULL;
                    $datanascita = isset($_POST['datanascita']) ? $_POST['datanascita'] : NULL;
                    
                    $codice_fiscale=strtolower($codice_fiscale);
                    $query = "INSERT INTO " . $_SESSION['tbl'] . " (codice_fiscale, nome, cognome, datanascita) VALUES ('$codice_fiscale','$nome', '$cognome', '$datanascita')";
                    if($datanascita < $dataattuale){
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }   
                    }else{
                        print ($htmlint);
                        echo "La data di nascita non è valida.<br>";
                        echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                    }
                break;
                case 'personaleamministrativo':
                    $cfimpiegato = isset($_POST['cfimpiegato']) ? $_POST['cfimpiegato'] : NULL;

                    $cfimpiegato=strtolower($cfimpiegato);
                    
                    $query = "SELECT codicefiscale
                    FROM impiegato
                    WHERE codicefiscale='$cfimpiegato'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun impiegato con questo codice.<br/>";
                        echo "Si prega di inserire prima i dati del impiegato <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (cfimpiegato) VALUES ('$cfimpiegato')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'primario':
                    $cfimpiegato = isset($_POST['cfimpiegato']) ? $_POST['cfimpiegato'] : NULL;

                    $cfimpiegato=strtolower($cfimpiegato);
                    
                    $query = "SELECT codicefiscale
                    FROM impiegato
                    WHERE codicefiscale='$cfimpiegato'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun impiegato con questo codice.<br/>";
                        echo "Si prega di inserire prima i dati del impiegato <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (cfimpiegato) VALUES ('$cfimpiegato')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'prenotazioneesame':
                    $codice = isset($_POST['codice']) ? $_POST['codice'] : NULL;
                    $medicoprescrittore = isset($_POST['medicoprescrittore']) ? $_POST['medicoprescrittore'] : NULL;
                    $ora = isset($_POST['ora']) ? $_POST['ora'] : NULL;
                    $data = isset($_POST['data']) ? $_POST['data'] : NULL;
                    $regime = isset($_POST['regime']) ? $_POST['regime'] : NULL;
                    $pazientecodicefiscale = isset($_POST['pazientecodicefiscale']) ? $_POST['pazientecodicefiscale'] : NULL;
                    $tipoesame = isset($_POST['tipoesame']) ? $_POST['tipoesame'] : NULL;
                    $avvertenze = isset($_POST['avvertenze']) ? $_POST['avvertenze'] : NULL;

                    $pazientecodicefiscale=strtolower($pazientecodicefiscale);
                           
                    $query = "SELECT codice_fiscale
                    FROM paziente
                    WHERE codice_fiscale='$pazientecodicefiscale'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun paziente con questo codice fiscale.<br/>";
                        echo "Si prega di inserire prima i dati del paziente <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                            $datimedico = explode(" ", $medicoprescrittore);
                            echo "".$datimedico[0]." ".$datimedico[1]."";
                            $query2 = "SELECT *
                            FROM impiegato as i INNER JOIN medico as m on m.cfimpiegato=i.codicefiscale
                            WHERE i.nome='$datimedico[0]'
                            AND i.cognome='$datimedico[1]'";
                            $result = pg_query($conn, $query2);
                            if (!$result) {//la query ha generato errori
                                echo "Non esiste nessun medico con questi dati.<br/>";
                                echo "Si prega di inserire prima i dati del medico o di <a href='opzioni.php'>riprovare</a>";
                            exit();
                            }else {
                                $query3 = "INSERT INTO " . $_SESSION['tbl'] . " (codice, medicoprescrittore, ora, data, regime, pazientecodicefiscale, tipoesame, avvertenze) VALUES ('$codice','$medicoprescrittore','$ora','$data','$regime','$pazientecodicefiscale','$pazientecodicefiscale', '$avvertenze')";
                                $result = pg_query($conn, $query3);
                                if (!$result) {
                                    print ($htmlint);
                                    echo "Si è verificato un errore. <br>";
                                    echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                    echo pg_last_error($conn);
                                    exit();
                                }else {
                                    print ($htmlint);
                                    echo "I dati passati salvati correttamente.<br>";
                                    echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                                }
                            }
                        }
                break;
                case 'prontosoccorso':
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $codiceospedale = isset($_POST['codiceospedale']) ? $_POST['codiceospedale'] : NULL;

                    $nome=strtolower($nome);
                    
                    $query = "SELECT codice
                    FROM ospedale
                    WHERE codice='$codiceospedale'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "La stanza selezzionata non esiste.<br/>";
                        echo "Si prega di inserire prima i dati della stanza <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $nome=strtolower($nome);
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (nome, codiceospedale) VALUES ('$nome','$codiceospedale')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'reparto':
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $codiceospedale = isset($_POST['codiceospedale']) ? $_POST['codiceospedale'] : NULL;
                    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : NULL;
                    $pattern = '/^[0-9]+$/';
                    $telefono = preg_match($pattern, $telefono) ? $telefono : 'non valido';
                    $orariovisita = isset($_POST['orariovisita']) ? $_POST['orariovisita'] : NULL;
                    
                    $query = "SELECT codice
                    FROM ospedale
                    WHERE codice='$codiceospedale'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "La stanza selezzionata non esiste.<br/>";
                        echo "Si prega di inserire prima i dati della stanza <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $nome=strtolower($nome);
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (nome, codiceospedale) VALUES ('$nome','$codiceospedale')";
                        if($telefono!='non valido' ){
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                print ($htmlint);
                                echo "Si è verificato un errore. <br>";
                                echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo "I dati passati salvati correttamente.<br>";
                                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                            }
                        }else {
                            print ($htmlint);
                            echo "I dati passati del telefono non sono conformi alle richieste.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'ricovero':
                    $codicefiscale = isset($_POST['codicefiscale']) ? $_POST['codicefiscale'] : NULL;
                    $datainizio = isset($_POST['datainizio']) ? $_POST['datainizio'] : NULL;
                    $datafine = isset($_POST['datafine']) ? $_POST['datafine'] : NULL;
                    
                    $datainizio = DateTime::createFromFormat('Y-m-d', $datainizio);
                    $datafine = DateTime::createFromFormat('Y-m-d', $datafine);
                    
                    if ($datainizio < $datafine) {
                        $codicefiscale=strtolower($codicefiscale);
                        $datainizio = $datainizio->format('Y-m-d');
                        $datafine = $datafine->format('Y-m-d');
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (codicefiscale, datainizio) VALUES ('$codicefiscale','$datainizio')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }else {
                        echo "Le date inserite non sono corrette.<br/>";
                        echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                        exit();
                    }
                break;
                case 'salaoperatoria':
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $stanzaid = isset($_POST['stanzaid']) ? $_POST['stanzaid'] : NULL;
                    
                    $query = "SELECT s.id
                    FROM stanza as s
                    WHERE s.id='$stanzaid'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "La stanza selezzionata non esiste.<br/>";
                        echo "Si prega di inserire prima i dati della stanza <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $nome=strtolower($nome);
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (nome, stanzaid) VALUES ('$nome','$stanzaid')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'sostituzione':
                    $cfprimario = isset($_POST['cfprimario']) ? $_POST['cfprimario'] : NULL;
                    $cfvice = isset($_POST['cfvice']) ? $_POST['cfvice'] : NULL;
                    $datainizio = isset($_POST['datainizio']) ? $_POST['datainizio'] : NULL;
                    $datafine = isset($_POST['datafine']) ? $_POST['datafine'] : NULL;
                    
                    $cfprimario=strtolower($cfprimario);
                    $cfvice=strtolower($cfvice);
                    $datainizio = DateTime::createFromFormat('Y-m-d', $datainizio);
                    $datafine = DateTime::createFromFormat('Y-m-d', $datafine);

                    $query = "SELECT cfimpiegato
                    FROM primario
                    WHERE cfimpiegato='$cfprimario'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Il primario inserito non esiste.<br/>";
                        echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "SELECT cfimpiegato
                        FROM viceprimario
                        WHERE cfimpiegato='$cfvice'";                        
                        $result = pg_query($conn, $query);
                        if (!$result) {//la query ha generato errori
                            echo "Il viceprimario inserito non esiste.<br/>";
                            echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                        exit();
                        }else {
                            if ($datainizio < $datafine) {
                                $datainizio = $datainizio->format('Y-m-d');
                                $datafine = $datafine->format('Y-m-d');
                                $query = "INSERT INTO " . $_SESSION['tbl'] . " (cfprimario, cfvice, datainizio, datafine) VALUES ('$cfprimario', '$cfvice', '$datainizio', '$datafine')";
                                $result = pg_query($conn, $query);
                                if (!$result) {
                                    print ($htmlint);
                                    echo "Si è verificato un errore. <br>";
                                    echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                    echo pg_last_error($conn);
                                    exit();
                                }else {
                                    print ($htmlint);
                                    echo "I dati passati salvati correttamente.<br>";
                                    echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                                }
                            }else {
                                echo "Le date inserite non sono corrette.<br/>";
                                echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                                exit();
                            }
                        }
                    }
                break;
                case 'specializzazione':
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                    $primario = isset($_POST['primario']) ? $_POST['primario'] : NULL;
                    
                    $primario=strtolower($primario);

                    $query = "SELECT codicefiscale
                    FROM primario
                    WHERE codicefiscale='$primario'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Il primario inserito non esiste.<br/>";
                        echo "Si prega di inserire prima i dati del primario <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (nome, primario) VALUES ('$nome','$primario')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                case 'stanza':
                    $idstanza = isset($_POST['idstanza']) ? $_POST['idstanza'] : NULL;
                    $numero = isset($_POST['numero']) ? $_POST['numero'] : NULL;
                    $piano = isset($_POST['piano']) ? $_POST['piano'] : NULL;
                    $nomereparto = isset($_POST['nomereparto']) ? $_POST['nomereparto'] : NULL;
                    $codiceospedale = isset($_POST['codiceospedale']) ? $_POST['codiceospedale'] : NULL;
                    
                    $nomereparto=strtolower($nomereparto);

                    $query = "SELECT nome, codiceospedale
                    FROM reparto
                    WHERE nome=$nomereparto
                    AND codiceospedale='$codiceospedale'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "I dati del Reparto non sono stati inseriti correttamente.<br/>";
                        echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        exit();
                    }else {           
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (idstanza, numero, piano, numeroreparto, codiceospedale) VALUES ('$idstanza','$nome', '$piano', '$nomereparto', '$codiceospedale')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }   
                    }
                break;
                case 'turnoinfermiere':
                    $orainizio = isset($_POST['orainizio']) ? $_POST['orainizio'] : NULL;
                    $orafine = isset($_POST['orafine']) ? $_POST['orafine'] : NULL;
                    $data = isset($_POST['data']) ? $_POST['data'] : NULL;
                    $codicefiscaleinfermiere = isset($_POST['codicefiscaleinfermiere']) ? $_POST['codicefiscaleinfermiere'] : NULL;
                    
                    $codicefiscaleinfermiere=strtolower($codicefiscaleinfermiere);

                    $query = "SELECT codicefiscale
                    FROM infermiere
                    WHERE codicefiscale='$codicefiscaleinfermiere'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "L'infermiere inserito non esiste.<br/>";
                        echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        if ($orainizio-$orafine<0) {
                            $query = "INSERT INTO " . $_SESSION['tbl'] . " (orainizio, orafine, data, codicefiscaleinfermiere) VALUES ('$orainizio', '$orafine', '$data', '$codicefiscaleinfermiere')";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                print ($htmlint);
                                echo "Si è verificato un errore. <br>";
                                echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo "I dati passati salvati correttamente.<br>";
                                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                            }
                        }else {
                            echo "Le date inserite non sono corrette.<br/>";
                            echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                            exit();
                        }
                    }
                break;
                case 'turnomedico':
                    $orainizio = isset($_POST['orainizio']) ? $_POST['orainizio'] : NULL;
                    $orafine = isset($_POST['orafine']) ? $_POST['orafine'] : NULL;
                    $data = isset($_POST['data']) ? $_POST['data'] : NULL;
                    $codicefiscalemedico = isset($_POST['codicefiscalemedico']) ? $_POST['codicefiscalemedico'] : NULL;

                    $codicefiscalemedico=strtolower($codicefiscalemedico);
                    
                    $query = "SELECT codicefiscale
                    FROM medico
                    WHERE codicefiscale='$codicefiscalemedico'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "L'infermiere inserito non esiste.<br/>";
                        echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        if ($orainizio-$orafine<0) {
                            $query = "INSERT INTO " . $_SESSION['tbl'] . " (orainizio, orafine, data, codicefiscalemedico) VALUES ('$orainizio', '$orafine', '$data', '$codicefiscalemedico')";
                            $result = pg_query($conn, $query);
                            if (!$result) {
                                print ($htmlint);
                                echo "Si è verificato un errore. <br>";
                                echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                                echo pg_last_error($conn);
                                exit();
                            }else {
                                print ($htmlint);
                                echo "I dati passati salvati correttamente.<br>";
                                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                            }
                        }else {
                            echo "Le date inserite non sono corrette.<br/>";
                            echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                            exit();
                        }
                    }
                break;
                case 'viceprimario':
                    $cfimpiegato = isset($_POST['cfimpiegato']) ? $_POST['cfimpiegato'] : NULL;

                    $cfimpiegato=strtolower($cfimpiegato);
                    
                    $query = "SELECT codicefiscale
                    FROM impiegato
                    WHERE codicefiscale='$cfimpiegato'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun impiegato con questo codice.<br/>";
                        echo "Si prega di inserire prima i dati del impiegato <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "INSERT INTO " . $_SESSION['tbl'] . " (cfimpiegato) VALUES ('$cfimpiegato')";
                        $result = pg_query($conn, $query);
                        if (!$result) {
                            print ($htmlint);
                            echo "Si è verificato un errore. <br>";
                            echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
                            echo pg_last_error($conn);
                            exit();
                        }else {
                            print ($htmlint);
                            echo "I dati passati salvati correttamente.<br>";
                            echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                        }
                    }
                break;
                default: 
                print ($htmlint);
                echo "C'è qualche problema<br>";
                echo "<a href='opzioni.php'>riprova</a>";
                break;
            };
    }
}
?>

</body>
</html>
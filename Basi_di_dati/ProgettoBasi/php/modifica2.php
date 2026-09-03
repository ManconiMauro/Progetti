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
  
  <body>
<BODY> 
NOW;

if (isset($_POST['toupdate']) && isset($_SESSION['tbl'])) {//sono stati passati correttamente i dati
    $conn = pg_connect("host=localhost port=5432 dbname=finale user=postgres password=SQL-secret5");
    $tbl = $_SESSION['tbl'];
    if (!$conn) {
    echo 'Connessione al database fallita.';
    exit();
    }
    else {
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
                    $query = "UPDATE ambulatoriointerno set  stanzaid='$stanzaid' WHERE codiceai='$codiceai'";
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
                $codiceai = isset($_POST['codiceai']) ? $_POST['codiceai'] : NULL;
                $indirizzo = isset($_POST['indirizzo']) ? $_POST['indirizzo'] : NULL;
                $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : NULL;
                $pattern = '/^[0-9]+$/';
                $telefono = preg_match($pattern, $telefono) ? $telefono : 'non valido';
                $orarioapertura = isset($_POST['orarioapertura']) ? $_POST['orarioapertura'] : NULL;

                $query = "UPDATE ambulatorioesterno set  indirizzo='$indirizzo', telefono='$telefono', orarioapertura='$orarioapertura' WHERE codiceae='$codiceae'";
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
                
                $query = "SELECT codice
                FROM ambulatorioesterno
                WHERE codice='$codiceae'";                        
                $result = pg_query($conn, $query);
                if (!$result) {//la query ha generato errori
                    echo "L'ambulatorio' selezzionato non esiste.<br/>";
                    echo "Si prega di inserire prima i dati dell'ambulatorio' <a href='opzioni.php'>riprovare</a>";
                    exit();
                }else {           
                    $query = "UPDATE disponibilitaesterna set data='$data', ora='$ora' WHERE codiceae='$codiceae'";
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
                
                $query = "SELECT codice
                FROM ambulatoriointerno
                WHERE codice='$codiceai'";                        
                $result = pg_query($conn, $query);
                if (!$result) {//la query ha generato errori
                    echo "L'ambulatorio' selezzionato non esiste.<br/>";
                    echo "Si prega di inserire prima i dati dell'ambulatorio' <a href='opzioni.php'>riprovare</a>";
                    exit();
                }else {           
                    $query = "UPDATE disponibilitainterna set data='$data', ora='$ora' WHERE codiceai='$codiceai'";
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
                
                $query ="SELECT codice
                FROM prenotazioneesame
                WHERE codice='$codiceprenotazione'";
                if (!$result) {//la query ha generato errori
                    echo "L'ambulatorio' selezzionato non esiste.<br/>";
                    echo "Si prega di inserire prima i dati dell'ambulatorio' <a href='opzioni.php'>riprovare</a>";
                    exit();
                }else {
                    $query = "UPDATE esame set descrizione='$descrizione', costopr='$costopr', costopu='$costopu' WHERE codice='$codice'";
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

                $query = "SELECT nome, codiceospedale
                FROM reparto
                WHERE nome='$nomereparto'
                AND codiceospedale='$codiceospedale'";                        
                $result = pg_query($conn, $query);
                if (!$result) {//la query ha generato errori
                    echo "I dati del Reparto non sono stati inseriti correttamente.<br/>";
                    echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                    exit();
                }else {        
                    $query = "UPDATE impiegato set nome='$nome', cognome='$cognome', dataassunzione='$dataassunzione', nomereparto='$nomereparto', codiceospedale='$codiceospedale' WHERE codicefiscale='$codicefiscale'";   
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
                echo "Non è possibile modificare dati dalla tabella infermiere, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'letto':
                echo "Non è possibile modificare dati dalla tabella letto, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'medico':
                echo "Non è possibile modificare dati dalla tabella infermiere, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'ospedale':
                $codice = isset($_POST['codice']) ? $_POST['codice'] : NULL;
                $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                $citta = isset($_POST['citta']) ? $_POST['citta'] : NULL;
                $indirizzo = isset($_POST['indirizzo']) ? $_POST['indirizzo'] : NULL;
                print ($htmlint);
                
                $query = "UPDATE ospedale set nome='$nome', citta='$citta', indirizzo='$indirizzo' WHERE codice='$codice'";
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
                echo "Non è possibile modificare dati dalla tabella patologia, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'paziente':
                $codicefiscale = isset($_POST['codicefiscale']) ? $_POST['codicefiscale'] : NULL;
                $nome = isset($_POST['nome']) ? $_POST['nome'] : NULL;
                $cognome = isset($_POST['cognome']) ? $_POST['cognome'] : NULL;
                $datanascita = isset($_POST['datanascita']) ? $_POST['datanascita'] : NULL;
                       
                $query = "UPDATE paziente set nome='$nome', cognome='$cognome', datanascita='$datanascita' WHERE codicefiscale='$codicefiscale'";
                if($datanascita-$dataAttuale < 0){
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
                echo "Non è possibile modificare dati dalla tabella personaleamministrativo, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'primario':
                echo "Non è possibile modificare dati dalla tabella primario, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'prenotazioneesame':
                $codice = isset($_POST['codice']) ? $_POST['codice'] : NULL;
                $medicoprescrittore = isset($_POST['medicoprescrittore']) ? $_POST['medicoprescrittore'] : NULL;
                $ora = isset($_POST['ora']) ? $_POST['ora'] : NULL;
                $data = isset($_POST['data']) ? $_POST['data'] : NULL;
                $regime = isset($_POST['regime']) ? $_POST['regime'] : NULL;
                $pazientecodicefiscale = isset($_POST['codicefiscale']) ? $_POST['codicefiscale'] : NULL;
                $tipoesame = isset($_POST['tipoesame']) ? $_POST['tipoesame'] : NULL;
                $avvertenze = isset($_POST['avvertenze']) ? $_POST['avvertenze'] : NULL;
                       
                $pazientecodicefiscale=strtolower($pazientecodicefiscale);

                $query = "SELECT codicefiscale
                FROM paziente
                WHERE codicefiscale='$pazientecodicefiscale'";                        
                $result = pg_query($conn, $query);
                if (!$result) {//la query ha generato errori
                    echo "Non esiste nessun paziente con questo codice fiscale.<br/>";
                    echo "Si prega di inserire prima i dati del paziente <a href='opzioni.php'>riprovare</a>";
                exit();
                }else {
                    $datimedico = explode(" ", $medicoprescrittore);
                    $query2 = "SELECT *
                    FROM impiegato as i INNER JOIN medico as m
                    WHERE i.nome=$datimedico[0]
                    AND i.cognome=$datimedico[1]
                    AND m.codicefiscale=i.codicefiscale";
                    $result = pg_query($conn, $query2);
                    if (!$result) {//la query ha generato errori
                        echo "Non esiste nessun medico con questi dati.<br/>";
                        echo "Si prega di inserire prima i dati del medico o di <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        $query = "UPDATE prenotazioneesame set medicoprescrittore='$medicoprescrittore', ora='$ora', data='$data', nome='$nome', regime='$regime', pazeintecodicefiscale='$pazeintecodicefiscale',tipoesame='$tipoesame', avvertenze='$avvertenze' WHERE codice='$codice'";
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
                echo "Non è possibile modificare dati dalla tabella prontosoccorso, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
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
                    $query = "UPDATE reparto set telefono='$telefono', orariovisita='$orariovisita' WHERE nome='$nome' AND codiceospedale='$codiceospedale'";
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
                $dataAttuale = date("Y-m-d");

                $datainizio = DateTime::createFromFormat('Y-m-d', $datainizio);
                $datafine = DateTime::createFromFormat('Y-m-d', $datafine);
                
                if ($datainizio < $datafine) {
                    $query = "UPDATE ricovero set datafine='$datafine' WHERE codicefiscale='$codicefiscale' AND datainizio='$datainizio'";
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
                echo "Non è possibile modificare dati dalla tabella personaleamministrativo, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'sostituzione':
                $cfprimario = isset($_POST['cfprimario']) ? $_POST['cfprimario'] : NULL;
                $cfvice = isset($_POST['cfvice']) ? $_POST['cfvice'] : NULL;
                $datainizio = isset($_POST['datainizio']) ? $_POST['datainizio'] : NULL;
                $datafine = isset($_POST['datafine']) ? $_POST['datafine'] : NULL;
                
                $datainizio = DateTime::createFromFormat('Y-m-d', $datainizio);
                $datafine = DateTime::createFromFormat('Y-m-d', $datafine);

                $query = "SELECT codicefiscale
                FROM primario
                WHERE codicefiscale=$cfprimario";                        
                $result = pg_query($conn, $query);
                if (!$result) {//la query ha generato errori
                    echo "Il primario inserito non esiste.<br/>";
                    echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                exit();
                }else {
                    $query = "SELECT codicefiscale
                    FROM viceprimario
                    WHERE codicefiscale='$cfvice'";                        
                    $result = pg_query($conn, $query);
                    if (!$result) {//la query ha generato errori
                        echo "Il viceprimario inserito non esiste.<br/>";
                        echo "Si prega di <a href='opzioni.php'>riprovare</a>";
                    exit();
                    }else {
                        if ($datainizio < $datafine) {
                            $query = "UPDATE sostituzione set datafine='$datafine' WHERE cfprimario='$cfprimario' AND cfvice='$cfvice' AND datainizio=$datainizio";
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
                echo "Non è possibile modificare dati dalla tabella specializzazione, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
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
                WHERE nome='$nomereparto'
                AND codiceospedale='$codiceospedale'";                        
                $result = pg_query($conn, $query);
                if (!$result) {//la query ha generato errori
                    echo "I dati del Reparto non sono stati inseriti correttamente.<br/>";
                    echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
                    exit();
                }else {           
                    $query = "UPDATE stanza set numero='$numero', piano='$piano' WHERE idstanza='$idstanza' AND nomereparto='$cfvice' AND codiceospedale=$codiceospedale";
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
                echo "Non è possibile modificare dati dalla tabella turnoinfermiere, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'turnomedico':
                echo "Non è possibile modificare dati dalla tabella turnomedico, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            case 'viceprimario':
                echo "Non è possibile modificare dati dalla tabella viceprimario, si prega di Eliminare e Inserire i dati che si vogliono sostituire";
                echo "Se vuoi puoi <a href='opzioni.php'>riprovare</a>";
            break;
            default: 
            print ($htmlint);
            echo "C'è qualche problema<br>";
            echo "<a href='opzioni.php'>riprova</a>";
            break;
  }
}
}
else {//non sono stati passati correttamente i dati
  print ($htmlint);
  echo "Non risultano dati passati<br>";
  echo "Se vuoi puoi <a href='index.php'>riprovare</a>";
}
?>
</BODY>
</HTML>
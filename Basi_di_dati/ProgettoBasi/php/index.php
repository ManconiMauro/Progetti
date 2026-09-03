<!DOCTYPE html>
<html>
<head>
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
<body > 
  
  <div class="container">
  <h1>Database ManageR</h1>
<?php
  $conn = pg_connect("host=localhost port=5432 dbname=cazzo user=postgres password=SQL-secret5");
  if (!$conn) {
  echo 'Connessione al database fallita.'; 
  exit();
  }else{
    //preparo una query per estrarre i nomi delle tabelle dal database dall'information_schema 
    $query = "SELECT table_name as table FROM information_schema.tables WHERE table_type='BASE TABLE' and table_schema='public' ORDER BY table_name";
    $result = pg_query($conn, $query);
    if (!$result) {
      echo "Si è verificato un errore.<br/>"; echo pg_last_error($conn);
      exit();
    }else{
      print ("<form action=\"opzioni.php\" method=\"POST\">"); 
      print ("<select class=\"form-control\" name=\"tabella\">");
        while ($row = pg_fetch_array($result)){
          print ("<option value=\"" . htmlspecialchars($row["table"]) . "\">"); echo $row["table"];
          print ("</option>");
        };
        print ("</select>");
        print ("<select class=\"form-control\" name=\"operazione\">");
        print ("<option value=\"Mostra\">Mostra</option>");
        print ("<option value=\"Modifica\">Modifica</option>");
        print ("<option value=\"Elimina\">Elimina</option>");
        print ("<option value=\"Inserisci\">Inserisci</option>");
        print ("<option value=\"Vice1Sost\">Viceprimari 1 sostituzione</option>");
        print ("<option value=\"Vice2Sost\">Viceprimari più di 2 sostituzioni</option>");
        print ("<option value=\"Vice0Sost\">Viceprimari 0 sostituzioni</option>");
        print ("<option value=\"OrdineProcedure\">Procedure trasporto deceduto</option>");
        print ("<input type=\"submit\"  >");
        print ("</form>");
      
    }
  }
?>
</div>


</body>
</html>

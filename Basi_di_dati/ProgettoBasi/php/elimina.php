<!--basic_del.php-->
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
//print_R($_POST);
if (isset($_POST['todelete']) && isset($_SESSION['tbl'])) {//sono stati passati correttamente i dati
    
    print ($htmlint);
    $conn = pg_connect("host=localhost port=5432 dbname=finale user=postgres password=SQL-secret5");
  if (!$conn) {
    echo 'Connessione al database fallita.';
    exit();
  }else {
    $query = "SELECT column_name
    FROM information_schema.key_column_usage
    WHERE table_name = '" . $_SESSION['tbl'] . "'
    AND constraint_name = (
    SELECT constraint_name
    FROM information_schema.table_constraints
    WHERE table_name = '" . $_SESSION['tbl'] . "'
    AND constraint_type = 'PRIMARY KEY'
    );";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);
    $primaryKeyColumn = $row['column_name'];

    $query = "DELETE FROM " . $_SESSION['tbl'] . " WHERE $primaryKeyColumn='" . $_POST['todelete'] . "';";
    //echo $query;
    $result = pg_query($conn, $query);
    if (!$result) {
        echo "Si è verificato un errore.<br/>";
        echo pg_last_error($conn);
        exit();
    }
    else {
        echo "Cancellazione avvenuta con successo<br><a href='index.php'>ritorna</a>";
        };
    }
        

}
else {//non sono stati passati correttamente i dati
  print ($htmlint);
  echo "Non risultano dati passati<br>";
  echo "Se vuoi puoi <a href='select_basic.php'>riprovare</a>";
}
?>
</BODY>
</HTML>
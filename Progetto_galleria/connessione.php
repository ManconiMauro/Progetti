<?php
// Parametri di connessione
$host     = 'localhost';
$utente   = 'root';
$password = '';            
$database = 'galleria';
$porta    = 3306; 

// eccezioni in caso di errore
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $utente, $password, $database, $porta);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Errore di connessione al database: ' . htmlspecialchars($e->getMessage()));
}
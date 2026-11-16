<?php
//** Controllo usato da nuova opera, se l'autore esiste restituisce true, nome, e cognome */
session_start();
header('Content-Type: application/json; charset=utf-8');
 
if (!isset($_SESSION['idUtente'])) {
    http_response_code(401);
    echo json_encode(['errore' => 'non autorizzato']);
    exit;
}
 
require_once 'connessione.php';
 
$cf = strtolower(trim($_GET['cf'] ?? ''));
if (!preg_match('/^[a-z0-9]{16}$/', $cf)) {
    http_response_code(400);
    echo json_encode(['errore' => 'codice fiscale non valido']);
    exit;
}
 
try {
    $stmt = $conn->prepare('SELECT nome, cognome FROM Autore WHERE codiceFiscale = ?');
    $stmt->bind_param('s', $cf);
    $stmt->execute();
    $autore = $stmt->get_result()->fetch_assoc();
    $stmt->close();
 
    echo json_encode($autore
        ? ['esiste' => true, 'nome' => $autore['nome'], 'cognome' => $autore['cognome']]
        : ['esiste' => false]);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['errore' => 'errore del database']);
}
$conn->close();
<?php
// Aggiunge un'opera al carrello dell'utente: crea un ordine "in Attesa".
session_start();

if (!isset($_SESSION['idUtente'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

require_once 'connessione.php';

$idUtente = (int) $_SESSION['idUtente'];
$idOpera  = filter_var($_POST['idOpera'] ?? null, FILTER_VALIDATE_INT);
$esito    = ['errore', 'Impossibile aggiungere l\'opera al carrello.'];

if ($idOpera) {
    try {
        $conn->begin_transaction();

        // Leggo l'opera e la blocco fino al commit, così due persone non la prendono insieme
        $stmt = $conn->prepare('SELECT stato, prezzo FROM Opera WHERE id = ? FOR UPDATE');
        $stmt->bind_param('i', $idOpera);
        $stmt->execute();
        $opera = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$opera) {
            $esito = ['errore', 'Opera non trovata.'];

        } elseif ((int) $opera['stato'] !== 0) {
            $esito = ['errore', 'Questa opera è già stata venduta.'];

        } elseif ($opera['prezzo'] === null || (float) $opera['prezzo'] <= 0) {
            $esito = ['errore', 'Questa opera è ancora in valutazione: non è acquistabile.'];

        } else {
            // È già nel carrello di questo utente?
            $stmt = $conn->prepare(
                "SELECT COUNT(*) FROM Ordine
                 WHERE idUtente = ? AND idOpera = ? AND statoOrdine = 'in Attesa'"
            );
            $stmt->bind_param('ii', $idUtente, $idOpera);
            $stmt->execute();
            $stmt->bind_result($giaPresente);
            $stmt->fetch();
            $stmt->close();

            if ($giaPresente > 0) {
                $esito = ['errore', 'Questa opera è già nel tuo carrello.'];
            } else {
                // Prossimo id libero (funziona anche se la colonna non è AUTO_INCREMENT)
                $nuovoId = (int) $conn->query('SELECT COALESCE(MAX(id), 0) + 1 FROM Ordine FOR UPDATE')
                                      ->fetch_row()[0];

                // La data di acquisto resta vuota: verrà messa quando l'ordine sarà pagato
                $stmt = $conn->prepare(
                    "INSERT INTO Ordine (id, dataAcquisto, statoOrdine, idOpera, idUtente)
                     VALUES (?, NULL, 'in Attesa', ?, ?)"
                );
                $stmt->bind_param('iii', $nuovoId, $idOpera, $idUtente);
                $stmt->execute();
                $stmt->close();

                $esito = ['ok', 'Opera aggiunta al carrello.'];
            }
        }

        $conn->commit();
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $esito = ['errore', 'Errore del database: ' . $e->getMessage()];
    }
}

$_SESSION['avviso'] = $esito;
$conn->close();

header('Location: ' . ($idOpera ? 'dettaglio.php?id=' . $idOpera : 'index.php'));
exit;
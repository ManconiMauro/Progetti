<?php
session_start();

// Serve essere loggati
if (!isset($_SESSION['idUtente'])) {
    header('Location: login.php');
    exit;
}

require_once 'connessione.php';
require_once 'funzioni.php';

$idUtente = (int) $_SESSION['idUtente'];

/* ------------------------------------------------------------------
   AZIONI (POST): annulla un ordine oppure acquista tutto il carrello
   ------------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $azione = $_POST['azione'] ?? '';

    try {
        if ($azione === 'annulla') {
            $idOrdine = filter_var($_POST['idOrdine'] ?? null, FILTER_VALIDATE_INT);
            $annullato = false;

            if ($idOrdine) {
                $stmt = $conn->prepare(
                    "UPDATE Ordine SET statoOrdine = 'Annullato'
                     WHERE id = ? AND idUtente = ? AND statoOrdine = 'in Attesa'"
                );
                $stmt->bind_param('ii', $idOrdine, $idUtente);
                $stmt->execute();
                $annullato = $stmt->affected_rows > 0;
                $stmt->close();
            }

            $_SESSION['avviso'] = $annullato
                ? ['ok', 'Ordine annullato.']
                : ['errore', 'Impossibile annullare questo ordine.'];

        } elseif ($azione === 'acquista') {
            // Transazione: o si aggiorna tutto o non si aggiorna niente
            $conn->begin_transaction();

            // Ordini in attesa dell'utente le cui opere sono ancora disponibili (bloccati fino al commit)
            $stmt = $conn->prepare(
                "SELECT o.id AS idOrdine, o.idOpera
                 FROM Ordine o
                 JOIN Opera p ON p.id = o.idOpera
                 WHERE o.idUtente = ? AND o.statoOrdine = 'in Attesa' AND p.stato = 0
                 FOR UPDATE"
            );
            $stmt->bind_param('i', $idUtente);
            $stmt->execute();
            $daPagare = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            if (!$daPagare) {
                $conn->rollback();
                $_SESSION['avviso'] = ['errore', 'Non ci sono opere disponibili da acquistare.'];
            } else {
                // Gli id arrivano dal database e sono convertiti in interi: sicuri da inserire nella query
                $idOrdini = implode(',', array_map('intval', array_column($daPagare, 'idOrdine')));
                $idOpere  = implode(',', array_map('intval', array_column($daPagare, 'idOpera')));

                // Ordine: in Attesa -> Pagato (con la data di oggi)
                $conn->query(
                    "UPDATE Ordine SET statoOrdine = 'Pagato', dataAcquisto = CURDATE()
                     WHERE id IN ($idOrdini)"
                );
                // Opera: stato = 1 (venduta)
                $conn->query("UPDATE Opera SET stato = 1 WHERE id IN ($idOpere)");

                $conn->commit();
                $_SESSION['avviso'] = ['ok', 'Acquisto completato! Grazie.'];
            }
        }
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $_SESSION['avviso'] = ['errore', 'Errore del database: operazione non riuscita.'];
    }

    // Redirect dopo il POST: evita di rinviare l'azione ricaricando la pagina
    header('Location: carrello.php');
    exit;
}

$avviso = $_SESSION['avviso'] ?? null;
unset($_SESSION['avviso']);

$righe = [];
$errore = null;
try {
    $stmt = $conn->prepare(
        "SELECT o.id AS idOrdine, p.id AS idOpera, p.titolo, p.prezzo, p.stato AS statoOpera
         FROM Ordine o
         JOIN Opera p ON p.id = o.idOpera
         WHERE o.idUtente = ? AND o.statoOrdine = 'in Attesa'
         ORDER BY o.id"
    );
    $stmt->bind_param('i', $idUtente);
    $stmt->execute();
    $righe = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $errore = 'Impossibile leggere il carrello.';
}

// Il totale conta solo le opere ancora disponibili
$totale = 0;
$disponibili = 0;
foreach ($righe as $r) {
    if ((int) $r['statoOpera'] === 0) {
        $totale += (float) $r['prezzo'];
        $disponibili++;
    }
}

$numeroCarrello = count($righe); // per il pallino nella barra
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carrello - Galleria d'arte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <?php require 'header.php'; ?>

    <header class="testata">
        <h1>Il tuo carrello</h1>
    </header>

    <main>
        <?php if ($avviso): ?>
            <p class="avviso <?= htmlspecialchars($avviso[0]) ?>" role="status"><?= htmlspecialchars($avviso[1]) ?></p>
        <?php endif; ?>

        <?php if ($errore): ?>
            <p class="messaggio"><?= htmlspecialchars($errore) ?></p>

        <?php elseif (!$righe): ?>
            <p class="messaggio">Il carrello è vuoto. <a href="index.php">Torna alla galleria</a>.</p>

        <?php else: ?>
            <ul class="elenco-carrello">
                <?php foreach ($righe as $r):
                    $titolo    = htmlspecialchars($r['titolo']);
                    $immagine  = immagineOpera($r['idOpera']);
                    $venduta   = (int) $r['statoOpera'] !== 0;
                ?>
                <li class="riga-carrello">
                    <div class="miniatura">
                        <?php if ($immagine): ?>
                            <img src="<?= $immagine ?>" alt="<?= $titolo ?>">
                        <?php else: ?>
                            <div class="segnaposto" aria-hidden="true"></div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <h2><a href="dettaglio.php?id=<?= (int) $r['idOpera'] ?>"><?= $titolo ?></a></h2>
                        <?php if ($venduta): ?>
                            <span class="non-disponibile">Non più disponibile: annulla l'ordine</span>
                        <?php endif; ?>
                    </div>

                    <span class="prezzo"><?= formattaPrezzo($r['prezzo']) ?></span>

                    <form method="post" action="carrello.php"
                          onsubmit="return confirm('Vuoi annullare questo ordine?');">
                        <input type="hidden" name="azione" value="annulla">
                        <input type="hidden" name="idOrdine" value="<?= (int) $r['idOrdine'] ?>">
                        <button type="submit" class="pulsante pulsante-rosso">Annulla</button>
                    </form>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="totale">
                <p>Totale: <strong><?= formattaPrezzo($totale) ?></strong></p>

                <form method="post" action="carrello.php">
                    <input type="hidden" name="azione" value="acquista">
                    <button type="submit" class="pulsante" <?= $disponibili === 0 ? 'disabled' : '' ?>>
                        Acquista tutto
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
<?php $conn->close(); ?>
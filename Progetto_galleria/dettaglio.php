<?php
session_start();

// Serve essere loggati
if (!isset($_SESSION['idUtente'])) {
    header('Location: login.php');
    exit;
}

require_once 'connessione.php';
require_once 'funzioni.php';

// Significato del campo "stato": modifica queste etichette se è diverso
$etichetteStato = [
    0 => 'Disponibile',
    1 => 'Venduta',
];

$idUtente   = (int) $_SESSION['idUtente'];
$id         = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$opera      = null;
$errore     = null;
$inCarrello = false;

if ($id) {
    try {
        // Query preparata: sicura contro SQL injection. JOIN per avere anche il nome dell'autore
        $stmt = $conn->prepare(
            'SELECT o.id, o.titolo, o.AnnoComposizione, o.prezzo, o.stato, o.autore,
                    a.nome, a.cognome
             FROM Opera o
             LEFT JOIN Autore a ON o.autore = a.codiceFiscale
             WHERE o.id = ?'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $opera = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($opera) {
            // L'utente ha già questa opera nel carrello?
            $stmt = $conn->prepare(
                "SELECT COUNT(*) FROM Ordine
                 WHERE idUtente = ? AND idOpera = ? AND statoOrdine = 'in Attesa'"
            );
            $stmt->bind_param('ii', $idUtente, $id);
            $stmt->execute();
            $stmt->bind_result($numeroOrdini);
            $stmt->fetch();
            $stmt->close();
            $inCarrello = $numeroOrdini > 0;
        }
    } catch (mysqli_sql_exception $e) {
        $errore = $e->getMessage();
    }
}

$avviso = $_SESSION['avviso'] ?? null;
unset($_SESSION['avviso']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $opera ? htmlspecialchars($opera['titolo']) . ' - ' : '' ?>Galleria d'arte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <?php require 'header.php'; ?>

    <header class="testata">
        <h1>Galleria d'arte</h1>
    </header>

    <main>
        <?php if ($avviso): ?>
            <p class="avviso <?= htmlspecialchars($avviso[0]) ?>" role="status">
                <?= htmlspecialchars($avviso[1]) ?>
                <?php if ($avviso[0] === 'ok'): ?>
                    <a href="carrello.php">Vai al carrello</a>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php if ($errore): ?>
            <p class="messaggio">Impossibile leggere l'opera: <?= htmlspecialchars($errore) ?></p>

        <?php elseif (!$opera): ?>
            <p class="messaggio">Opera non trovata. <a href="index.php">Torna alla griglia</a>.</p>

        <?php else:
            $titolo     = htmlspecialchars($opera['titolo']);
            $immagine   = immagineOpera($opera['id']);
            $stato      = $etichetteStato[(int) $opera['stato']] ?? $opera['stato'];
            $nomeAutore = trim($opera['nome'] . ' ' . $opera['cognome']);

            $venduta       = (int) $opera['stato'] !== 0;
            $inValutazione = $opera['prezzo'] === null || (float) $opera['prezzo'] <= 0;
            $acquistabile  = !$venduta && !$inValutazione;
        ?>
            <article class="dettaglio<?= $venduta ? ' venduta' : '' ?>">
                <div class="cornice">
                    <?php if ($immagine): ?>
                        <img src="<?= $immagine ?>" alt="<?= $titolo ?>">
                    <?php else: ?>
                        <div class="segnaposto">Immagine non ancora disponibile</div>
                    <?php endif; ?>
                </div>

                <div>
                    <h2><?= $titolo ?></h2>
                    <dl class="dati">
                        <div><dt>Codice opera</dt><dd><?= (int) $opera['id'] ?></dd></div>
                        <div><dt>Autore</dt><dd><?= htmlspecialchars($nomeAutore ?: 'Sconosciuto') ?></dd></div>
                        <div><dt>Codice fiscale autore</dt><dd><?= htmlspecialchars($opera['autore']) ?></dd></div>
                        <div><dt>Anno di composizione</dt><dd><?= htmlspecialchars($opera['AnnoComposizione']) ?></dd></div>
                        <div><dt>Prezzo</dt><dd><?= formattaPrezzo($opera['prezzo']) ?></dd></div>
                        <div><dt>Stato</dt><dd><?= htmlspecialchars((string) $stato) ?></dd></div>
                    </dl>

                    <?php if ($inValutazione && !$venduta): ?>
                        <p class="nota-dettaglio">Quest'opera è in valutazione: non è ancora acquistabile.</p>
                    <?php endif; ?>

                    <div class="azioni-dettaglio">
                        <?php if ($acquistabile && $inCarrello): ?>
                            <a class="pulsante" href="carrello.php">Già nel carrello: vai al carrello</a>

                        <?php elseif ($acquistabile): ?>
                            <form method="post" action="aggiungi_carrello.php">
                                <input type="hidden" name="idOpera" value="<?= (int) $opera['id'] ?>">
                                <button type="submit" class="pulsante">Aggiungi al carrello</button>
                            </form>
                        <?php endif; ?>

                        <a class="pulsante pulsante-secondario" href="index.php">Torna alle opere</a>
                    </div>
                </div>
            </article>
        <?php endif; ?>
    </main>
</body>
</html>
<?php $conn->close(); ?>
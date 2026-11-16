<?php
session_start();
if (!isset($_SESSION['utente'])) {
    header('Location: login.php');
    exit;
}

require_once 'connessione.php';
require_once 'funzioni.php';

// Filtri letti dall'indirizzo: ?q=testo&autore=codicefiscale
$ricerca     = trim($_GET['q'] ?? '');
$autoreScelto = trim($_GET['autore'] ?? '');
$filtriAttivi = ($ricerca !== '' || $autoreScelto !== '');

$autori = [];
$opere  = [];
$errore = null;

try {
    // Autori che hanno almeno un'opera (per il menu del filtro)
    $autori = $conn->query(
        'SELECT DISTINCT a.codiceFiscale, a.nome, a.cognome
         FROM Autore a
         JOIN Opera o ON o.autore = a.codiceFiscale
         ORDER BY a.cognome, a.nome'
    )->fetch_all(MYSQLI_ASSOC);

    // Query costruita in base ai filtri attivi (sempre con parametri, mai testo incollato)
    $sql = 'SELECT o.id, o.titolo, o.prezzo, o.stato, o.autore, a.nome, a.cognome
            FROM Opera o
            LEFT JOIN Autore a ON a.codiceFiscale = o.autore
            WHERE 1 = 1';
    $tipi = '';
    $parametri = [];

    if ($ricerca !== '') {
        // Ricerca per titolo: escludo i caratteri speciali di LIKE (% e _)
        $sql .= ' AND o.titolo LIKE ?';
        $tipi .= 's';
        $parametri[] = '%' . addcslashes($ricerca, '%_\\') . '%';
    }
    if ($autoreScelto !== '') {
        $sql .= ' AND o.autore = ?';
        $tipi .= 's';
        $parametri[] = $autoreScelto;
    }
    $sql .= ' ORDER BY a.cognome, a.nome, o.id';

    $stmt = $conn->prepare($sql);
    if ($parametri) {
        $stmt->bind_param($tipi, ...$parametri);
    }
    $stmt->execute();
    $opere = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    $errore = 'Impossibile leggere le opere: ' . $e->getMessage();
}

// Divido le opere in liste, una per autore
$gruppi = [];
foreach ($opere as $opera) {
    $gruppi[$opera['autore']][] = $opera;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galleria d'arte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <?php
    $numeroCarrello = contaCarrello($conn, $_SESSION['idUtente'] ?? 0);
    require 'header.php';
    ?>

    <header class="testata">
        <h1>Galleria d'arte</h1>
    </header>

    <main>
        <!-- Ricerca per titolo e filtro per autore -->
        <form method="get" action="index.php" class="ricerca" role="search">
            <div class="campo-ricerca">
                <label for="q">Cerca un'opera</label>
                <div class="casella-ricerca">
                    <svg class="icona" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="search" id="q" name="q" placeholder="Titolo dell'opera"
                           value="<?= htmlspecialchars($ricerca) ?>">
                </div>
            </div>

            <div class="campo-ricerca">
                <label for="autore">Autore</label>
                <select id="autore" name="autore" onchange="this.form.submit()">
                    <option value="">Tutti gli autori</option>
                    <?php foreach ($autori as $a): ?>
                        <option value="<?= htmlspecialchars($a['codiceFiscale']) ?>"
                            <?= $autoreScelto === $a['codiceFiscale'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nome'] . ' ' . $a['cognome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="pulsante">Cerca</button>
            <?php if ($filtriAttivi): ?>
                <a class="azzera" href="index.php">Azzera i filtri</a>
            <?php endif; ?>
        </form>

        <?php if ($errore): ?>
            <p class="messaggio"><?= htmlspecialchars($errore) ?></p>

        <?php elseif (!$opere): ?>
            <p class="messaggio">
                <?= $filtriAttivi
                    ? 'Nessuna opera corrisponde alla ricerca.'
                    : 'Non ci sono ancora opere in galleria.' ?>
            </p>

        <?php else: ?>
            <?php if ($filtriAttivi): ?>
                <p class="messaggio"><?= count($opere) ?> <?= count($opere) === 1 ? 'opera trovata' : 'opere trovate' ?></p>
            <?php endif; ?>

            <?php foreach ($gruppi as $opereAutore):
                $primo = $opereAutore[0];
                $nomeAutore = trim(($primo['nome'] ?? '') . ' ' . ($primo['cognome'] ?? ''));
            ?>
                <section class="gruppo-autore">
                    <h2><?= htmlspecialchars($nomeAutore !== '' ? $nomeAutore : 'Autore sconosciuto') ?></h2>

                    <ul class="griglia">
                        <?php foreach ($opereAutore as $opera):
                            $titolo    = htmlspecialchars($opera['titolo']);
                            $immagine  = immagineOpera($opera['id']);
                            $linkOpera = 'dettaglio.php?id=' . (int) $opera['id'];
                            $inValutazione = $opera['prezzo'] === null || (float) $opera['prezzo'] <= 0;
                            $venduta   = (int) $opera['stato'] !== 0;
                        ?>
                        <li class="opera<?= $venduta ? ' venduta' : '' ?>">
                            <a class="cornice" href="<?= $linkOpera ?>" aria-label="Apri <?= $titolo ?>">
                                <?php if ($immagine): ?>
                                    <img src="<?= $immagine ?>" alt="<?= $titolo ?>">
                                <?php else: ?>
                                    <div class="segnaposto">Immagine non ancora disponibile</div>
                                <?php endif; ?>
                            </a>
                            <div class="didascalia">
                                <h3><?= $titolo ?></h3>
                                <span class="prezzo<?= $inValutazione ? ' in-valutazione' : '' ?>">
                                    <?= formattaPrezzo($opera['prezzo']) ?>
                                </span>
                            </div>
                            <?php if ($venduta): ?>
                                <p class="testo-venduta">Venduta</p>
                            <?php endif; ?>
                            <a class="pulsante" href="<?= $linkOpera ?>">Vedi tutti i dati</a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
<?php $conn->close(); ?>
<?php
session_start();
 
// Serve essere loggati
if (!isset($_SESSION['idUtente'])) {
    header('Location: login.php');
    exit;
}
 
require_once 'connessione.php';
require_once 'funzioni.php';
 
const MAX_TESTO = 50; // lunghezza massima di titolo, nome e cognome
 
$errori = [];
$dati = ['titolo' => '', 'anno' => '', 'cf' => '', 'nome' => '', 'cognome' => ''];
$mostraNuovoAutore = false; // true = il form si "allunga" con nome e cognome
 
/* ------------------------------------------------------------------
   INVIO DEL FORM
   ------------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dati as $campo => $valore) {
        $dati[$campo] = trim($_POST[$campo] ?? '');
    }
    $dati['cf'] = strtolower($dati['cf']);
 
    // --- Validazione dei campi base ---
    if ($dati['titolo'] === '' || mb_strlen($dati['titolo']) > MAX_TESTO) {
        $errori[] = 'Inserisci un titolo (massimo ' . MAX_TESTO . ' caratteri).';
    }
 
    $data = DateTime::createFromFormat('!Y-m-d', $dati['anno']);
    if (!$data || $data->format('Y-m-d') !== $dati['anno']) {
        $errori[] = 'Inserisci una data di composizione valida.';
    }
 
    $autoreEsiste = false;
    $codiceValido = (bool) preg_match('/^[a-z0-9]{16}$/', $dati['cf']);
    if (!$codiceValido) {
        $errori[] = 'Il codice fiscale dell\'autore deve avere 16 caratteri.';
    }
 
    try {
        // --- L'autore esiste già? ---
        if ($codiceValido) {
            $stmt = $conn->prepare('SELECT 1 FROM Autore WHERE codiceFiscale = ?');
            $stmt->bind_param('s', $dati['cf']);
            $stmt->execute();
            $autoreEsiste = $stmt->get_result()->num_rows > 0;
            $stmt->close();
 
            if (!$autoreEsiste) {
                // Autore sconosciuto: servono nome e cognome
                $mostraNuovoAutore = true;
                if ($dati['nome'] === '' || $dati['cognome'] === ''
                    || mb_strlen($dati['nome']) > MAX_TESTO || mb_strlen($dati['cognome']) > MAX_TESTO) {
                    $errori[] = 'Questo autore non è ancora presente: inserisci anche nome e cognome (massimo '
                              . MAX_TESTO . ' caratteri).';
                }
            }
        }
 
        // --- Salvataggio ---
        if (!$errori) {
            $conn->begin_transaction();
 
            if (!$autoreEsiste) {
                $stmt = $conn->prepare('INSERT INTO Autore (codiceFiscale, nome, cognome) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $dati['cf'], $dati['nome'], $dati['cognome']);
                $stmt->execute();
                $stmt->close();
            }
 
            // Prossimo id libero (funziona anche se la colonna non è AUTO_INCREMENT)
            $nuovoId = (int) $conn->query('SELECT COALESCE(MAX(id), 0) + 1 FROM Opera FOR UPDATE')->fetch_row()[0];
 
            // Prezzo 0 = "in valutazione"; stato 0 = disponibile
            $stmt = $conn->prepare(
                'INSERT INTO Opera (id, titolo, AnnoComposizione, prezzo, stato, autore) VALUES (?, ?, ?, 0, 0, ?)'
            );
            $stmt->bind_param('isss', $nuovoId, $dati['titolo'], $dati['anno'], $dati['cf']);
            $stmt->execute();
            $stmt->close();
 
            $conn->commit();
 
            // Immagine facoltativa: salvata come immagini/<id>.<estensione>
            $erroreImmagine = salvaImmagineOpera($nuovoId, $_FILES['immagine'] ?? null);
 
            $messaggio = 'Opera inviata! Il prezzo non è ancora stabilito: faremo valutare la tua opera ai nostri esperti.';
            if ($erroreImmagine) {
                $messaggio .= ' Attenzione: ' . $erroreImmagine;
            }
            $_SESSION['avviso'] = ['ok', $messaggio];
 
            header('Location: nuova_opera.php');
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $errori[] = 'Errore del database: ' . $e->getMessage();
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
    <title>Nuova opera - Galleria d'arte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <?php require 'header.php'; ?>
 
    <header class="testata">
        <h1>Proponi una nuova opera</h1>
    </header>
 
    <main class="pagina-modulo">
        <?php if ($avviso): ?>
            <p class="avviso <?= htmlspecialchars($avviso[0]) ?>" role="status">
                <?= htmlspecialchars($avviso[1]) ?>
                <a href="index.php">Vai alla galleria</a>
            </p>
        <?php endif; ?>
 
        <?php if ($errori): ?>
            <div class="avviso errore" role="alert">
                <ul>
                    <?php foreach ($errori as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
 
        <form method="post" action="nuova_opera.php" enctype="multipart/form-data" class="modulo">
            <label for="titolo">Titolo</label>
            <input type="text" id="titolo" name="titolo" maxlength="<?= MAX_TESTO ?>" required
                   value="<?= htmlspecialchars($dati['titolo']) ?>">
 
            <label for="anno">Data di composizione</label>
            <input type="date" id="anno" name="anno" required value="<?= htmlspecialchars($dati['anno']) ?>">
 
            <label for="cf">Codice fiscale dell'autore</label>
            <input type="text" id="cf" name="cf" maxlength="16" required autocomplete="off"
                   value="<?= htmlspecialchars($dati['cf']) ?>">
            <p id="esitoAutore" class="esito-autore" aria-live="polite"></p>
 
            <!-- Compare solo se il codice fiscale non corrisponde a nessun autore -->
            <div id="nuovoAutore" class="nuovo-autore" <?= $mostraNuovoAutore ? '' : 'hidden' ?>>
                <p class="nota">Questo autore non è ancora presente: inserisci anche nome e cognome.</p>
 
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" maxlength="<?= MAX_TESTO ?>"
                       value="<?= htmlspecialchars($dati['nome']) ?>">
 
                <label for="cognome">Cognome</label>
                <input type="text" id="cognome" name="cognome" maxlength="<?= MAX_TESTO ?>"
                       value="<?= htmlspecialchars($dati['cognome']) ?>">
            </div>
 
            <label for="immagine">Immagine (facoltativa, JPG, PNG o WEBP, massimo 5 MB)</label>
            <input type="file" id="immagine" name="immagine" accept="image/jpeg,image/png,image/webp">
 
            <p class="nota">
                <strong>Prezzo</strong><br>
                Il prezzo non lo scegli tu: faremo valutare la tua opera ai nostri esperti.
            </p>
 
            <button type="submit" class="pulsante">Invia l'opera</button>
        </form>
    </main>
 
    <script>
        // Quando il codice fiscale è completo, controlla se l'autore esiste:
        // se non esiste, il form si allunga con nome e cognome.
        (function () {
            var cf      = document.getElementById('cf');
            var blocco  = document.getElementById('nuovoAutore');
            var esito   = document.getElementById('esitoAutore');
            var nome    = document.getElementById('nome');
            var cognome = document.getElementById('cognome');
 
            function mostraNuovoAutore(si) {
                blocco.hidden = !si;
                nome.required = si;
                cognome.required = si;
            }
 
            function controlla() {
                var valore = cf.value.trim();
                esito.textContent = '';
 
                if (!/^[A-Za-z0-9]{16}$/.test(valore)) {
                    mostraNuovoAutore(false);
                    return;
                }
 
                fetch('controlla_autore.php?cf=' + encodeURIComponent(valore))
                    .then(function (r) { return r.json(); })
                    .then(function (d) {
                        if (valore !== cf.value.trim()) return; // risposta vecchia: ignorata
                        if (d.esiste) {
                            esito.textContent = 'Autore trovato: ' + d.nome + ' ' + d.cognome;
                            mostraNuovoAutore(false);
                        } else if (d.esiste === false) {
                            mostraNuovoAutore(true);
                        }
                    })
                    .catch(function () { /* se il controllo fallisce, verifica comunque il server */ });
            }
 
            cf.addEventListener('input', controlla);
            if (cf.value.trim() !== '') controlla();
        })();
    </script>
</body>
</html>
<?php $conn->close(); ?>
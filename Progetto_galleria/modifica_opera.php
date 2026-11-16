<?php
session_start();
 
// Serve essere loggati
if (!isset($_SESSION['idUtente'])) {
    header('Location: login.php');
    exit;
}
 
require_once 'connessione.php';
require_once 'funzioni.php';
 
const MAX_TESTO = 50; // lunghezza massima del titolo
 
// Solo l'amministratore può stare qui
$eAdmin = !empty($_SESSION['admin']);
if (!$eAdmin) {
    http_response_code(403);
}
 
$errori = [];
$opera = null;          // dati dell'opera mostrati nel form
$nonTrovata = false;
$opere = [];
$autori = [];
 
$id = filter_var($_GET['id'] ?? $_POST['id'] ?? null, FILTER_VALIDATE_INT);
 
if ($eAdmin) {
    try {
        /* --------------------------------------------------------------
           SALVATAGGIO delle modifiche
           -------------------------------------------------------------- */
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $dati = [
                'titolo'  => trim($_POST['titolo'] ?? ''),
                'anno'    => trim($_POST['anno'] ?? ''),
                'prezzo'  => str_replace(',', '.', trim($_POST['prezzo'] ?? '')),
                'stato'   => $_POST['stato'] ?? '',
                'autore'  => trim($_POST['autore'] ?? ''),
            ];
 
            if ($dati['titolo'] === '' || mb_strlen($dati['titolo']) > MAX_TESTO) {
                $errori[] = 'Inserisci un titolo (massimo ' . MAX_TESTO . ' caratteri).';
            }
            $data = DateTime::createFromFormat('!Y-m-d', $dati['anno']);
            if (!$data || $data->format('Y-m-d') !== $dati['anno']) {
                $errori[] = 'Inserisci una data di composizione valida.';
            }
            if (!is_numeric($dati['prezzo']) || (float) $dati['prezzo'] < 0) {
                $errori[] = 'Il prezzo deve essere un numero maggiore o uguale a 0.';
            }
            if (!in_array($dati['stato'], ['0', '1'], true)) {
                $errori[] = 'Stato non valido.';
            }
            if ($dati['autore'] === '') {
                $errori[] = 'Scegli un autore.';
            }
 
            if (!$errori) {
                $prezzo = (float) $dati['prezzo'];
                $stato  = (int) $dati['stato'];
 
                $stmt = $conn->prepare(
                    'UPDATE Opera SET titolo = ?, AnnoComposizione = ?, prezzo = ?, stato = ?, autore = ?
                     WHERE id = ?'
                );
                $stmt->bind_param('ssdisi', $dati['titolo'], $dati['anno'], $prezzo, $stato, $dati['autore'], $id);
                $stmt->execute();
                $stmt->close();
 
                // Immagine facoltativa: sostituisce quella esistente
                $erroreImmagine = salvaImmagineOpera($id, $_FILES['immagine'] ?? null);
 
                $_SESSION['avviso'] = $erroreImmagine
                    ? ['errore', 'Dati salvati, ma l\'immagine non è stata caricata: ' . $erroreImmagine]
                    : ['ok', 'Modifiche salvate.'];
 
                header('Location: modifica_opera.php?id=' . $id);
                exit;
            }
 
            // Ci sono errori: ripropongo nel form quanto è stato scritto
            $opera = [
                'id' => $id, 'titolo' => $dati['titolo'], 'AnnoComposizione' => $dati['anno'],
                'prezzo' => $dati['prezzo'], 'stato' => $dati['stato'], 'autore' => $dati['autore'],
            ];
        }
 
        /* --------------------------------------------------------------
           CARICAMENTO dell'opera scelta
           -------------------------------------------------------------- */
        if ($opera === null && $id) {
            $stmt = $conn->prepare(
                'SELECT id, titolo, AnnoComposizione, prezzo, stato, autore FROM Opera WHERE id = ?'
            );
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $opera = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $nonTrovata = ($opera === null);
        }
 
        $opere  = $conn->query('SELECT id, titolo FROM Opera ORDER BY id')->fetch_all(MYSQLI_ASSOC);
        $autori = $conn->query('SELECT codiceFiscale, nome, cognome FROM Autore ORDER BY cognome, nome')
                       ->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
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
    <title>Modifica opere - Galleria d'arte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <?php require 'header.php'; ?>
 
    <header class="testata">
        <h1>Modifica opere</h1>
    </header>
 
    <main class="pagina-modulo">
        <?php if (!$eAdmin): ?>
            <p class="messaggio">Questa pagina è riservata all'amministratore. <a href="index.php">Torna alla galleria</a>.</p>
 
        <?php else: ?>
            <?php if ($avviso): ?>
                <p class="avviso <?= htmlspecialchars($avviso[0]) ?>" role="status"><?= htmlspecialchars($avviso[1]) ?></p>
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
 
            <!-- Scelta dell'opera da modificare -->
            <form method="get" action="modifica_opera.php" class="selettore">
                <div class="campo">
                    <label for="scelta">Opera da modificare</label>
                    <select id="scelta" name="id" onchange="this.form.submit()">
                        <option value="">Scegli un'opera…</option>
                        <?php foreach ($opere as $o): ?>
                            <option value="<?= (int) $o['id'] ?>" <?= (int) $o['id'] === (int) $id ? 'selected' : '' ?>>
                                <?= (int) $o['id'] ?> - <?= htmlspecialchars($o['titolo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="pulsante">Apri</button>
            </form>
 
            <?php if ($nonTrovata): ?>
                <p class="messaggio">Opera non trovata.</p>
 
            <?php elseif ($opera): ?>
                <form method="post" action="modifica_opera.php" enctype="multipart/form-data" class="modulo">
                    <input type="hidden" name="id" value="<?= (int) $opera['id'] ?>">
 
                    <label>Codice opera (non modificabile)</label>
                    <input type="text" value="<?= (int) $opera['id'] ?>" disabled>
 
                    <label for="titolo">Titolo</label>
                    <input type="text" id="titolo" name="titolo" maxlength="<?= MAX_TESTO ?>" required
                           value="<?= htmlspecialchars((string) $opera['titolo']) ?>">
 
                    <label for="anno">Data di composizione</label>
                    <input type="date" id="anno" name="anno" required
                           value="<?= htmlspecialchars((string) $opera['AnnoComposizione']) ?>">
 
                    <label for="prezzo">Prezzo in € (0 = in valutazione)</label>
                    <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" required
                           value="<?= htmlspecialchars((string) $opera['prezzo']) ?>">
 
                    <label for="stato">Stato</label>
                    <select id="stato" name="stato">
                        <option value="0" <?= (string) $opera['stato'] === '0' ? 'selected' : '' ?>>0 - Disponibile</option>
                        <option value="1" <?= (string) $opera['stato'] === '1' ? 'selected' : '' ?>>1 - Venduta</option>
                    </select>
 
                    <label for="autore">Autore</label>
                    <select id="autore" name="autore" required>
                        <?php foreach ($autori as $a): ?>
                            <option value="<?= htmlspecialchars($a['codiceFiscale']) ?>"
                                <?= $opera['autore'] === $a['codiceFiscale'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['nome'] . ' ' . $a['cognome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
 
                    <label for="immagine">Nuova immagine (facoltativa, sostituisce quella attuale)</label>
                    <input type="file" id="immagine" name="immagine" accept="image/jpeg,image/png,image/webp">
 
                    <button type="submit" class="pulsante">Salva modifiche</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>
<?php $conn->close(); ?>
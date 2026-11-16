<?php
session_start();
require_once 'connessione.php';
 
// Se sei già dentro, vai direttamente alla galleria
if (isset($_SESSION['utente'])) {
    header('Location: index.php');
    exit;
}
 
$errore = null;
$nomeUtente = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeUtente = trim($_POST['nomeUtente'] ?? '');
    $password   = $_POST['password'] ?? '';
 
    if ($nomeUtente === '' || $password === '') {
        $errore = 'Inserisci nome utente e password.';
    } else {
        try {
            // Query preparata: cerco l'utente per nome utente
            $stmt = $conn->prepare('SELECT id, nomeUtente, `password` FROM Utente WHERE nomeUtente = ?');
            $stmt->bind_param('s', $nomeUtente);
            $stmt->execute();
            $utente = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            // Stesso messaggio sia se l'utente non esiste sia se la password è sbagliata
            if ($utente && hash_equals((string) $utente['password'], $password)) {
                session_regenerate_id(true);
                $_SESSION['idUtente'] = (int) $utente['id'];
                $_SESSION['utente']   = $utente['nomeUtente'];
                $_SESSION['admin']  = ($utente['nomeUtente'] === 'admin');
 
                header('Location: index.php');
                exit;
            }else{
                $errore = 'Nome utente o password errati.';
            }
            
        } catch (mysqli_sql_exception $e) {
            $errore = 'Errore del database: impossibile effettuare l\'accesso.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accedi - Galleria d'arte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <main class="accesso">
        <h1>Galleria d'arte</h1>
        <p class="messaggio">Accedi per entrare nella galleria.</p>
 
        <form method="post" action="login.php" class="modulo">
            <label for="nomeUtente">Nome utente</label>
            <input type="text" id="nomeUtente" name="nomeUtente" autocomplete="username"
                   value="<?= htmlspecialchars($nomeUtente) ?>" autofocus>
 
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password">
 
            <button type="submit" class="pulsante">Accedi</button>
        </form>
    </main>
 
    <?php if ($errore): ?>
    <script>
        // Avviso in caso di credenziali errate
        alert(<?= json_encode($errore) ?>);
    </script>
    <?php endif; ?>
</body>
</html>
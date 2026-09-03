<?php
$query = "SELECT i.nome, i.cognome
          FROM impiegato i
          JOIN viceprimario v ON i.codicefiscale = v.cfimpiegato
          JOIN (
              SELECT cfvice
              FROM sostituzione
              GROUP BY cfvice
              HAVING COUNT(cfprimario) = 1
          ) s ON v.cfvice = s.cfvice;";

$result = pg_query($conn, $query);

if (!$result) {
    echo "Si è verificato un errore.<br/>" . pg_last_error($conn);
    exit();
} else {
    // Stampa l'header HTML per la tabella
    echo '<br><table class=\'container\'>';
    echo '<tr>';
    echo '<th>Nome</th>';
    echo '<th>Cognome</th>';
    echo '</tr>';
    
    // Itera sui risultati e stampa i dati
    while ($row = pg_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cognome']) . '</td>';
        echo '</tr>';
    }
    echo '</table><br>';
    echo "Torna al <a href='index.php'>menu</a>";
}
?>

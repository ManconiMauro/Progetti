<?php
// Numero di ordini in attesa da visualizzare sul carrello
if (!isset($numeroCarrello)) {
    $numeroCarrello = (isset($conn) && function_exists('contaCarrello'))
        ? contaCarrello($conn, $_SESSION['idUtente'] ?? 0)
        : 0;
}
$eAdmin = !empty($_SESSION['admin']);
?>
<div class="barra">

    <details class="menu">
        <summary aria-label="Apri il menu">
            <svg class="icona" viewBox="0 0 24 24" aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            <span>Menu</span>
        </summary>
        <ul class="menu-voci">
            <li>
                <a href="index.php">
                    <svg class="icona" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Galleria
                </a>
            </li>
            <li>
                <a href="nuova_opera.php">
                    <svg class="icona" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    Nuova opera
                </a>
            </li>
            <?php if ($eAdmin): ?>
            <li>
                <a href="modifica_opera.php">
                    <svg class="icona" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                    </svg>
                    Modifica opere
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="logout.php">
                    <svg class="icona" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Esci
                </a>
            </li>
        </ul>
    </details>
 
    <div class="azioni">
        <a class="carrello" href="carrello.php"
           aria-label="Carrello, <?= (int) $numeroCarrello ?> articoli">
            <svg class="icona" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <?php if ($numeroCarrello > 0): ?>
                <span class="badge"><?= (int) $numeroCarrello ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>
 
<script>
    // Chiude il menu cliccando fuori o premendo Esc
    (function () {
        var menu = document.querySelector('.menu');
        document.addEventListener('click', function (e) {
            if (menu.open && !menu.contains(e.target)) menu.open = false;
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') menu.open = false;
        });
    })();
</script>
<?php
/**funzione per formattare l'immagine salvata nella certella immagini*/
function immagineOpera($id)
{
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $estensione) {
        $percorso = 'immagini/' . (int) $id . '.' . $estensione;
        if (file_exists(__DIR__ . '/' . $percorso)) {
            return $percorso;
        }
    }
    return null;
}
 
/**funzione che formatta il prezzo */
function formattaPrezzo($prezzo)
{
    return number_format((float) $prezzo, 2, ',', '.') . ' €';
}
 
/**funzione che conta gli ordini in attesa del carrello di un utente */
function contaCarrello($conn, $idUtente)
{
    if (!$idUtente) {
        return 0;
    }
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Ordine WHERE idUtente = ? AND statoOrdine = 'in Attesa'");
    $stmt->bind_param('i', $idUtente);
    $stmt->execute();
    $stmt->bind_result($numero);
    $stmt->fetch();
    $stmt->close();
    return (int) $numero;
}

//** Salva l'immagine caricata dal from nuova Opera, se tutto è andato corretamente o se non è stata caricata una foto, restituisce null, altrimenti errore */
function salvaImmagineOpera($id, $file)
{
    if ($file === null || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // nessuna immagine scelta: va bene
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return 'Caricamento dell\'immagine non riuscito.';
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        return 'L\'immagine supera i 5 MB.';
    }
 
    $info  = @getimagesize($file['tmp_name']);
    $tipi  = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($tipi[$info[2]])) {
        return 'Formato immagine non valido (usa JPG, PNG o WEBP).';
    }
 
    $cartella = __DIR__ . '/immagini';
    if (!is_dir($cartella)) {
        mkdir($cartella, 0777, true);
    }
 
    // Tolgo eventuali vecchie immagini della stessa opera (anche con altra estensione)
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $estensione) {
        $vecchia = $cartella . '/' . (int) $id . '.' . $estensione;
        if (is_file($vecchia)) {
            unlink($vecchia);
        }
    }
 
    if (!move_uploaded_file($file['tmp_name'], $cartella . '/' . (int) $id . '.' . $tipi[$info[2]])) {
        return 'Impossibile salvare l\'immagine.';
    }
    return null;
}
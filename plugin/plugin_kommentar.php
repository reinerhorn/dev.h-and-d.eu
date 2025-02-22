<?php
// Direkten Zugriff verhindern
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Zugriff verweigert!');
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";

try {
    $db_connector = getDbConnection();
    $stmt = $db_connector->prepare('SELECT username, text, id FROM kommentar');
    $stmt->execute();
    $plugin_result = $stmt->get_result();

    $kommentar = [];
    while ($rec = $plugin_result->fetch_assoc()) {
        $kommentar[] = sprintf(
            '<div class="comment">
                <strong>%s</strong><br><br>
                <em>Kommentar:</em><br><br>
                %s<br>
                <hr>
                <small>ID: %s</small>
                <hr>
            </div>',
            htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'),
            nl2br(htmlspecialchars($rec['text'], ENT_QUOTES, 'UTF-8')),
            (int)$rec['id']
        );
    }

    echo '<div class="comment_container">' . implode('', $kommentar) . '</div>';
} catch (Exception $e) {
    error_log('Fehler beim Laden der Kommentare: ' . $e->getMessage());
    echo '<div class="comment_container">Fehler beim Laden der Kommentare.</div>';
} finally {
    /*$stmt->close();*/
    
}
?>
<div id="LanguageSelector">
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
$connection = getDbConnection();
global $connection; // Stellt sicher, dass $connection aus index.php verwendet wird

if (!isset($connection)) {
    die("Fehler: Keine Datenbankverbindung.");
}

// Sprachlabel abrufen
$result = $connection->query('SELECT label FROM translation WHERE fk_translation_placeholder="LANG_SELECTOR_LABEL" AND fk_language_id="' . $language . '"');
echo ($rec = $result->fetch_assoc()) ? $rec['label'] : 'ups...';

// Sprachen abrufen
$result = $connection->query('SELECT * FROM trans_language ORDER BY label ASC');
$page = isset($_REQUEST['page']) ? '&page=' . $_REQUEST['page'] : '';

while ($rec = $result->fetch_assoc()) {
    echo '<a href="?language=' . $rec['id'] . $page . '">' . $rec['label'] . '</a>';
}
?>
</div>
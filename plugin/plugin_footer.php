<footer class="footer">
<?php
if (!isset($main_db_connection)) {
    die('<p>Fehler: Datenbankverbindung nicht gesetzt.</p>');
}

$stmt = $main_db_connection->prepare('SELECT headline, link, language, label, version FROM footer LIMIT 1');
if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($headline, $link, $language, $label, $version);

    if (!$stmt->fetch()) {
        $headline = 'Kein Eintrag gefunden';
        $link = '#';
        $label = 'Kein Eintrag';
        $version = '';
    }
    $stmt->close();
} else {
    $headline = 'Fehler bei der Datenbankabfrage';
    $link = '#';
    $label = 'Fehler';
    $version = '';
}

// XSS-Schutz
$headline = htmlspecialchars($headline, ENT_QUOTES, 'UTF-8');
$link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
$label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
$version = htmlspecialchars($version, ENT_QUOTES, 'UTF-8');

echo '© 2020 - ' . date("Y") . ' <a title="' . $headline . '" href="' . $link . '">' . $label . ' ' . $version . '</a>';
?>
</footer>

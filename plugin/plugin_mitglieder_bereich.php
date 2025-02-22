<?php
 
 // Direkten Zugriff verhindern
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
  http_response_code(403);
  exit('Zugriff verweigert!');
}

// Prüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['email'])) {
    echo "Bitte erst einloggen";
    exit; // Falls nicht eingeloggt, sofort beenden
}

$user = $_SESSION['name'] ?? ""; // Kurzschreibweise für isset()

// Falls `user_id` gesetzt ist, beenden wir die Session und leiten um
if (isset($_SESSION['user_id'])) {
    session_destroy();
    header('Location: index.php'); // Falsches Komma korrigiert
    exit;
}

// Aktuelles Datum und Benutzername anzeigen
$datum = date('d.m.Y l H:i:s') . '<br>';
$datum .= 'Einen schönen, guten Tag: ' . htmlspecialchars($user, ENT_QUOTES, 'UTF-8') . '<br>';
$datum .= '<a href="/plugin/plugin_logout.php">Logout</a>'; // JavaScript entfernt für mehr Sicherheit

echo $datum;
?>
 
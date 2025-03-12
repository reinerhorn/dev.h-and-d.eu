<?php
 

 if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Alle Session-Variablen löschen
$_SESSION = [];

// Session-Cookie sicher löschen
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/', '', true, true);
}

// Session zerstören
session_destroy();

// Debugging nach dem Löschen
echo "<pre>";
echo "SESSION nach session_destroy():\n";
var_dump($_SESSION);
echo "COOKIE nach setcookie():\n";
var_dump($_COOKIE);
echo "</pre>";

// Weiterleitung zur Startseite
header("Location: /index.php", true, 302);
exit;
?>
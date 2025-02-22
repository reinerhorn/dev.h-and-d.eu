<?php
session_start();

// Alle Session-Variablen sicher löschen
$_SESSION = [];

// Session-Cookie ungültig machen (falls gesetzt)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session vollständig zerstören
session_destroy();

// Sicheres Redirect
header("Location: /index.php", true, 302);
exit;
?>
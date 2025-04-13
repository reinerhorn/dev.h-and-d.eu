<?php
// Kein session_start() nötig, wenn Session bereits aktiv ist
// Auch kein doppeltes session_destroy()

// Leere Session-Variablen löschen
$_SESSION = [];

// Session-Cookie löschen (optional & sicher)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session zerstören
session_destroy();

// Weiterleitung (keine Ausgabe vorher!)
header("Location: /index.php");
exit;
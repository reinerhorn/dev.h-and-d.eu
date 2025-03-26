<?php

 if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session-Variablen löschen
$_SESSION = [];
session_unset();
session_destroy();

// **Session-Cookie entfernen**
$cookie_name = session_name();
if (isset($_COOKIE[$cookie_name])) {
    $params = session_get_cookie_params();
    setcookie($cookie_name, '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    setcookie("PHPSESSID", '', time() - 42000, '/');
}

// **Sicherstellen, dass kein altes Session-Cookie mehr existiert**
header("Set-Cookie: PHPSESSID=deleted; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT; HttpOnly; SameSite=Lax");

// **Cache umgehen, falls Browser alte Seite speichert**
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// **Weiterleitung nach Logout**
header("Location: /index.php");
exit;
?>
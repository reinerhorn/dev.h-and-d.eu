<?php
session_start();
session_unset();
session_destroy();

// Korrekte URL-Konstruktion zur Login-Seite
$loginPage = dirname($_SERVER['PHP_SELF']) . "/admin/login.php";
$loginPage = rtrim($loginPage, "/"); // Doppelte Slashes vermeiden
header("Location: https://" . $_SERVER['HTTP_HOST'] . $loginPage);
exit();
?>
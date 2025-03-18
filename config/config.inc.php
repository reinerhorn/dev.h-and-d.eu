<?php
session_start();

$host = "localhost"; 
$user = "root"; 
$pass = "101TanZen101"; 
$dbname = "cms_database"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Datenbankverbindung fehlgeschlagen: " . $conn->connect_error);
}

function getUserRole($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT role FROM plugin_login_users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();
    return $role ?? null;
}

// Navigation aus der Datenbank laden
function getNavigation($role) {
    global $conn;
    $stmt = $conn->prepare("SELECT name, link FROM navigation WHERE role IS NULL OR role = ?");
    $stmt->bind_param("i", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>

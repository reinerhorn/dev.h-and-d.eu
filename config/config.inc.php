<?php
<<<<<<< HEAD

function getDbConnection()
{
  return new mysqli("", "root", "101TanZen101", "dbs060954hd");
} 
?>
=======
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


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

 
 
 

?>
>>>>>>> 25476e17555bdd1bf21b1834f3542ee06310f294

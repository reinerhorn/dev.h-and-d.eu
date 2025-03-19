<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    die("Zugriff verweigert!");
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mitgliederbereich</title>
</head>
<body>
    <header>
        <h1>Mitgliederbereich</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Startseite</a></li>
            <li><a href="member.php">Mitgliederbereich</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <h1>Willkommen im Mitgliederbereich!</h1>
    </main>
 
    <h1>Willkommen im Mitgliederbereich!</h1>
    <a href="index.php">Zurück</a> | <a href="logout.php">Logout</a>
</main>
<footer>
        <p>&copy; 2021</p>
    </footer>
</body>
</html>

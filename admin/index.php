
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Startseite</title>
</head>
<body>
    <h1>Willkommen</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Eingeloggt als Benutzer-ID: <?= $_SESSION['user_id'] ?></p>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</body>
</html>
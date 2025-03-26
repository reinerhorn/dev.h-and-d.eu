<?php
<<<<<<< HEAD
include_once $_SERVER['DOCUMENT_ROOT'] . "/inc/session.php"; 
#echo "<p>🔍 Session Role: " . ($_SESSION['role'] ?? 'Nicht gesetzt') . "</p>";
#include_once $_SERVER['DOCUMENT_ROOT'] . "/inc/web_besucher.php";
=======
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php") ;
>>>>>>> 25476e17555bdd1bf21b1834f3542ee06310f294

$header_content = $conn->query("SELECT content FROM header WHERE css = 'header'")->fetch_assoc()['content'] ?? '';
$footer_content = $conn->query("SELECT content FROM footer WHERE css = 'footer'")->fetch_assoc()['content'] ?? '';

// Navigation abrufen
$nav_items = $conn->query("SELECT * FROM navigation")->fetch_all(MYSQLI_ASSOC);

// Modul anhand des Links laden
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$stmt = $conn->prepare("SELECT content FROM modules WHERE name = ?");
$stmt->bind_param("s", $page);
$stmt->execute();
$stmt->bind_result($content);
$stmt->fetch();
$stmt->close();

if (!$content) {
    $content = "Seite nicht gefunden.";
}
?><!DOCTYPE html> 
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CMS Frontend</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" media="screen">
</head>
<body>
<header>
    <?= $header_content; ?>
</header>
    <nav>
     <?= $nav_items; ?>
    </nav>
    <main>
        <?php echo $content; ?>
    </main>
    <footer>
    <?= $footer_content; ?>
</footer>
</body>
</html>

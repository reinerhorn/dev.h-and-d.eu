<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php") ;

$header_content = $conn->query("SELECT content FROM header_footer WHERE type = 'header'")->fetch_assoc()['content'] ?? '';
$footer_content = $conn->query("SELECT content FROM Header_footer WHERE type = 'footer'")->fetch_assoc()['content'] ?? '';

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
        <ul>
            <?php foreach ($nav_items as $nav): ?>
                <li><a href="index.php?page=<?php echo htmlspecialchars($nav['link']); ?>"><?php echo htmlspecialchars($nav['label']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <main>
        <?php echo $content; ?>
    </main>
    <footer>
    <?= $footer_content; ?>
</footer>
</body>
</html>

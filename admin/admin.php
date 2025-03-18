<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include($_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php");
include($_SERVER['DOCUMENT_ROOT'] . "/config/functions.inc.php"); // Importierte Funktionen

// Prüfen, ob der Benutzer Admin ist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    die("Zugriff verweigert!");
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Daten für Header und Footer abrufen
$role = $_SESSION['role'] ?? 0;
$header_content = getHeaderFooterContent($conn, 'header', $role);
$header_links = getHeaderFooterLinks($conn, 'header', $role);
$footer_content = getHeaderFooterContent($conn, 'footer', 0);
$footer_links = getHeaderFooterLinks($conn, 'footer', $role);

$pages = $conn->query("SELECT * FROM page")->fetch_all(MYSQLI_ASSOC);
$navigations = $conn->query("SELECT * FROM navigation")->fetch_all(MYSQLI_ASSOC);
$users = $conn->query("SELECT * FROM plugin_login_users")->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/css/style.css" media="screen">
    <title>Admin-Bereich</title>
</head>
<body>
<header>
    <nav>
        <ul>
    <?php 
    $unique_header_links = []; // Sicherstellen, dass die Liste leer beginnt
    foreach ($header_links as $link): 
        $content = trim($link['content']);
    
        if (in_array($content, $unique_header_links)) {
        continue;
        }
    
        $unique_header_links[] = $content;
        echo "<li>{$content}</li>";
    endforeach; 
?>
        </ul>
    </nav>
    <?php if (empty($unique_header_links)): ?>
    <div><?= $header_content; ?></div>
    <?php endif; ?> 
</header>

<main>
    <h1>Admin-Bereich</h1>
    <a href="index.php">Zurück zur Webseite</a> | <a href="logout.php">Logout</a>

    <h2>Header verwalten</h2>
    <form method="POST" id="headerForm">      
 
<label for="header_type">Typ:</label>
<input type="text" name="header_type" value="<?= htmlspecialchars($header_type ?? '') ?>" required>

<label for="header_role">Rolle:</label>
<input type="text" name="header_role" value="<?= htmlspecialchars($header_role ?? '') ?>" required>
    
<label for="header_content">Inhalt:</label>
<input type="text" name="header_content" value="<?= htmlspecialchars($header_content) ?>" required>
    </form>
    <ul>
    <?php foreach ($header_links as $header): ?>
        <li>
            <?= htmlspecialchars($header['content']); ?>
            <a href="#" onclick="editHeader(<?= $header['id'] ?? 0; ?>, '<?= htmlspecialchars($header['content'] ?? '', ENT_QUOTES); ?>', '<?= htmlspecialchars($header['type'] ?? '', ENT_QUOTES); ?>', <?= $header['role'] ?? 0; ?>); return false;">Bearbeiten</a>
            <a href="admin.php?delete_header=<?= isset($header['id']) ? $header['id'] : ''; ?>" 
               onclick="return confirm('Wirklich löschen?');">Löschen</a>
        </li>
    <?php endforeach; ?>
</ul>

    <h2>Navigation verwalten</h2>
    <form method="POST">
        <input type="hidden" name="nav_id">
        <input type="text" name="name" placeholder="Menü-Name" required>
        <input type="text" name="link" placeholder="Link (z.B. index.php?page=1)" required>
        <select name="role">
            <option value="">Alle</option>
            <option value="1">Admin</option>
            <option value="2">Mitglied</option>
        </select>
        <button type="submit" name="save_nav">Speichern</button>
    </form>
    <ul>
        <?php foreach ($navigations as $nav): ?>
            <li>
                <?= isset($nav['name']) ? htmlspecialchars($nav['name']) : 'Unbenannt'; ?> (<?= isset($nav['link']) ? htmlspecialchars($nav['link']) : '#'; ?>)
                <a href="admin.php?delete_nav=<?= $nav['id'] ?>">Löschen</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Seitenverwaltung</h2>
    <form method="POST">
        <input type="hidden" name="page_id">
        <input type="text" name="title" placeholder="Titel" required>
        <textarea name="content" placeholder="Inhalt" required></textarea>
        <input type="text" name="language" placeholder="Sprache" required>
        <button type="submit" name="save_page">Speichern</button>
    </form>
    <ul>
        <?php foreach ($pages as $page): ?>
            <li>
                <?= htmlspecialchars($page['title']) ?> (<?= $page['language'] ?>)
                <a href="admin.php?delete_page=<?= $page['id'] ?>">Löschen</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Benutzerverwaltung</h2>
    <form method="POST">
        <input type="hidden" name="user_id">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort">
        <select name="role">
            <option value="1">Admin</option>
            <option value="2">Mitglied</option>
        </select>
        <button type="submit" name="save_user">Speichern</button>
    </form>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?= htmlspecialchars($user['username']) ?> (Rolle: <?= $user['role'] ?>)
                <a href="admin.php?delete_user=<?= $user['id'] ?>">Löschen</a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

    <footer>
  
    <nav>
        <ul>
    <?php 
            $unique_links = []; // Sicherstellen, dass die Liste leer beginnt
            foreach ($footer_links as $link): 
            $content = trim($link['content']);
    
    // Falls der Inhalt bereits ausgegeben wurde, überspringen
    if (in_array($content, $unique_links)) {
        continue;
    }
    
    $unique_links[] = $content; // Füge neuen Inhalt zur Liste hinzu
    echo "<li>{$content}</li>";
endforeach; 
?>
        </ul>
    </nav>
    <?php if (empty($unique_links)): ?>
    <div><?= $footer_content; ?></div>
    <?php endif; ?></div>
</footer>
 
<script>
function editHeader(id, content, type, role) {
    document.getElementById('headerForm').setAttribute('action', 'admin.php?edit_header=' + id);
    document.querySelector('input[name="header_type"]').value = type;
    document.querySelector('input[name="header_role"]').value = role;
    document.querySelector('input[name="header_content"]').value = content;
}
</script>
</body>
</html>
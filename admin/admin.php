<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include($_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php");
include($_SERVER['DOCUMENT_ROOT'] . "/config/functions.inc.php"); // Importierte Funktionen
$user_role = $_SESSION['role'] ?? 0; // Standardwert für Gäste
$role = $user_role; // Einheitlich
#echo "<pre>DEBUG: SESSION ROLE = " . ($_SESSION['role'] ?? 'NICHT GESETZT') . "</pre>";
#echo "<pre>DEBUG: Verwendete ROLE = " . $role . "</pre>";
// Prüfen, ob der Benutzer Admin ist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    die("Zugriff verweigert!");
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Daten für Header und Footer abrufen
$user_role = $_SESSION['role'] ?? 0; // Standardwert für Gäste
$role = $user_role; // Einheitliche Nutzung

// Verbindung prüfen
if (!$conn) {
    die("Fehler: Keine Verbindung zur Datenbank.");
}
$headerContent = getHeaderByRole($conn, $user_role);
if (empty($headerContent) || trim($headerContent) === '') {
    echo "<p style='color:red;'>Fehler: Kein Header-Inhalt gefunden!</p>";
} else {
   #echo "<p style='color:green;'>Header erfolgreich geladen.</p>"; // Optional für Debugging
}
$footerContent = getFooterByRole($conn, $role);
if (empty($footerContent)) {
    $footerContent = "<p>Fehler: Footer nicht geladen.</p>";
}

// Links abrufen
$footer_links = getFooterLinks($conn, $role) ?? [];
if (!is_array($footer_links)) {
    echo "<pre>FEHLER: getFooterLinks() hat keinen gültigen Wert zurückgegeben!</pre>";
    var_dump($footer_links);
    die();
}
$header_links = getHeaderLinks($conn, $role) ?? [];

// SQL-Daten abrufen
$pages = $conn->query("SELECT * FROM page") ? $conn->query("SELECT * FROM page")->fetch_all(MYSQLI_ASSOC) : [];
$navigations = $conn->query("SELECT * FROM navigation") ? $conn->query("SELECT * FROM navigation")->fetch_all(MYSQLI_ASSOC) : [];
$users = $conn->query("SELECT * FROM plugin_login_users") ? $conn->query("SELECT * FROM plugin_login_users")->fetch_all(MYSQLI_ASSOC) : [];

// Sicherstellen, dass Variablen existieren
$header_type = $_POST['header_type'] ?? '';
$header_role = $_POST['header_role'] ?? $role; // Default to $role if not set
 
// Standardüberprüfung für $header_links
if (!isset($header_links) || !is_array($header_links)) {
    $header_links = [];
}
 

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/css/style.css" media="screen">
    <link rel="stylesheet" type="text/css" href="/css/header.css" media="screen">
    <link rel="stylesheet" type="text/css" href="/css/footer.css" media="screen">
    <title>Admin-Bereich</title>
</head>

<body>

    <!-- Header wird nur einmal ausgegeben -->
    <header>
    <?= $headerContent ?>
    </header>
    <main>
        <h1>Admin-Bereich</h1>
        <a href="index.php">Zurück zur Webseite</a> | <a href="logout.php">Logout</a>

        <h2>Header verwalten</h2>
        <form method="POST" id="headerForm" action="admin.php">

            <label for="header_type">Typ:</label>
            <input type="text" name="header_type" value="<?= htmlspecialchars($header_type ?? '', ENT_QUOTES ) ?>" required>

            <label for="header_role">Rolle:</label>
            <input type="text" name="header_role" value="<?= htmlspecialchars($header_role ?? '', ENT_QUOTES) ?>" required>

            <label for="header_content">Inhalt:</label>
            <input type="text" name="header_content" value="<?= htmlspecialchars($headerContent ?? '', ENT_QUOTES) ?>" required>
        </form>
        <?php if (!empty($header_links)): ?>
            <ul>
                <?php foreach ($header_links as $header): ?>
                    <li>
                        <?= htmlspecialchars($header['content']); ?>
                        <a href="#" onclick="editHeader(<?= $header['id'] ?? 0; ?>, '<?= htmlspecialchars($header['content'] ?? '', ENT_QUOTES); ?>', <?= $header['role'] ?? 0; ?>); return false;">Bearbeiten</a>
                        <?php if (isset($header['id'])): ?>
                            <a href="admin.php?delete_header=<?= $header['id']; ?>" onclick="return confirm('Wirklich löschen?');">Löschen</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h2>Navigation verwalten</h2>
        <script>
            function editNav(id, name, link, role) {
                document.getElementById('navForm').setAttribute('action', 'admin.php?edit_nav=' + id);
                document.querySelector('input[name="nav_id"]').value = id;
                document.querySelector('input[name="nav_name"]').value = name;
                document.querySelector('input[name="nav_link"]').value = link;
                document.querySelector('input[name="nav_role"]').value = role;
            }
        </script>
        <form method="POST" id="navForm" action="admin.php">
            <input type="hidden" name="nav_id">

            <label for="nav_label">Menü-Label:</label>
            <input type="text" name="nav_label" required>

            <label for="nav_link">Link:</label>
            <input type="text" name="nav_link" required>

            <label for="nav_type">Typ:</label>
            <input type="text" name="nav_type" required>

            <label for="nav_css">CSS-Klasse:</label>
            <input type="text" name="nav_css">

            <label for="nav_role">Rolle:</label>
            <input type="text" name="nav_role" required>

            <label for="nav_parent">Elternelement (Parent ID):</label>
            <input type="text" name="nav_parent">
            <button type="submit" name="save_nav">Speichern</button>
            
        </form>

        <ul>
            <?php foreach ($navigations as $nav): ?>
                <form method="POST" class="nav-item-form">
                    <input type="hidden" name="nav_id" value="<?= $nav['id'] ?>">
                    <input type="text" name="nav_label" value="<?= htmlspecialchars($nav['label']) ?>" required>
                    <input type="text" name="nav_link" value="<?= htmlspecialchars($nav['link']) ?>" required>
                    <input type="text" name="nav_type" value="<?= htmlspecialchars($nav['type']) ?>" required>
                    <input type="text" name="nav_css" value="<?= htmlspecialchars($nav['css']) ?>">
                    <input type="text" name="nav_role" value="<?= $nav['role'] ?>" required>
                    <input type="text" name="nav_parent" value="<?= $nav['parent_id'] ?>">
                    <button type="submit" name="update_nav">Bearbeiten</button>
                    <button type="submit" name="delete_nav" onclick="return confirm('Menüpunkt wirklich löschen?');">Löschen</button>
                </form>
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
    <?= $footerContent  ?>
    <?PHP
    echo "<pre>DEBUG: SESSION ROLE = " . ($_SESSION['role'] ?? 'NICHT GESETZT') . "</pre>";
    echo "<pre>DEBUG: Verwendete ROLE = " . $role . "</pre>";?>
    </footer>
 
    

    <script>
        function editHeader(id, content, role) {
            document.getElementById('headerForm').setAttribute('action', 'admin.php?edit_header=' + id);
            document.querySelector('input[name="header_role"]').value = role;
            document.querySelector('input[name="header_content"]').value = content;
            document.querySelector('input[name="header_type"]').value = '<?= htmlspecialchars($header_type ?? '') ?>'; // Typ hinzufügen
        }
    </script>
</body>

</html>
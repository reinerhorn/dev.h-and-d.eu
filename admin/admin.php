<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php") ;

// Prüfen, ob der Benutzer Admin ist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    die("Zugriff verweigert!");
}

if (isset($_GET['delete_nav'])) {
    $nav_id = intval($_GET['delete_nav']);
    $stmt = $conn->prepare("DELETE FROM navigation WHERE id = ?");
    $stmt->bind_param("i", $nav_id);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// --- SEITENVERWALTUNG ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_page'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $language = $_POST['language'];
    $page_id = $_POST['page_id'] ?? null;

    if ($page_id) {
        $stmt = $conn->prepare("UPDATE page SET title = ?, content = ?, language = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $language, $page_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO page (title, content, language) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $language);
    }
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// --- NAVIGATION VERWALTEN ---
// Navigation löschen
if (isset($_GET['delete_nav'])) {
    $nav_id = intval($_GET['delete_nav']);
    $stmt = $conn->prepare("DELETE FROM navigation WHERE id = ?");
    $stmt->bind_param("i", $nav_id);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// Navigation bearbeiten
$edit_label = $edit_link = "";
if (isset($_GET['edit_nav'])) {
    $nav_id = intval($_GET['edit_nav']);
    $stmt = $conn->prepare("SELECT label, link FROM navigation WHERE id = ?");
    $stmt->bind_param("i", $nav_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($nav = $result->fetch_assoc()) {
        $edit_label = htmlspecialchars($nav['label']);
        $edit_link = htmlspecialchars($nav['link']);
    }
}

// Navigation speichern oder aktualisieren
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_nav'])) {
    $label = $_POST['name'];
    $link = $_POST['link'];
    $nav_id = $_POST['nav_id'] ?? null;

    if ($nav_id) {
        $stmt = $conn->prepare("UPDATE navigation SET label = ?, link = ? WHERE id = ?");
        $stmt->bind_param("ssi", $label, $link, $nav_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO navigation (label, link) VALUES (?, ?)");
        $stmt->bind_param("ss", $label, $link);
    }
    $stmt->execute();
    header("Location: admin.php");
    exit();
}


// --- BENUTZER VERWALTEN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $role = $_POST['role'];
    $user_id = $_POST['user_id'] ?? null;

    if ($user_id) {
        if ($password) {
            $stmt = $conn->prepare("UPDATE plugin_login_users SET username = ?, password = ?, role = ? WHERE id = ?");
            $stmt->bind_param("ssii", $username, $password, $role, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE plugin_login_users SET username = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sii", $username, $role, $user_id);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO plugin_login_users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $password, $role);
    }
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// Daten abrufen
$pages = $conn->query("SELECT * FROM page")->fetch_all(MYSQLI_ASSOC);
$navigations = $conn->query("SELECT * FROM navigation")->fetch_all(MYSQLI_ASSOC);
$users = $conn->query("SELECT * FROM plugin_login_users")->fetch_all(MYSQLI_ASSOC);
$modules = $conn->query("SELECT id, name FROM modules")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/style.css" media="screen">
    <title>Admin-Bereich</title>
</head>
<body>
    <h1>Admin-Bereich</h1>
    <a href="index.php">Zurück zur Webseite</a> | <a href="logout.php">Logout</a>

    <h2>Navigation verwalten</h2>
    <form method="POST">
        <input type="hidden" name="nav_id" value="<?= isset($_GET['edit_nav']) ? $_GET['edit_nav'] : ''; ?>">
        <input type="text" name="name" placeholder="Menü-Name" value="<?= $edit_label; ?>" required>
        <input type="text" name="link" placeholder="Link (z.B. index.php?page=1)" value="<?= $edit_link; ?>" required>
        <button type="submit" name="save_nav">Speichern</button>
    </form>
    <ul>
    <?php foreach ($navigations as $nav): ?>
        <li>
            <?= htmlspecialchars($nav['label']); ?>
            <a href="admin.php?edit_nav=<?= $nav['id'] ?>">Bearbeiten</a>
            <a href="admin.php?delete_nav=<?= $nav['id'] ?>" onclick="return confirm('Menüpunkt wirklich löschen?');">Löschen</a>
        </li>
    <?php endforeach; ?>
    </ul>
    <ul>
   

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
</body>
</html>
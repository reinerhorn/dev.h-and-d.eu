<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Prüfen, ob der Benutzer Admin ist
if (!isset($_SESSION['admin_a'])) {
    header('Location: /index.php');
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
$connection = getDbConnection();

// Variablen initialisieren
$id = $fk_card_id = $idx = $headline = $text = $link = "";

// Fehlerhandling aktivieren
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = $_POST['id'] ?? "";

        if ($action == "store") {
            $action = empty($id) ? "add" : "update";
        }

        if ($action == "add" || $action == "update") {
            $fk_card_id = $_POST['fk_card_id'] ?? "";
            $idx = $_POST['idx'] ?? "";
            $headline = $_POST['headline'] ?? "";
            $text = $_POST['text'] ?? "";
            $link = $_POST['link'] ?? "";
        }

        if ($action == "add") {
            $stmt = $connection->prepare("INSERT INTO p_card_content (fk_card_id, idx, headline, text, link) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sisss", $fk_card_id, $idx, $headline, $text, $link);
            $stmt->execute();
            $id = $connection->insert_id;
            $stmt->close();
        } elseif ($action == "update" && !empty($id)) {
            $stmt = $connection->prepare("UPDATE p_card_content SET fk_card_id=?, idx=?, headline=?, text=?, link=? WHERE id=?");
            $stmt->bind_param("sisssi", $fk_card_id, $idx, $headline, $text, $link, $id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action == "delete" && !empty($id)) {
            $stmt = $connection->prepare("DELETE FROM p_card_content WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            $id = "";
            $action = "add";
        } elseif ($action == "edit" && !empty($id)) {
            $stmt = $connection->prepare("SELECT fk_card_id, idx, headline, text, link FROM p_card_content WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($fk_card_id, $idx, $headline, $text, $link);
            $stmt->fetch();
           /* $stmt->close();*/
        }
    }
} catch (mysqli_sql_exception $e) {
    die("Datenbankfehler: " . $e->getMessage());
}

// Verbindung erst am Ende schließen
/*$connection->close();*/
?>

<div class="flex_container">
    <form name="editor" action="" method="post">
        <input type="hidden" name="action" value="page">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">

        <select name="record_selection" onchange="selectRecord()">
            <option value="">auswählen...</option>
            <option value="neu">neu</option>
            <option value="-" disabled></option>
            <?php
            $connection = getDbConnection(); // Verbindung erneut öffnen
            $stmt = $connection->prepare("SELECT id, fk_card_id FROM p_card_content");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($page = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($page['id'], ENT_QUOTES, 'UTF-8') . '">' . 
                     htmlspecialchars($page['fk_card_id'], ENT_QUOTES, 'UTF-8') . '</option>' . PHP_EOL;
            }
            $stmt->close();
            
            ?>
        </select>
        <br><br>

        <div class="group">
            <input type="text" id="idx" name="idx" class="input_color" value="<?= htmlspecialchars($idx, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="idx">Index</label>
        </div>

        <div class="group">
            <input type="text" id="headline" name="headline" class="input_color" value="<?= htmlspecialchars($headline, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="headline">Headline</label>
        </div>

        <div class="group">
            <input type="text" id="text" name="text" class="input_color" value="<?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="text">Text</label>
        </div>

        <div class="group">
            <input type="text" id="link" name="link" class="input_color" value="<?= htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="link">Link</label>
        </div>

        <button type="submit" onclick="this.form.elements['action'].value='store'">Speichern</button>
        <button type="submit" onclick="this.form.elements['action'].value='delete'">Löschen</button>
    </form>
</div>
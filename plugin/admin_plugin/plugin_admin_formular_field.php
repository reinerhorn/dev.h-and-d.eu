<?php
if (!isset($_SESSION['admin_a'])) {
    header('Location: /index.php');
    exit();
}

#require_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php"; // Stelle sicher, dass die Datenbankverbindung korrekt eingebunden ist
#$connection = getDbConnection();

$id = "";
$label = "";
$columns = "";
$use_placeholder = "";
$use_extra_label = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? "";

    if ($action === "store") {
        $action = empty($id) ? "add" : "update";
    }

    if (in_array($action, ["add", "update"], true)) {
        $label = $_POST['label'] ?? "";
        $columns = $_POST['columns'] ?? "";
        $use_placeholder = $_POST['use_placeholder'] ?? 0;
        $use_extra_label = $_POST['use_extra_label'] ?? 0;
    }

    if ($action === "add") {
        $stmt = $connection->prepare(
            "INSERT INTO p_content_formular (label, columns, use_placeholder, use_extra_label) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssii", $label, $columns, $use_placeholder, $use_extra_label);
        $stmt->execute();
        $id = $connection->insert_id;
    } elseif ($action === "update") {
        $stmt = $connection->prepare(
            "UPDATE p_content_formular SET label=?, columns=?, use_placeholder=?, use_extra_label=? WHERE id=?"
        );
        $stmt->bind_param("ssiii", $label, $columns, $use_placeholder, $use_extra_label, $id);
        $stmt->execute();
    } elseif ($action === "delete") {
        $stmt = $connection->prepare("DELETE FROM p_content_formular WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $id = "";
    } elseif ($action === "edit") {
        $stmt = $connection->prepare("SELECT * FROM p_content_formular WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $label = $row['label'];
            $columns = $row['columns'];
            $use_placeholder = $row['use_placeholder'];
            $use_extra_label = $row['use_extra_label'];
        }
    }
}
?>

<div class="flex_container">
    <form name="editor" action="" method="post">
        <input type="hidden" name="action" value="page">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
        <select name="record_selection" onchange="selectRecord()">
            <option value="">auswählen...</option>
            <option value="neu">neu</option>
            <option value="-" disabled=disabled></option>
            <?php
                $stmt = $connection->prepare("SELECT id, label FROM p_content_formular");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($page = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($page['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($page['label'], ENT_QUOTES, 'UTF-8') . '</option>' . PHP_EOL;
                }
            ?>
        </select>
        <br><br>

        <div class="group">
            <input type="text" id="label" name="label" class="input_color" value="<?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="label">Label</label>
        </div>

        <div class="group">
            <input type="text" id="columns" name="columns" class="input_color" value="<?php echo htmlspecialchars($columns, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="columns">Columns</label>
        </div>

        <div class="group">
            <input type="number" id="use_placeholder" name="use_placeholder" class="input_color" value="<?php echo (int)$use_placeholder; ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="use_placeholder">Use Placeholder</label>
        </div>

        <div class="group">
            <input type="number" id="use_extra_label" name="use_extra_label" class="input_color" value="<?php echo (int)$use_extra_label; ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="use_extra_label">Use Extra Label</label>
        </div>

        <button type="submit" name="action" value="store">Speichern</button>
        <button type="submit" name="action" value="delete">Löschen</button>
    </form>
</div>

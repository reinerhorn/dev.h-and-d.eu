<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    die("Direct access not allowed.");
}
 
$connection = getDbConnection();

$id = "";
$label = "";
$columns = "";
$use_placeholder = 0;
$use_extra_label = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : "";

    if ($action === "store") {
        $action = empty($id) ? "add" : "update";
    }

    if ($action === "add" || $action === "update") {
        $label = $_POST['label'] ?? "";
        $columns = $_POST['columns'] ?? "";
        $use_placeholder = isset($_POST['use_placeholder']) ? (int) $_POST['use_placeholder'] : 0;
        $use_extra_label = isset($_POST['use_extra_label']) ? (int) $_POST['use_extra_label'] : 0;
    }

    if ($action === "add") {
        $prepared_stmt = $connection->prepare(
            "INSERT INTO p_content_formular (label, columns, use_placeholder, use_extra_label) VALUES (?, ?, ?, ?)"
        );
        $prepared_stmt->bind_param("ssii", $label, $columns, $use_placeholder, $use_extra_label);
        $prepared_stmt->execute();
        
        $id = $connection->insert_id;
    } elseif ($action === "update") {
        $prepared_stmt = $connection->prepare(
            "UPDATE p_content_formular SET label=?, columns=?, use_placeholder=?, use_extra_label=? WHERE id=?"
        );
        $prepared_stmt->bind_param("ssiii", $label, $columns, $use_placeholder, $use_extra_label, $id);
        $prepared_stmt->execute();
    } elseif ($action === "delete") {
        $prepared_stmt = $connection->prepare("DELETE FROM p_content_formular WHERE id=?");
        $prepared_stmt->bind_param("i", $id);
        $prepared_stmt->execute();
        
        $id = "";
        $action = "add";
    } elseif ($action === "edit") {
        $prepared_stmt = $connection->prepare("SELECT * FROM p_content_formular WHERE id=?");
        $prepared_stmt->bind_param("i", $id);
        $prepared_stmt->execute();
        $result = $prepared_stmt->get_result();
        
        if ($rec = $result->fetch_assoc()) {
            $label = $rec['label'];
            $columns = $rec['columns'];
            $use_placeholder = $rec['use_placeholder'];
            $use_extra_label = $rec['use_extra_label'];
        }
    }
}
?>

<div class="flex_container">
    <form name="editor" action="" method="post">
        <input type="hidden" name="action" value="page">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <select name="record_selection" onchange="selectRecord()">
           <option value="">auswählen...</option>
           <option value="neu">neu</option>
           <option value="-" disabled></option>
           <?php
               $stmt = $connection->prepare("SELECT id, label FROM p_content_formular");
               $stmt->execute();
               $result = $stmt->get_result();
               while ($page = $result->fetch_assoc()) {
                   echo '<option value="' . htmlspecialchars($page['id']) . '">' . htmlspecialchars($page['label']) . '</option>' . PHP_EOL;
               }
              /* $stmt->close();*/
           ?>
       </select>
       <br><br>
    
       <div class="group">
           <input type="text" id="label" name="label" class="input_color" value="<?php echo htmlspecialchars($label); ?>" required>
           <label for="label">Label</label>
       </div>
       <div class="group">
           <input type="text" id="columns" name="columns" class="input_color" value="<?php echo htmlspecialchars($columns); ?>" required>
           <label for="columns">Columns</label>
       </div>
       <div class="group">
           <input type="number" id="use_placeholder" name="use_placeholder" class="input_color" value="<?php echo (int) $use_placeholder; ?>" required>
           <label for="use_placeholder">Use Placeholder</label>
       </div>
       <div class="group">
           <input type="number" id="use_extra_label" name="use_extra_label" class="input_color" value="<?php echo (int) $use_extra_label; ?>" required>
           <label for="use_extra_label">Use Extra Label</label>
       </div>
       <button type="submit" name="action" value="store">Speichern</button>
       <button type="submit" name="action" value="delete">Löschen</button>
    </form>
</div>


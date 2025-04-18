<?php
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
}
$connection = getDbConnection();
    $id=""; 
    $label="";
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = $_POST['id'];
        if ($action == "store") {
            $action = $id == "" ? "add" : "update";
        }
        if ($action == "add" || $action == "update") {
            $label = $_POST['label'];
        }  
        if ($action == "add") {
            $prepared_stmt = $connection->prepare(
            "INSERT INTO p_cardstack (label) VALUES (?)"
            );
            $prepared_stmt->bind_param("s", $label);
            $prepared_stmt->execute();
            $result = $connection->query("SELECT id FROM p_cardstack ORDER BY id DESC LIMIT 1");
            if ($rec = $result->fetch_assoc()) {
                $id = $rec['id'];
            }
        } elseif ($action == "update") {
            $prepared_stmt = $connection->prepare(
                "UPDATE p_cardstack SET label=? WHERE id=?"
            );
            $prepared_stmt->bind_param("ss", $label, $id);
            $prepared_stmt->execute();
        } elseif ($action == "delete") {
            $prepared_stmt = $connection->prepare(
                "DELETE FROM p_cardstack WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $id = "";
            $action = "add";
        } elseif ($action == "edit") {
            $prepared_stmt = $connection->prepare(
                "SELECT * FROM p_cardstack WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $p_card_result = $prepared_stmt->get_result();
            if ($p_card_record = $card_result->fetch_assoc()) {
				$label = $p_card_record['label'];
            }
        }
    } 
?>
<div class="flex_container">
    <div class="group">
    <input type="text" id="label" name="label" class="input_color" value="<?php echo $label?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="label">Label</label>
    </div>
        <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
        <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
</form>
</div>
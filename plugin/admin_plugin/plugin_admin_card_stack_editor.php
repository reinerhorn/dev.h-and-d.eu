<?php
if (!isset($_SESSION)) {
    session_start();
}
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
            "INSERT INTO p_card_stack (label) VALUES (?)"
            );
            $prepared_stmt->bind_param("s", $label);
            $prepared_stmt->execute();
            $result = $connection->query("SELECT id FROM p_card_stack ORDER BY id DESC LIMIT 1");
            if ($rec = $result->fetch_assoc()) {
                $id = $rec['id'];
            }
        } elseif ($action == "update") {
            $prepared_stmt = $connection->prepare(
                "UPDATE p_card_stack SET label=? WHERE id=?"
            );
            $prepared_stmt->bind_param("ss", $label, $id);
            $prepared_stmt->execute();
        } elseif ($action == "delete") {
            $prepared_stmt = $connection->prepare(
                "DELETE FROM p_card_stack WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $id = "";
            $action = "add";
        } elseif ($action == "edit") {
            $prepared_stmt = $connection->prepare(
                "SELECT * FROM p_card_stack WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $p_card_result = $prepared_stmt->get_result();
            if ($p_card_record = $card_result->fetch_assoc()) {
				$label = $p_card_record['label'];
            }
        }
        /*$stmt->close();*/
    } 
?>

<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">

    <div class="flex_container">
        <div class="group">
            <input type="text" name="label" class="input_color" value="<?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>" required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label for="label">Label</label>
        </div>
        
        <button type="submit" name="action" value="store">Speichern</button>
        <button type="submit" name="action" value="delete">Löschen</button>
    </div>
</form>
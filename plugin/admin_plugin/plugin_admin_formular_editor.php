<?php
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
}

$connection = getDbConnection();
    $id=""; 
    $label="";
    $columns="";
    $use_placeholder="";
    $use_extra_label="";

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = $_POST['id'];
        if ($action == "store") {
            $action = $id == "" ? "add" : "update";
        }
        if ($action == "add" || $action == "update") {
            $label = $_POST['label'];
            $columns = $_POST['columns'];
            $use_placeholder = $_POST['use_placeholder'];
            $use_extra_label = $_POST['use_extra_label'];
       
        }  
        if ($action == "add") {
            $prepared_stmt = $connection->prepare(
            "INSERT INTO p_content_formular (label, columns ,use_placeholder, use_extra_label) VALUES (?, ?, ?, ?)"
            );
            $prepared_stmt->bind_param("ssii", $label, $columns,$use_placeholder, $use_extra_label );
            $prepared_stmt->execute();
            $result = $connection->query("SELECT id FROM p_content_formular ORDER BY id DESC LIMIT 1");
            if ($rec = $result->fetch_assoc()) {
                $id = $rec['id'];
            }
        } elseif ($action == "update") {
            $prepared_stmt = $connection->prepare(
                "UPDATE p_content_formular SET label=?, columns=? ,use_placeholder=?, use_extra_label=? WHERE id=?"
            );
            $prepared_stmt->bind_param("siiis",  $label, $columns, $use_placeholder, $use_extra_label, $id);
            $prepared_stmt->execute();
        } elseif ($action == "delete") {
            $prepared_stmt = $connection->prepare(
                "DELETE FROM p_content_formular WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $id = "";
            $action = "add";
        } elseif ($action == "edit") {
            $prepared_stmt = $connection->prepare(
                "SELECT * FROM p_content_formular WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
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
        <input type="hidden" name="id" value="<?php echo isset($_POST['id']) ? $_POST['id'] : 'neu' ?>">
        <select name="record_selection" onchange="selectRecord()">
           <option value="">auswählen...</option>
           <option value="neu">neu</option>
           <option value="-" disabled=disabled></option>
           <?php
               $stmt = $connection->prepare("SELECT * FROM p_content_formular");
               $stmt->execute();
               $result = $stmt->get_result();
               while($page = $result->fetch_assoc()) {
                   echo '<option value="' . $page['id'] . '">' . $page['label'] .' '.$page['extra_label'] .'</option>' . PHP_EOL; 
               }
               $connection->close();
           ?>
       </select>
<br><br>
 
    <div class="group">
    <input type="text" id="label" name="label" class="input_color" value="<?php echo $label?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="email">Label</label>
    </div>
    <div class="group">
    <input type="text" id="columns" name="columns" class="input_color" value="<?php echo $columns?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="columns">Columns</label>
    </div>
    <div class="group">
    <input type="text" id="use_placeholder" name="use_placeholder" class="input_color" value="<?php echo $use_placeholder?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="use_placeholder">Use Placeholder</label>
    </div>
    <div class="group">
        <input type="text" id="use_extra_label" name="use_extra_label" class="input_color" value="<?php echo $use_extra_label?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="use_extra_label">Use Extra Label</label>
    </div>
        <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
        <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
</form>
</div>
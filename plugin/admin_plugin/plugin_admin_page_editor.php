<?php
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
} 
#include_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";   
#$connection = getDbConnection();
$id=""; 
$parent_id="";
$idx=0;
$name="";
$type="";
$css="";
$fk_translation_placeholder ="";
$meta_keywords="";  
$meta_description="";
$enabled=0;
$print_all=0;;

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];
    if ($action == "store") {
        $action = $id == "" ? "add" : "update";
    }
    if ($action == "add" || $action == "update") {
        $parent_id = $_POST['parent_id'];
        $idx = $_POST['idx'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $css = $_POST['css'];
        $fk_translation_placeholder = $_POST['fk_translation_placeholder'];
        $meta_keywords = $_POST['meta_keywords'];
        $meta_description = $_POST['meta_description'];
        $print_all = $_POST['print_all'];
        $enabled = $_POST['enabled'];
    }  
    if ($action == "add") {
        $prepared_stmt = $connection->prepare(
        "INSERT INTO page (parent_id ,idx, name, type, css, fk_translation_placeholder, meta_keywords, meta_description, print_all, enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? )"
        );
        $prepared_stmt->bind_param("sissssssii",$parent_id ,$idx, $name, $type, $css, $fk_translation_placeholder, $meta_keywords, $meta_description, $print_all, $enabled);
        $prepared_stmt->execute();
        $result = $connection->query("SELECT id FROM page ORDER BY id DESC LIMIT 1");
        if ($rec = $result->fetch_assoc()) {
            $id = $rec['id'];
        }
    } elseif ($action == "update") {
        $prepared_stmt = $connection->prepare(
            "UPDATE page SET parent_id=?, idx=?, name=?, type=?, css=?, fk_translation_placeholder=?, meta_keywords=?, meta_description=?, print_all=?, enabled=? WHERE id=?"
        );
        $prepared_stmt->bind_param("sissssssiis", $parent_id ,$idx, $name, $type, $css, $fk_translation_placeholder, $meta_keywords, $meta_description, $print_all, $enabled, $id);
        $prepared_stmt->execute();
    } elseif ($action == "delete") {
        $prepared_stmt = $connection->prepare(
            "DELETE FROM page WHERE id=?"
        );
        $prepared_stmt->bind_param("s", $id);
        $prepared_stmt->execute();
        $id = "";
        $action = "add";
    } elseif ($action == "edit") {
        $prepared_stmt = $connection->prepare(
            "SELECT * FROM page WHERE id=?"
        );
        $prepared_stmt->bind_param("s", $id);
        $prepared_stmt->execute();
        $result = $prepared_stmt->get_result();
        if ($rec = $result->fetch_assoc()) {
            $parent_id = $rec['parent_id'];
            $idx = $rec['idx'];
            $type = $rec['type'];
            $name = $rec['name'];
            $css = $rec['css'];
            $fk_translation_placeholder = $rec['fk_translation_placeholder'];
            $meta_keywords = $rec['meta_keywords'];
            $meta_description = $rec['meta_description'];
            $print_all = $rec['print_all'];
            $enabled = $rec['enabled'];
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
           $connection = getDbConnection();
           $stmt = $connection->prepare("SELECT * FROM page ");
           $stmt->execute();
           $result = $stmt->get_result();
           while($page = $result->fetch_assoc()) {
               echo '<option value="' . $page['id'] . '">' . $page['name']  .'</option>' . PHP_EOL; 
           }
        ?>
        </select>
<br><br><br>

    <div class="group">
    <input type="text" id="name" name="name" class="input_color" value="<?php echo $name?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="name"> Name</label>
    </div>
    <div class="group">
    <input type="text" id="type" name="type" class="input_color" value="<?php echo $css?>">
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="css">CSS</label>
    </div>
    <div class="group">
    <input type="text" id="css" name="css" class="input_color" value="<?php echo $type?>">
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="type">Type</label>
    </div>
    <div class="group">
    <input type="text" id="parent_id" name="parent_id" class="input_color" value="<?php echo $parent_id?>">
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="parent_id">Parent ID</label>
    </div>
    <div class="group">
    <input type="text" id="fk_translation_placeholder" name="fk_translation_placeholder" class="input_color" value="<?php echo $fk_translation_placeholder?>" >
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="fk_translation_placeholder">FK Translation Placeholder</label>
    </div>
    <div class="group">
    <input type="text" id="meta_keywords" name="meta_keywords" class="input_color" value="<?php echo $meta_keywords?>" >
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="meta_keywords">Meta Keywords</label>
    </div>
    <div class="group">
    <input type="text" id="meta_description" name="meta_description" class="input_color" value="<?php echo $meta_description?>">
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="meta_description">Meta Description</label>
    </div>  
    <div class="group">
    <input type="text" id="idx" name="idx" class="input_color" value="<?php echo $idx?>">
        <span class="highlight" value=""></span>
        <span class="bar" value=""></span>
        <label type="text" for="idx">Index</label>
    </div>
    <div class="group">
    <input type="text" id="enabled" name="enabled" class="input_color" value="<?php echo $enabled?>">
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="enabled">Enabled</label>
    </div>  
    <div class="group">
    <input type="text" id="print_all" name="print_all" class="input_color" value="<?php echo $print_all?>">
        <span class="highlight" value=""></span>
        <span class="bar" value=""></span>
        <label type="text" for="print_all">Print All</label>
    </div>
    <div class="group">
    <input type="text" id="admin_page " name="admin_page " class="input_color" value="<?php echo $admin_page?>">
        <span class="highlight" value=""></span>
        <span class="bar" value=""></span>
        <label type="text" for="admin_page">Print All</label>
    </div>
 
<br><br>
    <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
    <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
</form>
</div>
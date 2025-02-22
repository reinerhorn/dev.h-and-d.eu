<?php
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
} 
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";   
$connection = getDbConnection();
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
$print_all="";

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
        "INSERT INTO page ( parent_id ,idx,  name, type, css, fk_translation_placeholder,   meta_keywords, meta_description, print_all, enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? )"
        );
        $prepared_stmt->bind_param("sissssssii",$parent_id ,$idx, $name, $type, $css, $fk_translation_placeholder, $meta_keywords, $meta_description, $print_all, $enabled);
        $prepared_stmt->execute();
        $result = $connection->query("SELECT id FROM page ORDER BY id DESC LIMIT 1");
        if ($rec = $result->fetch_assoc()) {
            $id = $rec['id'];
        }
    } elseif ($action == "update") {
        $prepared_stmt = $connection->prepare(
            "UPDATE page SET parent_id=?, idx=?, name=?, type=?, css=?, fk_translation_placeholder=?,  meta_keywords=?, meta_description=?, print_all=?, enabled=? WHERE id=?"
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
   <input   type="text"  name="name" value="<?php echo $name ?>">
        <span class="highlight" value=""></span>
        <span class="bar" value=""></span>
    <label for="name"> Name</label>
</div>
<div class="group">
    <input type="text" name="type" value="<?php echo $type ?>">
    <label for="type">Type</label><br>
</div>
<div class="group">
    <input type="text" name="css" value="<?php echo $css ?>">
    <label for="css"> CSS</label><br>
</div>
<div class="group">
    <input type="text" name="parent_id" value="<?php echo $parent_id?>">
    <label for="parent_id">Parent ID</label>
</div>
<div class="group">
    <input type="text" name="fk_translation_placeholder" value="<?php echo $fk_translation_placeholder ?>">
    <label for="fk_translation_placeholder">FK Translation Placeholder</label>
</div>
<div class="group">
    <input type="text" name="meta_keywords" value="<?php echo $meta_keywords ?>">
    <label for="meta_keywords"><b>Meta Keywords</b></label>
<div class="group">
    <input type="text" name="meta_description" value="<?php echo $meta_description ?>">
    <label for="meta_description"><b>Meta Description</b></label>
<div class="group">
    <input type="text" name="idx" value="<?php echo $idx?>">
    <label for="idx"><b>Index</b></label>
</div>
<div class="group">
    <input type="text" name="print_all" value="<?php echo $print_all?>">
     <label for="print_all"><b>Print All</b></label><
</div>
<div class="group">
    <input type="text" name="enabled" value="<?php echo $enabled?>">
    <label for="enabled"><b>Enabled</b></label>
</div>
<br><br>
    <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
    <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
</form>
</div>
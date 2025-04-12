<!--  ===================================================================
	  Urheberrechtshinweis / Copyright

	  Die Gestaltung, Inhalte und Programmierung dieser Seiten
	  unterliegen dem Urheberrecht. Urheber ist Reiner Horn
	  Eine Verwendung der Inhalte außerhalb der vom Urheber betriebenen
	  Domains ist nicht gestattet. Ein Verstoß gegen diese Bestimmungen
	  wird als Urheberrechtsverletzung betrachtet und bei Bekanntwerdung 
	  unter Einsatz von Rechtsmitteln geahndet.
      Verwndung von der leeren datenbank und code muss eine genehmigung
      des Urhebers eingeholt werden.
      Die Datenbank und der Code sind urheberrechtlich geschützt.
      Die Verwendung der Datenbank und des Codes ist nur mit
      ausdrücklicher Genehmigung des Urhebers gestattet.
      Die Datenbank und der Code dürfen nicht ohne Genehmigung
      des Urhebers kopiert, verbreitet oder veröffentlicht werden.

	 Reiner Horn
	 Huaptstr. 8
	 40597 Düsseldorf
     horm.it@t-online.de
===================================================================  -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
$connection = getDbConnection();
$id= "";
$headline ="";
$text="";
$image_path ="";
$link="";
$image_description  ="";
$idx=""; 
$label="";
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];
    if ($action == "store") {
        $action = $id == "" ? "add" : "update";
    }
    if ($action == "add" || $action == "update") {
        $headline = $_POST['headline'];
        $text = $_POST['text'];
        $link = $_POST['link'];
        $image_path  = $_POST['image_path'];
        $image_description = $_POST['image_description'];
        $idx = $_POST['idx'];
        $label = $_POST['label'];
    }
    if ($action == "add") {
        $prepared_stmt = $connection->prepare(
            "INSERT INTO p_content_plaintext (headline, text, image_path, link, image_description, idx, label) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $prepared_stmt->bind_param("sssssis", $headline, $text, $image_path, $link, $image_description, $idx, $label);
        $prepared_stmt->execute();
        $result = $connection->query("SELECT id FROM p_content_plaintext ORDER BY id DESC LIMIT 1");
        if ($rec = $result->fetch_assoc()) {
            $id = $rec['id'];
        }
    } elseif ($action == "update") {
        $prepared_stmt = $connection->prepare(
            "UPDATE p_content_plaintext SET headline=?, text=?, image_path=?, link=?, image_description=?, idx=?, label=? WHERE id=?"
        );
        $prepared_stmt->bind_param("sssssiss", $headline, $text, $image_path, $link, $image_description, $idx, $label, $id);
        $prepared_stmt->execute();
    } elseif ($action == "delete") {
        $prepared_stmt = $connection->prepare(
            "DELETE FROM p_content_plaintext WHERE id=?"
        );
        $prepared_stmt->bind_param("s", $id);
        $prepared_stmt->execute();
        $id = "";
        $action = "add";
    } elseif ($action == "edit") {
        $prepared_stmt = $connection->prepare(
            "SELECT * FROM p_content_plaintext WHERE id=?"
        );
        $prepared_stmt->bind_param("s", $id);
        $prepared_stmt->execute();
        $result = $prepared_stmt->get_result();
        if ($rec = $result->fetch_assoc()) {
            $headline= $rec['headline'];
            $text = $rec['text'];
            $image_path = $rec['image_path'];
            $link = $rec['link'];
            $image_description = $rec['image_description'];
            $idx = $rec['idx'];
            $label=$rec['label'];
        }
    }
}
?>
<div class="flex_container">
<form class="box" name="editor" action="" method="post">
    <input type="hidden" name="action" value="page">
    <input type="hidden" name="id" value="<?php echo isset($_POST['id']) ? $_POST['id'] : 'neu' ?>">
   <select name="record_selection" onchange="selectRecord()">
       <option value="">auswählen...</option>
       <option value="neu">neu</option>
       <option value="-" disabled=disabled></option>
       <?php
           $connection = getDbConnection();
           $stmt = $connection->prepare("SELECT * FROM p_content_plaintext");
           $stmt->execute();
           $result = $stmt->get_result();
           while($rec = $result->fetch_assoc()) {
               echo '<option value="' . $rec['id'] . '">' . $rec['label'] .'</option>' . PHP_EOL;
           }
           $connection->close();
       ?>
   </select>
   <br><br>
   <div class="group">
    <input type="text" id="headline" name="headline" class="input_color" value="<?php echo $headline?>"required>
        <span class="highlight" value=""></span>
        <span class="bar" value=""></span>
        <label class="text" type="text" for="headline">Title</label>    
    </div>
    <div class="group">
    <input type="text" name="image_path" class="input_color" value="<?php echo $image_path?>"required><br>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label for="image_path">Image Path</label>  
    </div> 
    <div class="group">
    <input type="text" name="link" class="input_color" value="<?php echo $link?>"required><br>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label for="link"><b>Link</b></label>
    </div> 
    <div class="group">
    <input type="text" name="image_description" class="input_color" value="<?php echo $image_description?>"required><br>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label for="image_description"><b>Image Description</b></label>
    </div> 
    <div class="group">
    <input type="text" name="idx" class="input_color" value="<?php echo $idx?>"required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label for="label"><b>Label</b></label>
    </div>
    <div class="group">
    <input type="text"  name="label" class="input_color" value="<?php echo $label?>"required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label for="label"><b>Label</b></label>
    </div> 
    <textarea name="text" id="text"><?php echo $text?></textarea>
<br><br>
        <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
        <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
    </form>
</div>
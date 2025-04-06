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
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
}

$connection = getDbConnection();
    $id=""; 
    $fk_card_id="Null";
    $idx="";
    $headline="";
    $text="";
	$link="";

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = $_POST['id'];
        if ($action == "store") {
            $action = $id == "" ? "add" : "update";
        }
        if ($action == "add" || $action == "update") {
            
            $idx = $_POST['idx'];
            $headline = $_POST['headline'];
            $text = $_POST['text'];
			$link = $_POST['link'];
        }  
        if ($action == "add") {
            $prepared_stmt = $connection->prepare(
            "INSERT INTO p_card_content (idx ,headline, text, link) VALUES (?, ?, ?, ?)"
            );
            $prepared_stmt->bind_param("isss",   $idx ,$headline, $text, $link);
            $prepared_stmt->execute();
            $result = $connection->query("SELECT id FROM p_card_content ORDER BY id DESC LIMIT 1");
            if ($rec = $result->fetch_assoc()) {
                $id = $rec['id'];
            }
        } elseif ($action == "update") {
            $prepared_stmt = $connection->prepare(
                "UPDATE p_card_content SET fk_card_id=?, idx=? ,headline=?, text=?, link=? WHERE id=?"
            );
            $prepared_stmt->bind_param("sissss", $fk_card_id, $idx ,$headline, $text, $link, $id);
            $prepared_stmt->execute();
        } elseif ($action == "delete") {
            $prepared_stmt = $connection->prepare(
                "DELETE FROM p_card_content WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $id = "";
            $action = "add";
        } elseif ($action == "edit") {
            $prepared_stmt = $connection->prepare(
                "SELECT * FROM p_card_content WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $card_result = $prepared_stmt->get_result();
            if ($card_rec = $card_result->fetch_assoc()) {
				$fk_card_id = $card_rec['fk_card_id'];
				$idx = $card_rec['idx'];
				$headline = $card_rec['headline'];
				$text = $card_rec['text'];
				$link = $card_rec['link'];
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
               $stmt = $connection->prepare("SELECT * FROM p_card_content WHERE 1");
               $stmt->execute();
               $result = $stmt->get_result();
               while($page = $result->fetch_assoc()) {
                   echo '<option value="' . $page['id'] . '">' . $page['fk_card_id'] .'</option>' . PHP_EOL; 
               }
               $connection->close();
           ?>
       </select>
<br><br>
 
    <div class="group">
    <input type="text" id="idx" name="idx" class="input_color" value="<?php echo $idx?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="idx">Index</label>
    </div>
    <div class="group">
    <input type="text" id="headline" name="headline" class="input_color" value="<?php echo $headline?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="headline">Headline</label>
    </div>
    <div class="group">
        <input type="text" id="text" name="text" class="input_color" value="<?php echo $text?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="text">Text</label>
    </div>
    <div class="group">
        <input type="text" id="link" name="link" class="input_color" value="<?php echo $link?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="link">Link</label>
    </div>
  
        <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
        <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
</form>
</div>
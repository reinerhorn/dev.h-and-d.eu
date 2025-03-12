<?php
 
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php") ;
      
    $id = "";
    $vorname = "";
    $nachname = "";
    $strasse = "";
    $ort ="";
    $plz ="";
    $telefon ="";
    $beruf ="";
    $gehalt ="";
    $date ="";
    
    if(isset($_POST['action'])){
        $action = $_POST['action'];
        $id = $_POST['id'];

        if($action == "store") {
            $action = $id == "" ? "add" : "update";
        }

        if($action == "add" || $action == "update") {
            $vorname = $_POST['vorname'];
            $nachname = $_POST['nachname'];
            $strasse = $_POST['strasse'];
            $ort = $_POST['ort'];
            $plz = $_POST['plz'];
            $telefon = $_POST['telefon'];       
            $beruf = $_POST['beruf'];
            $gehalt = $_POST['gehalt'];
            $date= $_POST['geb'];
        }
        $connection = getDbConnection();
        if($action == "add") {
            $prepared_stmt = $connection->prepare(
                "INSERT INTO plugin_bewerbung (vorname, nachname, strasse, ort, plz, telefon, beruf, gehalt , geb) VALUES (?, ?, ?, ? ,? ,?, ?, ?, ?)");
            $prepared_stmt->bind_param("sssssssss", $vorname, $nachname, $strasse, $ort, $plz, $telefon, $beruf, $gehalt , $date  );
            $prepared_stmt->execute();            
            $result = $connection->query("SELECT id FROM plugin_bewerbung ORDER BY id DESC LIMIT 1");
            if($page = $result->fetch_assoc()) {
                $id = $page['id'];
            }
        } elseif($action == "update") {
            $prepared_stmt = $connection->prepare(
                "UPDATE plugin_bewerbung SET vorname=?, nachname=?, strasse=?, ort=?, plz=?, beruf=?, gehalt=?, telefon=?, geb=? WHERE id=?");
            $prepared_stmt->bind_param("ssssssssss", $vorname, $nachname, $strasse, $ort, $plz, $beruf, $gehalt, $telefon, $date , $id);
            $prepared_stmt->execute();
        } elseif($action == "delete") {
            $prepared_stmt = $connection->prepare(
                "DELETE FROM plugin_bewerbung WHERE id=?" );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $id = "";
            $action = "add";
        }  elseif($action == "edit") {
            $prepared_stmt = $connection->prepare(
                "SELECT * FROM plugin_bewerbung WHERE id=?"
            );
            $prepared_stmt->bind_param("s", $id);
            $prepared_stmt->execute();
            $result = $prepared_stmt->get_result();
            if($page = $result->fetch_assoc()) {
                $vorname = $page['vorname'];
                $nachname = $page['nachname'];
                $strasse = $page['strasse'];
                $ort = $page['ort'];
                $plz = $page['plz'];
                $telefon = $page['telefon'];
                $beruf = $page['beruf'];
                $gehalt = $page['gehalt'];
                $date = $page['geb'];
            }
        }
         
    }  
          
?>
        <!--Formular anzeigen -->
        <h2>  Bewerbungsform</h2>
        <div class="flex_container">
        <form id="editor" method="post" action="bewerbungs-editor.php">
            <select name="record_selection" onchange="selectRecord();">
                <option value="">auswählen...</option>
                <option value="neu">neu</option>
                <option value="-" disabled=disabled></option>
            <?php
            $connection = getDbConnection();
            $stmt = $connection->prepare("SELECT * FROM plugin_bewerbung");
            $stmt->execute();
            $result = $stmt->get_result();
            while($page = $result->fetch_assoc()) {
               echo '<option value="' . $page['id'] . '">' . $page['vorname'] . $page['nachname']  .' (' . $page['ort'] . ')    </option>' . PHP_EOL; 
            }
            $connection->close();
       
 
      
            echo "<h1>Bewerbung</h1>";
  
            ?>       
            </select> <br>
            <input type="hidden" name="action" value="">
            <input type="hidden" name="id" value="<?php echo $id ?>">       
            <label class="label-text" for="vorname">Vorname:</label><br>
            <input type="text" name= "vorname" maxlength="100" value="<?php echo $vorname?>"><br>
            <label class="label-text" for="nachname">Name</label><br>
            <input type="text" name="nachname" maxlength="100" value="<?php echo $nachname?>"><br>
            <label class="label-text" for="strasse">Strasse</label><br> 
            <input type="text" name="strasse" maxlength="100" value="<?php echo $strasse?>"><br>
            <label class="label-text" for="plz">PLZ:</label><br>
            <input type="text" name="plz" maxlength="8" value="<?php echo $plz?>"><br>
            <label class="label-text" for="ort">Ort</label><br>
            <input type="text" name="ort" maxlength="100" value="<?php echo $ort ?>"><br>
            <label class="label-text" for="beruf">Beruf</label><br>
            <input type="text" name="beruf" maxlength="100" value="<?php echo $beruf ?>"><br>
            <label class="label-text" for="gehalt">Gehaltsvorstellung</label><br>
            <input type="text" name="gehalt" maxlength="20" value="<?php echo $gehalt ?>"><br>
            <label class="label-text" for="telefon">Telefon</label><br>
            <input type="text" name="telefon" maxlength="40" value="<?php echo $telefon ?>"><br>
            <label for="id_datum">Geburtsdatum:</label><br>
            <input type="date" id="id_date" name="geb" value="<?php echo $date ?>"><br>                     
            <button onclick="return isSubmitForm('store')">speichern</button>
          <button onclick="return isSubmitForm('delete', ['vorname', 'nachname'])">löschen</button> 
        </form></div>
        
<?php 
 
             
return;
?>
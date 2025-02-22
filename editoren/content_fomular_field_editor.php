
<script src="/function/js/editor.js"></script>  
 
<?php
 
$connection = getDbConnection();
$id =""; 
$fk_formular_id ="";
$type ="";
$column ="";
$label="";
$row =""; 
$label_enabled ="";
$folder ="";
$content_label ="";
if (isset($_POST['action'])) {
    if($_POST['action'] == 'load_config') {
        $stmt = $connection->prepare(
            'SELECT * FROM p_content_formular_field WHERE id=? LIMIT 1'
        );
        $stmt->bind_param('s', $_POST['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($config = $result->fetch_assoc()) {
            $_POST['id'] = $config['id'];
            $_POST['fk_formular_id'] = $config['fk_formular_id']; 
            $_POST['type'] = $config['type']; 
            $_POST['label'] = $config['label']; 
            $_POST['column'] = $config['column'];
            $_POST['row'] = $config['row'];
            $_POST['label_enabled'] = $config['label_enabled'];
            $_POST['folder'] = $config['folder'];
        } else {
            $_POST['id'] = '';
        }
        
    } elseif($_POST['action'] == 'store') {
        $is_update = isset($_POST['id']) && $_POST['id'] != '';
        $content_label = null;
        $stmt = $connection->prepare(
            'SELECT * FROM p_content_formular WHERE id=?'
        );
        $stmt->bind_param('s', $_POST['']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($plugin = $result->fetch_assoc()) {
            $table_name = $plugin['label'];
            # WARN : Stelle ist gefaehrlich fuer Injection
            $resultb = $connection->query(
                "SELECT label FROM " . $table_name . " WHERE id='" . $_POST['id'] . "'"
            );
            if($content = $resultb->fetch_assoc()) {
                $content_label = $content['label'];

            }
      }   
     

        if(isset($content_label) && !$is_update) {
            $stmt = $connection->prepare(
                'UPDATE p_content_formular_field SET fk_formular_id=? type=? label=? column=? row=? label_enabled=? folder=? WHERE id=?'
            );
            $stmt->bind_param('sssssiiss', $_POST['fk_formular_id'], $_POST['type'],   $_POST['column'], $_POST['row'], $_POST['label_enabled'], $content_label, $_POST['folder'], $_POST['id']
        );
        } elseif(isset($content_label) && !$is_update) {
            $stmt = $connection->prepare(
                'INSERT INTO fk_formular_id, type, label, column, row, label_enabled, folder ) VALUES(?,?,?,?,?,?)'
            );
            $stmt->bind_param('sssssiiss',$_POST['fk_formular_id'], $_POST['type'],  $_POST['column'], $_POST['row'], $_POST['label_enabled'], $content_label, $_POST['folder'], $_POST['id']);
        }
        $stmt->execute();
    } elseif($_POST['action'] == 'delete' && isset($_POST['id']) && $_POST['id'] != '') {
        $stmt = $connection->prepare(
            'DELETE FROM p_content_formular_field WHERE id=?'
        );
        $stmt->bind_param('s', $_POST['p_content_formular_field']);
        $stmt->execute();
        unset($_POST);
    }
}  
?>
<body>
    <style>
    div.box {
    box-sizing: border-box; 
    border: 3px solid gray; 
    padding: 15px; 
    width: 350px;
}
div.box1 {
   margin-top:10px;
    box-sizing: border-box; 
    border: 1px solid gray; 
    padding: 15px; 
    width: 300px;
}
</style>

<div class="box">  
   <h1>Page Formular feld</h1> 
<form class="box" name="editor" action="" method="post">
    <input type="hidden" name="action" value="">

    <label for="id">Formular feld</label>
    <select onchange="if(this.options[this.selectedIndex].value=='reset') {resetForm(this.form)} else {this.form.elements['action'].value='load_config'; this.form.submit()}" name="id" onchange="">
        <option value="">Formular auswählen...</option>
        <option value="reset">neu</option>
        <option value="-" disabled=disabled></option>
    <?php

$stmt = $connection->prepare('SELECT p_content_formular.id AS p_content_formular_id, p_content_formular_field.fk_formular_id AS p_content_formular_field_fk_formular_id FROM p_content_formular_field JOIN p_content_formular on p_content_formular_field.fk_formular_id=p_content_formular.id ORDER BY p_content_formular_field.id;');
$stmt->execute();
$result = $stmt->get_result();
while($rec = $result->fetch_assoc()) {
    $selected = '';
    if(isset($_POST['id']) && $_POST['id'] == $rec['id']) {
        $selected = ' selected="selected"';
    }
    echo '<option' . $selected . ' value="' . $rec['id'] . '">' . $rec['p_content_formular_field_fk_formular_id'] . '</option>' . PHP_EOL; 
}
?>
</select><br><br>

<label for="id">Formular</label>

<select  
        <option value="">Formular auswählen...</option>
        <option value="reset">neu</option>
        <option value="-" disabled=disabled></option>
    <?php
 
$stmt = $connection->prepare ("SELECT * FROM p_content_formular WHERE 1");
$stmt->execute();
$result = $stmt->get_result();
while($rec = $result->fetch_assoc()) {
    $selected = '';
    if(isset($_POST['id']) && $_POST['id'] == $rec['id']) {
        $selected = ' selected="selected"';
    }
    echo '<option' . $selected . ' value="' . $rec['id'] . '">' . $rec['id'] . '</option>' . PHP_EOL; 
}
?>
</select>
<br><br>
    <label for="Type">Type</label>
    <input  type="text" placeholder="Type" name="type" value="<?php echo isset($_POST['type']) ? $_POST['type'] : '' ?>">
    <br><br>
    
    <label for="column">Column</label>
    <input  type="text" placeholder="Column" name="column" value="<?php echo isset($_POST['column']) ? $_POST['column'] : '' ?>">
    <br><br>
    <label for="row">Row</label>
    <input  type="text" placeholder="Row" name="row" value="<?php echo isset($_POST['row']) ? $_POST['row'] : '' ?>">
   <br><br>
    <label for="label_enabled">Label Enabled</label>
    <input  type="text" placeholder="Label Enabled" name="label_enabled" value="<?php echo isset($_POST['label_enabled']) ? $_POST['label_enabled'] : '' ?>">
    <br><br>
    <label for="folder">Folder</label>
    <input  type="text" placeholder="Folder" name="folder" value="<?php echo isset($_POST['folder']) ? $_POST['folder'] : '' ?>">

    <div class="box1">
        <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
        <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
    </div>
</form>
</div> 
</body>
</html>
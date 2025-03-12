<script src="/function/js/editor.js"></script>  
<?php
 
$connection = getDbConnection();
$id=""; 
$fk_formular_id="";
$type="";
$label="";
$use_placeholder=""; 
$use_extra_label="";
$content_label="";
if (isset($_POST['action'])) {
    if($_POST['action'] == 'load_config') {
        $stmt = $connection->prepare(
            'SELECT * FROM  p_content_formular WHERE id=? LIMIT 1'
        );
        $stmt->bind_param('s', $_POST['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($config = $result->fetch_assoc()) {
            $_POST['id'] = $config['id'];
            $_POST['label'] = $config['label']; 
            $_POST['columns'] = $config['columns'];
            $_POST['use_placeholder'] = $config['use_placeholder'];
            $_POST['use_extra_label'] = $config['use_extra_label'];
        }
    } elseif($_POST['action'] == 'store') {
        $is_update = isset($_POST['id']) && $_POST['id'] != '';
        $content_label = null;
        $stmt = $connection->prepare(
            'SELECT *,label FROM p_content_formular WHERE id=?'
        );
        if(isset($content_label) && $is_update) {
            $stmt = $connection->prepare(
                'UPDATE p_content_formular SET   column=?, use_placeholder=?, use_extra_label=?, WHERE id=?'
            );
            $stmt->bind_param('ssiis', $_POST['label'],  $_POST['columns'], $_POST['use_placeholder'], $_POST['use_extra_label'], $content_label, $_POST['id']);
        } elseif(isset($content_label) && !$is_update) {
            $stmt = $connection->prepare(
                'INSERT INTO p_content_formular (label, columns, use_placeholder , use_extra_label) VALUES(?,?,?,?)'
            );
            $stmt->bind_param('ssiis', $_POST['label'], $_POST['columns'], $_POST['use_placeholder'], $_POST['use_extra_label'], $_POST['id']);
        }
        $stmt->execute();
    } elseif($_POST['action'] == 'delete' && isset($_POST['id']) && $_POST['id'] != '') {
        $stmt = $connection->prepare(
            'DELETE FROM p_content_formular WHERE id=?'
        );
        $stmt->bind_param('s', $_POST['p_content_formular']);
        $stmt->execute();
        unset($_POST);
    }
}
?>
 
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
   <h1>Page Formular</h1> 
<form class="box" name="editor" action="" method="post">
    <input type="hidden" name="action" value="">
    <label for="id">Formular</label>
    <select onchange="if(this.options[this.selectedIndex].value=='reset') {resetForm(this.form)} else {this.form.elements['action'].value='load_config'; this.form.submit()}" name="id" onchange="">
        <option value="">Formular auswählen...</option>
        <option value="reset">neu</option>
        <option value="-" disabled=disabled></option>
    <?php

$stmt = $connection->prepare("SELECT * FROM p_content_formular ORDER BY label ASC");
$stmt->execute();
$result = $stmt->get_result();
while($rec = $result->fetch_assoc()) {
    $selected = '';
    if(isset($_POST['id']) && $_POST['id'] == $rec['id']) {
        $selected = ' selected="selected"';
    }
    echo '<option' . $selected . ' value="' . $rec['id'] . '">' . $rec['label'] . '</option>' . PHP_EOL; 
}
    ?>
    </select>
    <br><br>

<br><br>
<label for="label">Label</label>
    <input data-default="" type="text" placeholder="Label" name="label" value="<?php echo isset($_POST['label']) ? $_POST['label'] : '' ?>">
    <br><br>
    <label for="columns">Columns</label>
    <input  type="text" placeholder="Columns" name="columns" value="<?php echo isset($_POST['columns']) ? $_POST['columns'] : '' ?>">
    <br><br>
    <label for="use_placeholder">Use Placeholder </label>
    <input  type="text" placeholder="Use Placeholder " name="use_placeholder" value="<?php echo isset($_POST['use_placeholder']) ? $_POST['use_placeholder'] : '' ?>">
   <br><br>
    <label for="use_extra_label">Use Extra Label  </label>
    <input  type="text" placeholder="Use extra label" name="use_extra_label" value="<?php echo isset($_POST['use_extra_label']) ? $_POST['use_extra_label'] : '' ?>">
    <div class="box1">
        <button onclick="this.form.elements['action'].value='store'" type="submit">Speichern</button>
        <button onclick="this.form.elements['action'].value='delete'">Löschen</button>
    </div>
</form>
</div> 
 
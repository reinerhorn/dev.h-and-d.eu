<?php
session_start();
if (!isset($_SESSION['admin_a'])) {
    header('Location:/index.php');
    exit;
}

// Standardwerte für Variablen setzen
$data = [
    'id' => '', 'vorname' => '', 'nachname' => '', 'strasse' => '', 'ort' => '',
    'plz' => '', 'telefon' => '', 'beruf' => '', 'gehalt' => '', 'geburstag' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $data = array_merge($data, $_POST);
    
    $connection = getDbConnection();
    
    switch ($action) {
        case 'store':
            $action = empty($data['id']) ? 'add' : 'update';
        case 'add':
            $stmt = $connection->prepare("INSERT INTO plugin_bewerbung (vorname, nachname, strasse, ort, plz, telefon, beruf, gehalt, geburstag) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $data['vorname'], $data['nachname'], $data['strasse'], $data['ort'], $data['plz'], $data['telefon'], $data['beruf'], $data['gehalt'], $data['geburstag']);
            $stmt->execute();
            $data['id'] = $connection->insert_id;
            break;
        case 'update':
            $stmt = $connection->prepare("UPDATE plugin_bewerbung SET vorname=?, nachname=?, strasse=?, ort=?, plz=?, beruf=?, gehalt=?, telefon=?, geburstag=? WHERE id=?");
            $stmt->bind_param("sssssssssi", $data['vorname'], $data['nachname'], $data['strasse'], $data['ort'], $data['plz'], $data['beruf'], $data['gehalt'], $data['telefon'], $data['geburstag'], $data['id']);
            $stmt->execute();
            break;
        case 'delete':
            $stmt = $connection->prepare("DELETE FROM plugin_bewerbung WHERE id=?");
            $stmt->bind_param("i", $data['id']);
            $stmt->execute();
            $data = array_fill_keys(array_keys($data), '');
            break;
        case 'edit':
            $stmt = $connection->prepare("SELECT * FROM plugin_bewerbung WHERE id=?");
            $stmt->bind_param("i", $data['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $data = $row;
            }
            break;
    }
    $stmt->close();
    
}
?>

<div class="flex_container">
    <form id="editor" method="post" action="">
        <input type="hidden" name="action" id="formAction" value="">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
        
        <?php
        $fields = ["vorname" => "Vorname", "nachname" => "Name", "strasse" => "Strasse", "plz" => "PLZ", "ort" => "Ort", "beruf" => "Beruf", "gehalt" => "Gehaltsvorstellung", "telefon" => "Telefon"];
        foreach ($fields as $key => $label) {
            echo "<label class='label-text' for='$key'>$label:</label><br>";
            echo "<input type='text' name='$key' maxlength='100' value='" . htmlspecialchars($data[$key]) . "'><br>";
        }
        ?>
        
        <label for="geburstag">Geburtsdatum:</label><br>
        <input type="date" name="geburstag" value="<?php echo htmlspecialchars($data['geburstag']); ?>"><br>
        
        <button type="submit" onclick="document.getElementById('formAction').value='store'">Speichern</button>
        <button type="button" onclick="isSubmitForm('store')">Speichern</button>
    </form>
</div>

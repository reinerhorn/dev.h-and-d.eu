<script src="/function/js/chart.js"></script>
<script src="/function/js/charts-loader.js"></script>
<?php
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
}
//  Zeile 1 spalte 1
if(isset( $_SESSION["admin_a"]));
$istUserAngemeldet = isset( $_SESSION["admin_a"]);
if($istUserAngemeldet){
  $user= $_SESSION["name"] ;
} else {
  $user = "";
}
$datum="";
    $datum .= date('d.m.Y l H:i:s');
    $datum .= '<br> Einen schönen, guten Tag: ' . $user . '<br>';
    
 
   
    $conn = getDbConnection(); // Stelle die Datenbankverbindung her
    
    // 🔹 Bild hochladen
    function uploadImage($file) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        }
        return "";
    }
    
    // 🔹 Header hinzufügen
    if (isset($_POST['add'])) {
        $text = $_POST['text'] ?? '';
        $link = $_POST['link'] ?? '';
        $language = $_POST['language'] ?? '';
        $label = $_POST['label'] ?? '';
        $css = $_POST['css'] ?? '';
        $role = isset($_POST['role']) ? (int)$_POST['role'] : null;
        $image = isset($_FILES['image']) ? uploadImage($_FILES['image']) : '';
        $stmt = $conn->prepare("INSERT INTO header (text, link, images, language, label, css, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $text, $link, $image, $language, $label, $css, $role);
        $stmt->execute();
    }
    
    // 🔹 Header bearbeiten
    if (isset($_POST['edit'])) {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $text = $_POST['text'] ?? '';
        $link = $_POST['link'] ?? '';
        $language = $_POST['language'] ?? '';
        $label = $_POST['label'] ?? '';
        $css = $_POST['css'] ?? '';
        $role = isset($_POST['role']) ? (int)$_POST['role'] : null;
        $image = (!empty($_FILES['image']['name'])) ? uploadImage($_FILES['image']) : ($_POST['current_image'] ?? '');  
        $stmt = $conn->prepare("UPDATE header SET text=?, link=?, images=?, language=?, label=?, css=?, role=? WHERE id=?");
        $stmt->bind_param("ssssssis", $text, $link, $image, $language, $label, $css, $role, $id);
        $stmt->execute();
    }
    
    // 🔹 Header löschen
    if (isset($_GET['delete'])) {
        $id = isset($_GET['delete']) ? (int)$_GET['delete'] : 0;
        $stmt = $conn->prepare("DELETE FROM header WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    
    // 🔹 Alle Header abrufen
    $result = $conn->query("SELECT * FROM header");
    
    ?>

<div class="container">
  <div class="column "><?php echo $datum ?>
</div>
</div>
 
<body>
    <h2>Header hinzufügen</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="text" placeholder="Text" required>
        <input type="text" name="link" placeholder="Link" required>
        <input type="text" name="language" placeholder="Sprache">
        <input type="text" name="label" placeholder="Label">
        <input type="text" name="css" placeholder="CSS">
        <input type="number" name="role" placeholder="Rolle">
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add">Hinzufügen</button>
    </form>
    
    <h2>Header Liste</h2>
    <table border="1">
        <tr>
            <th>ID</th><th>Text</th><th>Link</th><th>Bild</th><th>Aktionen</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['text'] ?></td>
            <td><?= $row['link'] ?></td>
            <td><img src="<?= $row['images'] ?>" width="50"></td>
            <td>
                <a href="?delete=<?= $row['id'] ?>">Löschen</a>
                <form method="post" enctype="multipart/form-data" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="text" value="<?= $row['text'] ?>">
                    <input type="text" name="link" value="<?= $row['link'] ?>">
                    <input type="file" name="image" accept="image/*">
                    <input type="hidden" name="current_image" value="<?= $row['images'] ?>">
                    <button type="submit" name="edit">Bearbeiten</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
 
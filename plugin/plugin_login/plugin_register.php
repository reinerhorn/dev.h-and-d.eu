<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = "";
$email = "";
$username = "";
$password = ""; // Kein vorausgefülltes Passwort zur Sicherheit!

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($action === "store") {
        $action = empty($id) ? "add" : "update";
    }

    if ($action === "add" && $email && $username && $password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connection->prepare("INSERT INTO plugin_login_users (email, password, username) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password_hash, $username);
        $stmt->execute();
        $id = $connection->insert_id;
        $action = 'edit';
    } elseif ($action === "update" && $id && $email && $username) {
        $stmt = $connection->prepare("UPDATE plugin_login_users SET email=?, username=? WHERE id=?");
        $stmt->bind_param("ssi", $email, $username, $id);
        $stmt->execute();
    } elseif ($action === "delete" && $id) {
        $stmt = $connection->prepare("DELETE FROM plugin_login_users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $id = "";
        $action = "store";
    } elseif ($action === "edit" && $id) {
        $stmt = $connection->prepare("SELECT email, username FROM plugin_login_users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($page = $result->fetch_assoc()) {
            $email = $page['email'];
            $username = $page['username'];
        }
    }
}
?>
<div class="flex_container ">
<div class="form-container">
    <div class="button-group">
        <button class="button" onclick="location.href='?page=1692886141';">Datenschutzerklärung</button>
        <button class="button" onclick="location.href='?page=1692882619';">AGB</button> 
    </div>

    <div class="form-box">
        <form name="editor" method="post" action="">
            <input type="hidden" name="action" value="store">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
                <input type="text" id="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
                <label for="email">E-Mail-Adresse</label>
            </div>

            <div class="form-group">
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required>
                <label for="username">Username</label>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" required>
                <label for="password">Passwort</label>
            </div>

            <div class="form-check">
                <input type="checkbox" id="agree" name="agree" value="ich stimme zu" required>
                <label for="agree">Ich stimme der Datenschutzerklärung / AGB zu</label>
            </div>

            <button class="button" type="submit">Senden</button>
        </form>
    </div>
</div>
</div>
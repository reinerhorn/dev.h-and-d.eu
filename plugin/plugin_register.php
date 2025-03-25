<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
$connection = getDbConnection(); 
$id = "";
$email = "";
$password = "";
$username = "";
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];
    if ($action == "store") {
        $action = $id == "" ? "add" : "update";
    }
    if ($action == "add" || $action == "update") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = $_POST['username'];
    }
    if ($action == "add") {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $prepared_stmt = $connection->prepare( 
            "INSERT INTO plugin_login_users (email, password, username) VALUES (?, ?, ?)"
        );
        $prepared_stmt->bind_param("sss", $email, $password, $username);
        $prepared_stmt->execute();
        $result = $connection->query("SELECT UNIX_TIMESTAMP(id) as ts FROM plugin_login_users ORDER BY id DESC LIMIT 1");
        if ($page = $result->fetch_assoc()) {
            $id = $page['ts'];
        }
        $action = 'edit';
    } elseif ($action == "update") {
        $prepared_stmt = $connection->prepare(
            "UPDATE plugin_login_users SET email=?, username=? WHERE UNIX_TIMESTAMP(id)=?"
        );
        $prepared_stmt->bind_param("sss", $username, $email, $id);
        $prepared_stmt->execute();
    } elseif ($action == "delete") {
        $prepared_stmt = $connection->prepare(
            "DELETE FROM plugin_login_users WHERE UNIX_TIMESTAMP(id)=?"
        );
        $prepared_stmt->bind_param("s", $id);
        $prepared_stmt->execute();
        $id = "";
        $action = "store";
    } elseif ($action == "edit") {
        $prepared_stmt = $connection->prepare(
            "SELECT * FROM plugin_login_users WHERE UNIX_TIMESTAMP(id)=?"
        );
        $prepared_stmt->bind_param("s", $id);
        $prepared_stmt->execute();
        $plugin_result = $prepared_stmt->get_result();
        if ($page = $plugin_result->fetch_assoc()) {
            $email = $page['email'];
            $password = $page['password'];
            $username = $page['username'];
        }
    }
}
?> 
<div class="flex_container">
<div id="login-button login-button-text">
<button class="button" onclick="location.href='?page=1692886141';">Datenschutzerklärung</button>
<button class="button onclick="location.href='?page=1692882619';">AGB</button>
</div>
<div class="box">
<form name="editor" method="post" action="">   
    <input type="hidden" name="action" value="">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <div class="group">
    <input type="text" id="email" name="email" maxlength="100"   value="<?php echo $email?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="email">E-Mail-Adresse</label>
    </div>
    <div class="group">
    <input type="text" id="username" name="username" value="<?php echo $username?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="username">Username</label>
    </div>
    <div class="group">
    <input type="password" id="password" name="password" value="<?php echo $password?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="password">Password</label>
    </div>
    <div class="group">
    <label type="text" for="checkbox">Ich stimme der Datenschutzerklärung / AGB zu</label> 
    </div> 
    <input class="custom-checkbox"  type="checkbox" name="agree" value="ich stimme zu" required><br>  
    <button class="button" onclick="return isSubmitForm('store')">Senden</button>
    </form>
    </div>
</div> 
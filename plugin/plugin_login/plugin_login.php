<?php 
// Direkten Zugriff verhindern
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    exit('Zugriff verweigert!');
}

// E-Mail-Eingabe absichern
$email = isset($_REQUEST['email']) ? htmlspecialchars($_REQUEST['email'], ENT_QUOTES, 'UTF-8') : "";

?>

<div class="flex_container">
    <div class="login-button login-button-text">
        <button class="button" onclick="location.href='?page=1692886141';">Datenschutzerklärung</button>
        <button class="button" onclick="location.href='?page=1692882619';">AGB</button>
    </div>

    <div class="box">
        <form name="editor" method="post" action="">
            <div class="group">
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label for="email">E-Mail-Adresse</label>
            </div>
            <div class="group">
                <input type="password" id="password" name="password" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label for="password">Passwort</label>
            </div>
            <div class="group">
                <label for="checkbox">Ich stimme der Datenschutzerklärung / AGB zu</label>  
            </div>
            <input class="custom-checkbox" type="checkbox" name="agree" value="ich stimme zu" required>
            <br>
            <button class="button">Senden</button>
        </form>
    </div> 
</div>
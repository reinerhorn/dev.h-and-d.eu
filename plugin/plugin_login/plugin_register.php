<?php
 

 
?>
<div class="flex_container">
    <div id="login-button login-button-text">
        <button class="button" onclick="location.href='?page=1692886141';">Datenschutzerklärung</button>
        <button class="button" onclick="location.href='?page=1692882619';">AGB</button>
    </div>
    <div class="box">
        <form name="editor" method="post" action="">
            <input type="hidden" name="action" value="store">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userData['id']); ?>">
            <div class="group">
                <input type="text" id="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label for="email">E-Mail-Adresse</label>
            </div>
            <div class="group">
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label for="username">Username</label>
            </div>
            <div class="group">
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($userData['password']); ?>" required>
                <span class="highlight"></span>
                <span class="bar"></span>
                <label for="password">Password</label>
            </div>
            <div class="group">
                <label for="checkbox">Ich stimme der Datenschutzerklärung / AGB zu</label>
            </div>

            <?php if (!empty($userData['message'])): ?>
            <div class="alert"> <?php echo $userData['message']; ?> </div>
            <?php endif; ?>
            
            <input class="custom-checkbox" type="checkbox" name="agree" value="ich stimme zu" required><br>
            <button class="button" type="submit">Senden</button>
            <?php if ($loggedInAdmin == 1): ?>
                <button class="button" type="submit" name="action" value="edit">Bearbeiten</button>
                <button class="button" type="submit" name="action" value="delete" onclick="return confirm('Wirklich löschen?');">Löschen</button>
            <?php endif; ?>
        </form>
    </div>
</div>
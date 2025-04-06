<?php
?>
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
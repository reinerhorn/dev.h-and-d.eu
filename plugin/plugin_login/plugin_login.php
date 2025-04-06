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

<?php 
    $id = "";
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : "";;
?>
<div class="flex_container">
    <div id="login-button login-button-text">
    <button class="button" onclick="location.href='?page=1692886141';">Datenschutzerklärung</button>
    <button class="button" onclick="location.href='?page=1692882619';">AGB</button>
</div>
    <div class="box">
    <form name="editor" method="post" action="">
    <div class="group">
    <input type="text" id="email" name="email" value="<?php echo $email?>" required>
        <span class="highlight" value="true"></span>
        <span class="bar" value="true"></span>
        <label type="text" for="email">E-Mail-Adresse</label>
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
        <input class="custom-checkbox"  type="checkbox" name="agree" value="ich stimme zu" required>
    <br>
    <button class="button" >Senden</button>
    </form>
    </div> 
<div>
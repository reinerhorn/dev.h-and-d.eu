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
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
 $message="";
    if (isset($_SESSION['email'])) {
    } else {
    echo "Bitte erst einloggen";
 }
    if(isset( $_SESSION["name"]));
    $istBenutzerAngemeldet = isset( $_SESSION["name"]   );
    if($istBenutzerAngemeldet){
      $user= $_SESSION["name"] ; 
    } else {
      $user = "";
    }
    if (isset($_SESSION['user_id'])) {
      session_destroy();
      header('Location: index,php'); // Weiterleitung zur Login-Seite nach dem Logout
      exit;
}
$datum="";
$datum .= date('d.m.Y l H:i:s').'<br>';
$datum .= ' Einen schönen, guten Tag: ' . $user .' </a>';
echo $datum;
 ?>
 
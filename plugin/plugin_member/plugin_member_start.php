<?php
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
 
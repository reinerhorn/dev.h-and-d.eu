 <?php
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
  http_response_code(403);
  exit('Zugriff verweigert!');
}
if (!isset($main_db_connection)) {
  die('<p>Fehler: Datenbankverbindung nicht gesetzt.</p>');
}
  $page="";
  $stmt = $main_db_connection->prepare('SELECT * FROM header LIMIT 1');
  $stmt->execute();
  $header_result = $stmt->get_result();
  if($rec = $header_result->fetch_assoc()) {
    $text = $rec['text'];
    $link = $rec['link'];
    $images = $rec['images'];
    $label = $rec['label'];
    $css = $rec['css'];
  }
  echo '<div class="' . htmlspecialchars($css, ENT_QUOTES, 'UTF-8') . '"><a title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '"><img class="logo" src="' . htmlspecialchars($images, ENT_QUOTES, 'UTF-8') . '" alt="logo"></a><a class="companyname" title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</a></div>';
  $main_db_connection->close();
?>

 
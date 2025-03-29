<?php
try {
  if(isset($print_all) && $print_all) {
    $stmt = $main_db_connection->prepare("SELECT * FROM page_config JOIN p_content_plaintext ON page_config.plugin_content_id=p_content_plaintext.id WHERE UNIX_TIMESTAMP(page_config.fk_page_id)=? AND fk_language_id=? ORDER BY p_content_plaintext.idx ASC");
    $stmt->bind_param('is', $_REQUEST['page'], $_SESSION['language']);
  } else {
    $stmt = $main_db_connection->prepare(
      'SELECT * FROM p_content_plaintext WHERE id=? AND fk_language_id=? LIMIT 1'
    );
    $stmt->bind_param('ss', $plugin_content_id, $_SESSION['language']);
  }
  $stmt->execute();
  $plugin_result = $stmt->get_result();
  while($record = $plugin_result->fetch_assoc()) {
    $headline = $record['headline'];
    echo '<h1>' . $headline . '</h1>';
    $text = $record['text'];
    echo '<div class="holder"> ' . $text . '</div>';
  }
} catch(Error $err) {
  echo PHP_EOL . '<br><b>ERROR: ' . $err . '</b>';
}
?>
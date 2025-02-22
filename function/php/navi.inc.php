<?php 

  $stmt = $connection->prepare(
    'SELECT admin_page FROM page WHERE UNIX_TIMESTAMP(id)=?'
  );
  $stmt->bind_param('i', $_REQUEST['page']);
  $stmt->execute();
  $result = $stmt->get_result();
  $admin_page = 0;
  if($rec = $result->fetch_assoc()) {
    $admin_page = $rec['admin_page'];
  }

  $stmt = $connection->prepare('SELECT label, UNIX_TIMESTAMP(page.id) AS tsid, translation.label AS name FROM page JOIN translation ON page.fk_translation_placeholder=translation.fk_translation_placeholder WHERE parent_id is NULL AND type="main" AND translation.fk_language_id=? AND admin_page=? ORDER BY page.idx ASC');
  $stmt->bind_param('si', $_SESSION['language'], $admin_page);
  $stmt->execute();
  $result = $stmt->get_result();
  
  while($record = $result->fetch_assoc()) {
    $tsid =  $record['tsid'];
    $title = $record['name'];
      
    $css_class = '';
    if($page == $tsid) {
      $css_class = ' class="marked"';
    }
    $stmt_sub = $connection->prepare('SELECT UNIX_TIMESTAMP(page.id) as tsid, translation.label AS name FROM page JOIN translation ON page.fk_translation_placeholder=translation.fk_translation_placeholder WHERE UNIX_TIMESTAMP(page.parent_id)=? AND fk_language_id=? AND admin_page=? ORDER BY translation.label ASC');
    $stmt_sub->bind_param('isi', $tsid, $_SESSION['language'], $admin_page);
    $stmt_sub->execute();
    $sub_result = $stmt_sub->get_result();
   echo '<div><a'.' ' . 'title=' . $title . $css_class .' '. 'href="?page=' . $tsid .'">'. $title .'</a>' . PHP_EOL;
    echo '<div class="navigationDropDown">' . PHP_EOL;
    while($drop_record = $sub_result->fetch_assoc()) {
      $drop_title = $drop_record['name'];
      $drop_tsid = $drop_record['tsid'];
      if($page == $drop_tsid) {
        $drop_css_class = ' class="marked"';
      } else {
        $drop_css_class = '';
      }
      echo '<a' . $drop_css_class .' '.'title=' . $drop_title .' '. 'href="?page=' . $drop_tsid . '">'. $drop_title .'</a>';
    } 
   echo '</div></div>';
  }
?>
<?php
    $db_connector = getDbConnection();
    $stmt = $db_connector->prepare('SELECT * FROM kommentar');
    $stmt->execute();
    $plugin_result = $stmt->get_result();
    $commentar = '';
    while($rec = $plugin_result->fetch_assoc()) {
        $commentar .=   
            $rec['username'] . '<br><br>' . 'Kommentar: <br><br>' .
            $rec['text'] . "<br>" . "..................................." . "<br>" .
            $rec['id'] ."<br>" . "-----------------------------------" . "<br>";
    }
    echo '<div class="comment_container">' . $commentar . '</div>';
?>
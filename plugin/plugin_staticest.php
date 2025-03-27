<script src="/function/js/chart.js"></script>
<script src="/function/js/charts-loader.js"></script>
<?php
if (!isset($_SESSION['admin_a'])) {
	header('Location:/index.php');
}
//  Zeile 1 spalte 1
if(isset( $_SESSION["admin_a"]));
$istUserAngemeldet = isset( $_SESSION["admin_a"]);
if($istUserAngemeldet){
  $user= $_SESSION["name"] ;
} else {
  $user = "";
}
 
    
// besucher ip   Zeile 3 spalte 3
    $db_connector = getDbConnection();
    $stmt = $db_connector->prepare('SELECT * FROM plugin_besucher');
    $stmt->execute();
    $plugin_result = $stmt->get_result();
    $commentar = '';
    while($visitors_rec = $plugin_result->fetch_assoc()) {
        $commentar .= 'IP:'. ' '. 
        $visitors_rec['ip_address'] . '<br>' . 'Datum:' .' '.
        $visitors_rec['besucherdatum'] . "<br>" . 'Browser:' . ' '.
        $visitors_rec['browser'] ."<br>" . 'Betriebstsytem:' . ' '.
        $visitors_rec['betriebssystem'] ."<br>" . "--------------------------------------" . "<br>";
    }
// besucher Anzahl  Zeile 2 spalte 2
    $stmt = $db_connector->prepare('SELECT COUNT(*) AS count FROM plugin_besucher');
    $stmt->execute();
    $plugin_result = $stmt->get_result();
    $counter ='';
    while($count_rec = $plugin_result->fetch_assoc()) {
      $counter .= 'Besucher Anzahl:'. ' '. 
      $count = $count_rec['count'];
    }
// besucher Anzahl  Zeile 2 spalte 2 und 2
    $stmt = $db_connector->prepare('SELECT * FROM plugin_besucher ORDER BY id');
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array(); 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        echo "Keine Daten gefunden";
    }
    $stmt = $db_connector->prepare('SELECT *,COUNT(id) as anzahl FROM plugin_besucher GROUP BY besucherdatum ');
    $stmt->execute();
    $chart_result = $stmt->get_result();
      $data = array();
      while ($row = $chart_result->fetch_assoc()) {
        $data[] = $row;
      }

$db_connector->close();
?>
<style>
 
</style>
<div class="container">
  <div class="column "><?php echo $datum ?></div>
  <div class="column "><?php echo $counter ?></div>
 
</div>
 

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
$db_connector = getDbConnection();
    $stmt = $db_connector->prepare('SELECT *,COUNT(id) as anzahl FROM plugin_besucher GROUP BY besucherdatum ');
    $stmt->execute();
    $chart_result = $stmt->get_result();
      $data = array();
      while ($row = $chart_result->fetch_assoc()) {
        $data[] = $row;
      } 
?>
<div class="container">
    <div class="column visitors_container">

<script src="/function/js/charts-loader.js"></script> 
<?php 
$dates = array();
$counts = array();

foreach ($data as $row) {
    $dates[] = $row['ip_address'];
    $counts[] = intval($row['anzahl']);
}
// Diagramm erstellen
$chartData = "['Datum', 'Anzahl',  { role: 'style' }],";
for ($i = 0; $i < count($dates); $i++) {
    $chartData .= "['" . $dates[$i] . "', " . $counts[$i] . ", 'color: #76A7FA'],";
}
?>

<script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        <?php echo $chartData; ?>
    ]);

    var options = {
        title: 'Benutzeranzahl nach Datum',
        chartArea: {width: '50%', height: '70%'},
        is3D: true,
        hAxis: {title: 'IP Address', minValue: 0},
        vAxis: {title: 'Anzahl'},
        bars: 'vertical'
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}
</script>
 
<div id="chart_div" </div>
</div>
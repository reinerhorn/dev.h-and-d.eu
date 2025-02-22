<?php
// Verbindungsdaten zur MySQL-Datenbank
$host = 'localhost';
$user = 'dein_benutzer';
$pass = 'dein_passwort';
$dbname = 'website_counter';

// Verbindung herstellen
$conn = new mysqli($host, $user, $pass, $dbname);

// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// IP-Adresse des Besuchers
$ip = $_SERVER['REMOTE_ADDR'];

// Überprüfen, ob die IP-Adresse gesperrt ist
$query = "SELECT * FROM ip_blocklist WHERE ip = '$ip'";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    die('Zugang von dieser IP-Adresse ist gesperrt.');
}

// Referer und Zielseite
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direkt';
$page = $_SERVER['REQUEST_URI']; // Aktuelle Seite

// User-Agent
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Datum und Zeit
$now = new DateTime();
$date = $now->format('Y-m-d');
$week = $now->format('W');
$month = $now->format('m');
$year = $now->format('Y');

// Besuchszeit
$visit_time = $now->format('Y-m-d H:i:s');

// Überprüfen, ob dieser Besucher heute schon da war
$query = "SELECT * FROM visitors WHERE ip = '$ip' AND date = '$date'";
$result = $conn->query($query);
if ($result->num_rows == 0) {
    // Neuen Besuch speichern
    $stmt = $conn->prepare("INSERT INTO visitors (ip, user_agent, referer, page, visit_time, date, week, month, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiii", $ip, $user_agent, $referer, $page, $visit_time, $date, $week, $month, $year);
    $stmt->execute();
    $stmt->close();
}

// Besucher-Statistiken abfragen

// Gesamtzahl der Besucher heute
$query = "SELECT COUNT(*) AS total_visitors FROM visitors WHERE date = '$date'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
echo "Besucher heute: " . $row['total_visitors'] . "<br>";

// Besucher-Statistiken nach Woche
$query = "SELECT COUNT(*) AS total_visitors_week FROM visitors WHERE week = '$week' AND year = '$year'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
echo "Besucher diese Woche: " . $row['total_visitors_week'] . "<br>";

// Besucher-Statistiken nach Monat
$query = "SELECT COUNT(*) AS total_visitors_month FROM visitors WHERE month = '$month' AND year = '$year'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
echo "Besucher diesen Monat: " . $row['total_visitors_month'] . "<br>";

// Besucher-Statistiken nach Jahr
$query = "SELECT COUNT(*) AS total_visitors_year FROM visitors WHERE year = '$year'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
echo "Besucher dieses Jahr: " . $row['total_visitors_year'] . "<br>";

// Besucher von gestern
$yesterday = $now->modify('-1 day')->format('Y-m-d');
$query = "SELECT COUNT(*) AS total_visitors_yesterday FROM visitors WHERE date = '$yesterday'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
echo "Besucher gestern: " . $row['total_visitors_yesterday'] . "<br>";

$conn->close();
?>

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
#include $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
// Session starten, falls noch nicht aktiv
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
#$conn = getDbConnection();
// Datenbankverbindung herstellen
$connection = new mysqli("localhost", "root", "101TanZen101", "dbs060954hd");

if ($connection->connect_error) {
    die("Datenbankverbindung fehlgeschlagen: " . $connection->connect_error);
}

// Standardwerte setzen
$role = 0; // Standard für öffentliche Seiten (NULL wird als 0 behandelt)
$page_id = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 0;

// Falls keine `page_id` übergeben wurde, lade die Startseite aus der Konfiguration
if ($page_id === 0) {
    define("PAGE_START_ID", 1692882220); // **Hier die korrekte Startseiten-ID eintragen**
    $page_id = PAGE_START_ID;
}

// Prüfen, ob die Seite existiert und die Rolle bestimmen
$stmt = $connection->prepare(
    'SELECT UNIX_TIMESTAMP(id) AS tsid, COALESCE(role, 0) AS role FROM page WHERE UNIX_TIMESTAMP(id) = ? LIMIT 1'
);
$stmt->bind_param('i', $page_id);
$stmt->execute();
$result = $stmt->get_result();
if ($rec = $result->fetch_assoc()) {
    $page_id = (int) $rec['tsid'];
    $role = (int) $rec['role']; // Falls NULL, wird es 0 (öffentliche Seite)
} else {
    echo "Fehler: Kein Eintrag für $page_id gefunden!<br>";
}
$stmt->close();

// Navigationstyp bestimmen
$nav_id = 'generalNav'; // Standard (öffentlich)
if ($role === 1) {
    $nav_id = 'adminNav';  
} elseif ($role === 2) { 
    $nav_id = 'memberNav'; 
}

// Debugging (nur zur Fehlersuche, später entfernen)
#echo "Role: $role <br>";
#echo "Page ID: " . ($page_id ?: "Keine") . "<br>";

?>

<!-- Navigation -->
<div class="Navigation" id="<?php echo $nav_id; ?>">
    <?php
    $stmt = $connection->prepare(
        'SELECT page.id, UNIX_TIMESTAMP(page.id) AS tsid, translation.label AS name 
        FROM page 
        JOIN translation ON page.fk_translation_placeholder = translation.fk_translation_placeholder 
        WHERE page.parent_id IS NULL 
        AND page.type = "main" 
        AND translation.fk_language_id = ? 
        AND COALESCE(page.role, 0) = ? 
        ORDER BY page.idx ASC'
    );
    $stmt->bind_param('si', $_SESSION['language'], $role);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($record = $result->fetch_assoc()) {
        $tsid = $record['tsid'];
        $title = $record['name'];
        $css_class = ($page_id == $tsid) ? ' class="marked"' : '';

        echo '<div class="nav-item"><a' . $css_class . ' title="' . htmlspecialchars($title) . '" href="?page=' . $tsid . '">' . htmlspecialchars($title) . '</a>';

        // Unterseiten abrufen
        $stmt_sub = $connection->prepare(
            'SELECT UNIX_TIMESTAMP(page.id) AS tsid, translation.label AS name 
            FROM page 
            JOIN translation ON page.fk_translation_placeholder = translation.fk_translation_placeholder 
            WHERE UNIX_TIMESTAMP(page.parent_id) = ? 
            AND fk_language_id = ? 
            AND COALESCE(page.role, 0) = ? 
            ORDER BY translation.label ASC'
        );
        $stmt_sub->bind_param('isi', $tsid, $_SESSION['language'], $role);
        $stmt_sub->execute();
        $sub_result = $stmt_sub->get_result();

        if ($sub_result->num_rows > 0) {
            echo '<div class="navigationDropDown">';
            while ($drop_record = $sub_result->fetch_assoc()) {
                $drop_title = $drop_record['name'];
                $drop_tsid = $drop_record['tsid'];
                $drop_css_class = ($page_id == $drop_tsid) ? ' class="marked"' : '';
                echo '<a' . $drop_css_class . ' title="' . htmlspecialchars($drop_title) . '" href="?page=' . $drop_tsid . '">' . htmlspecialchars($drop_title) . '</a>';
            }
            echo '</div>';
        }

        echo '</div>';
    }

    $stmt->close();
    ?>
</div>
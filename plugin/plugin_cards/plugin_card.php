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
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";

$db_connector = getDbConnection();

// SQL-Abfrage, um mehrere Karten mit zugehörigem Inhalt zu laden
$sql = "
    SELECT s.id AS stack_id, c.headline, c.text, c.link 
    FROM p_card_stack s
    JOIN p_card_editor e ON s.id = e.fk_cardstack_id
    JOIN p_card_content c ON e.id = c.fk_card_id
    ORDER BY s.id DESC, c.id DESC 
    LIMIT 6
";

$result = $db_connector->query($sql);

$cards_three = "";
$cards_one = "";
$counter = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $card = "<div>
                    <h3>" . htmlspecialchars($row["headline"]) . "</h3>
                    <p>" . nl2br(htmlspecialchars($row["text"])) . "</p>";

        if (!empty($row["link"])) {
            $card .= "<a href='" . htmlspecialchars($row["link"]) . "' target='_blank'>Mehr erfahren</a>";
        }
        $card .= "</div>";

        if ($counter < 2 || $counter == 3) {
            $cards_three .= $card;
        } else {
            $cards_one .= $card;
        }
        $counter++;
    }
} else {
    $cards_three = "<p>Keine Karten verfügbar.</p>";
}

/*$result->close();*/
?>

<div class="card_container">
    <div class="three">
        <?= $cards_three ?>
    </div>
    <div class="one">
        <?= $cards_one ?>
    </div>
 
    
 
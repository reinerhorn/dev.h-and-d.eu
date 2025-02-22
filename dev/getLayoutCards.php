<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";

function getLayoutCards() {
    global $mysqli;

    // SQL-Abfrage mit UNIX_TIMESTAMP für Sortierung nach Timestamp-IDs
    $stmt = $mysqli->prepare("
        SELECT c.headline, c.text, c.link 
        FROM p_card_stack s
        JOIN p_card_editor e ON s.id = e.fk_cardstack_id
        JOIN p_card_content c ON e.id = c.fk_card_id
        ORDER BY UNIX_TIMESTAMP(s.id) DESC, UNIX_TIMESTAMP(c.id) DESC 
        LIMIT 6
    ");

    if (!$stmt) {
        die("Datenbankfehler: " . $mysqli->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Karten generieren
    $cards = [];
    while ($row = $result->fetch_assoc()) {
        $cardHtml = "<div class='card'>
                        <h3>" . htmlspecialchars($row["headline"]) . "</h3>
                        <p>" . nl2br(htmlspecialchars($row["text"])) . "</p>";

        if (!empty($row["link"])) {
            $cardHtml .= "<a href='" . htmlspecialchars($row["link"]) . "' target='_blank'>Mehr erfahren</a>";
        }

        $cardHtml .= "</div>";
        $cards[] = $cardHtml;
    }

    $stmt->close();

    // Falls keine Karten vorhanden sind, eine Standard-Nachricht anzeigen
    if (empty($cards)) {
        return "<p>Keine Karten verfügbar.</p>";
    }

    // Karten als HTML-String zurückgeben
    return implode("\n", $cards);
}
?>
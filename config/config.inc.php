<?php
function getDbConnection() {
    static $connection = null;

    if ($connection === null) {
        $connection = new mysqli("localhost", "root", "101TanZen101", "dbs060954hd");

        if ($connection->connect_errno) {
            error_log("Datenbankverbindungsfehler: " . $connection->connect_error);
            die("<h1>Fehler: Datenbank nicht erreichbar</h1>");
        }

        // Zeichensatz auf UTF-8 setzen
        if (!$connection->set_charset("utf8mb4")) {
            error_log("Fehler beim Setzen des Zeichensatzes: " . $connection->error);
            die("<h1>Fehler beim Setzen des Zeichensatzes</h1>");
        }
    }
    return $connection;
}
?>
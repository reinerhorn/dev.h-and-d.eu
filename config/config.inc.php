<?php

function getDbConnection() {
    static $connection = null;

    if ($connection === null) {
        $config = require '/etc/hd_config.php'; // Sicher außerhalb des Webroots speichern!

        $connection = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

        if ($connection->connect_error) {
            error_log("Datenbankverbindungsfehler: " . $connection->connect_error);
            die("<h1>Datenbank nicht erreichbar</h1>");
        }
    }
    return $connection;
}

?>
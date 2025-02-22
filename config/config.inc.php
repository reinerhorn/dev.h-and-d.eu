<?php

function getDbConnection() {
  static $connection = null;

  if ($connection === null) {
      $connection = new mysqli("localhost", "root", "", "");
      if ($connection->connect_error) {
          error_log("Datenbankverbindungsfehler: " . $connection->connect_error);
          die("<h1>Datenbank nicht erreichbar</h1>");
      }
  }
  return $connection;
}
 

?>

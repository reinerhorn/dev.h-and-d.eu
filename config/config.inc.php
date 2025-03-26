<?php
/*$mysqli = new mysqli("", "root", "101TanZen101", "dbs06091854");
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
   exit();
  }*/
function getDbConnection()
{
  return new mysqli("", "root", "101TanZen101", "dbs06091954");
} 
?>

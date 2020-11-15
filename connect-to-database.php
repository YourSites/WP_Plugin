<?php

$db_host = "localhost";

$db_username = "yoursites";

$db_password = "KomputerKinderKlub2020";

$db_name = "yoursites";

// Verbindung zur Datenbank erstellen

$databaseConnection = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($databaseConnection->connect_error) {
  die("Verbindung fehlgeschlagen: " . $databaseConnection->connect_error);
}



?>
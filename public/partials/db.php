<?php
  $servername = "localhost";
  $username = getenv('DB_USER') ?: 'root';
  $password = getenv('DB_PASS') ?: '';
  $dbname = "macan_dimsum_go";
  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>

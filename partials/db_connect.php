<?php
$server = "localhost";
$username = "root"; 
$password = ""; 
$database = "car-wash"; 
$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: ");
  }
?>

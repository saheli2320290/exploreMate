<?php

$host = "localhost";
$username = "root";
$password = "Saheli@1249";
$database = "exploremate";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<?php
include "db.php";
session_start();

$user_id = $_SESSION['user_id'] ?? 1;
$destination_id = $_GET['id'];

$check = $conn->query("
SELECT * FROM user_trip 
WHERE user_id=$user_id 
AND destination_id=$destination_id
");

if($check->num_rows == 0){

$conn->query("
INSERT INTO user_trip (user_id, destination_id, status)
VALUES ($user_id, $destination_id, 'pending')
");

}

echo "OK";
?>
<?php
// signup.php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$user_level = $_POST['user_level'];
$username  = $_POST['username'];
$email     = $_POST['email'];
$password  = $_POST['password'];

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* Check username already exists */
$check = $conn->prepare("SELECT username FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {

header("Location: signUpPage.html?exist=1");
exit();

} else {

$stmt = $conn->prepare("INSERT INTO users (user_level, username, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $user_level, $username, $email, $hashedPassword);

if ($stmt->execute()) {

header("Location: signUpPage.html?success=1");
exit();

} else {

header("Location: signUpPage.html?error=1");
exit();

}

$stmt->close();
}

$check->close();
$conn->close();
}
?>
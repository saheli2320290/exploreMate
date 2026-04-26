<?php
session_start();
include "db.php";


$user_level = strtolower($_POST['user_level']); 
$username = $_POST['username'];
$password = $_POST['password'];


$sql = "SELECT * FROM users WHERE username='$username' AND user_level='$user_level'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    
    if (password_verify($password, $row['password'])) {
        
       
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_level'] = $row['user_level'];

      
        if ($row['user_level'] == "admin") {
            header("Location: adminDashboard.html");
            exit();
        } else {
            header("Location: userDashboard.html");
            exit();
        }
    } else {
       
        header("Location: SignInPage.html?error=login");
        exit();
    }
} else {
   
    header("Location: SignInPage.html?error=login");
    exit();
}
?>
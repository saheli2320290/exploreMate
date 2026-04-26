<?php
session_start();

// 1. Database connection
$conn = new mysqli("localhost", "root", "Saheli@1249", "exploremate");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Initialize variables
$id = "";
$user = [
    "username" => "",
    "email" => ""
];

// 3. Get user data for editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }
}

// 4. Update user
if (isset($_POST['update_user'])) {
    $id = intval($_POST['id']);
    $username = $_POST['username'];
    $email = $_POST['email'];

    $update_stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $username, $email, $id);

    if ($update_stmt->execute()) {
        header("Location: users.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>

<link rel="stylesheet" href="style.css">

<style>
.container {
    max-width: 500px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

/* NEW: helper text */
.small-text {
    font-size: 12px;
    color: #777;
    margin-top: 3px;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
}

.btn-save {
    background: #198754;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

.btn-save:hover {
    background: #157347;
}

.back-link {
    display: block;
    margin-top: 15px;
    text-align: center;
    color: #0d6efd;
    text-decoration: none;
}
</style>
</head>

<body>

<nav class="navbar">
    <div class="logo">
        <span class="icon">🌐</span> ExploreMate
    </div>

    <ul class="menu">
        <li><a href="adminDashboard.html">Home</a></li>
        <li><a href="About.html">About Us</a></li>
        <li><a href="Home.html">Sign Out</a></li>
    </ul>
</nav>

<div class="container">

<h2>Edit User Details</h2>
<hr>

<form method="POST">

<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

<!-- USERNAME -->
<div class="form-group">
    <label>Username</label>
    <input type="text" name="username"
           value="<?php echo htmlspecialchars($user['username']); ?>"
           required>
    <div class="small-text"></div>
</div>

<!-- EMAIL -->
<div class="form-group">
    <label> Email Address</label>
    <input type="email" name="email"
           value="<?php echo htmlspecialchars($user['email']); ?>"
           required>
    <div class="small-text"></div>
</div>

<button type="submit" name="update_user" class="btn-save">
    Update User
</button>

<a href="users.php" class="back-link">Cancel and Go Back</a>

</form>

</div>

</body>
</html>
<?php
session_start();

// 1. Database connection
$conn = new mysqli("localhost", "root", "Saheli@1249", "exploremate");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* -----------------------------
   2. DELETE USER (SAFE - POST)
------------------------------*/
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: users.php");
    exit();
}

/* -----------------------------
   3. FETCH USERS
------------------------------*/
$result = $conn->query("SELECT id, username, email FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
<link rel="stylesheet" href="style.css">
    <style>
        
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #0d6efd;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #ffc107;
            color: black;
        }

        .btn-delete {
            background: #dc3545;
             color: black;
        }

        .back-link {
            text-decoration: none;
            color: #0d6efd;
            font-weight: bold;
        }

        form {
            display: inline;
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

    <href="adminDashboard.html" class="back-link" >
    

    <h2>User Management</h2>
    
    <button onclick="window.location.href='adminDashboard.html'" 
        style="padding:10px 20px; background:#198754; color:white; border:none; border-radius:5px; cursor:pointer;">
    Back To Dashboard
</button>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>

            <td><?php echo htmlspecialchars($row['username']); ?></td>

            <td><?php echo htmlspecialchars($row['email']); ?></td>

            <td>

                <!-- EDIT -->
                <a href="update_user.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">
                    Edit
                </a>

                <!-- DELETE (SAFE POST FORM) -->
                <form method="POST" onsubmit="return confirm('Delete this user?');">
                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-delete">
                        Delete
                    </button>
                </form>

            </td>
        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>
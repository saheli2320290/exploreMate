<?php
include "db.php";

/* DELETE */
if(isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    $stmt = $conn->prepare("DELETE FROM destinations WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: view_destination.php");
    exit();
}

/* UPDATE */
if(isset($_POST['update'])) {

    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $category_name = $_POST['category_name'];
    $description = $_POST['description'];
    $google_place = $_POST['google_place'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    /* ⭐ ADDED */
    $open_time = $_POST['open_time'];
    $close_time = $_POST['close_time'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0)
    {
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);

        $stmt = $conn->prepare("UPDATE destinations SET
            name=?, category_name=?, description=?, image=?, google_place=?, latitude=?, longitude=?, open_time=?, close_time=?
            WHERE id=?");

        $stmt->bind_param(
            "sssssssssi",
            $name,
            $category_name,
            $description,
            $image,
            $google_place,
            $latitude,
            $longitude,
            $open_time,
            $close_time,
            $id
        );
    }
    else
    {
        $stmt = $conn->prepare("UPDATE destinations SET
            name=?, category_name=?, description=?, google_place=?, latitude=?, longitude=?, open_time=?, close_time=?
            WHERE id=?");

        $stmt->bind_param(
            "ssssssssi",
            $name,
            $category_name,
            $description,
            $google_place,
            $latitude,
            $longitude,
            $open_time,
            $close_time,
            $id
        );
    }

    $stmt->execute();

    header("Location: view_destination.php");
    exit();
}

/* EDIT DATA */
$editData = null;

if(isset($_GET['edit'])) {
    $id = intval($_GET['edit']);

    $stmt = $conn->prepare("SELECT * FROM destinations WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $editData = $stmt->get_result()->fetch_assoc();
}

/* FETCH ALL */
$result = $conn->query("SELECT * FROM destinations ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Destinations</title>

<link rel="stylesheet" href="style.css">

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

<style>

.view_container {
    width: 90%;
    max-width: 1100px;
    margin: 40px auto;
    color: #333333;
}

.view_formbox {
    background: #ffffff;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    color: #333333;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    color: #333333;
    background: #fff;
}

textarea {
    height: 90px;
}

.update-btn {
    width: 100%;
    padding: 12px;
    background: #ffc107;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    color: #000;
}

.cancel-btn {
    width: 100%;
    padding: 12px;
    background: #007bff;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    color: #000;
    margin-top: 10px;
}

h2 {
    color: #007bff;
    text-align: center;
    margin-top: 30px;
}

.view_card {
    background: #ffffff;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 10px;
    display: flex;
    gap: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
    color: #333333;
}

label{
    color: #007bff;
    font-weight: bold;
}

.view_card img {
    width: 200px;
    height: 140px;
    object-fit: cover;
    border-radius: 8px;
}

.btn {
    padding: 8px 12px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
}

.edit {
    background: #ffc107;
    color: black;
}

.delete {
    background:#dc3545;
    color: black;
    border: none;
    cursor: pointer;
}

.cat-tag {
    background: #eee;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 12px;
    color: #333;
}

</style>
</head>

<body>

<div class="view_container">

<h2>Manage Destinations</h2>

<!-- EDIT FORM -->
<?php if($editData): ?>
<div class="view_formbox">

<h3>Edit Destination</h3>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?php echo $editData['id']; ?>">

<label>Name</label>
<input type="text" name="name" value="<?php echo $editData['name']; ?>" required>

<label>Category</label>
<select name="category_name" required>
<option value="Historical and Religious" <?php if($editData['category_name']=="Historical and Religious") echo "selected"; ?>>Historical and Religious</option>
<option value="Nature Spots" <?php if($editData['category_name']=="Nature Spots") echo "selected"; ?>>Nature Spots</option>
<option value="Recreational Activates" <?php if($editData['category_name']=="Recreational Activates") echo "selected"; ?>>Recreational Activates</option>
<option value="Food and Beverages" <?php if($editData['category_name']=="Food and Beverages") echo "selected"; ?>>Food and Beverages</option>
</select>

<label>Description</label>
<textarea name="description"><?php echo $editData['description']; ?></textarea>

<label>Google Map Link</label>
<input type="text" name="google_place" value="<?php echo $editData['google_place']; ?>">

<label>Latitude</label>
<input type="text" name="latitude" value="<?php echo $editData['latitude']; ?>">

<label>Longitude</label>
<input type="text" name="longitude" value="<?php echo $editData['longitude']; ?>">

<!-- ⭐ TIME ADDED -->
<label>Open Time</label>
<input type="time" name="open_time" value="<?php echo $editData['open_time']; ?>" required>

<label>Close Time</label>
<input type="time" name="close_time" value="<?php echo $editData['close_time']; ?>" required>

<label>Change Image</label>
<input type="file" name="image">

<button type="submit" name="update" class="update-btn">Update</button>

<button type="button" class="cancel-btn" onclick="window.location.href='view_destination.php'">
Cancel
</button>

</form>
</div>
<?php endif; ?>

<!-- LIST -->
<?php while($row = $result->fetch_assoc()): ?>

<div class="view_card">

<img src="uploads/<?php echo (!empty($row['image']) ? $row['image'] : 'noimage.png'); ?>">

<div>

<span class="cat-tag"><?php echo $row['category_name']; ?></span>
<h3><?php echo $row['name']; ?></h3>
<p><?php echo $row['description']; ?></p>

<p>
📍 <?php echo $row['latitude']; ?>,
<?php echo $row['longitude']; ?>
</p>

<!-- ⭐ TIME DISPLAY -->
<p>
🕒 Open: <?php echo $row['open_time']; ?> <br>
🕒 Close: <?php echo $row['close_time']; ?>
</p>

<a class="btn edit" href="view_destination.php?edit=<?php echo $row['id']; ?>">Edit</a>

<form method="POST" style="display:inline;">
<input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
<button type="submit" class="btn delete" onclick="return confirm('Delete this destination?')">
Delete
</button>
</form>

</div>
</div>

<?php endwhile; ?>

</div>

</body>
</html>
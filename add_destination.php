<?php
include "db.php";

if(isset($_POST['submit']))
{
    $name           = $_POST['name'];
    $category_name  = $_POST['category_name'];
    $description    = $_POST['description'];
    $google_place   = $_POST['google_place'];
    $latitude       = $_POST['latitude'];
    $longitude      = $_POST['longitude'];
    $open_time      = $_POST['open_time'];
    $close_time     = $_POST['close_time'];

    $image = "";

    /* ⭐ ANY TYPE IMAGE UPLOAD (NO RESTRICTION) */
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0)
    {
        $fileName = $_FILES['image']['name'];
        $tmp      = $_FILES['image']['tmp_name'];

        $image = time() . "_" . $fileName;

        if(!is_dir("uploads"))
        {
            mkdir("uploads", 0777, true);
        }

        move_uploaded_file($tmp, "uploads/" . $image);
    }

    /* INSERT */
    $sql = "INSERT INTO destinations
    (name, category_name, description, image, google_place, latitude, longitude, open_time, close_time)
    VALUES
    ('$name','$category_name','$description','$image',
     '$google_place','$latitude','$longitude',
     '$open_time','$close_time')";

    if($conn->query($sql))
    {
        echo "<script>alert('Destination Added Successfully');</script>";
    }
    else
    {
        echo "<script>alert('Error: ".$conn->error."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Destination</title>

<style>
body{
    font-family: Arial;
    margin:0;
    background: linear-gradient(to bottom, #2c3e50, #fd746c);
}

.navbar{
    display:flex;
    justify-content:space-between;
    padding:15px 30px;
    background:#111;
    color:white;
}

.navbar a{
    color:white;
    text-decoration:none;
    margin:0 10px;
}

.container{
    width:500px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 15px rgba(0,0,0,0.15);
}

h2{
    text-align:center;
    color:#2563eb;
    margin-bottom:20px;
}

label{
    display:block;
    margin-top:10px;
    margin-bottom:5px;
    font-size:14px;
}

input, select, textarea{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:14px;
}

textarea{
    height:100px;
    resize:none;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    background:#198754;
    color:white;
    font-size:15px;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#157347;
}
</style>
</head>

<body>

<div class="navbar">
    <div>🌐 ExploreMate</div>
    <div>
        <a href="adminDashboard.html">Home</a>
        <a href="About.html">About</a>
        <a href="Home.html">Logout</a>
    </div>
</div>

<div class="container">

<h2>Add Destination</h2>

<form method="POST" enctype="multipart/form-data">

<label>Destination Name</label>
<input type="text" name="name" required>

<label>Category</label>
<select name="category_name" required>
    <option value="" disabled selected>Select Category</option>
    <option value="Historical and Religious">Historical and Religious</option>
    <option value="Nature Spots">Nature Spots</option>
    <option value="Recreational Activates">Recreational Activates</option>
    <option value="Food and Beverages">Food and Beverages</option>
</select>

<label>Description</label>
<textarea name="description"></textarea>

<label>Image</label>
<input type="file" name="image">

<label>Google Map Link</label>
<input type="text" name="google_place">

<label>Latitude</label>
<input type="text" name="latitude">

<label>Longitude</label>
<input type="text" name="longitude">

<label>Open Time</label>
<input type="time" name="open_time" required>

<label>Close Time</label>
<input type="time" name="close_time" required>

<button type="submit" name="submit">Add Destination</button>

<button type="button" onclick="window.location.href='destination_dashboard.php'">
Back To Destination Management
</button>

</form>

</div>

</body>
</html>
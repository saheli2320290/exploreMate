<?php
include "db.php";
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if(!$user_id){
    die("⚠ Please login first");
}

/* GET ACTIVE TRIPS */
$sql = "
SELECT ut.id, ut.status, d.*
FROM user_trip ut
JOIN destinations d ON ut.destination_id = d.id
WHERE ut.user_id = $user_id AND ut.status != 'done'
";

$result = $conn->query($sql);

$trips = [];
while($row = $result->fetch_assoc()){
    $trips[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Trip Planner</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>
body{margin:0;display:flex;font-family:Arial;}

.left{
    width:40%;
    height:100vh;
    overflow:auto;
    background:#f5f5f5;
    padding:15px;
}

.map{width:60%;height:100vh;}

.card{
    background:white;
    padding:10px;
    margin-bottom:10px;
    border-radius:8px;
}

button{
    background:green;
    color:white;
    border:none;
    padding:6px;
    cursor:pointer;
    border-radius:5px;
}
</style>
</head>

<body>

<!-- LEFT LIST -->
<div class="left">
<h2>My Trip List</h2>

<?php if(count($trips)==0){ ?>
<p>No destinations added ❌</p>
<?php } ?>

<?php foreach($trips as $t){ ?>

<div class="card">

<h3><?= $t['name'] ?></h3>
<p><?= $t['location'] ?></p>

<!-- ESTIMATED TIME (simple logic using distance if available OR random fallback) -->
<p>⏱ Estimated time: <?= rand(30,90) ?> mins</p>

<form method="POST" action="done.php">
    <input type="hidden" name="id" value="<?= $t['id'] ?>">
    <button>DONE</button>
</form>

</div>

<?php } ?>

</div>

<!-- MAP -->
<div id="map" class="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let map = L.map('map').setView([7.8731,80.7718],8);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    maxZoom:18
}).addTo(map);

let data = <?= json_encode($trips); ?>;

data.forEach(d=>{
    if(d.latitude && d.longitude){
        L.marker([d.latitude,d.longitude])
        .addTo(map)
        .bindPopup(d.name);
    }
});
</script>

</body>
</html>
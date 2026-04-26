<?php
include "db.php";

$result = $conn->query("SELECT * FROM destinations");

$places = [];
while($row = $result->fetch_assoc()){
    $places[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Plan Trip</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
body{
    margin:0;
    font-family:Arial, sans-serif;
}

#topbar{
    background:#1e293b;
    padding:10px;
    display:flex;
    gap:10px;
    align-items:center;
}

#topbar button,
#topbar select{
    padding:8px 14px;
    border:none;
    border-radius:6px;
    font-size:14px;
    cursor:pointer;
}

#topbar button{
    background:#0ea5e9;
    color:white;
}

#categoryFilter{
    background:#0ea5e9;
    color:white;
    font-weight:bold;
    border:2px solid #2563eb;
}

#container{
    display:flex;
}

#map{
    width:70%;
    height:100vh;
}

#side{
    width:30%;
    background:linear-gradient(180deg,#dbeafe,#eff6ff);
    padding:15px;
    overflow-y:auto;
    border-left:2px solid #bfdbfe;
}

#side h3{
    margin-top:0;
    color:#1e3a8a;
}

.final-btn{
    width:100%;
    padding:10px;
    background: #2563eb;
    color:white;
    border:none;
    border-radius:10px;
    font-weight:bold;
    cursor:pointer;
    margin-bottom:10px;
    font-size:15px;
}

.card{
    background:white;
    padding:12px;
    margin-bottom:14px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
    border:1px solid #e5e7eb;
}

.card img{
    width:100%;
    height:170px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:10px;
}

.btn{
    padding:7px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
    margin-right:6px;
    margin-top:8px;
}

.add{ background:#16a34a; color:white; }
.remove{ background:#dc2626; color:white; }
.view{ background:#2563eb; color:white; }

.map-label{
    background:white;
    border:1px solid #ddd;
    color:green;
    font-weight:bold;
    font-size:13px;
    padding:3px 8px;
    border-radius:6px;
    box-shadow:0 2px 6px rgba(0,0,0,.15);
}
</style>
</head>

<body>

<div id="topbar">

    <button onclick="showMyLocation()">📍 My Location</button>
    <button onclick="resetMap()">🔄 Reset</button>

    <select id="categoryFilter" onchange="filterMap()">
        <option value="" selected disabled>Select Destination</option>
        <option value="all">All Destinations</option>
        <option value="Historical and Religious">Historical and Religious</option>
        <option value="Nature Spots">Nature Spots</option>
        <option value="Recreational Activates">Recreational Activates</option>
        <option value="Food and Beverages">Food and Beverages</option>
    </select>

</div>

<div id="container">

<div id="map"></div>

<div id="side">

    <h3>⭐ My List</h3>

    <a href="finallist.php">
        <button class="final-btn">
            🗺️ View Final Trip Report
        </button>
    </a>

    <div id="myList"></div>

</div>

</div>

<script>

let map = L.map('map').setView([7.8731,80.7718],8);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    maxZoom:18
}).addTo(map);

let places = <?php echo json_encode($places); ?>;

let markersLayer = L.layerGroup().addTo(map);
let myList = [];
let userMarker = null;

/* ADD MARKER */
function addMarker(place){

let lat = parseFloat(place.latitude);
let lng = parseFloat(place.longitude);

if(isNaN(lat) || isNaN(lng)) return;

let marker = L.marker([lat,lng]).addTo(markersLayer);

marker.bindTooltip(place.name,{
    permanent:true,
    direction:"top",
    className:"map-label"
});

marker.bindPopup(`
<b style="color:green;">📍 ${place.name}</b><br>

${place.location ? place.location + '<br><br>' : ''}

🕒 <b>Open:</b> ${place.open_time ? place.open_time : '--'}<br>
🕒 <b>Close:</b> ${place.close_time ? place.close_time : '--'}<br><br>

<p>${place.description ? place.description : ''}</p>

<button class="btn add" onclick="addToList(${place.id})">
➕ Add to My List
</button>
`);

}

/* FILTER */
function filterMap(){

let category = document.getElementById("categoryFilter").value;

markersLayer.clearLayers();

if(category === ""){
    return;
}

if(category === "all"){
    places.forEach(place => addMarker(place));
    return;
}

places.forEach(place => {
    if(place.category_name === category){
        addMarker(place);
    }
});

}

/* RESET */
function resetMap(){

document.getElementById("categoryFilter").selectedIndex = 0;

markersLayer.clearLayers();

if(userMarker){
    map.removeLayer(userMarker);
    userMarker = null;
}

map.setView([7.8731,80.7718],8);

}

/* LOCATION */
function showMyLocation(){

navigator.geolocation.getCurrentPosition(function(pos){

let lat = pos.coords.latitude;
let lng = pos.coords.longitude;

map.setView([lat,lng],12);

if(userMarker){
    map.removeLayer(userMarker);
}

userMarker = L.marker([lat,lng]).addTo(map)
.bindTooltip("📍 You are here",{
    permanent:true,
    direction:"top",
    className:"map-label"
});

});

}

/* ADD TO LIST */
function addToList(id){

let place = places.find(p => p.id == id);

if(!myList.some(p => p.id == id)){
    myList.push(place);
    renderList();
}

}

/* REMOVE */
function removeFromList(id){

myList = myList.filter(p => p.id != id);
renderList();

}

/* RENDER LIST */
function renderList(){

let div = document.getElementById("myList");
div.innerHTML = "";

myList.forEach(p => {

div.innerHTML += `
<div class="card">

<img src="uploads/${p.image}" alt="${p.name}">

<b>${p.name}</b><br>

${p.location ? p.location + '<br>' : ''}

🕒 ${p.open_time ? p.open_time : '--'} to ${p.close_time ? p.close_time : '--'}<br>

<button class="btn view"
onclick="map.setView([${p.latitude},${p.longitude}],14)">
📍 View
</button>

<button class="btn remove"
onclick="removeFromList(${p.id})">
❌ Remove
</button>

</div>
`;

});

}

</script>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Destination Dashboard</title>
<link rel="stylesheet" href="style.css">
  <style>
    h2 { 
        color: #007bff;
        text-align: center;
        margin-top: 30px;
    }

    .Destination_container {  
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;   
        margin-top: 60px;  
        padding: 20px;
    }  

    .card {  
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        
         width:320px;
    height:220px;
        background: white;  
        padding: 25px;  
        border-radius: 12px;  
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);  
        transition: 0.3s;  
    }  

    .card:hover {  
        transform: translateY(-5px);  
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);  
    }  

    .card h2 {
        font-size: 22px;
        margin: 10px 0;
        color: #007bff;   
    }

    p {  
        color: #666;  
        margin-bottom: 25px;  
        font-size: 16px;
        text-align: center;
        line-height: 1.5;
    }

    .btn {
        background-color: #198754;
        color: white;
        padding: 10px 30px;
        text-decoration: none;
        border-radius: 8px;
        display: inline-block;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn:hover {
        background-color: #157347;
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

<h2>Destination Management</h2>

<div class="Destination_container">

    <div class="card">
        
        <br>➕</br> 
        <h2>Add Destination</h2>
        <p>Add new tourist places to the system</p>
        <a href="add_destination.php" class="btn add">Open</a>
    </div>

    <div class="card">
        <br>📋</br>
        <h2> View Destinations</h2>
        <p>Manage all saved destinations</p>
        <a href="view_destination.php" class="btn view">Open</a>
    </div>

    

</div>

</body>
</html>
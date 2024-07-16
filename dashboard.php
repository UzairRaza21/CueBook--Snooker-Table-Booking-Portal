<?php
session_start();
include "conn.php";
if (!isset($_SESSION['club_email'])){
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cuebook.css">
    <title>Admin Dashboard</title>
</head>
<body>
<!-- Nav Start -->
<nav>
    <div id="logo-pic">
        <img src="./assets/logo/svg/logo-no-background.svg" alt="threads" width="180" height="60">
    </div>
    
    <div>
        <ul id="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products-upload.php">Upload Ads</a></li>
            <li><a href="productlist.php">Ads List</a></li>
            <li><a href="ads-sale.php">Sale</a></li>
            <li><a href="ads-lease.php">Lease</a></li>
            <li><a href="ads-market-off.php">Market Off</a></li>
            <li><a href="logout.php">Logout</a></li>

        </ul>
    </div>

    <div id="menu" onclick="openMenu()">&#9776;</div>
</nav>

<div id="nav-col">
    <div id="nav-col-links" class="nav-col-links">
        <a id="link" href="dashboard.php">Dashboard</a>
        <a id="link" href="products-upload.php">Upload Ads</a>
        <a id="link" href="productlist.php">Ads List</a>
        <a id="link" href="ads-sale.php">Sale</a>
        <a id="link" href="ads-lease.php">Lease</a>
        <a id="link" href="ads-market-off.php">Market Off</a>
        <a id="link" href="logout.php">Log out</a>
    </div>
</div>

<!-- Nav End -->





<script src="app.js"></script>

</body>
</html>

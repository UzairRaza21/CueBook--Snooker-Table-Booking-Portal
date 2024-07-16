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
        <img src="assets/logo/svg/logo-no-background.svg" alt="threads" width="180" height="60">
    </div>
    
    <div>
        <ul id="nav-links">
            <li><a href="dashboard.php">New Table</a></li>
            <li><a href="add-on.php">Add-ons</a></li>
            <li><a href="inventory.php">Inventory</a></li>
            <li><a href="expense.php">Expense</a></li>
            <li><a href="closing.php">Closing</a></li>
            <li><a href="logout.php">Logout</a></li>

        </ul>
    </div>

    <div id="menu" onclick="openMenu()">&#9776;</div>
</nav>

<div id="nav-col">
    <div id="nav-col-links" class="nav-col-links">
        <a id="link" href="new-table.php">New Table</a>
        <a id="link" href="add-on.php">Add-ons</a>
        <a id="link" href="inventory.php">Inventory</a>
        <a id="link" href="expense.php">Expense</a>
        <a id="link" href="closing.php">Closing</a>
        <a id="link" href="logout.php">Log out</a>
    </div>
</div>

<!-- Nav End -->

<div class="date-search-bar" >
        <label for="search">Select Date :</label>
        <input type="date" id="search-date" autocomplete="off"  placeholder="DD-MM-YYYY" >
</div>






<script src="jquery.js"></script>
<script src="app.js"></script>
    <!-- Live Search -->
    <script>
        $(document).ready(function(){
            // For Search Bar of Date
            $('#search').on("keyup", function(e) {
                var search_term = $(this).val();
                $.ajax({
                    url: "ajax-live-search-sale.php",
                    type: "POST",
                    data: { search: search_term },
                    success: function(data) {
                        if (data) {
                            $("#ads-container").html(data);
                        } else {
                            $("#ads-container").html("<h4>No Record Found</h4>");
                        }
                    }
                });
            });
        
        // To book New Table
        $('').on("click",function(){
            
        })
        
        
        
        
        });
    </script>
</body>
</html>

<?php
session_start();
include "conn.php";

if (!isset($_SESSION['club_email'])) {
    header("location: login.php");
    exit();
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

<?php echo "Welcome, " . htmlspecialchars($_SESSION['clubname']); ?>

<div class="date-search-bar">
    <label for="search">Select Date:</label>
    <input type="date" id="search-date" autocomplete="off" placeholder="DD-MM-YYYY">
</div>

<div class="new-tables-container">

<form id="create-table-form">
    <h2>Create New Table</h2>
    <div class="cue-input-field">
        <label for="customer_name">Table Name:*</label>
        <input type="text" name="customer_name" class="table-input" id="customer_name" placeholder="e.g Shahid Khan or M. Ali" required>
    </div>
    <div class="cue-input-field">
        <label for="customer_price">Rate:*</label>
        <input type="text" name="customer_price" id="customer_price" class="table-input" placeholder="e.g 4.5" required>
    </div>
    <div class="cue-input-field">
        <label for="customer_mobile_no">Mobile:*</label>
        <input type="text" name="customer_mobile_no" id="customer_mobile_no" class="table-input" placeholder="e.g 0300-1234567" required>
    </div>
    <div class="cue-input-field">
        <label for="customer_email">Email:*</label>
        <input type="email" name="customer_email" id="customer_email" class="table-input" placeholder="e.g abc@email.com" required>
    </div>
    <div class="cue-input-field">
        <input type="submit" class="table-button" value="Create Table">
    </div>
</form>

</div>


<script src="jquery.js"></script>
<script src="app.js"></script>

<script>
$(document).ready(function() {
    // Handle Create Table Form
    $('#create-table-form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'create_table.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    // Append new table to the list
                    $('.new-tables-container').append(`
                        <div class="existing-table">
                            <p>Name: ${data.customer_name}</p>
                            <p>Rate: ${data.customer_rate}</p>
                            <p>Mobile: ${data.customer_phone}</p>
                            <p>Email: ${data.customer_email}</p>
                            <form class="check-in-form">
                                <input type="hidden" name="customer_id" value="${data.customer_id}">
                                <input type="submit" value="Check In" class="table-button">
                            </form>
                            <form class="check-out-form" style="display: none;">
                                <input type="hidden" name="customer_id" value="${data.customer_id}">
                                <input type="submit" value="Check Out" class="table-button">
                            </form>
                        </div>
                    `);
                    // Clear the form fields
                    $('#create-table-form')[0].reset();
                } else {
                    alert('Error: ' + data.error);
                }
            }
        });
    });

    // Handle Check In Form
    $(document).on('submit', '.check-in-form', function(event) {
        event.preventDefault();
        const $form = $(this);
        $.ajax({
            url: 'check_in.php',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    // Hide check-in form and show check-out form
                    $form.siblings('.check-out-form').show();
                    $form.hide();
                } else {
                    alert('Error: ' + data.error);
                }
            }
        });
    });

    // Handle Check Out Form
    $(document).on('submit', '.check-out-form', function(event) {
        event.preventDefault();
        const $form = $(this);
        $.ajax({
            url: 'check_out.php',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    // Optionally handle UI update after check-out
                    $form.hide(); // Optionally hide the check-out form after submission
                } else {
                    alert('Error: ' + data.error);
                }
            }
        });
    });

    // Add a new form dynamically
    $('#add-form-button').on('click', function() {
        $('#forms-container').append(`
            <form class="create-table-form">
                 <h2>Create New Table</h2>
                <div class="cue-input-field">
                    <label for="customer_name">Table Name:*</label>
                    <input type="text" name="customer_name[]" class="table-input" placeholder="e.g Shahid Khan or M. Ali" required>
                </div>
                <div class="cue-input-field">
                    <label for="customer_price">Rate:*</label>
                    <input type="text" name="customer_price[]" class="table-input" placeholder="e.g 4.5" required>
                </div>
                <div class="cue-input-field">
                    <label for="customer_mobile_no">Mobile:*</label>
                    <input type="text" name="customer_mobile_no[]" class="table-input" placeholder="e.g 0300-1234567" required>
                </div>
                <div class="cue-input-field">
                    <label for="customer_email">Email:*</label>
                    <input type="email" name="customer_email[]" class="table-input" placeholder="e.g abc@email.com" required>
                </div>
                <div class="cue-input-field">
                    <input type="submit" class="table-button" value="Create Table">
                </div>
            </form>
        `);
    });
});

</script>


<!-- Live Search -->
<script>
    // $(document).ready(function(){
    //     // For Search Bar of Date
    //     $('#search-date').on("keyup", function(){
    //         var search_term = $(this).val();
    //         $.ajax({
    //             url: "ajax-live-search-sale.php",
    //             type: "POST",
    //             data: { search: search_term },
    //             success: function(data) {
    //                 if (data) {
    //                     $("#ads-container").html(data);
    //                 } else {
    //                     $("#ads-container").html("<h4>No Record Found</h4>");
    //                 }
    //             }
    //         });
    //     });
    // });
</script>
</body>
</html>

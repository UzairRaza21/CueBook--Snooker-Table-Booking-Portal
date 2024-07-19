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
    <input type="date" id="search-date" autocomplete="off">
</div>

<div class="new-tables-container">
    <form id="create-table-form">
        <h2>Create New Table</h2>
        <div class="cue-input-field">
            <label for="customer_name">Table Name:*</label>
            <input type="text" name="customer_name" class="table-input" id="customer_name" placeholder="e.g Shahid Khan or M. Ali" required>
        </div>
        <div class="cue-input-field">
            <label for="customer_price">Rate per Min:*</label>
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
                    $('.new-tables-container').append(`
                        <div class="existing-table" data-customer-id="${data.customer_id}">
                            <div class="club-customer-info">
                                <p>Name: ${data.customer_name}</p>
                                <p>Rate: Rs. ${data.customer_rate}/min</p>
                            </div>
                            <div class="club-customer-contact">
                                <p>Mobile: ${data.customer_phone}</p>
                                <p>Email: ${data.customer_email}</p>
                            </div>
                            <div class="club-customer-checks">
                                <p>Check In Time: <br> <span class="check-in-time">${data.customer_check_in_time || 'N/A'}</span></p>
                                <div class="club-customer-checks-arrow">&#8594;</div>
                                <p>Check Out Time: <br> <span class="check-out-time">${data.customer_check_out_time || 'N/A'}</span></p>
                            </div>
                            <div class="stopwatch">
                                <span class="stopwatch-time">00:00:00</span>
                                <p>Total Price: Rs. <span class="total-price">0.00</span></p>
                            </div>
                            <form class="check-in-form">
                                <input type="hidden" name="customer_id" value="${data.customer_id}">
                                <input type="hidden" name="customer_rate" value="${data.customer_rate}">
                                <input type="submit" value="Check In" class="table-button">
                            </form>
                            <form class="check-out-form" style="display: none;">
                                <input type="hidden" name="customer_id" value="${data.customer_id}">
                                <input type="submit" value="Check Out" class="table-button">
                            </form>
                        </div>
                    `);
                    $('#create-table-form')[0].reset();
                } else {
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                alert('Error: Unable to process request.');
            }
        });
    });

    // Handle Check In Form
    $(document).on('submit', '.check-in-form', function(event) {
        event.preventDefault();
        const $form = $(this);
        const $table = $form.closest('.existing-table');
        const $stopwatch = $table.find('.stopwatch-time');
        const $totalPrice = $table.find('.total-price');
        const ratePerMin = parseFloat($form.find('input[name="customer_rate"]').val());
        const startTime = Date.now();
        $form.data('startTime', startTime);

        const timerInterval = setInterval(() => {
            const elapsedTime = Date.now() - startTime;
            const formattedTime = new Date(elapsedTime).toISOString().substr(11, 8);
            $stopwatch.text(formattedTime);
            const elapsedMinutes = elapsedTime / 60000;
            const totalPrice = (elapsedMinutes * ratePerMin).toFixed(2);
            $totalPrice.text(totalPrice);
        }, 1000);
        $form.data('timerInterval', timerInterval);

        $.ajax({
            url: 'check_in.php',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $table.find('.check-in-time').text(data.check_in_time);
                    $form.siblings('.check-out-form').show();
                    $form.hide();
                } else {
                    clearInterval(timerInterval);
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                clearInterval(timerInterval);
                alert('Error: Unable to process request.');
            }
        });
    });

    // Handle Check Out Form
    $(document).on('submit', '.check-out-form', function(event) {
        event.preventDefault();
        const $form = $(this);
        const $table = $form.closest('.existing-table');
        const startTime = $form.siblings('.check-in-form').data('startTime');
        const elapsedTime = Date.now() - startTime;
        clearInterval($form.siblings('.check-in-form').data('timerInterval'));
        const formattedTime = new Date(elapsedTime).toISOString().substr(11, 8);
        const ratePerMin = parseFloat($form.siblings('.check-in-form').find('input[name="customer_rate"]').val());
        const elapsedMinutes = elapsedTime / 60000;
        const totalPrice = (elapsedMinutes * ratePerMin).toFixed(2);
        $table.find('.stopwatch-time').text(formattedTime);
        $table.find('.total-price').text(totalPrice);

        $.ajax({
            url: 'check_out.php',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    $table.find('.check-out-time').text(data.check_out_time);
                    $form.hide();
                } else {
                    alert('Error: ' + data.error);
                }
            },
            error: function() {
                alert('Error: Unable to process request.');
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
                    <label for="customer_price">Rate per Min:*</label>
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
    $(document).ready(function() {
        $('#search-date').on("change", function() {
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
                },
                error: function() {
                    $("#ads-container").html("<h4>Error: Unable to process request.</h4>");
                }
            });
        });
    });
</script>
</body>
</html>

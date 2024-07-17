<?php
session_start();
include "conn.php";

if (!isset($_SESSION['club_email'])) {
    header("location: login.php");
    exit();
}

$form_submitted = false;
$check_in_done = false;
$check_out_done = false;
$cue_clubname = $_SESSION['clubname'];
$customer_id = null; // Variable to store customer ID

// Initialize variables to handle form submission and status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['table_created'])) {
        $form_submitted = true;
        $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
        $customer_rate = mysqli_real_escape_string($conn, $_POST['customer_price']);
        $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_mobile_no']);
        $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);

        // Insert new customer record
        $sql_customer_info = "INSERT INTO `{$cue_clubname}_customer` (`customer_name`, `customer_mobile_no`, `customer_price`, `customer_email`) VALUES ('{$customer_name}', '{$customer_phone}', '{$customer_rate}', '{$customer_email}')";
        $result_customer_info = mysqli_query($conn, $sql_customer_info);

        if ($result_customer_info) {
            $customer_id = mysqli_insert_id($conn);
            $check_in_done = false;
            $check_out_done = false;
        }
    } elseif (isset($_POST['check_in']) && isset($_POST['customer_id'])) {
        $customer_id = intval($_POST['customer_id']); // Ensure customer_id is an integer
        $sqli_customer_check_in = "UPDATE `{$cue_clubname}_customer` SET `customer_visit_date` = NOW(), `customer_check_in_time` = NOW() WHERE `customer_id` = {$customer_id}";
        $result_customer_check_in = mysqli_query($conn, $sqli_customer_check_in);
        if ($result_customer_check_in) {
            $check_in_done = true;
        }
    } elseif (isset($_POST['check_out']) && isset($_POST['customer_id'])) {
        $customer_id = intval($_POST['customer_id']); // Ensure customer_id is an integer
        $sqli_customer_check_out = "UPDATE `{$cue_clubname}_customer` SET `customer_check_out_time` = NOW() WHERE `customer_id` = {$customer_id}";
        $result_customer_check_out = mysqli_query($conn, $sqli_customer_check_out);
        if ($result_customer_check_out) {
            $check_out_done = true;
        }
        mysqli_close($conn);
    }
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
    <div class="new-table-add">
        <h3>Create Table</h3>
        <?php if (!$form_submitted): ?>
        <!-- Form 1 -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="add-new-table">
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
                <input type="submit" name="table_created" class="table-button" value="+ Create" id="create-button">
            </div>
        </form>
        <?php else: ?>
            <!-- Hidden field to pass customer_id if form is submitted -->
            <input type="hidden" id="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
        <?php endif; ?>

        <?php if ($form_submitted && !$check_in_done): ?>
        <!-- Section 2 Start-->
        <div class="new-table-section-2">
            <!-- Check In -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
                <input type="submit" name="check_in" value="Check In">
            </form>
        <?php endif; ?>

        <?php if ($form_submitted && $check_in_done && !$check_out_done): ?>
            <!-- Check out -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
                <input type="submit" value="Check out" name="check_out">
            </form>
        </div>
        <?php endif; ?>
        <!-- Section-2 End -->
    </div>
</div>

<script src="jquery.js"></script>
<script src="app.js"></script>
<!-- Live Search -->
<script>
    $(document).ready(function(){
        // For Search Bar of Date
        $('#search-date').on("keyup", function(){
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
    });
</script>
</body>
</html>

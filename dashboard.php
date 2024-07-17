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
<?php echo "Welcome, " . $_SESSION['clubname']; ?>

<div class="date-search-bar" >
        <label for="search">Select Date :</label>
        <input type="date" id="search-date" autocomplete="off"  placeholder="DD-MM-YYYY" >
</div>

<div class="new-tables-container">
    <div class="new-table-add">
        <h3>Create Table</h3>
        <?php
        $form_submitted = false;
        $cue_clubname = $_SESSION['clubname'];
        if (isset($_POST['table_created'])) {
            $form_submitted = true;
            include 'conn.php';
            $customer_name = $_POST['customer_name'];
            $customer_rate = $_POST['customer_price'];
            $customer_phone = $_POST['customer_mobile_no'];
            $customer_email = $_POST['customer_email'];

            $sql_customer_info = "INSERT INTO `{$cue_clubname}_customer` (`customer_name`, `customer_mobile_no`, `customer_price`, `customer_email`) VALUES ('{$customer_name}', '{$customer_phone}', '{$customer_rate}', '{$customer_email}')";
            $result_customer_info = mysqli_query($conn, $sql_customer_info);
        }

        if (!$form_submitted) {
        ?>
        <!-- Form 1 -->
        <form action="" method="post" class="add-new-table">
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
        <?php } ?>

        <?php
        if ($form_submitted && $result_customer_info) {
        ?>
        <!-- Section 2 Start-->
        <div class="new-table-section-2">
            <!-- Check In -->
            <form action="" method="post">
                <input type="date" name="customer_visit_date" style="display:none">
                <input type="time" name="customer_check_in_time" style="display:none">
                <input type="submit" name="check_in" value="Check In">
            </form>
            <!-- Check out -->
            <form action="" method="post">
                <input type="time" name="customer_check_out_time" style="display:none">
                <input type="submit" value="Check out" name="check_out">
            </form>
        </div>

        <?php
            if (isset($_POST['check_in'])) {
                $customer_visit_date = $_POST['customer_visit_date'];
                $customer_check_in = $_POST['customer_check_in_time'];
                $sqli_customer_check_in = "INSERT INTO `{$cue_clubname}_customer` (`customer_visit_date`, `customer_check_in_time`) VALUES ('{$customer_visit_date}', '{$customer_check_in}')";
                $result_customer_check_in = mysqli_query($conn, $sqli_customer_check_in);
            }

            if (isset($_POST['check_out'])) {
                $customer_check_out = $_POST['customer_check_out_time'];
                $sqli_customer_check_out = "INSERT INTO `{$cue_clubname}_customer` (`customer_check_out_time`) VALUES ('{$customer_check_out}')";
                $result_customer_check_out = mysqli_query($conn, $sqli_customer_check_out);
                mysqli_close($conn);
            }
        }
        ?>
        <!-- Section-2 End -->
    </div>
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

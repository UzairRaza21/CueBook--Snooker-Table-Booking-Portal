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

// Initialize or retrieve session variables
if (!isset($_SESSION['tables'])) {
    $_SESSION['tables'] = [];
}

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
            $_SESSION['tables'][$customer_id] = [
                'customer_id' => $customer_id,
                'form_submitted' => true,
                'check_in_done' => false,
                'check_out_done' => false
            ];
        }
    } elseif (isset($_POST['check_in']) && isset($_POST['customer_id'])) {
        $customer_id = intval($_POST['customer_id']); // Ensure customer_id is an integer
        $sqli_customer_check_in = "UPDATE `{$cue_clubname}_customer` SET `customer_visit_date` = NOW(), `customer_check_in_time` = NOW() WHERE `customer_id` = {$customer_id}";
        $result_customer_check_in = mysqli_query($conn, $sqli_customer_check_in);
        if ($result_customer_check_in) {
            $_SESSION['tables'][$customer_id]['check_in_done'] = true;
        }
    } elseif (isset($_POST['check_out']) && isset($_POST['customer_id'])) {
        $customer_id = intval($_POST['customer_id']); // Ensure customer_id is an integer
        $sqli_customer_check_out = "UPDATE `{$cue_clubname}_customer` SET `customer_check_out_time` = NOW() WHERE `customer_id` = {$customer_id}";
        $result_customer_check_out = mysqli_query($conn, $sqli_customer_check_out);
        if ($result_customer_check_out) {
            $_SESSION['tables'][$customer_id]['check_out_done'] = true;
        }
        mysqli_close($conn);
    }elseif (isset($_POST['create_another'])) {
        // Reset the form submission flag to allow creating another table
        $form_submitted = false;
    }
}

// Retrieve the state from session
$form_submitted = $form_submitted || isset($_SESSION['tables']);
// Get session variables
 $form_submitted = isset($_SESSION['form_submitted']) ? $_SESSION['form_submitted'] : false;
 $check_in_done = isset($_SESSION['check_in_done']) ? $_SESSION['check_in_done'] : false;
 $check_out_done = isset($_SESSION['check_out_done']) ? $_SESSION['check_out_done'] : false;
 $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
?>



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
                <input type="submit" name="table_created" class="table-button" value="Info Submit" id="create-button">
            </div>
        </form>
        <?php endif; ?>

        <?php foreach ($_SESSION['tables'] as $table): ?>
        <div class="existing-table">
            <?php if ($table['form_submitted'] && !$table['check_in_done']): ?>
            <!-- Check In -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($table['customer_id']); ?>">
                <input type="submit" name="check_in" value="Check In">
            </form>
            <?php endif; ?>

            <?php if ($table['form_submitted'] && $table['check_in_done'] && !$table['check_out_done']): ?>
            <!-- Check out -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($table['customer_id']); ?>">
                <input type="submit" value="Check out" name="check_out">
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if ($form_submitted): ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="submit" name="create_another" value="Create Another Table">
        </form>
        <?php endif; ?>
    </div>
<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = intval($_POST['customer_id']);
    $cue_clubname = $_SESSION['clubname'];
    $sqli_customer_check_in = "UPDATE `{$cue_clubname}_customer` SET `customer_visit_date` = NOW(), `customer_check_in_time` = NOW() WHERE `customer_id` = ?";
    
    if ($stmt = mysqli_prepare($conn, $sqli_customer_check_in)) {
        mysqli_stmt_bind_param($stmt, 'i', $customer_id);
        $result_customer_check_in = mysqli_stmt_execute($stmt);
        echo json_encode(['success' => $result_customer_check_in]);
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

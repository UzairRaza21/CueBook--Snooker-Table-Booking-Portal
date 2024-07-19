<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = intval($_POST['customer_id']);
    $cue_clubname = $_SESSION['clubname'];
    
    // Prepare SQL query to update check-out time
    $sqli_customer_check_out = "UPDATE `{$cue_clubname}_customer` 
                                SET `customer_check_out_time` = NOW() 
                                WHERE `customer_id` = ?";
    
    if ($stmt = mysqli_prepare($conn, $sqli_customer_check_out)) {
        mysqli_stmt_bind_param($stmt, 'i', $customer_id);
        $result_customer_check_out = mysqli_stmt_execute($stmt);
        
        // Fetch the new check-out time
        $sqli_check_out_time = "SELECT `customer_check_out_time` FROM `{$cue_clubname}_customer` WHERE `customer_id` = ?";
        if ($stmt_time = mysqli_prepare($conn, $sqli_check_out_time)) {
            mysqli_stmt_bind_param($stmt_time, 'i', $customer_id);
            mysqli_stmt_execute($stmt_time);
            mysqli_stmt_bind_result($stmt_time, $check_out_time);
            mysqli_stmt_fetch($stmt_time);
            mysqli_stmt_close($stmt_time);
        }

        echo json_encode(['success' => $result_customer_check_out, 'check_out_time' => $check_out_time]);
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

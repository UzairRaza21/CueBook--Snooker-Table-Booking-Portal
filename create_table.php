<?php
session_start();
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_rate = mysqli_real_escape_string($conn, $_POST['customer_price']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_mobile_no']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);

    $cue_clubname = $_SESSION['clubname'];
    $sql_customer_info = "INSERT INTO `{$cue_clubname}_customer` (`customer_name`, `customer_mobile_no`, `customer_price`, `customer_email`) VALUES (?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql_customer_info)) {
        mysqli_stmt_bind_param($stmt, 'ssss', $customer_name, $customer_phone, $customer_rate, $customer_email);
        $result_customer_info = mysqli_stmt_execute($stmt);
        if ($result_customer_info) {
            $customer_id = mysqli_insert_id($conn);
            echo json_encode([
                'success' => true,
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'customer_rate' => $customer_rate,
                'customer_phone' => $customer_phone,
                'customer_email' => $customer_email
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

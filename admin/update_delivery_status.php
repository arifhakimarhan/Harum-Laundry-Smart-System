<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../auth/db_connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'];
    $new_status = $_POST['new_status'];

    $sql_update_status = "
        UPDATE payments 
        SET delivery_status = :new_status 
        WHERE payment_id = :payment_id";
    $stmt_update_status = $pdo->prepare($sql_update_status);
    $stmt_update_status->bindParam(':new_status', $new_status, PDO::PARAM_STR);
    $stmt_update_status->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
    $stmt_update_status->execute();

    header('Location: admin_dashboard.php');
}

?>
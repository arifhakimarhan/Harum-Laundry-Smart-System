<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../auth/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["payment_id"])) {
    $payment_id = filter_var($_POST["payment_id"], FILTER_SANITIZE_NUMBER_INT);

    if (is_numeric($payment_id) && $payment_id > 0) {
        $sql = "UPDATE payments SET isReceive = 1 WHERE payment_id = :payment_id";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                header("Location: admin_dashboard.php?status=success");
                exit(); 
            } else {
                echo "Error updating payment: " . implode(" ", $stmt->errorInfo());
            }
        } else {
            echo "Error preparing SQL query: " . implode(" ", $pdo->errorInfo());
        }
    } else {
        echo "Invalid payment ID.";
    }
} else {
    echo "Payment ID not provided.";
}
?>

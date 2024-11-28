<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../auth/db_connect.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['new_status'];

    // Update the status in the database
    $sql_update_status = "UPDATE bookings SET status = :new_status WHERE booking_id = :booking_id";
    $stmt_update = $pdo->prepare($sql_update_status);
    $stmt_update->execute(['new_status' => $new_status, 'booking_id' => $booking_id]);

    // Redirect back to the bookings page (or any other page)
    header("Location: admin_dashboard.php");
    exit();
}
?>

<?php
include('auth/db_connect.php');

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    $sql = "SELECT s.price FROM bookings b 
            JOIN services s ON b.service_id = s.service_id 
            WHERE b.booking_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$booking_id]);

    if ($row = $stmt->fetch()) {
        echo json_encode(['price' => $row['price']]);
    } else {
        echo json_encode(['price' => 0]);
    }
}
?>

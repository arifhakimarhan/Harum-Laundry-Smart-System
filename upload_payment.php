<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('auth/db_connect.php');

try {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];

    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['payment_image']) && $_FILES['payment_image']['error'] == 0) {
        
        $targetDir = "uploads/";  

        $fileName = basename($_FILES["payment_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        if (in_array($fileType, $allowedTypes)) {

            if (move_uploaded_file($_FILES["payment_image"]["tmp_name"], $targetFilePath)) {
                
                $sql = "INSERT INTO payments (user_id, booking_id, amount, payment_image, payment_date, isReceive) 
                        VALUES (:user_id, :booking_id, :amount, :payment_image, NOW(), 0)";
                
                $stmt = $pdo->prepare($sql);
                
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':booking_id', $booking_id);
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':payment_image', $fileName);
                
                if ($stmt->execute()) {
                    echo "<script>
                            alert('Payment uploaded successfully!');
                            window.location.href = 'booking.php';
                          </script>";
                } else {
                    echo "<script>
                            alert('Failed to insert payment into the database.');
                            window.location.href = 'booking.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Failed to upload the payment image!');
                        window.location.href = 'booking.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Only JPG, JPEG, PNG, and GIF files are allowed.');
                    window.location.href = 'booking.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Please upload an image file.');
                window.location.href = 'booking.php';
              </script>";
    }
} catch (Exception $e) {
    echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href = 'booking.php';
          </script>";
}
?>

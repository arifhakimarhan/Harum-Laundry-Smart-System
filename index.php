<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include ('auth/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql_pending = "SELECT COUNT(*) AS pending_count FROM bookings WHERE user_id = ? AND status = 'Pending'";
$stmt_pending = $pdo->prepare($sql_pending);
$stmt_pending->bindParam(1, $user_id, PDO::PARAM_INT); 
$stmt_pending->execute();
$row_pending = $stmt_pending->fetch(PDO::FETCH_ASSOC);
$_SESSION['pending_booking'] = $row_pending['pending_count'];

$sql_total = "SELECT COUNT(*) AS total_count FROM bookings WHERE user_id = ?";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->bindParam(1, $user_id, PDO::PARAM_INT); 
$stmt_total->execute();
$row_total = $stmt_total->fetch(PDO::FETCH_ASSOC);
$_SESSION['booking_count'] = $row_total['total_count'];

$sql_in_progress = "SELECT COUNT(*) AS progress_count FROM bookings WHERE user_id = ? AND status = 'Confirmed'";
$stmt_in_progress = $pdo->prepare($sql_in_progress);
$stmt_in_progress->bindParam(1, $user_id, PDO::PARAM_INT); 
$stmt_in_progress->execute();
$row_in_progress = $stmt_in_progress->fetch(PDO::FETCH_ASSOC);
$_SESSION['laundry_progress'] = $row_in_progress['progress_count'];

$sql_in_progress = "SELECT COUNT(*) AS completed_count FROM bookings WHERE user_id = ? AND status = 'Completed'";
$stmt_in_progress = $pdo->prepare($sql_in_progress);
$stmt_in_progress->bindParam(1, $user_id, PDO::PARAM_INT); 
$stmt_in_progress->execute();
$row_in_progress = $stmt_in_progress->fetch(PDO::FETCH_ASSOC);
$_SESSION['completed'] = $row_in_progress['completed_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harum Laundry</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<style>
    
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    background-color: #f4f4f4;
}

/* Main Content Styling */
.content {
    margin-left: 0px;
    padding: 20px;
    width: calc(100% - 560px);
}

.laundry-name {
    font-size: 24px;
    font-weight: bold;
    color:#ddd;
    margin-bottom: 20px;
    text-align: center;
}

footer {
    text-align: center;
    color:#ddd;
    margin-top: 20px;
    font-size: 12px;
}
.navbar {
    background-color: #33444f ; /* Light gray background, change as needed */
    padding: 50px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional for slight shadow effect */
}

.navbar nav a {
    display: block; /* Stack links vertically */
    padding: 10px;
    margin: 5px 0;
    color: #ffff; /* Link text color */
    text-decoration: none; /* Remove underline */
    border-radius: 4px; /* Rounded corners for links */
    text-align: left;
}


.navbar nav a:hover {
    background-color: #ddd; /* Light hover effect */
    color: #000; /* Darker text on hover */
}


 </style>

<body>

    <div class="navbar">
        <div class="laundry-name">Harum Laundry</div>
        
        <nav>
        <a href="index.php">🏠 Home</a>
        <a href="booking.php">📅 Booking</a>
        <a href="pricing.php">💲 Pricing</a>
        <a href="contact.php">📞 Contact Us</a>
        <a href="user_settings.php">⚙️ User Setting</a>
        <a href="logout.php">🚪 Log Out</a>
        </nav>
        
        <footer>
            &copy; <?php echo date("Y"); ?> Harum Laundry
        </footer>
    </div>

    <div class="content">
    <div class="feature">
        <h2>Welcome: <?php echo $_SESSION['username']; ?></h2>

        <div class="slideshow-container">
            <div class="mySlides fade">
                <img src="img/abaya_icon.png" alt="Laundry Service 1" style="width:100px">
            </div>
            <div class="mySlides fade">
                <img src="img/jacket_icon.png" alt="Laundry Service 2" style="width:100px">
            </div>
            <div class="mySlides fade">
                <img src="img/dress_icon.png" alt="Laundry Service 3" style="width:100px">
            </div>
            <div class="mySlides fade">
                <img src="img/suits_icon.png" alt="Laundry Service 4" style="width:100px">
            </div>
            <div class="mySlides fade">
                <img src="img/shirt_icon.png" alt="Laundry Service 5" style="width:100px">
            </div>
        </div>

        <div class="status-grid">
            <div class="status-box">
                <h3>Pending Booking</h3>
                <p><?php echo isset($_SESSION['pending_booking']) ? $_SESSION['pending_booking'] : 0; ?></p>
            </div>
            <div class="status-box">
                <h3>Your Booking</h3>
                <p><?php echo isset($_SESSION['booking_count']) ? $_SESSION['booking_count'] : 0; ?></p>
            </div>
            <div class="status-box">
                <h3>Laundry In Progress</h3>
                <p><?php echo isset($_SESSION['laundry_progress']) ? $_SESSION['laundry_progress'] : 0; ?></p>
            </div>
            <div class="status-box">
                <h3>Completed</h3>
                <p><?php echo isset($_SESSION['completed']) ? $_SESSION['completed'] : 0; ?></p>
            </div>
        </div>
    </div>
</div>


</body>
</html>


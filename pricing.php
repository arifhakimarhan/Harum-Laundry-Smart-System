<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include ('auth/db_connect.php');

$sql = "SELECT * FROM services";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Pricing</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<style>

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
    background-color: #33444f; /* Light gray background, change as needed */
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
            <h2>Our Services and Pricing</h2>
            
            <div class="service-list">
                <?php while ($row = $result->fetch()): ?>
                    <div class="service-item">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['service_name']); ?>" class="service-image">

                        <h3><?php echo htmlspecialchars($row['service_name']); ?></h3>
                        <p>Price: RM <?php echo number_format($row['price'], 2); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

</body>
</html>

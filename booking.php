<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_no'])) {
    $_SESSION['user_no'] = '';  
}

include ('auth/db_connect.php');

$sql = "SELECT * FROM services";
$result = $pdo->query($sql); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $booking_date = $_POST['booking_date'];
    $booking_quantity = $_POST['booking_quantity']; // New addition
    $user_id = $_SESSION['user_id'];

    // Assuming you have a column `quantity` in your `bookings` table
    $sql = "INSERT INTO bookings (user_id, service_id, booking_date, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$user_id, $service_id, $booking_date, $booking_quantity])) {  // Include quantity in query
        $_SESSION['message'] = "Booking successfully created!";
    } else {
        $_SESSION['message'] = "Failed to create booking!";
    }

    header("Location: booking.php");
    exit();
}


$sql = "SELECT b.booking_id, b.booking_date, b.status, b.quantity, s.service_name, s.price 
        FROM bookings b 
        JOIN services s ON b.service_id = s.service_id 
        WHERE b.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<style>

.laundry-name {
    font-size: 24px;
    font-weight: bold;
    color:#ddd;
    margin-bottom: 20px;
    text-align: center;
}

/* Main Content Styling */
.content {
    margin-left: 0px;
    padding: 20px;
    width: calc(100% - 560px);
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
            <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <h3>Book a Service</h3>
            <form action="booking.php" method="POST">
                <div class="form-group">
                    <label for="service_id">Select Service</label>
                    <select name="service_id" id="service_id" required>
                    <option value="" disabled selected>--Please Select--</option>
                        <?php while ($row = $result->fetch()): ?>
                            <option value="<?php echo $row['service_id']; ?>">
                                <?php echo htmlspecialchars($row['service_name'] . ' - RM' . $row['price']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="booking_quantity">Quantity</label>
                    <input type="number" name="booking_quantity" id="booking_quantity" required>
                </div>
                <div class="form-group">
                    <label for="booking_date">Booking Date</label>
                    <input type="date" name="booking_date" id="booking_date" required>
                </div>
                <button type="submit">Book Now</button>
            </form>

            <h3>Your Bookings</h3>
            <div class="status-grid">
                <?php foreach ($bookings as $row): ?>
                    <div class="status-box">
                        <h3><?php echo htmlspecialchars($row['service_name']); ?></h3>
                        <p>Date: <?php echo htmlspecialchars($row['booking_date']); ?></p>
                        <p>Quantity: <?php echo htmlspecialchars($row['quantity']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>

                        <?php if ($row['status'] == 'Confirmed' && !isset($row['payment_image'])): ?>
                            <div class="status-box">
                                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                <button onclick="openModal(<?php echo $row['booking_id']; ?>)">Proceed to Pay</button>
                            </div>
                        <?php elseif ($row['status'] == 'Paid'): ?>
                            <button disabled>Waiting for Admin...</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Submit Payment</h2>
            <form action="upload_payment.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="booking_id" id="booking_id">
                
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" readonly><br><br>

                <label for="user_no">User Number:</label>
                <input type="text" name="user_no" value="<?php echo $_SESSION['user_no']; ?>" required><br><br>

                <label for="amount">Amount:</label>
                <input type="number" name="amount" step="0.01" required readonly><br><br>

                <label for="amount">Qr Payment:</label>
                <div style="width: 300px; height: 300px; background-image: url('img/qr.jpg'); background-size: cover; background-position: center;"></div>


                <label for="payment_image">Upload Payment Proof (image):</label>
                <input type="file" name="payment_image" accept="image/*"><br><br>

                <button type="submit" name="submit">Submit Payment</button>
            </form>
        </div>
    </div>
    
    <script>
       function openModal(bookingId) {
            document.getElementById('paymentModal').style.display = 'block';
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_booking_details.php?booking_id=' + bookingId, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    document.getElementById('booking_id').value = bookingId;
                    document.querySelector('input[name="amount"]').value = data.price;
                }
            };
            xhr.send();
        }

        function closeModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('paymentModal')) {
                closeModal();
            }
        }
    </script>

</body>
</html>

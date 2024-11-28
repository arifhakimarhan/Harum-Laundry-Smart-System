<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // If session is not set, redirect to login
    exit();
}

include('auth/db_connect.php');

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();

// If no user is found, redirect back to login
if (!$user) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Only update if a password is entered
    $address = $_POST['address']; // Corrected: Get the address from the form

    // Only update password if it's not empty
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, email = ?, password = ?, address = ? WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $hashed_password, $address, $_SESSION['username']]); // Fixed: Use $address
    } else {
        // Update only username, email, and address if password is not provided
        $sql = "UPDATE users SET username = ?, email = ?, address = ? WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $address, $_SESSION['username']]); // Fixed: Use $address
    }

    $_SESSION['message'] = "Settings successfully updated!";
    header("Location: user_settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
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
            <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>

            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <h3>Update Your Settings</h3>
            <form action="user_settings.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username"
                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" rows="4"
                        required><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="password">New Password (Leave empty to keep current password)</label>
                    <input type="password" name="password" id="password">
                </div>
                <button type="submit">Update Settings</button>
            </form>
        </div>
    </div>

</body>

</html>
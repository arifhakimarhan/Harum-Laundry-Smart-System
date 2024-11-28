<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../auth/db_connect.php');

$sql_payments = "SELECT SUM(amount) AS total_payments FROM payments";
$stmt_payments = $pdo->prepare($sql_payments);
$stmt_payments->execute();
$total_payments = $stmt_payments->fetch(PDO::FETCH_ASSOC);
$total_payments_amount = $total_payments['total_payments'] ? 'RM ' . number_format($total_payments['total_payments'], 2) : 'RM 0.00';

$sql_bookings = "SELECT COUNT(*) AS total_bookings FROM bookings";
$stmt_bookings = $pdo->prepare($sql_bookings);
$stmt_bookings->execute();
$total_bookings = $stmt_bookings->fetch(PDO::FETCH_ASSOC);
$total_bookings_count = $total_bookings['total_bookings'] ? $total_bookings['total_bookings'] : 0;

$sql_customers = "SELECT COUNT(*) AS total_customers FROM users WHERE user_type = 'customer'";
$stmt_customers = $pdo->prepare($sql_customers);
$stmt_customers->execute();
$total_customers = $stmt_customers->fetch(PDO::FETCH_ASSOC);
$total_customers_count = $total_customers['total_customers'] ? $total_customers['total_customers'] : 0;

$sql_payments = "
    SELECT 
        p.payment_id, 
        u.username, 
        p.booking_id, 
        p.amount, 
        p.payment_image, 
        p.payment_date,
        p.delivery_status 
    FROM payments p
    JOIN users u ON u.id = p.user_id
";
$stmt_payments = $pdo->prepare($sql_payments);
$stmt_payments->execute();
$payments = $stmt_payments->fetchAll(PDO::FETCH_ASSOC);


$sql_customers_details = "SELECT id, username, email, user_no, address FROM users WHERE user_type = 'customer'";
$stmt_customers_details = $pdo->prepare($sql_customers_details);
$stmt_customers_details->execute();
$customers = $stmt_customers_details->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">




<head>

<style>

/* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f7fc;
    color: #333;
    line-height: 1.6;
}

h1, h2, h3 {
    color: #4C4C4C;
}

/* Sidebar Styles */
.sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, #2C3E50 0%, #34495E 100%);
    color: white;
    padding: 30px 20px;
    position: fixed;
    transition: all 0.3s ease;
    box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
    font-weight: 700;
    color: #ecf0f1;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.sidebar ul {
    list-style: none;
    padding-left: 0;
}

.sidebar ul li {
    margin: 20px 0;
}

.sidebar ul li a {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 18px;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-radius: 6px;
    transition: background-color 0.3s ease, padding-left 0.3s ease;
}

.sidebar ul li a i {
    margin-right: 10px;
    font-size: 20px;
}

/* Icons */
.sidebar ul li a.dashboard i {
    content: "\f0e4"; /* Font Awesome Dashboard Icon */
}

.sidebar ul li a.customers i {
    content: "\f007"; /* Font Awesome User Icon */
}

.sidebar ul li a.payments i {
    content: "\f09d"; /* Font Awesome Credit Card Icon */
}

.sidebar ul li a.bookings i {
    content: "\f1d3"; /* Font Awesome Calendar Icon */
}

.sidebar ul li a.logout i {
    content: "\f2f5"; /* Font Awesome Sign-Out Icon */
}

/* Hover Effects */
.sidebar ul li a:hover {
    background-color: #1abc9c;
    padding-left: 30px;
}

.sidebar ul li a.active {
    background-color: #16a085;
}

/* Responsive Sidebar */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        box-shadow: none;
    }

    .sidebar ul li a {
        padding: 10px 15px;
        font-size: 16px;
    }

    .sidebar h2 {
        font-size: 20px;
    }
}



/* Main Content Area */
main.content {
    margin-left: 260px;
    padding: 20px;
}

.dashboard-cards {
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
}

.card {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 30%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.card h3 {
    font-size: 24px;
    margin-bottom: 10px;
}

.card p {
    font-size: 20px;
    color: #27ae60;
    font-weight: bold;
}

/* Tables */
table {
    width: 100%;
    margin-bottom: 40px;
    border-collapse: collapse;
}

table th, table td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
}

table th {
    background-color: #2980b9;
    color: white;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tr:hover {
    background-color: #e0e0e0;
}

table td img {
    width: 100px;
    border-radius: 5px;
}

/* Form Buttons */
button {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #2980b9;
}

form {
    display: inline-block;
}

/* Section Titles */
section h2 {
    margin-bottom: 20px;
    font-size: 28px;
    color: #34495e;
}

/* Customer and Booking Sections */
#customers, #payments, #bookings {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Alert Styles for Empty Results */
td[colspan="8"], td[colspan="6"] {
    text-align: center;
    font-style: italic;
    color: #7f8c8d;
}

/* Responsive Design for Smaller Screens */
@media screen and (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    main.content {
        margin-left: 0;
    }

    .dashboard-cards {
        flex-direction: column;
    }

    .card {
        width: 100%;
        margin-bottom: 20px;
    }

    table th, table td {
        padding: 10px;
        font-size: 14px;
    }

    table img {
        width: 80px;
    }
}


</style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <aside class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
        <li><a href="#dashboard" class="dashboard"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
        <li><a href="#customers" class="customers"><i class="fas fa-users"></i>Customer Details</a></li>
        <li><a href="#payments" class="payments"><i class="fas fa-credit-card"></i>Payments</a></li>
        <li><a href="#bookings" class="bookings"><i class="fas fa-calendar-alt"></i>Bookings</a></li>
        <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
        </ul>
    </aside>

    <main class="content">
        <section id="dashboard">
            <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total Payments</h3>
                    <p><?php echo $total_payments_amount; ?></p>
                </div>
                <div class="card">
                    <h3>Total Bookings</h3>
                    <p><?php echo $total_bookings_count; ?></p>
                </div>
                <div class="card">
                    <h3>Total Customers</h3>
                    <p><?php echo $total_customers_count; ?></p>
                </div>
            </div>
        </section>
        <section id="customers">
            <h2>Customer Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User No Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($customers) > 0) {
                        foreach ($customers as $customer) {
                            echo "<tr>";
                            echo "<td>" . $customer["id"] . "</td>";
                            echo "<td>" . $customer["username"] . "</td>";
                            echo "<td>" . $customer["email"] . "</td>";
                            echo "<td>" . $customer["user_no"] . "</td>";
                            echo "<td>" . $customer["address"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No customers found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <section id="payments">
    <h2>Payment Details</h2>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Username</th>
                <th>Booking ID</th>
                <th>Amount</th>
                <th>Payment Image</th>
                <th>Payment Date</th>
                <th>Delivery Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    echo "<tr>";
                    echo "<td>" . $payment["payment_id"] . "</td>";
                    echo "<td>" . $payment["username"] . "</td>";
                    echo "<td>" . $payment["booking_id"] . "</td>";
                    echo "<td>RM " . number_format($payment["amount"], 2) . "</td>";
                    
                    // Wrap the image in an <a> tag
                    echo "<td><a href='../uploads/" . $payment["payment_image"] . "' target='_blank'>
                                <img src='../uploads/" . $payment["payment_image"] . "' alt='Payment Image' width='100'>
                            </a></td>";
                    
                    echo "<td>" . $payment["payment_date"] . "</td>";
                    echo "<td>" . $payment["delivery_status"] . "</td>";

                    if ($payment["delivery_status"] == 'Received') {
                        echo "<td>";
                        echo "<form method='POST' action='update_delivery_status.php' style='display:inline-block;'>
                                <input type='hidden' name='payment_id' value='" . $payment["payment_id"] . "'>
                                <input type='hidden' name='new_status' value='Ready to Deliver'>
                                <button type='submit'>Mark as Ready to Deliver</button>
                              </form>";
                        echo "</td>";
                    } else {
                        echo "<td>Ready for Delivery</td>";
                    }

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No payments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>



        <section id="bookings">
            <h2>Customer Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Username</th>
                        <th>Service ID</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_bookings_details = "
                        SELECT 
                            b.booking_id, 
                            u.username, 
                            b.service_id, 
                            b.booking_date, 
                            b.status,
                            b.quantity
                        FROM bookings b
                        JOIN users u ON u.id = b.user_id
                    ";
                    $stmt_bookings_details = $pdo->prepare($sql_bookings_details);
                    $stmt_bookings_details->execute();
                    $bookings = $stmt_bookings_details->fetchAll(PDO::FETCH_ASSOC);

                    if (count($bookings) > 0) {
                        foreach ($bookings as $booking) {
                            echo "<tr>";
                            echo "<td>" . $booking["booking_id"] . "</td>";
                            echo "<td>" . $booking["username"] . "</td>";
                            echo "<td>" . $booking["service_id"] . "</td>";
                            echo "<td>" . $booking["booking_date"] . "</td>";
                            echo "<td>" . $booking["status"] . "</td>";
                            echo "<td>" . $booking["quantity"] . "</td>";
                            echo "<td>"; 

                            if ($booking["status"] == 'Pending') {
                                echo "<form method='POST' action='update_status.php' style='display:inline-block;'>
                                        <input type='hidden' name='booking_id' value='" . $booking["booking_id"] . "'>
                                        <input type='hidden' name='new_status' value='Confirmed'>
                                        <button type='submit'>To Confirm</button>
                                    </form>";
                            } elseif ($booking["status"] == 'Confirmed') {
                                echo "<form method='POST' action='update_status.php' style='display:inline-block;'>
                                        <input type='hidden' name='booking_id' value='" . $booking["booking_id"] . "'>
                                        <input type='hidden' name='new_status' value='Completed'>
                                        <button type='submit'>To Complete</button>
                                    </form>";
                            } else {
                                echo "Done"; 
                            }

                            echo "</td>"; 
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No bookings found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>


    </main>
</body>

</html>
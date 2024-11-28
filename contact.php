<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles/style.css">
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



        /* Additional styles for the FAQ section */
        .faq-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .faq-section h2 {
            font-size: 2em;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .faq-item {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: background-color 0.3s ease-in-out, max-height 0.5s ease-in-out;
            max-height: 50px; /* Collapsed height */
            overflow: hidden;
        }

        .faq-item h3 {
            font-size: 1.3em;
            color: #555;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .faq-item p {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .faq-item:hover {
            background-color: #f1f1f1;
            max-height: 200px; /* Expanded height */
        }

        .faq-item:hover p {
            opacity: 1;
            transform: translateY(0);
        }

        .faq-item h3:hover {
            color: #0056b3;
        }
    </style>
</head>
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
            <h2>Contact Us</h2>

            <div class="contact-details">
                <div class="contact-info">
                    <h4>Our Store</h4>
                    <p><strong>Address:</strong> 97, Jln Diplomatik, Presint 15,<br>
                    62050 Putrajaya, Wilayah Persekutuan Putrajaya,</p>
                    <p><strong>Phone:</strong> 03-3326654</p>
                    <p><strong>Email:</strong> harumlaundry@gmail.com</p>
                </div>
            </div>
            
            <div class="contact-section">
                <form action="#" method="post" class="contact-form">
                    <h4>Send us a message:</h4>
                    <p>We'd love to hear from you! You can reach us via the following methods:</p>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit">Send Message</button>
                </form>
                <div class="map-container">
                    <h4>Find Us Here</h4>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3787.3942326487822!2d101.72200717472941!3d2.945771397030476!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdc9f21a42a813%3A0x3d320283fc15b45b!2sKedai%20Dobi%20Harum!5e1!3m2!1sen!2smy!4v1731524675811!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- FAQ Section -->
           <!-- FAQ Section -->
<div class="faq-section">
    <h2>Frequently Asked Questions</h2>

    <div class="faq-item">
        <h3>What are your operating hours?</h3>
        <p>We are open Monday to Sunday from 9 AM to 9 PM. We are closed on Wednesday.</p>
    </div>

    <div class="faq-item">
        <h3>How do I book a laundry service?</h3>
        <p>You can book a service through our website on the 'Booking' page or contact us directly at 03-3326654.</p>
    </div>

    <div class="faq-item">
        <h3>Do you offer delivery services?</h3>
        <p>Yes, we offer delivery services within the local area. Please contact us for more details.</p>
    </div>

    <div class="faq-item">
        <h3>What types of laundry services do you offer?</h3>
        <p>We offer dry cleaning, regular washing, ironing, and folding services for clothes and household items.</p>
    </div>

    <div class="faq-item">
        <h3>What is your pricing?</h3>
        <p>Please refer to our 'Pricing' page for detailed information on our rates.</p>
    </div>

    <div class="faq-item">
        <h3>Do you handle delicate or special fabrics?</h3>
        <p>Yes, we handle delicate and special fabrics with care. Please inform our staff if any special handling is required.</p>
    </div>

    <div class="faq-item">
        <h3>Can I track my laundry order?</h3>
        <p>Yes, you can track your laundry order through our website or by contacting our customer service.</p>
    </div>

    <div class="faq-item">
        <h3>What should I do if I need same-day service?</h3>
        <p>Please contact us as early as possible to check the availability of same-day services.</p>
    </div>

    <div class="faq-item">
        <h3>Do you offer bulk laundry services for businesses?</h3>
        <p>Yes, we provide bulk laundry services for businesses, including hotels, gyms, and restaurants. Contact us for pricing and details.</p>
    </div>

    <div class="faq-item">
        <h3>What payment methods do you accept?</h3>
        <p>We accept online payment options like e-wallets and Qr Payment.</p>
    </div>

    <div class="faq-item">
        <h3>What is your return policy for damaged items?</h3>
        <p>If an item is damaged during our service, please report it immediately. We will assess the situation and offer a fair resolution.</p>
    </div>

    <div class="faq-item">
        <h3>How do I provide feedback about your services?</h3>
        <p>You can provide feedback through our review on our social media pages like google.</p>
    </div>
</div>


        </div>
    </div>

</body>
</html>

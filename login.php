<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('auth/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type']; 

            if ($_SESSION['user_type'] == 'admin') {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harum Laundry - User Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: url('img/gambar_login.png') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
        overflow: hidden;
    }

    .main-content {
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Aligns content to the top */
        position: relative;
        height: 100%;
        width: 100%;
        padding-top: 70px; /* Adds space from the top */
    }

    /* Slide Container */
    .slide-container {
        position: absolute;
        top: 10;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .slide {
        display: none;
        text-align: center;
    }

    .slide img {
        width: 100%;
        max-width: 150%;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .slide.active {
        display: block;
    }

    /* Dot Navigation */
    .dot-container {
        position: absolute;
        bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1;
    }

    .dot {
        height: 12px;
        width: 12px;
        margin: 0 5px;
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
    }

    .dot.active {
        background-color: #764ba2;
    }

    /* Login Container */
    .login-container {
        padding: 40px;
        max-width: 400px;
        width: 100%;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.85);
        border-radius: 15px;
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
    }

    .login-container .welcome-heading {
        font-size: 24px;
        color: #764ba2;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .login-container h2 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #333;
    }

    .login-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .login-container label {
        font-size: 14px;
        color: #555;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 10px;
    }

    .login-container input[type="submit"] {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
    }

    .login-container input[type="submit"]:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
    }

    .login-container .error {
        color: #ff4d4d;
        font-size: 14px;
        text-align: center;
        margin-top: 10px;
    }

    .login-container .signup-link {
        font-size: 14px;
        color: #764ba2;
        margin-top: 20px;
        text-decoration: none;
    }

    .login-container .signup-link:hover {
        color: #667eea;
        text-decoration: underline;
    }
</style>
<body>

<div class="main-content">
    <!-- Slide Container -->
    <div class="slide-container">
        <div class="slide active">
            <img src="img/slide5.png" alt="Slide 1">
        </div>
        <div class="slide">
            <img src="img/slide6.png" alt="Slide 2">
        </div>
        <div class="slide">
            <img src="img/slide7.png" alt="Slide 3">
        </div>

        <div class="slide">
            <img src="img/slide8.png" alt="Slide 4">
        </div>

        <div class="slide">
            <img src="img/slide9.png" alt="Slide 4">
        </div>

        <div class="slide">
            <img src="img/slide10.png" alt="Slide 4">
        </div>

        <div class="slide">
            <img src="img/slide11.png" alt="Slide 4">
        </div>



    </div>

    <!-- Login Container -->
    <div class="login-container">
        <p class="welcome-heading">WELCOME TO HARUM LAUNDRY</p>
        <h2>Login to Your Account</h2>
        
        <?php 
        if (isset($error_message)) {
            echo "<p class='error'>" . htmlspecialchars($error_message) . "</p>";
        }
        ?>

        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>
        
        <a href="signup.php" class="signup-link">Don't have an account? Sign up</a>
    </div>
</div>

<!-- Dot Navigation -->
<div class="dot-container">
    <div class="dot active" onclick="goToSlide(0)"></div>
    <div class="dot" onclick="goToSlide(1)"></div>
    <div class="dot" onclick="goToSlide(2)"></div>
    <div class="dot" onclick="goToSlide(3)"></div>
    <div class="dot" onclick="goToSlide(4)"></div>
    <div class="dot" onclick="goToSlide(5)"></div>
    <div class="dot" onclick="goToSlide(6)"></div>

</div>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    function goToSlide(index) {
        currentSlide = index;
        showSlide(currentSlide);
    }

    // Auto slide every 5 seconds
    setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 5000);

    showSlide(currentSlide);
</script>

</body>
</html>

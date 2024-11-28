<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('auth/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_no = trim($_POST['user_no']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $address = trim($_POST['address']);  // New address field

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Check if username or email already exists
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error_message = "Username or email already exists!";
        } else {
            // Insert the new user into the database with user_type 'customer' and address
            $sql = "INSERT INTO users (user_no, username, email, password, address, user_type) VALUES (:user_no, :username, :email, :password, :address, 'customer')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_no', $user_no);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // Store password as plain text (for demo purposes only)
            $stmt->bindParam(':address', $address);   // Bind address parameter

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = 'customer';
                header("Location: index.php");
                exit();
            } else {
                $error_message = "There was an error creating your account.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Harum Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Body Styling with background image */
    body {
        font-family: 'Poppins', sans-serif;
        background: url('img/gambar_login.png') no-repeat center center fixed; /* Replace 'your-image.jpg' with the path to your background image */
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
    }

    /* Container for the form */
    .container {
        background-color: rgba(255, 255, 255, 0.85); /* Slight transparency to allow background image to show through */
        border-radius: 15px;
        padding: 40px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }

    .container:hover {
        transform: translateY(-5px);
        box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.3);
    }

    /* Heading */
    h2 {
        font-size: 28px;
        color: #764ba2;
        font-weight: bold;
        margin-bottom: 15px;
    }

    /* Form Styling */
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        font-size: 14px;
        color: #555;
        text-align: left;
        margin-bottom: -5px;
    }

    /* Input Fields */
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #764ba2;
        outline: none;
    }

    /* Submit Button */
    input[type="submit"] {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
    }

    /* Error Message */
    .error {
        color: #ff4d4d;
        font-size: 14px;
        text-align: center;
        margin-top: 10px;
    }

    /* Sign in Link */
    .signin-link {
        text-align: center;
        font-size: 14px;
        color: #764ba2;
        margin-top: 20px;
        text-decoration: none;
    }

    .signin-link:hover {
        color: #667eea;
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 600px) {
        .container {
            padding: 30px;
        }

        h2 {
            font-size: 24px;
        }
    }
</style>
<body>

    <div class="container">
        <h2>Create an Account</h2>
        
        <?php 
        if (isset($error_message)) {
            echo "<p class='error'>" . htmlspecialchars($error_message) . "</p>";
        }
        ?>

        <form action="signup.php" method="POST">
            <label for="user_no">User No:</label>
            <input type="text" name="user_no" required>

            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="address">Address:</label> <!-- New field for address -->
            <input type="text" name="address" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" value="Sign Up">
        </form>
        
        <a href="login.php" class="signin-link">Already have an account? Login here</a>
    </div>

</body>
</html>

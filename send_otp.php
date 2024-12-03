<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "u247141684_vosys";
$password = "vosysOlshco5";
$database = "u247141684_votesystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate OTP
$otp = mt_rand(100000, 999999);

// Email sending logic
$email = $_POST['email'];
$subject = 'OTP Verification';
$message = 'Your OTP is: ' . $otp;

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email format';
    exit();
}

// Send email (basic example, for better results consider using a library like PHPMailer)
if (mail($email, $subject, $message)) {
    // Store OTP in database using prepared statement
    $stmt = $conn->prepare("INSERT INTO otp_verifcation (email, otp) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $otp); // Bind email and OTP as strings

    if ($stmt->execute()) {
        // Store OTP in session (for validation on next step)
        $_SESSION['otp'] = $otp;
        echo 'OTP sent successfully and stored in database';
    } else {
        echo 'Failed to store OTP in database';
    }

    // Close statement
    $stmt->close();
} else {
    echo 'Failed to send OTP';
}

// Close connection
$conn->close();
?>

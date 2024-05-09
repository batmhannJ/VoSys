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

// Send email
if (mail($email, $subject, $message)) {
    // Store OTP in database
    $sql = "INSERT INTO otp_verifcation (email, otp) VALUES ('$email', '$otp')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['otp'] = $otp;
        echo 'OTP sent successfully and stored in database';
    } else {
        echo 'Failed to store OTP in database';
    }
} else {
    echo 'Failed to send OTP';
}

// Close connection
$conn->close();
?>

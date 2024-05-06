<?php
session_start();

// Include your database connection file
include 'includes/conn.php';

// Generate OTP
$otp = mt_rand(100000, 999999);

// Email sending logic
$email = $_POST['email'];
$subject = 'OTP Verification';
$message = 'Your OTP is: ' . $otp;

if (mail($email, $subject, $message)) {
    // Store OTP in the database
    $stmt = $conn->prepare("INSERT INTO otp_verification (email, otp) VALUES (?, ?)");
    $stmt->bind_param("si", $email, $otp); // Bind parameters
    if ($stmt->execute()) {
        $_SESSION['success'] = 'OTP sent successfully';
        echo 'OTP sent successfully';
    } else {
        $_SESSION['error'] = 'Failed to store OTP in the database';
        echo 'Failed to store OTP in the database';
    }
} else {
    $_SESSION['error'] = 'Failed to send OTP';
    echo 'Failed to send OTP';
}
?>

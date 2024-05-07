<?php
session_start();

// Generate OTP
$otp = mt_rand(100000, 999999);

// Email sending logic
$email = $_POST['email'];
$subject = 'OTP Verification';
$message = 'Your OTP is: ' . $otp;

if (mail($email, $subject, $message)) {
    $_SESSION['otp'] = $otp;
    echo 'OTP sent successfully';
} else {
    echo 'Failed to send OTP';
}
?>

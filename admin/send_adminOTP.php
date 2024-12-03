<?php
session_start();

// Static email address (as specified)
$email = 'reyeshannahjoy82@gmail.com';

// Database connection
$connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Generate OTP
$otp = mt_rand(100000, 999999);

// Check if email already exists in the otp_verification table
$query_check = "SELECT * FROM otp_verifcation WHERE email = ?";
$stmt_check = mysqli_prepare($connection, $query_check);
mysqli_stmt_bind_param($stmt_check, "s", $email);
mysqli_stmt_execute($stmt_check);
$result = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result) > 0) {
    // Update existing OTP
    $query_update = "UPDATE otp_verifcation SET otp = ?, created_at = NOW() WHERE email = ?";
    $stmt_update = mysqli_prepare($connection, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ss", $otp, $email);
    mysqli_stmt_execute($stmt_update);
} else {
    // Insert new OTP
    $query_insert = "INSERT INTO otp_verifcation (email, otp, created_at) VALUES (?, ?, NOW())";
    $stmt_insert = mysqli_prepare($connection, $query_insert);
    mysqli_stmt_bind_param($stmt_insert, "ss", $email, $otp);
    mysqli_stmt_execute($stmt_insert);
}

// Send email
$subject = 'Password Reset Token';
$message = "Your OTP is: $otp\n\nIf you did not request this, please ignore this email.";
$headers = 'From: noreply@example.com'; // Change to your system's email

if (mail($email, $subject, $message, $headers)) {
    echo "OTP sent successfully to $email.";
} else {
    echo "Failed to send OTP. Please check your email configuration.";
}

// Close database connections
mysqli_close($connection);
?>

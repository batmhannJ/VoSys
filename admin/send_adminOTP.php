<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the email is fixed to "reyeshannahjoy82@gmail.com"
    $allowedEmail = "reyeshannahjoy82@gmail.com";
    $email = $allowedEmail; // Fixing email

    // Database connection
    $connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Generate OTP
    $otp = mt_rand(100000, 999999);

    // Insert OTP into database
    $query = "INSERT INTO otp_verification (email, otp, created_at) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare statement failed: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute statement failed: " . mysqli_error($connection));
    }

    // Send email with OTP
    $subject = 'OTP Verification';
    $message = "Hello, your OTP is: $otp.\n\nUse this code to reset your password.";
    $headers = "From: no-reply@yourdomain.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "OTP sent successfully to $email.";
    } else {
        echo "Failed to send OTP.";
    }

    // Close connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo "Invalid request.";
}
?>
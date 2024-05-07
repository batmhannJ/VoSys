<?php
session_start();
include 'includes/header.php'; // Include your header file

// Ensure database connection is established before proceeding
// Replace "your_database_connection" with your actual database connection code

if (isset($_POST['email']) && isset($_POST['otp'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    
    // Assuming you have a database connection established
    // Replace this with your actual database logic to check if the OTP is correct using prepared statements
    $query = "SELECT * FROM otp_verifcation WHERE email = ? AND otp = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Redirect to change password page if OTP is correct
        header("Location: change_pass.php");
        exit();
    } else {
        // Show generic error message
        echo 'Incorrect email or OTP';
    }
} else {
    // If email or OTP parameter is missing
    echo 'Missing email or OTP parameter';
}
?>

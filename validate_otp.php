<?php
session_start();
include 'includes/header.php'; // Include your header file

// Ensure database connection is established before proceeding
// Replace "your_database_connection" with your actual database connection code

if (isset($_POST['email']) && isset($_POST['otp'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Assuming you have a database connection established
    // Replace this with your actual database connection code
    $connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Assuming you have a table named "otp_verification"
    $query = "SELECT * FROM otp_verification WHERE email = ? AND otp = ?";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare statement failed: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute statement failed: " . mysqli_error($connection));
    }
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("Get result failed: " . mysqli_error($connection));
    }
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Redirect to change password page if OTP is correct
        header("Location: change_pass.php");
        exit();
    } else {
        // Show generic error message
        echo 'Incorrect email or OTP';
    }

    // Close database connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    // If email or OTP parameter is missing
    echo 'Missing email or OTP parameter';
}
?>

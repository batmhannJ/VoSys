<?php
session_start();

if (isset($_POST['email'])) { // Only check for email here
    $email = $_POST['email'];

    $connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Generate OTP
    $otp = mt_rand(100000, 999999);

    // Assuming you have a table named "otp_verification"
    $query = "INSERT INTO otp_verifcation (email, otp) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare statement failed: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute statement failed: " . mysqli_error($connection));
    }

    // Send email
    $subject = 'OTP Verification';
    $message = 'Your OTP is: ' . $otp;
    if (mail($email, $subject, $message)) {
        $_SESSION['otp'] = $otp; // Store OTP in session for further validation
        echo 'OTP sent successfully';
    } else {
        echo 'Failed to send OTP';
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} else {
    // If email parameter is missing
    die('Missing email parameter');
}
?>

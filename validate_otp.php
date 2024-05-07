<?php
session_start();

// Check if email and OTP parameters are received
if (isset($_POST['email']) && isset($_POST['otp'])) {
    // Assuming you have a database connection established
    // Replace this with your actual database logic to check if the OTP is correct
    
    // Retrieve the email and OTP from the POST data
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    
    
    $query = "SELECT * FROM otp_verifcation WHERE email = '$email' AND otp = '$otp'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        echo 'OTP correct';
    } else {
        echo 'Incorrect OTP';
    }

    // For this example, let's assume the OTP is correct if it matches "123456"
    if ($otp === '123456') {
        echo 'OTP correct';
    } else {
        echo 'Incorrect OTP';
    }
} else {
    // If email or OTP parameter is missing
    echo 'Missing email or OTP parameter';
}
?>

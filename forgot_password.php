<?php
session_start();

// Include PHPMailer autoload file
require 'vendor/autoload.php';

// Function to send OTP email
function sendOTP($email, $otp) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Set to true to enable exceptions

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'olshco.electionupdates@gmail.com'; // Your SMTP username
        $mail->Password = 'ljzujblsyyprijmx'; // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; // Port for TLS

        // Email content
        $mail->setFrom('olshco.electionupdates@gmail.com', 'EL-UPS OLSHCO');
        $mail->addAddress($email);
        $mail->Subject = 'OTP Verification';
        $mail->Body = 'Your OTP is: ' . $otp;

        // Send email
        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        // Log error
        error_log('Error sending email: ' . $mail->ErrorInfo);
        echo 'Exception Message: ' . $e->getMessage(); // Output exception message for debugging
        return false; // Failed to send email
    }
}

// Log received email parameter
$email = $_POST['email'] ?? null;
error_log('Received email: ' . $email); // Log email value received

// Check if email parameter is received
if ($email) {
    $otp = mt_rand(100000, 999999); // Generate a random OTP
    if (sendOTP($email, $otp)) {
        echo 'OTP sent successfully';
    } else {
        echo 'Failed to send OTP';
    }
} else {
    echo 'No email parameter received';
}
?>

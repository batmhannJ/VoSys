<?php
session_start();

// Include PHPMailer autoload file
require 'vendor/autoload.php';

// Function to send OTP email
function sendOTP($email, $otp) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    // Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

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
    if ($mail->send()) {
        return true; // Email sent successfully
    } else {
        // Log error
        error_log('Error sending email: ' . $mail->ErrorInfo);
        return false; // Failed to send email
    }
}

// Function to handle form submission
function handleFormSubmission() {
    // Include your database connection file
    include 'includes/conn.php';

    if (isset($_POST['resetPass'])) {
        $email = $_POST['email'];
        $entered_otp = $_POST['otp']; // Get OTP entered by the user

        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM voters WHERE email = ?");
        $stmt->bind_param("s", $email); // Bind parameter
        $stmt->execute();
        $result = $stmt->get_result(); // Get result

        if ($result->num_rows > 0) {
            // Retrieve the OTP sent via email
            $stmt = $conn->prepare("SELECT otp FROM otp_verification WHERE email = ?");
            $stmt->bind_param("s", $email); // Bind parameter
            $stmt->execute();
            $otp_result = $stmt->get_result();

            if ($otp_result->num_rows > 0) {
                $row = $otp_result->fetch_assoc();
                $stored_otp = $row['otp'];

                // Verify if the entered OTP matches the one sent via email
                if ($entered_otp == $stored_otp) {
                    // Redirect to change_pass.php if OTP is correct
                    header("Location: change_pass.php?email=$email");
                    exit();
                } else {
                    $_SESSION['error'] = "Incorrect OTP. Please try again.";
                    header("Location: forgot_password.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "No OTP found for this email. Please try again.";
                header("Location: forgot_password.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Email not found. Please try again.";
            header("Location: forgot_password.php");
            exit();
        }
    }
}

// Main code
handleFormSubmission();
?>

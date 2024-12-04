<?php
session_start();

// Directly use the fixed email
$email = "reyeshannahjoy82@gmail.com";

try {
    // Generate token
    $token = bin2hex(random_bytes(16));

    // Prepare the reset link
    $resetLink = "http://vosys/admin/reset_password.php?token=$token";
    $subject = "Reset Your Password";
    $message = "Click the link below to reset your password:\n\n";
    $message .= "Yes, it is me: $resetLink\n\n";
    $message .= "If you did not request this, you can safely ignore this email.";
    $headers = "From: no-reply@yourwebsite.com";

    // Send email
    if (mail($email, $subject, $message, $headers)) {
        $_SESSION['success'] = "Reset link has been sent to the specified email.";
    } else {
        $_SESSION['error'] = "Failed to send email. Please try again.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred: " . $e->getMessage();
}

// Redirect back to the form page
header("Location: osa_forgotpass.php");
exit;

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fixedEmail = "reyeshannahjoy82@gmail.com";

    // Generate token
    $token = bin2hex(random_bytes(16));

    // (Optional) Add logic to save the token in the database or handle reset requests.

    // Send email
    $resetLink = "http://vosys/admin/reset_password.php?token=$token";
    $subject = "Reset Your Password";
    $message = "Click the link below to reset your password:\n\n";
    $message .= "$resetLink\n\n";
    $message .= "If you did not request this, you can safely ignore this email.";
    $headers = "From: no-reply@yourwebsite.com";

    if (mail($fixedEmail, $subject, $message, $headers)) {
        $_SESSION['success'] = "A reset link has been sent to your email.";
    } else {
        $_SESSION['error'] = "Failed to send the reset link. Please try again.";
    }

    header("Location: osa_forgotpass.php");
    exit;
}
?>

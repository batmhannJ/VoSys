<?php
session_start();
include 'includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fixedEmail = "reyeshannahjoy82@gmail.com";

    // Generate token
    $token = bin2hex(random_bytes(16));

    // URL for "Yes, it is me"
    $resetLink = "http://vosys.org/admin/defaultpass_admin.php?token=$token";

    // URL for "Deny"
    $denyLink = "http://vosys.org/admin/deny_reset.php?email=$fixedEmail";

    // Email content
    $subject = "Reset Your Password";
    $message = "
        <html>
        <head>
            <title>Reset Your Password</title>
        </head>
        <body>
            <p>Someone requested a password reset for your account. If this was you, click the button below:</p>
            <a href='$resetLink' style='
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
            '>Yes, it is me</a>
            <br><br>
            <p>If you did not request this, click the button below to deny the request:</p>
            <a href='$denyLink' style='
                display: inline-block;
                background-color: #f44336;
                color: white;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
            '>Deny</a>
        </body>
        </html>
    ";

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: VoSysTeam@vosys.org";

    // Send email
    if (mail($fixedEmail, $subject, $message, $headers)) {
        $_SESSION['success'] = "A reset link has been sent to your email.";
    } else {
        $_SESSION['error'] = "Failed to send the reset link. Please try again.";
    }

    header("Location: osa_forgotpass.php");
    exit;
}
?>

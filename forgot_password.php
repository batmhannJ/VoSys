<?php
session_start();

// Include your header file
include 'includes/header.php';

// Include your database connection file
include 'includes/conn.php';

// Check if the form is submitted
if (isset($_POST['resetPass'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM voters WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind parameter
    $stmt->execute();
    $result = $stmt->get_result(); // Get result

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store the token in the database along with the user's email and timestamp
        $stmt = $conn->prepare("INSERT INTO password_reset (email, token, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $token); // Bind parameters
        if ($stmt->execute()) {
            // Send password reset email
            $reset_link = "http://vosys.org/change_pass.php?token=$token";
            $subject = "Password Reset";
            $message = "Click the following link to reset your password: $reset_link";
            if (mail($email, $subject, $message)) {
                $_SESSION['success'] = "Password reset link has been sent to your email.";
                // Redirect to success page
                header("Location: password_reset_success.php");
                exit();
            } else {
                $_SESSION['error'] = "Failed to send password reset email. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Failed to store password reset information. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Email not found. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Include your CSS files -->
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box-body">
            <div class="login-logo">
                <img src="images/olshco.png" class="olshco-logo" alt="College Voting System Logo">
                <b>College Voting System</b>
            </div>
            <p class="login-box-msg">Forgot Password? Enter your email address to reset your password.</p>

            <!-- Forgot password form -->
            <form action="" method="POST">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="resetPass">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'includes/scripts.php' ?>
</body>
</html>

<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your header file
include 'includes/header.php';

// Include your database connection file
include 'includes/conn.php';

// Check if the form is submitted
if (isset($_POST['resetPass'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM voters WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store the token in the database along with the user's email and timestamp
        $stmt = $conn->prepare("INSERT INTO password_reset (email, token, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $token]);

        // Send password reset email
        $reset_link = "http://vosys.org/forgot_password.php?token=$token";
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: $reset_link";
        mail($email, $subject, $message);

        $_SESSION['success'] = "Password reset link has been sent to your email.";
    } else {
        $_SESSION['error'] = "Email not found. Please try again.";
    }

    // Redirect the user back to the login page after processing
    header("Location: voters_login.php");
    exit();
}

// HTML for your forgot password form
?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box-body">
            <div class="login-logo">
                <img src="images/olshco.png" class="olshco-logo" alt="College Voting System Logo">
                <b>College Voting System</b>
            </div>
            <p class="login-box-msg">Forgot Password? Enter your email address to reset your password.</p>

            <!-- Forgot password form -->
            <form action="forgot_password.php" method="POST">
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
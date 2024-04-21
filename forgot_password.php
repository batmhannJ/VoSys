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

// Check if token is provided in the URL
if (isset($_GET['token'])) {
    // Get the token from the URL
    $token = $_GET['token'];
    // Display the form for changing the password
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change Password</title>
        <!-- Include your CSS files -->
    </head>
    <body>
        <div class="container">
            <h2>Change Password</h2>
            <!-- Password reset form -->
            <form action="update_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    // If the token parameter is not set, display an error message or redirect to another page
    $_SESSION['error'] = "Token not found. Please try again.";
    header("Location: forgot_password.php");
    exit();
}
?>

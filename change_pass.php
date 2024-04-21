<?php
// Include your header file
include 'includes/header.php';

// Include your database connection file
include 'includes/conn.php';

// Check if the token parameter is set in the URL
if (isset($_GET['token'])) {
    // Get the token from the URL
    $token = $_GET['token'];

    // Query the database to check if the token exists and is valid
    $stmt = $conn->prepare("SELECT email FROM password_reset WHERE token = ? AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 60");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, display password reset form
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
        // Token is invalid or expired, redirect to another page or display an error message
        header("Location: index.php"); // Redirect to index.php or another page
        exit;
    }
} else {
    // If the token parameter is not set, redirect to another page or display an error message
    header("Location: index.php"); // Redirect to index.php or another page
    exit;
}
?>

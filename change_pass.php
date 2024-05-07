
<?php
// Include your header file
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/header.php';
echo "<script>alert('PHP script is executing');</script>";
include 'includes/session.php';

if (isset($_POST['reset'])) {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the entered password matches the confirm password
    if ($password != $confirm_password) {
        $_SESSION['error'] = 'Password and confirm password do not match';
        echo "<script>alert('Password and confirm password do not match');</script>"; // Display alert
        exit; // Exit here without redirection
    }

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Establish database connection (assuming $conn is your database connection)
    include 'includes/db_connect.php'; // Adjust the filename as per your actual file

    // Update the password in the database
    $sql = "UPDATE voters SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Password updated successfully';
            echo "<script>alert('Password updated successfully');</script>"; // Display alert
            // No redirection needed here
        } else {
            $_SESSION['error'] = 'Failed to update password: ' . $stmt->error;
            echo "<script>alert('Failed to update password');</script>"; // Display alert
        }
        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Prepare statement failed: ' . $conn->error;
        echo "<script>alert('Prepare statement failed');</script>"; // Display alert
    }

    // Exit here without redirection
    exit;
} else {
    $_SESSION['error'] = 'Invalid request';
    echo "<script>alert('Invalid request');</script>"; // Display alert
    // No redirection needed here
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change Password</title>
        <!-- Include your CSS files -->
    </head>
    <body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box-body">
            <div class="login-logo">
                <img src="images/olshco.png" class="olshco-logo" alt="College Voting System Logo">
                <b>College Voting System</b>
            </div>
            <p class="login-box-msg">Change Password</p>
            <!-- Password reset form -->
            <form action="" method="POST">
                <input type="hidden" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                <div class="form-group has-feedback">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group has-feedback">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="row">
                        <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary" id="reset" name="reset">Reset Password</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
        
    <?php include 'includes/scripts.php' ?>

    </body>
    </html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your header fil

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset'])) {
    $email = $_POST['email'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $_SESSION['error'] = 'Password and confirm password do not match';
        header("Location: change_pass.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    include 'includes/conn.php';

    $sql = "UPDATE voters SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Password updated successfully';
            header("Location: voters_login.php"); // Redirect to a page indicating success
            exit;
        } else {
            $_SESSION['error'] = 'Failed to update password: ' . $stmt->error;
            header("Location: change_pass.php");
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Prepare statement failed: ' . $conn->error;
        header("Location: change_pass.php");
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid request';
    header("Location: voters_login.php");
    exit;
}
?>
<?php 
include 'includes/header.php';
?>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-box-body">
        <div class="login-logo">
            <img src="images/olshco.png" class="olshco-logo" alt="College Voting System Logo">
            <b>College Voting System</b>
        </div>
        <p class="login-box-msg">Change Password</p>
        <!-- Password reset form -->
        <form action="update_password.php" method="POST" onsubmit="return validateForm()">
            <input type="hidden" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
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
                    <button type="submit" class="btn btn-primary" name="reset">Reset Password</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<?php
// Include your header file
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Move session_start() to the beginning of the file
session_start();
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

<?php include 'includes/scripts.php' ?>

<script>
    function validateForm() {
        var newPassword = document.getElementById("new_password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        if (newPassword !== confirmPassword) {
            alert("Password and confirm password do not match");
            return false;
        }
        return true;
    }
</script>
</body>
</html>

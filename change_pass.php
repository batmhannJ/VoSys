<?php
// Include your header file
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php include 'includes/header.php'; ?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box-body">
            <div class="login-logo">
                <img src="images/olshco.png" class="olshco-logo" alt="College Voting System Logo">
                <b>College Voting System</b>
            </div>
            <p class="login-box-msg">Change Password</p>
            <!-- Password reset form -->
            <form id="password_reset_form" method="POST">
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
                        <button type="button" class="btn btn-primary" onclick="resetPassword()" id="reset" name="reset">Reset Password</button>
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

    function resetPassword() {
        if (validateForm()) {
            var form = document.getElementById("password_reset_form");
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_password.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Request was successful
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(response.message); // Display success message
                            window.location.href = "voters_login.php"; // Redirect to login page
                        } else {
                            alert(response.message); // Display error message
                        }
                    } else {
                        // Request failed
                        alert("Failed to update password");
                    }
                }
            };
            xhr.send(formData);
        }
    }
</script>

</body>
</html>

<?php
// Include your header file
include 'includes/header.php';
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
            <form id="forgotPasswordForm">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <input type="number" class="form-control" id="otp" name="otp" placeholder="Enter OTP" required>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-primary" id="sendOTP">Send OTP</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" id="validateOTP" name="validateOTP">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Include your scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sendOTP').addEventListener('click', function() {
                var email = document.querySelector('input[name="email"]').value; // Get email value from input field
                sendOTP(email);
            });

            // Handle form submission
            document.getElementById('forgotPasswordForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission behavior

                var email = document.querySelector('input[name="email"]').value;
                var otp = document.querySelector('input[name="otp"]').value;

                // Validate OTP
                validateOTP(email, otp);
            });
        });

        function sendOTP(email) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'send_otp.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    alert(response); // Show response message (e.g., "OTP sent successfully")
                }
            };
            xhr.send('email=' + encodeURIComponent(email));
        }

        function validateOTP(email, otp) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'validate_otp.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        window.location.href = 'change_pass.php'; // Redirect to change_pass.php if OTP is correct
                    } else {
                        alert(response.message); // Show error message if OTP is incorrect
                    }
                } else {
                    alert('Error occurred. Please try again.'); // Show error message
                }
            };
            xhr.send('email=' + encodeURIComponent(email) + '&otp=' + encodeURIComponent(otp));
        }
    </script>
</body>
</html>

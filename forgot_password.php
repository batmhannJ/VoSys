
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
            <form action="" method="POST">
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
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="resetPass">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'includes/scripts.php' ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('sendOTP').addEventListener('click', function() {
        var email = document.querySelector('input[name="email"]').value; // Get email value from input field
        sendOTP(email);
    });
});


function sendOTP(email) {
    console.log('Sending OTP to email:', email); // Log email value
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'send_otp.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            console.log('Response received:', xhr.responseText); // Log the response received
            if (xhr.status == 200) {
                var response = xhr.responseText;
                alert(response); // Show response message (e.g., "OTP sent successfully")
            } else {
                alert('Error occurred. Please try again.'); // Show error message
            }
        }
    };
    xhr.send('email=' + encodeURIComponent(email));
}

</script>
</body>
</html>

<?php
session_start();

// Include your database connection file
include 'includes/conn.php';

// Check if the form is submitted
if (isset($_POST['resetPass'])) {
    $email = $_POST['email'];
    $entered_otp = $_POST['otp']; // Get OTP entered by the user

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM voters WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind parameter
    $stmt->execute();
    $result = $stmt->get_result(); // Get result

    if ($result->num_rows > 0) {
        // Retrieve the OTP sent via email
        $stmt = $conn->prepare("SELECT otp FROM otp_verification WHERE email = ?");
        $stmt->bind_param("s", $email); // Bind parameter
        $stmt->execute();
        $otp_result = $stmt->get_result();

        if ($otp_result->num_rows > 0) {
            $row = $otp_result->fetch_assoc();
            $stored_otp = $row['otp'];

            // Verify if the entered OTP matches the one sent via email
            if ($entered_otp == $stored_otp) {
                // Redirect to change_pass.php if OTP is correct
                header("Location: change_pass.php?email=$email");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect OTP. Please try again.";
                header("Location: forgot_password.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "No OTP found for this email. Please try again.";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Email not found. Please try again.";
        header("Location: forgot_password.php");
        exit();
    }
}
?>

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
        var email = '<?php echo $user['email']; ?>';
        sendOTP(email);
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
</script>
</body>
</html>
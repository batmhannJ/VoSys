
<?php
// Include your header file
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/header.php';
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
            <form action="update_password.php" method="POST">
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
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

    </body>
    </html>

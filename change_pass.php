<?php
// Include your header file
include 'includes/header.php';

// Include your database connection file
include 'includes/conn.php';
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
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <input type="number" class="form-control" id="otp" name="otp" placeholder="Enter OTP" required>
                    </div>
                   <div class="col-sm-3">
                        <button type="button" class="btn btn-primary" id="sendOTP">Send OTP</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        </div>
    </body>
    </html>

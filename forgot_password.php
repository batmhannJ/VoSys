<?php include 'includes/header.php'; ?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box-body">
            <div class="login-logo">
                <img src="images/olshco.png" class="olshco-logo" alt="College Voting System Logo">
                <b>College Voting System</b>
            </div>
            <p class="login-box-msg">Forgot Password? Enter your email address to reset your password.</p>

            <!-- Forgot password form -->
            <form action="forgot_password.php" method="POST">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
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
</body>
</html>
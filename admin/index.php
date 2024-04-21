<?php
session_start();

// Redirect users based on session
if (isset($_SESSION['admin'])) {
    header('location: admin/home.php');
    exit();
}

if (isset($_SESSION['voter'])) {
            header('location: home.php'); // Default redirect if organization is not found
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box-body">
            <div class="login-logo">
            <img src="images/olshco.png" class="olshco-logo" alt="College Voting System Logo">
                <b>ADMIN LOGIN </b><br>
                <b>College Voting System</b>
            </div>
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="login.php" method="POST">
                <div class="form-group has-feedback">
                    <img src="images/user-solid.svg" class="loglogo">
                    <span class="form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <img src="images/user-solid.svg" class="loglogo">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                    <span class="form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <img src="images/lock-solid.svg" class="loglogo">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                    <span class="form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <div class="g-recaptcha" data-sitekey="6LddHcIpAAAAAJS6Wnenkllxyr3tWUSlSCu8o9eO"></div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btnlogin" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
                    </div>
                </div>
            </form>
            <!--<p class="text-center"><a href="forgot_password.php">Forgot Password?</a></p>-->
        </div>
        <?php
        if (isset($_SESSION['error'])) {
            echo "
                <div class='callout callout-danger text-center mt20'>
                    <p>" . $_SESSION['error'] . "</p> 
                </div>
            ";
            unset($_SESSION['error']);
        }
        ?>
    </div>

    <?php include 'includes/scripts.php' ?>
</body>
<script>
        const togglePassword = document
            .querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', () => {
            // Toggle the type attribute using
            // getAttribure() method
            const type = password
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            password.setAttribute('type', type);
            // Toggle the eye and bi-eye icon
            this.classList.toggle('bi-eye');
        });
    </script>
</html>

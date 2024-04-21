
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  
    <div class="login-box-body">
      <div class="login-logo">
        <img src="/images/olshco.png" class="olshco-logo" alt>
        <b>ADMIN LOGIN </b><br>
        <b>College Voting System</b>
      </div>
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="login.php" method="POST">
            <div class="form-group has-feedback">
            <!--<img src="images/user-solid.svg" class="loglogo">-->
                <input type="text" class="form-control" name="username" placeholder="Username" required>
                <span class="form-control-feedback"></span>
            </div>
          <div class="form-group has-feedback">
            <!--<img src="images/lock-solid.svg" class="loglogo">-->
            <span class="form-control-feedback"></span>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
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
    </div>
    <?php
        if(isset($_SESSION['error'])){
            echo "
                <div class='callout callout-danger text-center mt20'>
                    <p>".$_SESSION['error']."</p> 
                </div>
            ";
            unset($_SESSION['error']);
        }
    ?>
</div>
    
<?php include 'includes/scripts.php' ?>
</body>
</html>
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
                    <input type="text" class="form-control" name="voter" placeholder="Voter's ID" required>
                    <span class="form-control-feedback"></span>
                  <label style="font-size:15px;"></label>
                </div>

                <div class="input-wrap has has-feedback">
                  <input
                    type="password"
                    minlength="8"
                    class="input-field"
                    name="password"
                    placeholder="Password" style="font-size: 15px;"
                    autocomplete="off"
                    required
                  />
                  <label style="font-size:15px;"></label>
                  <i class="bi bi-eye-slash" id="togglePassword"></i>
                  <span class="form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    <div class="g-recaptcha" data-sitekey="6LddHcIpAAAAAJS6Wnenkllxyr3tWUSlSCu8o9eO">
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btnlogin" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
                    </div>
                </div>
                <p class="text-center"><a href="forgot_password.php">Forgot Password?</a></p>
            </form>
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

          <div class="carousel">
            <div class="images-wrapper">
              <img src="./images/c.png" class="image img-1 show" alt="" />
              <img src="./images/j.png" class="image img-2 show" alt="" />
              <img src="./images/y.png" class="image img-3 show" alt="" />
              <img src="./images/ct.png" class="image img-4 show" alt="" />
              <img src="./images/p.png" class="image img-5 shozw" alt="" />
              <img src="./images/h.png" class="image img-6 show" alt="" />
            </div>
        </div>
      </div>
    </main>








        </div>
    </div>
    <script>
  const images = document.querySelectorAll('.image');
  let currentIndex = 0;

  function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    updateSlider();
  }

  function updateSlider() {
  const offset = -currentIndex * images[0].offsetWidth || 0;
  document.querySelector('.images-wrapper').style.transform = `translateX(${offset}px)`;

  // Flash effect
  images.forEach(image => {
    image.style.opacity = 0; // Hide all images
  });
  images[currentIndex].style.opacity = 1; // Show the current image
}


  setInterval(nextImage, 3000); // Change image every 3 seconds
</script>



    <script src="app.js"></script>
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

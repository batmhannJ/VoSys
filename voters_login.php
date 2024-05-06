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
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

*,
*::before,
*::after {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

body,
input {
  font-family: "Poppins", sans-serif;
}

main {
  width: 100%;
  min-height: 100vh;
  overflow: hidden;
  background-image: url("images/4.png");
  background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  padding: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.box {
  position: relative;
  width: 100%;
  max-width: 1020px;
  height: 640px;
  background-color: #fff;
  border-radius: 3.3rem;
  box-shadow: 0 60px 40px -30px rgba(0, 0, 0, 0.27);
}

.inner-box {
  position: absolute;
  width: calc(100% - 4.1rem);
  height: calc(100% - 4.1rem);
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.forms-wrap {
  position: absolute;
  height: 100%;
  width: 45%;
  top: 0;
  left: 0;
  display: grid;
  grid-template-columns: 1fr;
  grid-template-rows: 1fr;
  transition: 0.8s ease-in-out;
}

form {
  max-width: 300px;
  width: 100%;
  margin: 0 auto;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  grid-column: 1 / 2;
  grid-row: 1 / 2;
  transition: opacity 0.02s 0.4s;
}

form.sign-up-form {
  opacity: 0;
  pointer-events: none;
}

.logo {
  display: flex;
  align-items: center;
}

.logo img {
    margin-top: -15px;
  width: 60px;
  margin-right: 0.3rem;
}

.logo h4 {
  font-size: 1.1rem;
  margin-top: -1px;
  letter-spacing: -0.5px;
  color: #151111;
}

.heading h2 {
  font-size: 3rem; /* Increase the font size of the heading */
  font-weight: 20px;
  color: maroon; /* Change text color to dark gray */
  margin-bottom: 10px; /* Add some space below the heading */
}

.heading hr {
  border: none; /* Remove the default border */
  height: 2px; /* Set the height of the hr */
  background-color: #555; /* Change the color of the hr */
  margin: 0 auto; /* Center the hr */
  width: 100%; /* Set the width of the hr */
  margin-bottom: 10px; /* Add some space below the hr */
}

.heading h6 {
  color: #bababa;
  font-weight: 400;
  font-size: 0.75rem;
  display: inline;
}

.toggle {
  color: #151111;
  text-decoration: none;
  font-size: 0.75rem;
  font-weight: 500;
  transition: 0.3s;
}

.toggle:hover {
  color: #8371fd;
}

.input-wrap {
  position: relative;
  height: 37px;
  margin-bottom: 2rem;
}

.input-field {
  position: absolute;
  width: 100%;
  height: 100%;
  background: none;
  border: none;
  outline: none;
  border-bottom: 1px solid #bbb;
  padding: 0;
  font-size: 0.95rem;
  color: #151111;
  transition: 0.4s;
}

label {
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.95rem;
  color: #bbb;
  pointer-events: none;
  transition: 0.4s;
}

.input-field.active {
  border-bottom-color: #151111;
}

.input-field.active + label {
  font-size: 0.75rem;
  top: -2px;
}

.sign-btn {
  display: inline-block;
  width: 100%;
  height: 43px;
  background-color: #1357a6;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 0.8rem;
  font-size: 0.8rem;
  margin-bottom: 2rem;
  transition: 0.3s;
}

.sign-btn:hover {
  background-color: gray;
}

.back-btn {
  display: inline-block;
  width: 100%;
  height: 43px;
  background-color: maroon;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 0.8rem;
  font-size: 0.8rem;
  margin-bottom: 2rem;
  transition: 0.3s;
}

.back-btn:hover {
  background-color: gray;
}

.text {
  color: #bbb;
  font-size: 0.7rem;
}

.text a {
  color: #bbb;
  transition: 0.3s;
}

.text a:hover {
  color: #8371fd;
}

main.sign-up-mode form.sign-in-form {
  opacity: 0;
  pointer-events: none;
}

main.sign-up-mode form.sign-up-form {
  opacity: 1;
  pointer-events: all;
}

main.sign-up-mode .forms-wrap {
  left: 55%;
}

main.sign-up-mode .carousel {
  left: 0%;
}

.carousel {
  position: absolute;
  height: 100%;
  width: 55%;
  left: 45%;
  top: 0;
  background-image: url("images/bg.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  border-radius: 2rem;
  display: grid;
  grid-template-rows: auto 1fr;
  padding-bottom: 2rem;
  overflow: hidden;
  transition: 0.8s ease-in-out;
}

.images-wrapper {
  display: flex;
  transition: transform 0.8s ease-in-out;
  margin-left: 20px; /* Adjust margin as needed */
}

.image {
  margin-top: 60px;
  height: 500px;
  width: 500px;
  opacity: 0;
  display: flex;
  transition: opacity 0.3s, transform 0.5s;
  border-radius: 50%; /* Make the image circular */
  box-shadow: 20px 30px 20px rgba(0, 0, 0, 0.9); /* Apply box shadow */
}

.img-1,
.img-2,
.img-3,
.img-4,
.img-5,
.img-6 {
  transform: translateX(0);
}

.image.show {
  opacity: 1;
  transform: translateX(0);
}

@media (max-width: 768px) {
  .carousel {
    width: 70%; /* Adjust carousel width for smaller screens */
    left: 50%;
    transform: translateX(-50%);
  }
  
  .images-wrapper {
    margin-left: 0; /* Remove left margin for smaller screens */
  }

  .image {
    height: 50vw; /* Adjust height for smaller screens */
    width: 50vw; /* Adjust width for smaller screens */
  }
}

@media (max-width: 480px) {
  .image {
    height: 70vw; /* Adjust height for even smaller screens */
    width: 70vw; /* Adjust width for even smaller screens */
  }
}


.text-slider {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.text-wrap {
  max-height: 2.2rem;
  overflow: hidden;
  margin-bottom: 2.5rem;
}

.text-group {
  display: flex;
  flex-direction: column;
  text-align: center;
  transform: translateY(0);
  transition: 0.5s;
}

.text-group h2 {
  line-height: 2.2rem;
  font-weight: 600;
  font-size: 1.6rem;
  color: black;
}

.bullets {
  display: flex;
  align-items: center;
  justify-content: center;
}

.bullets span {
  display: block;
  width: 1rem;
  height: 1rem;
  background-color: #aaa;
  margin: 0 0.25rem;
  border-radius: 50%;
  cursor: pointer;
  transition: 0.3s;
}

.bullets span.active {
  width: 1.1rem;
  background-color: #151111;
  border-radius: 1rem;
}

@media (max-width: 850px) {
  .box {
    height: auto;
    max-width: 550px;
    overflow: hidden;
  }

  .inner-box {
    position: static;
    transform: none;
    width: revert;
    height: revert;
    padding: 2rem;
  }

  .forms-wrap {
    position: revert;
    width: 100%;
    height: auto;
  }

  form {
    max-width: revert;
    padding: 1.5rem 2.5rem 2rem;
    transition: transform 0.8s ease-in-out, opacity 0.45s linear;
  }


  form.sign-up-form {
    transform: translateX(100%);
  }

  main.sign-up-mode form.sign-in-form {
    transform: translateX(-100%);
  }

  main.sign-up-mode form.sign-up-form {
    transform: translateX(0%);
  }

  .carousel {
    position: fixed;
    height: auto;
    width: 200%;
    padding: 3rem 2rem;
    display: none;
  }

  .images-wrapper {
    display: none;
  }

  .text-slider {
    width: 100%;
  }
}

@media (max-width: 530px) {
  main {
    padding: 1rem;
  }

  .box {
    border-radius: 2rem;
  }

  .inner-box {
    padding: 1rem;
  }

  .carousel {
    padding: 1.5rem 1rem;
    border-radius: 1.6rem;
  }

  .text-wrap {
    margin-bottom: 1rem;
  }

  .text-group h2 {
    font-size: 1.2rem;
  }

  form {
    padding: 1rem 1rem 1.5rem;
  }
}
</style>

<body>
    <main>
      <div class="box">
        <div class="inner-box">
          <div class="forms-wrap">
            <form action="login.php" method="post" autocomplete="off" class="sign-in-form">
              <div class="logo">
                <img src="./images/olshco.png" alt="easyclass" />
                <h4 style="font-size:28px; color: maroon;"><b>VOSYS - OLSHCO</b></h4>
              </div>

              <div class="heading">
                <center><h2>Voters Login Page</h2></center>
                <hr>
              </div>

              <div class="actual-form has-feedback">
                 <div class="input-wrap">
                  <input
                    type="text"
                    minlength="4"
                    class="input-field"
                    name="voter"
                    placeholder="Voter's ID" style="font-size: 15px;"
                    required
                  />
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
            </div>

                <input type="submit" name="login" value="Sign In" class="sign-btn" style="font-size:15px;">
                <input type="submit" name="login" value="Back to Homepage" class="back-btn" style="font-size:15px;">
                        <?php
                if (isset($_SESSION['error'])) {
                    echo "
                        <div class='callout callout-danger text-center mt20' style='width: 300px; margin: 0 auto;'>
                            <p>" . $_SESSION['error'] . "</p> 
                        </div>
                    ";
                    unset($_SESSION['error']);
                }
                ?>
                
              </div>
            </form>
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

</html>

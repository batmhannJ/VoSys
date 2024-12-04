<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = htmlspecialchars($_POST['token']); // Sanitize token
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Password validation
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset_password.php?token=$token");
        exit;
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPassword)) {
        $_SESSION['error'] = "Password must be at least 8 characters long, include uppercase, lowercase, numbers, and special characters.";
        header("Location: reset_password.php?token=$token");
        exit;
    }

    // Verify token
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: osa_forgotpass.php");
        exit;
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE organization = 'OSAadmin' AND email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();    

    // Delete token
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $_SESSION['success'] = "Password successfully updated.";
    header("Location: osa_forgotpass.php");
    exit;
}

// Display the password reset form if token is valid
if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
</head>
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
  background-color: maroon;
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

.input-wrap {

position: relative;
width: 100%;
max-width: 400px;
margin-top: -5px;
}

.input-field {
width: 100%;
padding: 10px; /* Adjust the padding to make space for the icon */
font-size: 15px;
box-sizing: border-box;
border: 1px solid #ccc;
border-radius: 4px;
}

.togglePassword {
position: absolute;
top: 50%;
right: 10px;
transform: translateY(-50%);
cursor: pointer;
font-size: 18px;
color: #aaa;
}

.togglePassword:hover {
color: #333;
}
</style>
<body>
    <main>
        <div class="box">
            <form action="reset_password.php" method="post" class="sign-in-form">
                <div class="logo">
                    <img src="./images/olshco.png" alt="Logo" />
                    <h4 style="font-size:28px; color: maroon;"><b>VOSYS - OLSHCO</b></h4>
                </div>
                <div class="heading">
                    <center><h2>Reset Password</h2></center>
                    <hr>
                </div>
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo "<div class='success-message'>" . $_SESSION['success'] . "</div>";
                    unset($_SESSION['success']);
                }
                ?>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="input-wrap">
                    <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                    <i class="fa fa-fw fa-eye togglePassword" id="togglePassword"></i>
                </div>
                <div class="input-wrap">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <i class="fa fa-fw fa-eye togglePassword" id="togglePassword1"></i>
                </div>
                <button type="submit" class="sign-btn">Change Password</button>
            </form>
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
    </main>


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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const togglePassword = document.getElementById('togglePassword');
            togglePassword.addEventListener('click', function () {
                const input = document.getElementById('new_password');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.toggle('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.toggle('fa-eye-slash');
                }
            });
            const togglePassword1 = document.getElementById('togglePassword1');
            togglePassword1.addEventListener('click', function () {
                const input = document.getElementById('confirm_password');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.toggle('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.toggle('fa-eye-slash');
                }
            });
        });
    </script>
</body>
</html>

<?php
} else {
    echo "Invalid request.";
}
?>

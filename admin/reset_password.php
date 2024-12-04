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

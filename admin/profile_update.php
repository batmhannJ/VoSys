<?php
include 'includes/session.php';

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'home.php';
}

if (isset($_POST['save'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $photo = $_FILES['photo']['name'];
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Check if the entered OTP matches the stored OTP
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
        // OTP matched, proceed with saving the updated information
        // Your saving logic goes here
        
        // Clear the OTP from session
        unset($_SESSION['otp']);
        
        $_SESSION['success'] = 'Profile updated successfully';
    } else {
        // OTP verification failed
        $_SESSION['error'] = 'OTP verification failed';
        header('Location: '.$return);
        exit;
    }
} else {
    $_SESSION['error'] = 'Fill up required details first';
}

header('Location: '.$return);
?>

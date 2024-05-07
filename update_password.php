<?php
include 'includes/session.php';

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'home.php';
}

if (isset($_POST['resetPass'])) {
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password']; 
    $otp = $_POST['otp'];

    // Check if the entered password matches the confirm password
    if ($password != $confirm_password) {
        $_SESSION['error'] = 'Password and confirm password do not match';
        header('Location: '.$return);
        exit;
    }

    // Check if the entered OTP matches the stored OTP
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
        // OTP matched, proceed with saving the updated information
        // Hash the new password
        $new_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE voters SET password = '$new_password' WHERE email = '".$user['email']."'";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Admin profile updated successfully';
        } else {
            $_SESSION['error'] = $conn->error;
        }

        // Clear the OTP from session
        unset($_SESSION['otp']);

        header('Location: '.$return);
        exit;
    } else {
        // OTP verification failed
        $_SESSION['error'] = 'OTP verification failed';
        header('Location: '.$return);
        exit;
    }
} else {
    $_SESSION['error'] = 'Fill up required details first';
    header('Location: '.$return);
    exit;
}
?>

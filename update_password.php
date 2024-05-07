<?php
include 'includes/session.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'voters_login.php';
}

if (isset($_POST['reset'])) {
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password']; 
    $email = $_POST['email'];

    // Check if the entered password matches the confirm password
    if ($password != $confirm_password) {
        $_SESSION['error'] = 'Password and confirm password do not match';
        header('Location: '.$return);
        exit;
    }
    else {
        $new_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE voters SET email = '$email', password = '$new_password' WHERE id = '".$user['id']."'";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Password updated successfully';
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

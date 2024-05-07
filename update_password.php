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

        $sql = "UPDATE voters SET password = '$new_password' WHERE email = '".$user['email']."'";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Password updated successfully';
        } else {
            $_SESSION['error'] = $conn->error;
        }
        header('Location: '.$return);
        exit;
    } 
} else {
    $_SESSION['error'] = 'Fill up required details first';
    header('Location: '.$return);
    exit;
}
?>

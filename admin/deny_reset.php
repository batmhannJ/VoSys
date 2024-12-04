<?php
// deny_reset.php
session_start();
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    // Log or handle denied reset requests
    $_SESSION['success'] = "Password reset request denied.";
}
header("Location: osa_forgotpass.php");
exit;
?>

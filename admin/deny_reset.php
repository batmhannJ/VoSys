<?php
// deny_reset.php
session_start();
include 'includes/conn.php'; // Ensure to include the database connection

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Delete the token associated with the email from the database
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the delete query
    if ($stmt->execute()) {
        $_SESSION['success'] = "Password reset request denied and token deleted.";
    } else {
        $_SESSION['error'] = "Failed to deny the reset request. Please try again.";
    }
}

header("Location: osa_forgotpass.php");
exit;
?>

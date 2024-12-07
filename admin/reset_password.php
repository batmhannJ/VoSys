<?php
session_start();
include 'includes/conn.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the passwords match
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: defaultpass_admin.php?token=$token");
        exit;
    }

    // Verify token
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid token.";
        header("Location: defaultpass_admin.php?token=$token");
        exit;
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Update password in the admin table
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE organization = 'OSA' AND email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();

    // Delete token after resetting the password
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    header("Location: reset_success.php");
    exit;
}
?>

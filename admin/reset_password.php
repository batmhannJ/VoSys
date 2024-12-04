<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset_password.php?token=$token");
        exit;
    }

    // Verify token
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid token.";
        header("Location: osa_forgotpass.php");
        exit;
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
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

// HTML form for new password
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    echo "
    <form action='reset_password.php' method='post'>
        <input type='hidden' name='token' value='$token'>
        <label for='new_password'>New Password:</label>
        <input type='password' name='new_password' required>
        <label for='confirm_password'>Confirm Password:</label>
        <input type='password' name='confirm_password' required>
        <button type='submit'>Change Password</button>
    </form>";
} else {
    echo "Invalid request.";
}
?>

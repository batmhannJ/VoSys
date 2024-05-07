<?php
include 'includes/session.php';

if (isset($_POST['reset'])) {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the entered password matches the confirm password
    if ($password != $confirm_password) {
        $_SESSION['error'] = 'Password and confirm password do not match';
        header('Location: '.$_SERVER['HTTP_REFERER']); // Redirect back to the previous page
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE voters SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Password updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update password: ' . $stmt->error;
        }
        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Prepare statement failed: ' . $conn->error;
    }

    // Redirect back to the previous page
    header('Location: '.$_SERVER['HTTP_REFERER']);
    exit;
} else {
    $_SESSION['error'] = 'Invalid request';
    header('Location: '.$_SERVER['HTTP_REFERER']);
    exit;
}
?>

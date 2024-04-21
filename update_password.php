<?php
// Include your database connection file
include 'includes/conn.php';

// Check if the form is submitted
if (isset($_POST['resetPassword'])) {
    // Retrieve token and new password from the form
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Update password in the database
    $stmt = $conn->prepare("UPDATE voters SET password = ? WHERE token = ?");
    $stmt->bind_param("ss", $new_password, $token);
    if ($stmt->execute()) {
        // Password updated successfully, redirect to success page
        header("Location: password_update_success.php");
        exit();
    } else {
        // Error in updating password, redirect back to change_pass.php with error message
        header("Location: change_pass.php?error=1");
        exit();
    }
} else {
    // If the form is not submitted, redirect to error page or another appropriate action
    header("Location: error.php");
    exit();
}
?>

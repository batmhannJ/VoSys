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
        header("Location: update_password.php"); // Redirect back to the form
        exit; // Exit here without further processing
    }

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Establish database connection (assuming $conn is your database connection)
    include 'includes/conn.php'; // Adjust the filename as per your actual file

    // Update the password in the database
    $sql = "UPDATE voters SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Password updated successfully';
            header("Location: update_password.php"); // Redirect to a success page or back to the form
            exit; // Exit here after successful password update
        } else {
            $_SESSION['error'] = 'Failed to update password: ' . $stmt->error;
            header("Location: update_password.php"); // Redirect back to the form with error message
            exit; // Exit here after displaying the error message
        }
        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Prepare statement failed: ' . $conn->error;
        header("Location: update_password.php"); // Redirect back to the form with error message
        exit; // Exit here after displaying the error message
    }
} else {
    $_SESSION['error'] = 'Invalid request';
    header("Location: update_password.php"); // Redirect back to the form with error message
    exit; // Exit here after displaying the error message
}
?>
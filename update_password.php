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
            $response = [
                'success' => true,
                'message' => 'Password updated successfully'
            ]; // Exit here after successful password update
        } else {
            $_SESSION['error'] = 'Failed to update password';
            header("Location: update_password.php"); // Redirect back to the form with error message
            exit; // Exit here after displaying the error message
        }
        // Close the sta
    } else {
        $_SESSION['error'] = 'Failed to prepare statement';
        header("Location: update_password.php"); // Redirect back to the form with error message
        exit; // Exit here after displaying the error message
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request'
    ];
}
header('Content-Type: application/json');
echo json_encode($response);
?>

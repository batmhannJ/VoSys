<?php
include 'includes/session.php';

// Validate the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['new_password'], $_POST['confirm_password'], $_POST['otp'])) {
        $password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $otp = $_POST['otp'];

        // Check if the entered password matches the confirm password
        if ($password != $confirm_password) {
            $_SESSION['error'] = 'Password and confirm password do not match';
            header('Location: '.$return);
            exit;
        }

        // Check if the entered OTP matches the stored OTP
        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
            // OTP matched, proceed with saving the updated information
            $email = $user['email'];
            $new_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement
            $stmt = $conn->prepare("UPDATE voters SET password = ? WHERE email = ?");
            if ($stmt) {
                // Bind parameters and execute the statement
                $stmt->bind_param("ss", $new_password, $email);
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Password updated successfully';
                } else {
                    $_SESSION['error'] = 'Failed to update password';
                }
                // Close statement
                $stmt->close();
            } else {
                $_SESSION['error'] = 'Database error';
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
        // Required fields not set
        $_SESSION['error'] = 'Fill up required details first';
        header('Location: '.$return);
        exit;
    }
} else {
    // Invalid request method
    $_SESSION['error'] = 'Invalid request';
    header('Location: '.$return);
    exit;
}
?>

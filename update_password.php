<?php

include 'includes/session.php';

// Debugging statement
error_log("POST data: " . print_r($_POST, true));

if (isset($_POST['reset'])) {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Debugging statement
    error_log("Email: " . $email);
    error_log("Password: " . $password);
    error_log("Confirm Password: " . $confirm_password);

    // Check if the entered password matches the confirm password
    if ($password != $confirm_password) {
        $response = [
            'success' => false,
            'message' => 'Password and confirm password do not match'
        ];
    } else {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Debugging statement
        error_log("Hashed Password: " . $hashed_password);

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
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to update password: ' . $stmt->error
                ];
            }
            // Close the statement
            $stmt->close();
        } else {
            $response = [
                'success' => false,
                'message' => 'Prepare statement failed: ' . $conn->error
            ];
        }
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request'
    ];
}

// Debugging statement
error_log("Response: " . print_r($response, true));

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>

<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'jpcs_home.php';
}

if (isset($_POST['save'])) {
    $curr_password = $_POST['curr_password'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $photo = $_FILES['photo']['name'];

    // Initialize the error array
    $_SESSION['error'] = [];

    // Verify current password
    if (password_verify($curr_password, $voter['password'])) {
        // Process photo upload
        if (!empty($photo)) {
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            $fileMimeType = mime_content_type($_FILES['photo']['tmp_name']);
            $fileInfo = getimagesize($_FILES['photo']['tmp_name']);

            // Validate MIME type and ensure it's an actual image
            if (!in_array($fileMimeType, $allowedMimeTypes) || !$fileInfo) {
                $_SESSION['error'][] = 'Invalid file type. Only JPG and PNG are allowed.';
                header('location:' . $return);
                exit();
            }

            // Sanitize filename and generate unique name
            $photo = uniqid() . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

            // Move the file securely
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $photo)) {
                $_SESSION['error'][] = 'Failed to upload file.';
                header('location:' . $return);
                exit();
            }
        } else {
            $photo = $voter['photo']; // Keep the existing photo if no new one is uploaded
        }

        // Hash the new password if it's changed
        if ($password == $voter['password']) {
            $password = $voter['password'];
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Update the database with prepared statements
        $sql = "UPDATE voters SET firstname = ?, lastname = ?, password = ?, photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ssssi', $firstname, $lastname, $password, $photo, $voter['id']);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'User profile updated successfully';
                unset($_SESSION['error']); // Clear error session variable
            } else {
                $_SESSION['error'][] = 'Failed to update profile.';
            }
            $stmt->close();
        } else {
            $_SESSION['error'][] = 'Failed to prepare statement.';
        }
    } else {
        $_SESSION['error'][] = 'Incorrect password';
    }
} else {
    $_SESSION['error'][] = 'Fill up required details first';
}

header('location:' . $return);
?>

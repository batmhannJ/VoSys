<?php
include 'includes/session.php';
include 'includes/conn.php';

// Pagkuha ng return URL
if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'jpcs_home.php'; // Default return URL
}

if (isset($_POST['save'])) {
    $curr_password = $_POST['curr_password'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $photo = $_FILES['photo']['name'];

    // Initialize error array
    $_SESSION['error'] = [];

    // Verify current password
    if (password_verify($curr_password, $voter['password'])) {

        // Process photo upload
        if (!empty($photo)) {
            $fileTmpName = $_FILES['photo']['tmp_name'];
            $fileMimeType = mime_content_type($fileTmpName);
            $allowedMimeTypes = ['image/jpeg', 'image/png'];

            // Validate MIME type and ensure it's an actual image
            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                $_SESSION['error'][] = 'Invalid file type. Only JPG and PNG are allowed.';
                header('location:' . $return);
                exit();
            }

            // Check if file is actually an image
            if (!getimagesize($fileTmpName)) {
                $_SESSION['error'][] = 'File is not a valid image.';
                header('location:' . $return);
                exit();
            }

            // Validate file extension to prevent PHP files or files with .php in their name
            $fileExtension = pathinfo($photo, PATHINFO_EXTENSION);
            if (strtolower($fileExtension) === 'php' || strpos(strtolower($photo), '.php') !== false) {
                $_SESSION['error'][] = 'PHP files or any other non-image files are not allowed. Please upload a valid image.';
                header('location:' . $return);
                exit();
            }

            // Sanitize the file name and create a unique name
            $photo = uniqid() . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

            // Move the file securely
            if (!move_uploaded_file($fileTmpName, 'images/' . $photo)) {
                $_SESSION['error'][] = 'Failed to upload photo.';
                header('location:' . $return);
                exit();
            }
        } else {
            // Retain the existing photo if no new one is uploaded
            $photo = $voter['photo'];
        }

        // Hash the new password if it's changed
        if (empty($password)) {
            // Keep the old password if not changed
            $password = $voter['password'];
        } else {
            // Hash new password
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Update database with prepared statements
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
        $_SESSION['error'][] = 'Incorrect current password.';
    }
} else {
    $_SESSION['error'][] = 'Fill up required details first.';
}

// Redirect to return URL
header('location:' . $return);
?>
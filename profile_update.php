<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'index.php';
}

if (isset($_POST['save'])) {
    $curr_password = $_POST['curr_password'];
    $password = $_POST['password'] ?? null;
    $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
    $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
    $photo = $_FILES['photo']['name'];

    $_SESSION['error'] = [];

    // Verify user and password
    if (password_verify($curr_password, $voter['password'])) {
        // Process photo upload
        if (!empty($photo)) {
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            $fileMimeType = mime_content_type($_FILES['photo']['tmp_name']);

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                $_SESSION['error'][] = 'Invalid file type. Only JPG and PNG are allowed.';
                header('location:' . $return);
                exit();
            }

            $photo = uniqid() . '.' . pathinfo($photo, PATHINFO_EXTENSION);
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $photo)) {
                $_SESSION['error'][] = 'Failed to upload photo.';
                header('location:' . $return);
                exit();
            }
        } else {
            $photo = $voter['photo']; // Retain old photo
        }

        // Hash new password only if it is provided
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $password = $voter['password']; // Retain old password
        }

        // Update user details
        $sql = "UPDATE voters SET firstname = ?, lastname = ?, password = ?, photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ssssi', $firstname, $lastname, $password, $photo, $voter['id']);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'User profile updated successfully.';
            } else {
                $_SESSION['error'][] = 'Failed to update profile.';
            }
            $stmt->close();
        } else {
            $_SESSION['error'][] = 'Database error.';
        }
    } else {
        $_SESSION['error'][] = 'Incorrect current password.';
    }
} else {
    $_SESSION['error'][] = 'Fill up required details first.';
}

header('location:' . $return);
?>

<?php
include 'includes/session.php';

if (isset($_POST['save'])) {
    $curr_password = $_POST['curr_password'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $photo = $_FILES['photo']['name'];

    // Initialize the error array
    $_SESSION['error'] = [];

    if (password_verify($curr_password, $voter['password'])) {
        if (!empty($photo)) {
            move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $photo);
            $filename = $photo;
        } else {
            $filename = $voter['photo'];
        }

        if ($password == $voter['password']) {
            $password = $voter['password'];
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE voters SET firstname = '$firstname', lastname = '$lastname', password = '$password', photo = '$filename' WHERE id = '" . $voter['id'] . "'";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'User profile updated successfully';
        } else {
            $_SESSION['error'][] = $conn->error; // Push error message onto the array
        }
    } else {
        $_SESSION['error'][] = 'Incorrect password'; // Push error message onto the array
    }
} else {
    $_SESSION['error'][] = 'Fill up required details first'; // Push error message onto the array
}

header('location:' . $return);
?>

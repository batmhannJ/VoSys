<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['login'])) {
    $logging = $_POST['logging-in'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the value of logging-in to determine admin or sub-admin login
    if ($logging === 'admin') {
        $table = 'admin';
        //header('location: home.php');
    } elseif ($logging === 'sub_admin') {
        $table = 'sub_admin';
        //header('location: subhome.php');
    } else {
        $_SESSION['error'] = 'Invalid user type.';
        header('location: index.php');
        exit();
    }

    $sql = "SELECT * FROM $table WHERE username = '$username'";
    $query = $conn->query($sql);

    if ($query->num_rows < 1) {
        $_SESSION['error'] = 'Cannot find account with the username';
    } else {
        $row = $query->fetch_assoc();
        $role = $row['userRole'];
        if (password_verify($password, $row['password'])) {
            $_SESSION[$logging] = $row['id'];

            echo 'Session variables set: ';
            var_dump($_SESSION);

            if ($role == 'admin') {
                header('location: home.php');
                exit();
            } elseif ($role == 'subAdmin'){
                header('location: subhome.php');
                exit();
            } else {
                // Redirect to a generic home page if the organization is not recognized
                header('location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Incorrect password';
        }
    }
} else {
    $_SESSION['error'] = 'Input admin credentials first';
}

?>

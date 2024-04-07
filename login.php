<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['login'])) {
    $voter = $_POST['voter'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM voters WHERE voters_id = '$voter'";
    $query = $conn->query($sql);

    if ($query->num_rows < 1) {
        $_SESSION['error'] = 'Cannot find voter with the ID';
    } else {
        $row = $query->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['voter'] = $row['id'];
            $organization = $row['organization'];
            // Check the organization and redirect accordingly
            if ($organization == 'CSC') {
                header('location: home.php');
                exit();
            } elseif ($organization == 'JPCS') {
                header('location: jpcs_home.php');
                exit();
            } elseif ($organization == 'YMF') {
                header('location: educ_home.php');
                exit();
            } elseif ($organization == 'CODE-TG') {
                header('location: code_home.php');
                exit();
            } elseif ($organization == 'PASOA') {
                header('location: pasoa_home.php');
                exit();
            } elseif ($organization == 'HMSO') {
                header('location: hmso_home.php');
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
    $_SESSION['error'] = 'Input voter credentials first';
}

// Redirect to the main page in case of any other conditions
header('location: index.php');
exit();
?>

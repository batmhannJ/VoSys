<?php
    session_start();
    include 'includes/conn.php';

    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $query = $conn->query($sql);

        if($query->num_rows < 1){
            $_SESSION['error'] = 'Cannot find account with the username';
        }
        else{
            $row = $query->fetch_assoc();
            if(password_verify($password, $row['password'])){
                $_SESSION['admin'] = $row['id'];
                // Redirect to respective landing pages
                if ($username == 'OSAadmin') {
                    header('location: home.php');
                    exit();
                } elseif ($username == 'JPCSadmin') {
                    header('location: home_jpcs.php');
                    exit();
                } elseif ($username == 'CSCadmin') {
                    header('location: home_csc.php');
                    exit();
                } else {
                    // Redirect to a default landing page if no specific landing page is defined for the admin
                    header('location: index.php');
                }
                exit(); // Stop further execution after redirection
            }
            else{
                $_SESSION['error'] = 'Incorrect password';
            }
        }
        
    }
    else{
        $_SESSION['error'] = 'Input admin credentials first';
    }

    header('location: index.php'); // Redirect to the login page in case of any errors
?>

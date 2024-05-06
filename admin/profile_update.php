<?php
include 'includes/session.php';

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'home.php';
}

if (isset($_POST['save'])) {
    $curr_password = $_POST['curr_password'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $photo = $_FILES['photo']['name'];

    // Check if the current password is correct
    if (password_verify($curr_password, $user['password'])) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        // Send OTP via email
        $mail = new PHPMailer();
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'olshco.electionupdates@gmail.com'; // Your Gmail email
            $mail->Password = 'ljzujblsyyprijmx'; // Your Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('olshco.electionupdates@gmail.com', 'EL-UPS OLSHCO');
            $mail->addAddress($user['email']); // Assuming you have user's email stored in $user['email']
            $mail->Subject = 'OTP Verification';
            $mail->Body = "Your OTP is: $otp";

            $mail->send();
            $_SESSION['success'] = 'OTP sent successfully. Check your email.';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error sending OTP: ' . $mail->ErrorInfo;
            header('Location: '.$return);
            exit;
        }

        // Redirect to OTP verification page
        header('Location: home.php');
        exit;
    } else {
        $_SESSION['error'] = 'Incorrect password';
    }
} else {
    $_SESSION['error'] = 'Fill up required details first';
}

header('Location: '.$return);
?>

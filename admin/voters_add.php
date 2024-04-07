<?php
include 'includes/session.php';

	require 'C:\Xampp\htdocs\votesystem\admin\PHPMailer\src\PHPMailer.php';
	require 'C:\Xampp\htdocs\votesystem\admin\PHPMailer\src\SMTP.php';
	require 'C:\Xampp\htdocs\votesystem\admin\PHPMailer\src\Exception.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

if (isset($_POST['add'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $yearlvl = $_POST['yearLvl'];
    $organization = $_POST['organization'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format';
        header('location: voters.php');
        exit;
    }

    // Generate voter ID, password, and hash the password
    $set = '1234567890';
    $voter = substr(str_shuffle($set), 0, 7);
    $setPass = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?";
    $randomPassword = substr(str_shuffle($setPass), 0, 10);
    $password = password_hash($randomPassword, PASSWORD_DEFAULT);
    
    // Insert the new voter into the database
    $sql = "INSERT INTO voters (voters_id, genPass, password, firstname, lastname, email, yearLvl, organization) VALUES ('$voter', '$randomPassword', '$password', '$firstname', '$lastname', '$email', '$yearlvl', '$organization')";

    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Voter added successfully';


        $mail = new PHPMailer();
        var_dump($mail);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'olshco.electionupdates@gmail.com'; // Gmail email
            $mail->Password = 'ljzujblsyyprijmx'; // Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('olshco.electionupdates@gmail.com', 'EL-UPS OLSHCO');
            $mail->addAddress($email);
            $mail->Subject = 'Voter Registration';
            $mail->Body = "Hello $firstname $lastname,\n\nYou have been registered as a voter.\n\nVoter ID: $voter\nPassword: $randomPassword\n\nPlease keep this information confidential.\n";

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function ($str, $level) {
                // Log or echo the debug output
                file_put_contents('smtp_debug.log', $str . PHP_EOL, FILE_APPEND);
            };

            $mail->send();
            echo 'Confirmation email sent.';
        } catch (Exception $e) {
        	file_put_contents('error.log', 'Error sending confirmation email: ' . $mail->ErrorInfo . PHP_EOL, FILE_APPEND);
            echo 'Error sending confirmation email. Error: ' . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = 'Error adding voter: ' . $conn->error;
    }
} else {
    $_SESSION['error'] = 'Fill up the add form first';
}


header('location: voters.php');
?>

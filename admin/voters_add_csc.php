<?php
include 'includes/session.php';

require '/home/u247141684/domains/vosys.org/public_html/admin/PHPMailer/src/PHPMailer.php';
require '/home/u247141684/domains/vosys.org/public_html/admin/PHPMailer/src/SMTP.php';
require '/home/u247141684/domains/vosys.org/public_html/admin/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['add'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $yearlvl = $_POST['yearLvl'];
    $organization = $_POST['organization'];

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM voters WHERE email = '$email'";
    $resultEmail = $conn->query($checkEmailQuery);

    if ($resultEmail->num_rows > 0) {
        $_SESSION['error'] = 'Email address already exists';
        header('location: voters_csc.php');
        exit;
    }

    // Check if the full name already exists in the database
    $checkNameQuery = "SELECT * FROM voters WHERE firstname = '$firstname' AND lastname = '$lastname'";
    $resultName = $conn->query($checkNameQuery);

    if ($resultName->num_rows > 0) {
        $_SESSION['error'] = 'Voter with the same name already exists';
        header('location: voters_csc.php');
        exit;
    }

    // Check if the voter ID already exists in the database
    do {
        $voter = substr(str_shuffle('1234567890'), 0, 7);
        $checkVoterQuery = "SELECT * FROM voters WHERE voters_id = '$voter'";
        $resultVoter = $conn->query($checkVoterQuery);
    } while ($resultVoter->num_rows > 0);

    // Generate a password with format: lastname + 4 random numbers
    $specialChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';
    $randomSpecialChar = $specialChars[rand(0, strlen($specialChars) - 1)];
    $randomNumbers = substr(str_shuffle('0123456789'), 0, 4);
    $randomPassword = $lastname . $randomSpecialChar . $randomNumbers;
    $password = password_hash($randomPassword, PASSWORD_DEFAULT);
    
    // Insert the new voter into the database
    $sql = "INSERT INTO voters (voters_id, genPass, password, firstname, lastname, email, yearLvl, organization) 
            VALUES ('$voter', '$randomPassword', '$password', '$firstname', '$lastname', '$email', '$yearlvl', '$organization')";

    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Voter added successfully';

        // Send confirmation email
        $mail = new PHPMailer();
        try {
            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'olshco.electionupdates@gmail.com'; // Gmail email
            $mail->Password = 'ljzujblsyyprijmx'; // Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Set email content
            $mail->setFrom('olshco.electionupdates@gmail.com', 'EL-UPS OLSHCO');
            $mail->addAddress($email);
            $mail->Subject = 'Voter Registration';
            $mail->Body = "Hello $firstname $lastname,\n\nYou have been registered as a voter.\n\nVoter ID: $voter\nPassword: $randomPassword\n\nPlease keep this information confidential.\n";

            // Enable debug mode
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function ($str, $level) {
                // Log or echo the debug output
                file_put_contents('smtp_debug.log', $str . PHP_EOL, FILE_APPEND);
            };

            // Send email
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

header('location: voters_csc.php');
?>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/session.php';
include 'includes/conn.php';

require 'vendor/autoload.php';
require '/home/u247141684/domains/vosys.org/public_html/admin/PHPMailer/src/PHPMailer.php';
require '/home/u247141684/domains/vosys.org/public_html/admin/PHPMailer/src/SMTP.php';
require '/home/u247141684/domains/vosys.org/public_html/admin/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['save_excel_data'])) {
    try {
        $fileName = $_FILES['import_file']['name'];
        $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

        $allowed_ext = ['xls', 'csv', 'xlsx'];

        if (!in_array($file_ext, $allowed_ext)) {
            throw new Exception("Please upload a xls, csv or xlsx file.");
        }

        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach ($data as $row) {
            if ($count > 0) {
                $firstname = $row['0'];
                $lastname = $row['1'];
                $email = $row['2'];
                $organization = $row['3'];
                $yearlvl = $row['4'];
                
                // Check if organization is YMF, then read the major field
                $major = '';
                if ($organization == 'YMF') {
                    // For YMF, expect major in column 5 (index 5)
                    $major = isset($row['5']) ? $row['5'] : '';
                }

                $existingQuery = "SELECT * FROM voters WHERE firstname = '$firstname' AND lastname = '$lastname' AND email = '$email'";
                $existingResult = mysqli_query($conn, $existingQuery);

                if(mysqli_num_rows($existingResult) == 0) {
                    $set = '1234567890';
                    $voter = substr(str_shuffle($set), 0, 7);
                    
                    // Generate a password with format: lastname + 1 special character + 4 random numbers
                    $specialChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';
                    $randomSpecialChar = $specialChars[rand(0, strlen($specialChars) - 1)];
                    $randomNumbers = substr(str_shuffle('0123456789'), 0, 4);
                    $randomPassword = $lastname . $randomSpecialChar . $randomNumbers;
                    
                    $password = password_hash($randomPassword, PASSWORD_DEFAULT);

                    // Add major field to the SQL query for YMF organization
                    if ($organization == 'YMF') {
                        $studentQuery = "INSERT INTO voters (voters_id, genPass, password, firstname, lastname, email, organization, major, yearLvl) 
                                        VALUES ('$voter', '$randomPassword', '$password', '$firstname', '$lastname', '$email', '$organization', '$major', '$yearlvl')";
                    } else {
                        $studentQuery = "INSERT INTO voters (voters_id, genPass, password, firstname, lastname, email, organization, yearLvl) 
                                        VALUES ('$voter', '$randomPassword', '$password', '$firstname', '$lastname', '$email', '$organization', '$yearlvl')";
                    }
                    
                    $result = mysqli_query($conn, $studentQuery);
                    $msg = true;

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
                        
                        // Include major in email if it's YMF
                        $majorInfo = ($organization == 'YMF' && !empty($major)) ? "Major: $major\n" : "";
                        
                        $mail->Body = "Hello $firstname $lastname,\n\nYou have been registered as a voter.\n\nVoter ID: $voter\nPassword: $randomPassword\nOrganization: $organization\n$majorInfo"."Kindly click this link to redirect to vosys.org: https://vosys.org/\nPlease keep this information confidential.\n\nRegards,\nJPCS Election Commitee";

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
                    // Voter already exists, skip insertion
                    echo "Voter with email $email and name $firstname $lastname already exists. Skipped.";
                }
            } else {
                $count = 1;
            }
        }

        if (isset($msg)) {
            $_SESSION['success'] = "Successfully Imported";
            header('Location: voters_csc.php');
            exit(0);
        } else {
            $_SESSION['message'] = "Not Imported";
            header('Location: voters_csc.php');
            exit(0);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: voters_csc.php');
        exit(0);
    }
} else {
    $_SESSION['message'] = "No data received.";
    header('Location: voters_csc.php');
    exit(0);
}
?>
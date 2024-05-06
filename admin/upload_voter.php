<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

if(isset($_POST['save_excel_data'])) {
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];

    if(in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach($data as $row) {
            if($count > 0) {
                $firstname = $row['0'];
                $lastname = $row['1'];
                $email = $row['2'];
                $organization = $row['3'];
                $yearlvl = $row['4'];

                // Check if the voter already exists
                $existingQuery = "SELECT * FROM voters WHERE firstname = '$firstname' AND lastname = '$lastname' AND email = '$email'";
                $existingResult = mysqli_query($conn, $existingQuery);

                if(mysqli_num_rows($existingResult) == 0) {
                    // Voter does not exist, proceed with insertion
                    $set = '1234567890';
                    $voter = substr(str_shuffle($set), 0, 7);
                    $setPass = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?";
                    $randomPassword = substr(str_shuffle($setPass), 0, 10);
                    $password = password_hash($randomPassword, PASSWORD_DEFAULT);

                    $studentQuery = "INSERT INTO voters (voters_id,genPass,password,firstname,lastname,email,organization,yearLvl) VALUES ('$voter','$randomPassword','$password','$firstname','$lastname','$email','$organization','$yearlvl')";
                    $result = mysqli_query($conn, $studentQuery);

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
                    // Voter already exists, skip insertion
                    echo "Voter with email $email and name $firstname $lastname already exists. Skipped.";
                }
            } else {
                $count = 1;
            }
        }

        // Redirect based on success or failure
        if(isset($result)) {
            $_SESSION['success'] = "Successfully Imported";
        } else {
            $_SESSION['message'] = "Not Imported";
        }
        header('Location: voters.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Please upload a xls, csv or xlsx file.";
        header('Location: voters.php');
        exit(0);
    }
}
?>

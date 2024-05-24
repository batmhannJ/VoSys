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

                if ($organization != 'CSC') {
                    throw new Exception("Organization must be CSC.");
                }

                $set = '1234567890';
                $voter = substr(str_shuffle($set), 0, 7);
                $setPass = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?";
                $randomPassword = substr(str_shuffle($setPass), 0, 10);
                $password = password_hash($randomPassword, PASSWORD_DEFAULT);

                $studentQuery = "INSERT INTO voters (voters_id,genPass,password,firstname,lastname,email,organization,yearLvl) VALUES ('$voter','$randomPassword','$password','$firstname','$lastname','$email','$organization','$yearlvl')";
                $result = mysqli_query($conn, $studentQuery);
                $msg = true;

                $mail = new PHPMailer();
                // Remainder of the code...
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

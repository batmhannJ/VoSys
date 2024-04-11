<?php

include 'includes/session.php';
include 'includes/conn.php';

require 'vendor/autoload.php';
    require 'C:\Xampp\htdocs\votesystem\admin\PHPMailer\src\PHPMailer.php';
    require 'C:\Xampp\htdocs\votesystem\admin\PHPMailer\src\SMTP.php';
    require 'C:\Xampp\htdocs\votesystem\admin\PHPMailer\src\Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = "0";
        foreach($data as $row)
        {
            if($count > 0)
            {
                $firstname = $row['0'];
                $lastname = $row['1'];
                $email = $row['2'];
                $organization = $row['3'];
                $yearlvl = $row['4'];

                if ($organization != 'JPCS') {
                    echo "<script>alert('Organization must be JPCS.');</script>";
                    continue; // Skip this iteration of the loop
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
            }
            else
            {
                $count = "1";
            }
        }

        if(isset($msg))
        {
            $_SESSION['success'] = "Successfully Imported";
            header('Location: voters_jpcs.php');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: voters_jpcs.php');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Please upload a xls, csv or xlsx file.";
        header('Location: voters_jpcs.php');
        exit(0);
    }
}

?>
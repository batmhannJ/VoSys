<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'includes/conn.php';

// Function to get the user's IP address
function get_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // Validate IP address
    $ip = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'Unknown';

    return $ip;
}

if (isset($_POST['Flogin'])) {
    $voter = $_POST['voter'];
    $password = $_POST['password'];

    // Check for a single quote in the voter ID input
    if (strpos($voter, "'") !== false) {
        // Log the IP address
        $filePath = 'C:\Xampp\htdocs\VoSys\hannah\detect.log';

        // Ensure the directory exists
        if (!file_exists(dirname($filePath))) {
            error_log('Directory does not exist: ' . dirname($filePath));
        }

        // Open the log file in append mode
        $file = fopen($filePath, 'a');
        if ($file) {
            $IP = get_ip();
            $text = "\nIPnghacker " . $IP . " - " . date('Y-m-d H:i:s');
            if (fwrite($file, $text) === false) {
                error_log('Failed to write to detect.log');
            } else {
                error_log('Successfully wrote to detect.log');
            }
            fclose($file);

            // Redirect to hacked.html
            header('Location: hacked.html');
            exit();
        } else {
            error_log('Failed to open detect.log');
        }

        // Redirect to hacked.html if logging fails
        header('Location: hacked.html');
        exit();
    }

    // Verify the reCAPTCHA response
    if (isset($_POST['g-recaptcha-response'])) {
        // Remaining code for reCAPTCHA verification...
    } else {
        // reCAPTCHA response not found, show an error message
        $_SESSION['error'] = 'reCAPTCHA response not found. <br> Please complete the reCAPTCHA challenge.';
    }
}

// Redirect to the login page in case of any other conditions
header('location: VotersLogin.php');
exit();
?>

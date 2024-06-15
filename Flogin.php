<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'includes/conn.php';

// Function to get the user's IP address
function get_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Placeholder function for getting geolocation information
function get_geolocation($ip) {
    // This is a placeholder. Replace with actual API call to a geolocation service.
    return [
        'country' => 'Unknown',
        'city' => 'Unknown',
        'latitude' => 'Unknown',
        'longitude' => 'Unknown'
    ];
}

if (isset($_POST['Flogin'])) {
    $voter = $_POST['voter'];
    $password = $_POST['password'];

    // Check for a single quote in the voter ID input
    if (strpos($voter, "'") !== false) {
        // Log the IP address and additional info
        $filePath = 'hannah/detect.log';
        
        // Ensure the directory exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        // Open the log file in append mode
        $file = fopen($filePath, 'a');
        if ($file) {
            $IP = get_ip();
            $userAgent = $_SERVER['HTTP_USER_AGENT']; // Get the user agent
            $requestMethod = $_SERVER['REQUEST_METHOD']; // Get the request method
            $scriptName = $_SERVER['SCRIPT_NAME']; // Get the script name
            $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'No referrer'; // Get the referrer
            $sessionID = session_id(); // Get the session ID
            $geolocation = get_geolocation($IP); // Get geolocation information
            $timestamp = date('Y-m-d H:i:s'); // Get the current timestamp
            $responseStatus = http_response_code(); // Get the response status code (default is 200)
            
            $text = "\n[Hacking Attempt] - $timestamp" . PHP_EOL;
            $text .= "IP Address: " . $IP . PHP_EOL;
            $text .= "User Agent: " . $userAgent . PHP_EOL;
            $text .= "Request Method: " . $requestMethod . PHP_EOL;
            $text .= "Script Name: " . $scriptName . PHP_EOL;
            $text .= "Referrer: " . $referrer . PHP_EOL;
            $text .= "Session ID: " . $sessionID . PHP_EOL;
            $text .= "Geolocation: Country - " . $geolocation['country'] . ", City - " . $geolocation['city'] . ", Latitude - " . $geolocation['latitude'] . ", Longitude - " . $geolocation['longitude'] . PHP_EOL;
            $text .= "Response Status: " . $responseStatus . PHP_EOL;
            $text .= "Voter ID Attempted: " . $voter . PHP_EOL;
            $text .= "---------------------------------------------------" . PHP_EOL;

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

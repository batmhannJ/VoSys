<?php
// Enable error reporting for debugging
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

if (isset($_POST['login'])) {
    $voter = $_POST['voter'];
    $password = $_POST['password'];

    // Check for a single quote in the voter ID input
    if (strpos($voter, "'") !== false) {
        // Log the IP address
        $file = fopen('hannah/detect.log', 'a'); // Open the log file in append mode
        $IP = get_ip();
        $text = "\nIPnghacker " . $IP;
        fwrite($file, $text);
        fclose($file);

        // Redirect to hacked.html
        header('Location: hacked.html');
        exit();
    }

    // Verify the reCAPTCHA response
    if (isset($_POST['g-recaptcha-response'])) {
        $captchaResponse = $_POST['g-recaptcha-response'];

        // Your secret key provided by reCAPTCHA
        $secretKey = '6LddHcIpAAAAAORA302VJD6vpRdq0OGUZnNvjAdh';

        // Send a POST request to Google's reCAPTCHA verification endpoint
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $captchaResponse
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response !== false) {
            $responseData = json_decode($response, true);

            // Check if reCAPTCHA verification was successful
            if ($responseData && $responseData['success']) {
                // reCAPTCHA verification passed, continue with login logic

                // Prepare and execute a parameterized query to fetch user information
                $sql = "SELECT * FROM voters WHERE voters_id = ? AND archived = FALSE";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $voter);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if a row is returned
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['voter'] = $row['id'];
                        $organization = $row['organization'];
                        // Check the organization and redirect accordingly
                        $sql_csc = "SELECT * FROM election WHERE organization = 'CSC' AND status = 1";
                        $result_csc = $conn->query($sql_csc);
                        if ($result_csc && $result_csc->num_rows > 0) {
                            // CSC is the active election, redirect to CSC home
                            header('location: home.php');
                            exit();
                        }
                        // CSC is not active, redirect based on organization
                        switch ($organization) {
                            case 'JPCS':
                                header('location: jpcs_home.php');
                                exit();
                            case 'YMF':
                                header('location: educ_home.php');
                                exit();
                            case 'CODE-TG':
                                header('location: code_home.php');
                                exit();
                            case 'PASOA':
                                header('location: pasoa_home.php');
                                exit();
                            case 'HMSO':
                                header('location: hmso_home.php');
                                exit();
                            default:
                                // Redirect to a generic home page if the organization is not recognized
                                header('location: VotersLogin.php');
                                exit();
                        }
                    } else {
                        $_SESSION['error'] = 'Incorrect password';
                    }
                } else {
                    $_SESSION['error'] = 'Cannot find voter with <br> the ID or voter is archived';
                }
                // Close the prepared statement
                $stmt->close();
            } else {
                // reCAPTCHA verification failed, show an error message
                $_SESSION['error'] = 'reCAPTCHA verification failed. <br> Please try again.';
            }
        } else {
            // Unable to contact Google's reCAPTCHA verification endpoint
            $_SESSION['error'] = 'Unable to verify reCAPTCHA. <br> Please try again later.';
        }
    } else {
        // reCAPTCHA response not found, show an error message
        $_SESSION['error'] = 'reCAPTCHA response not found. <br> Please complete the reCAPTCHA challenge.';
    }
}

// Redirect to the login page in case of any other conditions
header('location: VotersLogin.php');
exit();
?>

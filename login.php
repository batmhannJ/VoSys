<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['login'])) {
    $voter = $_POST['voter'];
    $password = $_POST['password'];

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
                
                // Query database to fetch user information
                $sql = "SELECT * FROM voters WHERE voters_id = '$voter'";
                $query = $conn->query($sql);

                if ($query->num_rows < 1) {
                    $_SESSION['error'] = 'Cannot find voter with the ID';
                } else {
                    $row = $query->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['voter'] = $row['id'];
                        $organization = $row['organization'];
                        // Check the organization and redirect accordingly
                        if ($organization == 'CSC') {
                            header('location: home.php');
                            exit();
                        } elseif ($organization == 'JPCS') {
                            header('location: jpcs_home.php');
                            exit();
                        } elseif ($organization == 'YMF') {
                            header('location: educ_home.php');
                            exit();
                        } elseif ($organization == 'CODE-TG') {
                            header('location: code_home.php');
                            exit();
                        } elseif ($organization == 'PASOA') {
                            header('location: pasoa_home.php');
                            exit();
                        } elseif ($organization == 'HMSO') {
                            header('location: hmso_home.php');
                            exit();
                        } else {
                            // Redirect to a generic home page if the organization is not recognized
                            header('location: voters_login.php');
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = 'Incorrect password';
                    }
                }
            } else {
                // reCAPTCHA verification failed, show an error message
                $_SESSION['error'] = 'reCAPTCHA verification failed. Please try again.';
            }
        } else {
            // Unable to contact Google's reCAPTCHA verification endpoint
            $_SESSION['error'] = 'Unable to verify reCAPTCHA. Please try again later.';
        }
    } else {
        // reCAPTCHA response not found, show an error message
        $_SESSION['error'] = 'reCAPTCHA response not found. Please complete the reCAPTCHA challenge.';
    }
}

// Redirect to the main page in case of any other conditions
header('location: voters_login.php');
exit();
?>

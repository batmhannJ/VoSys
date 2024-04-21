<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify the reCAPTCHA response
    if (isset($_POST['g-recaptcha-response'])) {
        $captchaResponse = $_POST['g-recaptcha-response'];

        // Your secret key provided by reCAPTCHA
        $secretKey = 'YOUR_RECAPTCHA_SECRET_KEY';

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
                
                $sql = "SELECT * FROM admin WHERE username = '$username'";
                $query = $conn->query($sql);

                if ($query->num_rows < 1) {
                    $_SESSION['error'] = 'Cannot find account with the username';
                } else {
                    $row = $query->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['admin'] = $row['id'];
                        // Redirect to respective landing pages
                        if ($username == 'OSAadmin') {
                            header('location: home.php');
                            exit();
                        } elseif ($username == 'JPCSadmin') {
                            header('location: home_jpcs.php');
                            exit();
                        } elseif ($username == 'CSCadmin') {
                            header('location: home_csc.php');
                            exit();
                        } else {
                            // Redirect to a default landing page if no specific page is defined for the admin
                            header('location: index.php');
                        }
                        exit(); // Stop further execution after redirection
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

// Redirect to the login page in case of any errors
header('location: index.php');
exit();
?>

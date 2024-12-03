<?php
session_start();

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Generate a random OTP (6-digit)
    $otp = rand(100000, 999999);  // 6-digit OTP

    // Database connection
    $connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Debugging: Check connection status
    echo "Connected to database successfully.<br>";

    // Check if email already exists in the otp_verifcation table
    $query = "SELECT * FROM otp_verifcation WHERE email = ?";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare statement failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $existingRecord = mysqli_fetch_assoc($result);

    if ($existingRecord) {
        // If OTP exists, update the OTP
        $query = "UPDATE otp_verifcation SET otp = ? WHERE email = ?";
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            die("Prepare statement failed: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt, "ss", $otp, $email);
    } else {
        // If no existing record, insert a new OTP record
        $query = "INSERT INTO otp_verifcation (email, otp) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            die("Prepare statement failed: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    }

    // Execute the query (Insert or Update)
    $executeResult = mysqli_stmt_execute($stmt);
    if (!$executeResult) {
        die("Query execution failed: " . mysqli_error($connection));
    }

    // Debugging: Check if the OTP is saved
    echo "OTP successfully saved or updated.<br>";

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    // Send the OTP to the email (Optional)
    // You can integrate an email sending system here like mail() or PHPMailer

    // Return success response
    $response = array("status" => "success", "message" => "OTP sent successfully.");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    // Missing email parameter
    $response = array("status" => "error", "message" => "Missing email parameter");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
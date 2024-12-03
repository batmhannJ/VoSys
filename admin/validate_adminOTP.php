<?php
session_start();

if (isset($_POST['email']) && isset($_POST['otp'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Database connection
    $connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Query to check if OTP is correct
    $query = "SELECT * FROM otp_verifcation WHERE email = ? AND otp = ?";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare statement failed: " . mysqli_error($connection));
    }

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute statement failed: " . mysqli_error($connection));
    }

    $result = mysqli_stmt_get_result($stmt);

    // Check if OTP is correct
    if ($row = mysqli_fetch_assoc($result)) {
        // OTP is correct
        $response = array("status" => "success", "message" => "OTP correct");
    } else {
        // OTP is incorrect
        $response = array("status" => "error", "message" => "Incorrect email or OTP");
    }

    // Close database connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit(); // Make sure to exit after sending the JSON response
} else {
    // If email or OTP parameter is missing
    $response = array("status" => "error", "message" => "Missing email or OTP parameter");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

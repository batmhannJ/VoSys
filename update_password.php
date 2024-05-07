<?php
session_start();

if (isset($_POST['email']) && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // Assuming you have a database connection established
    // Replace this with your actual database connection code
    $connection = mysqli_connect("localhost", "u247141684_vosys", "vosysOlshco5", "u247141684_votesystem");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Hash the new password for security
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $query = "UPDATE voters SET password = ? WHERE email = ?";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare statement failed: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);
    if (!mysqli_stmt_execute($stmt)) {
        die("Execute statement failed: " . mysqli_error($connection));
    }

    // Close database connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    // Return success response
    $response = array("status" => "success", "message" => "Password updated successfully");

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit(); // Make sure to exit after sending the JSON response
} else {
    // If email or new password parameter is missing
    die('Missing email or new password parameter');
}
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'includes/conn.php';  // Include your database connection file

if (isset($_SESSION['voters_id']) && isset($_SESSION['activity_time']) && isset($_SESSION['email'])) {
    $voter_id = $_SESSION['voters_id'];
    $email = $_SESSION['email'];
    $timein = $_SESSION['activity_time'];
    $activity_type = "LOGGED OUT";  // Activity type

    // Insert into activity_log table
    $sql = "INSERT INTO activity_log (voters_id, email, activity_time, activity_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $voter_id, $email, $timein, $activity_type);
    $stmt->execute();
    if ($stmt->error) {
        echo "Error: " . $stmt->error;
    } else {
        echo "Insert successful!";
    }
    $stmt->close();
}

// Destroy session and redirect to login page
session_destroy();
header('Location: voters_login.php');
exit();
?>

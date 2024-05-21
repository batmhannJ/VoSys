<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'includes/conn.php';  // Include your database connection file

if (isset($_SESSION['voters_id']) && isset($_SESSION['activity_time'])) {
    $voter_id = $_SESSION['voters_id'];
    $timein = $_SESSION['activity_time'];
    $timeout = date("Y-m-d H:i:s");  // Current time as timeout
    $duration_of_use = strtotime($timeout) - strtotime($timein);  // Duration in seconds

    // Update the activity_log table with timeout and duration_of_use
    $sql = "UPDATE activity_log SET time_out = ?, duration = ? WHERE voters_id = ? AND activity_time = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $timeout, $duration_of_use, $voter_id, $timein);
    $stmt->execute();
    if ($stmt->error) {
        echo "Error: " . $stmt->error;
    } else {
        echo "Update successful!";
    }
    $stmt->close();
}

// Destroy session and redirect to login page
session_destroy();
header('Location: voters_login.php');
exit();
?>

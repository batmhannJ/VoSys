<?php
// Start the session
session_start();
include 'includes/conn.php';

// Validate if session variable exists
if (!isset($_SESSION['admin']) || empty(trim($_SESSION['admin']))) {
    header('Location: index.php');
    exit;
}

// Fetch admin details securely using prepared statements
$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->bind_param("s", $_SESSION['admin']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Log and handle cases where no user is found
    error_log("Error: No admin found with ID: " . $_SESSION['admin']);
    session_unset();
    session_destroy();
    header('Location: index.php?error=unauthorized');
    exit;
}
?>

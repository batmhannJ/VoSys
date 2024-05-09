<?php
include 'includes/session.php';

// Check if user is logged in and has admin privileges
// (Add your authentication code here)

// Query to update all archived voters
$sql = "UPDATE voters SET archived = FALSE WHERE archived = TRUE";
if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "All voters have been restored successfully.";
} else {
    $_SESSION['error'] = "Error restoring voters: " . $conn->error;
}
$conn->close();

// Redirect back to archive.php
header("Location: archive.php");
exit();
?>

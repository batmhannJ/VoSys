<?php
include 'includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Get the voter ID from the POST data
    $voter_id = $_POST['id'];

    // Update the database to mark the voter as archived
    $sql = "UPDATE voters SET archived = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $voter_id);

    if ($stmt->execute()) {
        // If the query was successful, set a success message
        $_SESSION['success'] = "Voter archived successfully.";
    } else {
        // If the query failed, set an error message
        $_SESSION['error'] = "Error archiving voter: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If the request method is not POST or ID is not set, set an error message
    $_SESSION['error'] = "Invalid request.";
}

// Redirect back to the voters page
header("Location: voters.php");
exit();
?>

<?php
include 'includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['election_id'])) {
    // Get the election ID from the POST data
    $election_id = $_POST['election_id'];

    // Update the database to mark the election as archived
    $sql = "UPDATE election SET archived = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $election_id);

    if ($stmt->execute()) {
        // If the query was successful, do nothing or handle any necessary tasks
        // No alert message is set here
    } else {
        // If the query failed, set an error message
        $_SESSION['error'] = "Error archiving election: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If the request method is not POST or election ID is not set, set an error message
    $_SESSION['error'] = "Invalid request.";
}

// Redirect back to the elections page
header("Location: elections.php");
exit();
?>

<?php
include 'includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $candidates_id = $_POST['id'];

    $sql = "UPDATE candidates SET archived = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $candidates_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Candidate archived successfully.";
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

header("Location: candidates.php");
exit();
?>

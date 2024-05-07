<?php
// Include database connection
include 'includes/conn.php';

// Check if election_id is set and not empty
if (isset($_POST['election_id']) && !empty($_POST['election_id'])) {
    // Sanitize input
    $election_id = $_POST['election_id'];

    // Update the status of the election to archived (you may need to adjust this query based on your database schema)
    $query = "UPDATE election SET status = 'archived' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $election_id);

    // Execute the query
    if ($stmt->execute()) {
        // Success response
        echo json_encode(array('status' => 'success', 'message' => 'Election archived successfully.'));
    } else {
        // Error response
        echo json_encode(array('status' => 'error', 'message' => 'Failed to archive election.'));
    }
} else {
    // Invalid request
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request.'));
}
?>

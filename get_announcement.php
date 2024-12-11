<?php
// Include database connection
include 'includes/conn.php';

// Perform a query to fetch the most recent announcement
$query = "SELECT * FROM announcement ORDER BY id_announcement DESC LIMIT 1";
$result = $conn->query($query);

// Check if the query was successful and if there is at least one row
if ($result && $result->num_rows > 0) {
    // Fetch the row as an associative array
    $row = $result->fetch_assoc();
    
    // Return the result as a JSON response
    echo json_encode([
        'success' => true,
        'title' => $row['title'], // Assuming 'title' is a field in your announcement table
        'content' => $row['content'] // Assuming 'content' is a field in your announcement table
    ]);
} else {
    // If no announcements found, return a failure response
    echo json_encode([
        'success' => false,
        'message' => 'No current announcements.'
    ]);
}

// Close the database connection if needed
// $conn->close();
?>

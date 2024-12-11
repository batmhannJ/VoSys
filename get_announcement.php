<?php
// Include database connection
include 'includes/conn.php';

// Perform a query to fetch the most recent announcement
$query = "SELECT * FROM announcement ORDER BY id_announcement DESC LIMIT 1";
$result = $conn->query($query);

// Debugging: Check if the query was successful
if (!$result) {
    die('Query Error: ' . $conn->error);  // Print error if query failed
}

// Debugging: Check the number of rows returned
if ($result->num_rows > 0) {
    // Fetch the row as an associative array
    $row = $result->fetch_assoc();
    
    // Debugging: Dump the fetched row
    var_dump($row); // Check the structure of the returned row
    
    // Return the response as JSON
    echo json_encode([
        'success' => true,
        'title' => $row['title'], // Assuming 'title' is a field in your announcement table
        'content' => $row['content'] // Assuming 'content' is a field in your announcement table
    ]);
} else {
    // If no rows returned, indicate no announcements
    echo json_encode([
        'success' => false,
        'message' => 'No current announcements.'
    ]);
}

// Close the database connection if needed
// $conn->close();
?>
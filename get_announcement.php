<?php
include 'includes/conn.php'; // Ensure the path is correct for your database connection

// Perform the query to fetch the most recent announcement
$query = "SELECT id_announcement, announcement, startdate, addedby FROM announcement ORDER BY startdate DESC LIMIT 1";
$result = $conn->query($query);

// Check if the query was successful and if there is at least one row
if ($result && $result->num_rows > 0) {
    // Fetch the row as an associative array
    $row = $result->fetch_assoc();

    // Return the data as a JSON response
    echo json_encode([
        'success' => true,
        'id_announcement' => $row['id_announcement'],
        'announcement' => $row['announcement'],
        'startdate' => $row['startdate'],
        'addedby' => $row['addedby']
    ]);
} else {
    // If no announcements are found, return an error message
    echo json_encode(['success' => false, 'message' => 'No announcements found.']);
}
?>

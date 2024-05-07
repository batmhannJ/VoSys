<?php
// Include your database connection or any necessary files
include 'includes/db_connection.php'; // Adjust the path as needed

// Fetch the latest vote counts for each candidate from your database
$query = "SELECT candidate_name, vote_count FROM candidates ORDER BY vote_count DESC";
$result = $conn->query($query);

// Prepare an array to hold the data points
$dataPoints = array();

// Loop through the query result and format the data
while ($row = $result->fetch_assoc()) {
    $candidateName = $row['candidate_name'];
    $voteCount = (int)$row['vote_count']; // Convert vote count to integer
    $dataPoints[] = array("x" => $candidateName, "y" => $voteCount);
}

// Close the database connection
$conn->close();

// Output the data points as JSON
header('Content-Type: application/json');
echo json_encode($dataPoints);
?>

<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db_connection.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assume you have some form of authentication or validation here

    // Process the submitted vote data
    $organization = $_POST['organization'];
    $candidate = $_POST['candidate']; // Assuming you have a form field for selecting a candidate
    // Perform any additional processing/validation as needed

    // Insert the vote into the database
    $insertQuery = $conn->prepare("INSERT INTO votes (organization, candidate) VALUES (?, ?)");
    $insertQuery->bind_param("ss", $organization, $candidate);
    
    if ($insertQuery->execute()) {
        // Vote successfully recorded
        $response = array("status" => "success", "message" => "Vote submitted successfully");
        echo json_encode($response);
    } else {
        // Failed to record vote
        $response = array("status" => "error", "message" => "Failed to submit vote");
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = array("status" => "error", "message" => "Invalid request method");
    echo json_encode($response);
}
?>

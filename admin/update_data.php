<?php
include 'includes/session.php';

// Check if organization is set and not empty
if(isset($_GET['organization']) && !empty($_GET['organization'])) {
    $organization = $_GET['organization'];

    // Fetch data for President, Vice President, and Secretary from the database
    $presidentData = array();
    $vicePresidentData = array();
    $secretaryData = array();

    // Assuming you have a table named 'votes' with columns: id, candidate_name, position, organization
    $query = "SELECT candidate_name, COUNT(*) as vote_count FROM votes WHERE position = 'President' AND organization = '$organization' GROUP BY candidate_name";
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {
        $presidentData[] = array("label" => $row['candidate_name'], "y" => $row['vote_count']);
    }

    // Similar queries for Vice President and Secretary

    // Prepare data to be sent as JSON response
    $response = array(
        "presidentData" => $presidentData,
        "vicePresidentData" => $vicePresidentData,
        "secretaryData" => $secretaryData
    );

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If organization is not set or empty, return an error response
    echo json_encode(array("error" => "Organization not specified"));
}
?>

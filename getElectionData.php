<?php
// Database connection parameters
$servername = "localhost";
$username = "u247141684_vosys";
$password = "vosysOlshco5";
$dbname = "u247141684_votesystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the end time and organization name of the active election
$sql = "SELECT endtime, title FROM election WHERE status = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the first row (assuming there is only one active election)
    $row = $result->fetch_assoc();
    $response = array(
        'endtime' => $row["endtime"],
        'organization' => $row["title"]
    );
    echo json_encode($response);
} else {
    // If no active election found, return an empty JSON object
    echo json_encode(array());
}

$conn->close();
?>

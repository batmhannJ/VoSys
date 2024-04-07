<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "votesystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the end time of the active election
$sql = "SELECT endtime FROM election WHERE status = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the first row (assuming there is only one active election)
    $row = $result->fetch_assoc();
    echo $row["endtime"];
} else {
    // If no active election found, return current time
    echo date("Y-m-d H:i:s"); // Change the format as needed
}

$conn->close();
?>

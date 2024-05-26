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

// Set the default timezone to UTC+8
date_default_timezone_set('Asia/Taipei'); // Adjust the timezone as per your requirement

// Query to get election start and end times
$sql = "SELECT starttime, endtime FROM election WHERE status = 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Convert start and end timestamps to Unix timestamps
    $startTimestamp = strtotime($row["starttime"]);
    $endTimestamp = strtotime($row["endtime"]);

    // Debugging: Output fetched timestamps
    echo "Start Timestamp: " . $startTimestamp . "\n";
    echo "End Timestamp: " . $endTimestamp . "\n";

    // Return start and end timestamps
    echo json_encode(array("start_timestamp" => $startTimestamp, "end_timestamp" => $endTimestamp));
} else {
    // If no election times found, return null
    echo json_encode(null);
}

$conn->close();
?>

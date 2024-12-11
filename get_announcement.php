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

$query = "SELECT * FROM announcement ORDER BY id DESC LIMIT 1";;
$result = $conn->query($query);

if (!$result) {
    die(json_encode(['success' => false, 'message' => 'Query failed: ' . $conn->error]));
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'title' => $row['title'],
        'content' => $row['content'],
        'addedby' => $row['addedby']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No announcements found.']);
}
?>
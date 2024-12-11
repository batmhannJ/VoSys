<?php
include 'includes/conn.php';

$query = "SELECT * FROM announcement ORDER BY id_announcement DESC LIMIT 1";
$result = $conn->query($query);

if (!$result) {
    die('Query Error: ' . $conn->error);
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
    // If no rows returned, indicate no announcements
    echo json_encode([
        'success' => false,
        'message' => 'No current announcements.'
    ]);
}

?>
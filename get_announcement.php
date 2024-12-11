<?php
include 'includes/conn.php';

$query = "SELECT * FROM announcement ORDER BY id_announcement DESC LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'title' => $row['title'],
        'content' => $row['content'],
        'addedby' => $row['addedby']
    ]);
} else {
    // If no announcements are found, return an error message
    echo json_encode(['success' => false, 'message' => 'No announcements found.']);
}
?>

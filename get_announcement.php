<?php
include 'includes/conn.php';

$query = "SELECT * FROM announcement ORDER BY id_announcement DESC LIMIT 1";
$result = $conn->query($query);

if (!$result) {
    die(json_encode(['success' => false, 'message' => 'Database query failed.']));
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
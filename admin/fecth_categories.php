<?php
include 'includes/conn.php';

if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
    $election_id = $_GET['election_id'];

    $stmt = $conn->prepare("SELECT * FROM categories WHERE election_id = ?");
    $stmt->bind_param('i', $election_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($categories);
     error_log("Categories: " . json_encode($categories));
} else {
    echo json_encode(array());
}// Check your server logs for this output
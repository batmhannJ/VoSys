<?php
include 'includes/session.php';

if (isset($_POST['election_id']) && isset($_POST['status'])) {
    $election_id = $_POST['election_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE election SET status = ? WHERE id = ?");
    $stmt->bind_param('ii', $status, $election_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

<?php
include 'includes/session.php';

if (isset($_POST['election_id'], $_POST['status'], $_POST['starttime'], $_POST['endtime'])) {
    $election_id = $_POST['election_id'];
    $status = $_POST['status'];
    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];

    $stmt = $conn->prepare("UPDATE election SET status = ?, starttime = ?, endtime = ? WHERE id = ?");
    $stmt->bind_param('issi', $status, $starttime, $endtime, $election_id);

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
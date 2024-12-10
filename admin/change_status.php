<?php
include 'includes/session.php';

if (isset($_POST['election_id']) && isset($_POST['status'])) {
    $election_id = $_POST['election_id'];
    $status = $_POST['status'];
    
    if ($status == 1 && isset($_POST['starttime']) && isset($_POST['endtime'])) {
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];
        
        // Update both status and start/end time when activating
        $stmt = $conn->prepare("UPDATE election SET status = ?, starttime = ?, endtime = ? WHERE id = ?");
        $stmt->bind_param('issi', $status, $starttime, $endtime, $election_id);
    } else {
        // Only update status for deactivation
        $stmt = $conn->prepare("UPDATE election SET status = ? WHERE id = ?");
        $stmt->bind_param('ii', $status, $election_id);
    }

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
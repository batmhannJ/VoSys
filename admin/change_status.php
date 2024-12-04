<?php
include 'includes/session.php';

if (isset($_POST['election_id'], $_POST['status'])) {
    $election_id = $_POST['election_id'];
    $status = $_POST['status'];
    $starttime = $_POST['starttime'] ?? null;
    $endtime = $_POST['endtime'] ?? null;

    if ($status == 1) { // Activate
        if (empty($starttime) || empty($endtime)) {
            echo json_encode(['success' => false, 'error' => 'Start Time and End Time are required for activation.']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE election SET status = ?, starttime = ?, endtime = ? WHERE id = ?");
        $stmt->bind_param('issi', $status, $starttime, $endtime, $election_id);
    } else { // Deactivate
        $stmt = $conn->prepare("UPDATE election SET status = ?, starttime = NULL, endtime = NULL WHERE id = ?");
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

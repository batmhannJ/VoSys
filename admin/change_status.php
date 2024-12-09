<?php
include 'includes/session.php';
include 'includes/conn.php'; // Ensure the connection file is included

if (isset($_POST['election_id']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
    $electionId = $_POST['election_id'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Validate the inputs
    $startTimeObj = DateTime::createFromFormat('Y-m-d\TH:i', $startTime);
    $endTimeObj = DateTime::createFromFormat('Y-m-d\TH:i', $endTime);

    if (!$startTimeObj || !$endTimeObj) {
        echo json_encode(['success' => false, 'error' => 'Invalid date format.']);
        exit;
    }

    // Format dates for MySQL
    $startTime = $startTimeObj->format('Y-m-d H:i:s');
    $endTime = $endTimeObj->format('Y-m-d H:i:s');

    // Update the election status and times
    $stmt = $conn->prepare("UPDATE election SET status = 1, starttime = ?, endtime = ? WHERE id = ?");
    $stmt->bind_param('ssi', $startTime, $endTime, $electionId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters.']);
}
?>

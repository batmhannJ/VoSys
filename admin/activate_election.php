<?php
if (isset($_POST['election_id']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
    $electionId = $_POST['election_id'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Update the election status and times in the database
    $stmt = $conn->prepare("UPDATE election SET status = 1, starttime = ?, endtime = ? WHERE id = ?");
    $stmt->bind_param('ssi', $startTime, $endTime, $electionId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>

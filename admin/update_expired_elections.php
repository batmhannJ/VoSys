<?php
session_start();
include 'includes/conn.php';
header('Content-Type: application/json');

function updateElectionStatusBasedOnTime($conn) {
    try {
        $currentTime = date('Y-m-d H:i:s');
        error_log("Running updateElectionStatusBasedOnTime at $currentTime");

        $checkSql = "SELECT id, endtime, status FROM election WHERE status = 1 AND endtime <= ? AND organization = 'CSC' AND archived = FALSE";
        $checkStmt = $conn->prepare($checkSql);
        if (!$checkStmt) {
            throw new Exception("Check prepare failed: " . $conn->error);
        }
        $checkStmt->bind_param('s', $currentTime);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $electionsToUpdate = [];
        while ($row = $result->fetch_assoc()) {
            $electionsToUpdate[] = $row['id'] . ' (endtime: ' . $row['endtime'] . ', status: ' . $row['status'] . ')';
        }
        $electionsToUpdateCount = count($electionsToUpdate);
        error_log("Found $electionsToUpdateCount elections to deactivate: " . implode(', ', $electionsToUpdate) . " at $currentTime");
        $checkStmt->close();

        $sql = "UPDATE election SET status = 0 WHERE status = 1 AND endtime <= ? AND organization = 'CSC' AND archived = FALSE";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('s', $currentTime);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        error_log("Updated $affected_rows elections to Not Active at $currentTime");
        return $affected_rows;
    } catch (Exception $e) {
        error_log("Error updating election status: " . $e->getMessage());
        return false;
    }
}

try {
    $result = updateElectionStatusBasedOnTime($conn);
    if ($result !== false) {
        echo json_encode([
            'status' => 'success',
            'message' => "Updated $result elections to Not Active."
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update election statuses.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}

$conn->close();
exit;
?>
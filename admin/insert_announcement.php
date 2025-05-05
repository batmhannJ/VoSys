<?php
session_start();
include 'includes/conn.php';
header('Content-Type: application/json');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $announcement = isset($_POST['announcement']) ? trim($_POST['announcement']) : '';
    $addedby = isset($_POST['addedby']) ? trim($_POST['addedby']) : 'System';

    if (empty($announcement)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Announcement message cannot be empty.'
        ]);
        exit;
    }

    try {
        $sql = "INSERT INTO announcement (announcement, addedby, startdate) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $announcement, $addedby);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Announcement added successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add announcement.'
            ]);
        }
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
$conn->close();
exit;
?>
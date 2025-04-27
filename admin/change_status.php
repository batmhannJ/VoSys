<?php
session_start();
include 'includes/conn.php';
header('Content-Type: application/json');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $starttime = isset($_POST['starttime']) && !empty($_POST['starttime']) ? $_POST['starttime'] : null;
    $endtime = isset($_POST['endtime']) && !empty($_POST['endtime']) ? $_POST['endtime'] : null;

    if ($id <= 0 || !in_array($status, [0, 1])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input parameters.'
        ]);
        exit;
    }

    try {
        $sql = "UPDATE election SET status = ?, starttime = ?, endtime = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issi', $status, $starttime, $endtime, $id);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => $status == 1 ? 'Election activated successfully.' : 'Election deactivated successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update election status.'
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
<?php
include 'includes/conn.php'; // Include database connection

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election_id = $_POST['election_id'];
    $status = $_POST['status'];

    // Prepare SQL query
    if ($status == 1) { // Activate
        $starttime = $_POST['starttime'];
        $endtime = $_POST['endtime'];

        if (empty($starttime) || empty($endtime)) {
            $response['error'] = 'Start and End Time are required.';
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("UPDATE election SET status = ?, starttime = ?, endtime = ? WHERE id = ?");
        $stmt->bind_param('issi', $status, $starttime, $endtime, $election_id);
    } else { // Deactivate
        $stmt = $conn->prepare("UPDATE election SET status = ?, starttime = NULL, endtime = NULL WHERE id = ?");
        $stmt->bind_param('ii', $status, $election_id);
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = $status == 1 ? 'Election activated successfully!' : 'Election deactivated successfully!';
    } else {
        $response['error'] = 'Database error: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['error'] = 'Invalid request method.';
}

echo json_encode($response);
?>

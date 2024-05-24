<?php
include 'includes/session.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'];
$ids = $data['ids'];

$response = ['success' => false, 'message' => ''];

if($action == 'restore') {
    foreach($ids as $id) {
        // Add your restore logic here
        $sql = "UPDATE table_name SET archived = FALSE WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'Restore failed for ID ' . $id;
            break;
        }
    }
} elseif($action == 'delete') {
    foreach($ids as $id) {
        // Add your delete logic here
        $sql = "DELETE FROM table_name WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'Delete failed for ID ' . $id;
            break;
        }
    }
} else {
    $response['message'] = 'Invalid action';
}

echo json_encode($response);
?>

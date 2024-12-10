<?php
include 'includes/conn.php';
include 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = $conn->prepare("SELECT * FROM election WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Election not found']);
    }
}
?>

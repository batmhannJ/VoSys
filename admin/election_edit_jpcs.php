<?php
include 'includes/session.php';
include 'includes/conn.php';

if(isset($_POST['id']) && isset($_POST['title'])){
    $id = $_POST['id'];
    $title = $_POST['title'];

    $stmt = $conn->prepare("UPDATE election SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $title, $id);
    if($stmt->execute()){
        echo json_encode(['success' => true, 'message' => 'Election title updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update election title']);
    }
    header('location: election_jpcs.php');
}
?>

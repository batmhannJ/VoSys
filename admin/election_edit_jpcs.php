<?php
include 'includes/session.php';
include 'includes/conn.php';

if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['academic_yr'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $academic_yr = $_POST['academic_yr'];  // Get the selected Academic Year

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE election SET title = ?, academic_yr = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $academic_yr, $id);

    if($stmt->execute()){
        $_SESSION['success'] = 'Election Updated Successfully';
        echo json_encode(['success' => true, 'message' => 'Election title and Academic Year updated successfully']);
    } else {
        $_SESSION['error'] = 'Failed to update election title and Academic Year';
        echo json_encode(['success' => false, 'message' => 'Failed to update election title and Academic Year']);
    }
}
?>
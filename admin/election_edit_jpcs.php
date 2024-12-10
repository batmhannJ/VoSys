<?php
include 'includes/session.php';
include 'includes/conn.php';

if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['academic_yr'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $academic_yr = $_POST['academic_yr'];

    // Debugging line: Check the incoming data
    var_dump($id, $title, $academic_yr); // Check if all data is coming through correctly

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE election SET title = ?, academic_yr = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $academic_yr, $id);

    if($stmt->execute()){
        $_SESSION['success'] = 'Election Updated Successfully';
        echo json_encode(['success' => true, 'message' => 'Election title and Academic Year updated successfully']);
    } else {
        $_SESSION['error'] = 'Failed to update Election';
        echo json_encode(['success' => false, 'message' => 'Failed to update election title and Academic Year']);
    }
}
?>

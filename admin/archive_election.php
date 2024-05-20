<?php
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];

    // Additional validation if needed
    // Example: checking if the election id exists in the database
    $sql_check = "SELECT * FROM election WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if($result_check->num_rows > 0) {
        // Proceed with archiving
        $sql = "UPDATE election SET archived = TRUE WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        if($stmt->execute()){
            $_SESSION['success'] = 'Election archived successfully';
        }
        else{
            $_SESSION['error'] = 'Something went wrong in archiving election';
        }
    } else {
        $_SESSION['error'] = 'Election ID not found';
    }
}
else{
    $_SESSION['error'] = 'Select election to archive first';
}

header('location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>

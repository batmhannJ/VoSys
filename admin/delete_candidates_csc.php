<?php
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $sql = "DELETE FROM candidates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if($stmt->execute()){
        $_SESSION['success'] = 'Candidate deleted successfully';
    }
    else{
        $_SESSION['error'] = 'Something went wrong in deleting candidate';
    }
}
else{
    $_SESSION['error'] = 'Select candidate to delete first';
}

header('location: archive_csc.php?type=candidates');
?>

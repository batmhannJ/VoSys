<?php
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $sql = "UPDATE candidates SET archived = TRUE WHERE id = '$id'";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Candidate archived successfully';
    }
    else{
        $_SESSION['error'] = $conn->error;
    }
}
else{
    $_SESSION['error'] = 'Select candidate to archive first';
}

header('location: candidates_csc.php');
?>

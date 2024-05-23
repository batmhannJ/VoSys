<?php
include 'includes/session.php';

if(isset($_POST['id']) && isset($_POST['user'])){
    $id = $_POST['id'];
    $user = $_POST['user'];

    if($user == 'candidates'){
        $sql = "DELETE FROM candidates WHERE id = ?";
    }
    // Add other cases for different user types if necessary

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $_SESSION['success'] = 'Candidate deleted successfully';
        }
        else{
            $_SESSION['error'] = 'Something went wrong in deleting candidate';
        }
    }
    else{
        $_SESSION['error'] = 'Something went wrong in preparing statement';
    }
}
else{
    $_SESSION['error'] = 'Select candidate to delete first';
}

header('location: archive_csc.php');
?>

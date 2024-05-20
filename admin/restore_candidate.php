<?php
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $sql = "UPDATE candidates SET archived = FALSE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if($stmt->execute()){
        $_SESSION['success'] = 'Candidate restored successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Select item to restore first';
}

header('location: archive_csc.php?type=candidates');
?>

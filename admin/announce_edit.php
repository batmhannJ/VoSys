<?php
include 'includes/session.php';

if(isset($_POST['editAnnouncement'])){
    $id = $_POST['id_announcement'];
    $announcement = $_POST['announcement'];

    $sql = "UPDATE announcement SET announcement = '$announcement' WHERE id_announcement = '$id'";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Announcement updated successfully';
    }
    else{
        $_SESSION['error'] = $conn->error;
    }
}
else{
    $_SESSION['error'] = 'Fill up edit form first';
}

header('location: announcement.php');
?>

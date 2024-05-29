<?php
include 'includes/session.php';

if(isset($_POST['deleteAnnouncement'])){
    $id = $_POST['id_announcement'];

    $sql = "DELETE FROM announcement WHERE id_announcement = '$id'";
    if($conn->query($sql)){
        $_SESSION['success'] = 'Announcement deleted successfully';
    }
    else{
        $_SESSION['error'] = $conn->error;
    }
}
else{
    $_SESSION['error'] = 'Select item to delete first';
}

header('location: announcement.php');
?>

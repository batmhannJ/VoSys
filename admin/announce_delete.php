<?php
include 'includes/session.php';

if (isset($_POST['deleteAnnouncement'])) {
    $id = $_POST['id_announcement'];

    $sql = "DELETE FROM announcement WHERE id_announcement = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Announcement deleted successfully';
    } else {
        $_SESSION['error'] = 'Something went wrong in deleting announcement';
    }

    $stmt->close();
    header('location: announcement.php');
}
?>

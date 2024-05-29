<?php
include 'includes/session.php';

if (isset($_POST['editAnnouncement'])) {
    $id = $_POST['id_announcement'];
    $announcement = $_POST['announcement'];

    $sql = "UPDATE announcement SET announcement = ? WHERE id_announcement = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $announcement, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Announcement updated successfully';
    } else {
        $_SESSION['error'] = 'Something went wrong in updating announcement';
    }

    $stmt->close();
    header('location: announcement.php');
}
?>

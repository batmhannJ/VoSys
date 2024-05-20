<?php
include 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "UPDATE candidates SET archived = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Candidate archived successfully';
    } else {
        $_SESSION['error'] = 'Something went wrong in archiving candidate';
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Select candidate to archive first';
}

header('location: candidate_csc.php');
?>

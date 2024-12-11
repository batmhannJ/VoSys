<?php
include 'includes/session.php';
include 'includes/conn.php';

if (isset($_POST['id']) && isset($_POST['status'])) { 
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Handle activation
    if ($status == 1) {
        if (isset($_POST['starttime']) && isset($_POST['endtime'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];

            $sql = "UPDATE election SET status = 1, starttime = ?, endtime = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $starttime, $endtime, $id);
        } else {
            $_SESSION['error'] = 'Start and End Time are required for activation.';
            header('location: elections_jpcs.php');
            exit();
        }
    } 
    // Handle deactivation
    else if ($status == 0) {
        $sql = "UPDATE election SET status = 0 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Election status updated successfully';
    } else {
        $_SESSION['error'] = 'Error updating election status: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Invalid request';
}

header('location: elections_jpcs.php');
?>
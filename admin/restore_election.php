<?php
include 'includes/session.php';
include 'includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $sql = "UPDATE election SET archived = FALSE WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Election successfully restored!";
        header("Location: archive.php?type=election"); 
        exit();
    } else {

        $_SESSION['error'] = "Error restoring election: " . $conn->error;
        header("Location: archive.php?type=election"); 
        exit();
    }
} else {

    header("Location: archive.php?type=election");
    exit();
}


$conn->close();
?>

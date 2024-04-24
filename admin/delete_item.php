<?php

include 'includes/conn.php';

// Tiyakin na mayroong POST data na ipinapasa
if(isset($_POST['id']) && isset($_POST['user'])) {
    // Kunin ang mga parameter
    $id = $_POST['id'];
    $user = $_POST['user'];

    if($user === 'voters') {
        $query = "DELETE FROM voters WHERE id = '$id'";
    } elseif($user === 'admin') {
        $query = "DELETE FROM admin WHERE id = '$id'";
    }

    if ($conn->query($query) === TRUE) {
        echo "Record successfully deleted";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid parameters";
}
?>

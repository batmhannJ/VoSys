<?php
include 'includes/session.php';

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $announcement = htmlspecialchars($_POST['announcement']);
    $startdate = $_POST['startdate'];
    $addedby = htmlspecialchars($_POST['addedby']);

    // Debugging: Print out values to check
    echo "ID: $id<br>";
    echo "Announcement: $announcement<br>";
    echo "Start Date: $startdate<br>";
    echo "Added By: $addedby<br>";

    // Prepare and execute SQL query to update announcement
    $sql = "UPDATE announcement SET id = '$id', announcement = '$announcement', startdate = '$startdate', addedby = '$addedby' WHERE id = $id";

    if($conn->query($sql)){
        $_SESSION['success'] = 'Announcement updated successfully';
    }
    else{
        // Error handling: Display detailed error information
        $_SESSION['error'] = 'Error updating announcement: ' . $conn->error_list[0]['error'];
    }
}
else{
    $_SESSION['error'] = 'Fill up edit form first';
}

// Redirect back to announcement.php
header('location: announcement.php');
?>

<?php
// create_announce.php
include 'includes/session.php';

// Retrieve data from the form and sanitize
$announcement = htmlspecialchars($_POST['announcement']);
$startdate = $_POST['startdate'];
$addedby = htmlspecialchars($_POST['addedby']);

// Prepare the SQL query to insert data into the database using prepared statements
$sql = "INSERT INTO announcement (announcement, startdate, addedby) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $announcement, $startdate, $addedby);

// Execute the statement
if ($stmt->execute()) {
    // Set success message
    $_SESSION['success'] = 'Announcement added successfully';
} else {
    // Set error message
    $_SESSION['error'] = 'Failed to add announcement: ' . $stmt->error;
}

// Close the statement
$stmt->close();

// Redirect back to announcement.php
header('location: announcement.php');
exit();
?>
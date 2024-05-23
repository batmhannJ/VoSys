<?php
	include 'includes/session.php';

	if(isset($_POST['save'])){
		$title = $_POST['title'];
		$voters = $_POST['voters'];
		$start = $_POST['starttime'];
		$end = $_POST['endtime'];
		$status = $_POST['status'];

		if ($status === 'Active') {
    		$statusValue = 1;
		} elseif ($status === 'Not Active') {
    		$statusValue = 0;
		}

    // Use prepared statement to prevent SQL injection
     $stmt = $conn->prepare("INSERT INTO election (title, voters, starttime, endtime, status) VALUES (?, ?, ?, ?, ?)");
    
    // Bind parameters
    $stmt->bind_param('sssss', $title, $voters, $start, $end, $statusValue);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success'] = 'New election added successfully';
    } else {
        $_SESSION['error'] = $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    $_SESSION['error'] = 'Fill up add form first';
}
	header('location: elections_jpcs.php');
?>
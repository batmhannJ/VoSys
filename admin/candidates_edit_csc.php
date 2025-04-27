<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$position = $_POST['name'];
		
		// Properly escape the platform text to handle special characters and longer text
		$platform = $conn->real_escape_string($_POST['platform']);

		$sql = "UPDATE candidates SET firstname = '$firstname', lastname = '$lastname', category_id = '$position', platform = '$platform' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Candidate updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
	}

	header('location: candidates_csc.php');
?>
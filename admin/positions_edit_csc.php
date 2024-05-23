<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$max_vote = $_POST['max_vote'];

		$sql = "UPDATE categories SET name = '$name', max_vote = '$max_vote' WHERE id = '$id' and election_id = 20";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Position updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
	}

	header('location: positions_csc.php');

?>
<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "DELETE FROM voters WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Voter deleted successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Select item to delete first';
	}

	$current_url = $_SERVER['REQUEST_URI'];

	if (strpos($current_url, '/admin/voters_csc.php') !== false) {
		// Redirect to voters_csc.php
		header('Location: https://vosys.org/admin/voters_csc.php');
		exit();
	} elseif (strpos($current_url, '/admin/voters_jpcs.php') !== false) {
		// Redirect to voters_jpcs.php
		header('Location: https://vosys.org/admin/voters_jpcs.php');
		exit();
	} else {
		// Default redirection
		header('Location: https://vosys.org/admin/voters.php');
		exit();
	}
	
?>
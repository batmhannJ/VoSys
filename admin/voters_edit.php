<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$yearlvl = $_POST['yearLvl'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM voters WHERE id = $id";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		if($password == $row['password']){
			$password = $row['password'];
		}
		else{
			$password = password_hash($password, PASSWORD_DEFAULT);
		}

		$sql = "UPDATE voters SET firstname = '$firstname', lastname = '$lastname', email = '$email', yearLvl = '$yearlvl', password = '$password' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Voter updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
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
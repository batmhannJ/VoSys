<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "DELETE FROM sub_admin WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Sub-Admin deleted successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Select item to delete first';
	}

	header('location: sub_admin.php');
	
?>
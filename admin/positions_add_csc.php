<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$name = $_POST['name'];
		$max_vote = $_POST['max_vote'];

		$sql = "SELECT * FROM categories WHERE election_id = 20 ORDER BY priority DESC LIMIT 1";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		$priority = $row['priority'] + 1;
		
		$sql = "INSERT INTO categories (election_id, name, max_vote, priority) VALUES ('20', '$name', '$max_vote', '$priority')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Position added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: positions_csc.php');
?>
<?php
	session_start();
	include 'includes/conn.php';

	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		header('location: index.php');
		exit;
	}

	$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
	$stmt->bind_param("s", $_SESSION['admin']);
	$stmt->execute();
	$user = $stmt->get_result()->fetch_assoc();
	
	
?>
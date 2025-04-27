<?php
	$conn = new mysqli('localhost', 'root', '', 'u247141684_votesystem');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>
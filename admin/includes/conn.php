<?php
	$conn = new mysqli('localhost', 'u247141684_vosys', 'vosysOlshco5', 'u247141684_votesystem');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>
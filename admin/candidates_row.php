<?php 
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$sql = "SELECT *, candidates.id AS canid FROM candidates LEFT JOIN categories ON candidates.category_id = categories.id WHERE categories.election_id = 1 AND categories.id = '$id'";
		$query = $conn->query($sql);
		
		$candidates = array(); // Initialize an array to store candidates
		
		// Fetching all candidates related to the specified category
		while($row = $query->fetch_assoc()) {
			$candidates[] = $row; // Add each candidate to the array
		}

		echo json_encode($candidates); // Encode the array of candidates to JSON and echo it
	}
?>

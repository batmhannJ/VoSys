<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	$output = array('error' => false, 'list' => '', 'message' => []);

	// Fetch categories for election ID 1
	$sql = "SELECT * FROM categories WHERE election_id = 1";
	$query = $conn->query($sql);

	// Iterate through each category (position)
	while ($row = $query->fetch_assoc()) {
		$position = slugify($row['name']);
		$pos_id = $row['id'];

		// Check if this position has been submitted via POST
		if (isset($_POST[$position])) {
			// Handle multiple selection (if max_vote > 1)
			if ($row['max_vote'] > 1) {
				if (count($_POST[$position]) > $row['max_vote']) {
					$output['error'] = true;
					$output['message'][] = '<li>You can only choose '.$row['max_vote'].' candidates for '.$row['description'].'</li>';
				} else {
					// Loop through the selected candidates and fetch their details
					foreach ($_POST[$position] as $key => $values) {
						$sql = "SELECT * FROM candidates WHERE id = '$values'";
						$cmquery = $conn->query($sql);
						
						// Ensure that candidate exists before attempting to use data
						if ($cmquery->num_rows > 0) {
							$cmrow = $cmquery->fetch_assoc();
							$output['list'] .= "
								<div class='row votelist'>
									<span class='col-sm-5'><span class='pull-right'><b>".$row['name']." :</b></span></span> 
									<span class='col-sm-7'>".$cmrow['firstname']." ".$cmrow['lastname']."</span>
								</div>
							";
						} else {
							$output['error'] = true;
							$output['message'][] = "<li>Invalid candidate selected for position: ".$row['name']."</li>";
						}
					}
				}
			} else { // Handle single selection
				$candidate = $_POST[$position];

				// Ensure that the selected candidate exists
				$sql = "SELECT * FROM candidates WHERE id = '$candidate'";
				$csquery = $conn->query($sql);

				if ($csquery->num_rows > 0) {
					$csrow = $csquery->fetch_assoc();
					$output['list'] .= "
						<div class='row votelist'>
							<span class='col-sm-5'><span class='pull-right'><b>".$row['name']." :</b></span></span> 
							<span class='col-sm-7'>".$csrow['firstname']." ".$csrow['lastname']."</span>
						</div>
					";
				} else {
					$output['error'] = true;
					$output['message'][] = "<li>Invalid candidate selected for position: ".$row['name']."</li>";
				}
			}
		}
	}

	// Return the result as a JSON response
	echo json_encode($output);
?>

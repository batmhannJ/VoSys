<?php
	
	include 'includes/session.php';
	include 'includes/slugify.php';

	$output = array('error' => false, 'list' => '');

	$sql = "SELECT * FROM categories WHERE election_id = 20";
	$query = $conn->query($sql);

	while ($row = $query->fetch_assoc()) {
		$position = slugify($row['name']);
		$pos_id = $row['id'];

		if (isset($_POST[$position])) {
			if ($row['max_vote'] > 1) {
				// Check if the number of selected candidates exceeds max_vote
				if (count($_POST[$position]) > $row['max_vote']) {
					$output['error'] = true;
					$output['message'][] = '<li>You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'] . '</li>';
				} else {
					// Process all selected candidates
					foreach ($_POST[$position] as $key => $value) {
						$sql = "SELECT * FROM candidates WHERE id = '$value'";
						$cmquery = $conn->query($sql);
						$cmrow = $cmquery->fetch_assoc();
						$output['list'] .= "
    <div class='row votelist'>
        <div class='col-xs-5 col-sm-5 text-right'>
            <strong>" . $row['name'] . ":</strong>
        </div>
        <div class='col-xs-7 col-sm-7'>
            " . $csrow['firstname'] . " " . $csrow['lastname'] . "
        </div>
    </div>
";
					}
				}
			}
			// Handle single selection (max_vote = 1)
			else {
				$candidate = is_array($_POST[$position]) ? $_POST[$position][0] : $_POST[$position]; // Ensure it's a single value
				$sql = "SELECT * FROM candidates WHERE id = '$candidate'";
				$csquery = $conn->query($sql);
				$csrow = $csquery->fetch_assoc();
				// Include both the candidate's name and position
				$output['list'] .= "
    <div class='row votelist'>
        <div class='col-xs-5 col-sm-5 text-right'>
            <strong>" . $row['name'] . ":</strong>
        </div>
        <div class='col-xs-7 col-sm-7'>
            " . $csrow['firstname'] . " " . $csrow['lastname'] . "
        </div>
    </div>
";
			}
		}
	}

	echo json_encode($output);
?>

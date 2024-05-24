<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['vote'])){
		if(count($_POST) == 1){
			$_SESSION['error'][] = 'Please vote for at least one candidate';
		}
		else{
			$_SESSION['post'] = $_POST;
			$sql = "SELECT * FROM categories WHERE election_id = 20";
			$query = $conn->query($sql);
			$error = false;
			$sql_array = array();
			$votes_info = '';
			while($row = $query->fetch_assoc()){
				$position = slugify($row['name']);
				$pos_id = $row['id'];
				if(isset($_POST[$position])){
					if($row['max_vote'] > 1){
						if(count($_POST[$position]) > $row['max_vote']){
							$error = true;
							$_SESSION['error'][] = 'You can only choose '.$row['max_vote'].' candidates for '.$row['name'];
						}
						else{
							foreach($_POST[$position] as $key => $values){
								// Fetch candidate name from database
								$candidate_sql = "SELECT firstname, lastname FROM candidates WHERE id = '$values'";
								$candidate_query = $conn->query($candidate_sql);
								$candidate_row = $candidate_query->fetch_assoc();
								$candidate_name = $candidate_row['firstname'] . ' ' . $candidate_row['lastname'];

								$sql_array[] = "INSERT INTO votes_csc (voters_id, election_id, candidate_id, category_id, organization) VALUES ('".$voter['id']."', '20', '$values', '$pos_id', 'CSC')";
								// Append vote information to $votes_info
								$votes_info .= "<p><strong>Position:</strong> ".$row['name']."<br>";
								$votes_info .= "<strong>Candidate:</strong> $candidate_name</p>";
							}
						}
						
					}
					else{
						$candidate = $_POST[$position];
						// Fetch candidate name from database
						$candidate_sql = "SELECT firstname, lastname FROM candidates WHERE id = '$candidate'";
						$candidate_query = $conn->query($candidate_sql);
						$candidate_row = $candidate_query->fetch_assoc();
						$candidate_name = $candidate_row['firstname'] . ' ' . $candidate_row['lastname'];

						$sql_array[] = "INSERT INTO votes_csc (voters_id, election_id, candidate_id, category_id, organization) VALUES ('".$voter['id']."', '20', '$candidate', '$pos_id', 'CSC')";
						// Append vote information to $votes_info
						$votes_info .= "<p><strong>Position:</strong> ".$row['name']."<br>";
						$votes_info .= "<strong>Candidate:</strong> $candidate_name</p>";
					}

				}
				
			}

			if(!$error){
				foreach($sql_array as $sql_row){
					$conn->query($sql_row);
				}

				unset($_SESSION['post']);
				$_SESSION['success'] = 'Ballot Submitted';

				// Sending email to the voter
				$to = $voter['email']; // Voter's email address
				$subject = 'Your Voting Confirmation';
				$message = "<html><body>";
				$message .= "<p>Dear Voter,</p>";
				$message .= "<p>Thank you for casting your vote. Below are the details of your votes:</p>";
				$message .= $votes_info; // Append the votes information
				$message .= "<p>Thank you,<br>JPCS Election Committee</p>";
				$message .= "</body></html>";

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				// Send email
				mail($to, $subject, $message, $headers);

			}

		}

	}
	else{
		$_SESSION['error'][] = 'Select candidates to vote first';
	}
	header("location: home.php");
exit();
?>

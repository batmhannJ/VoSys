<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['vote_jpcs'])){
		if(count($_POST) == 1){
			$_SESSION['error'][] = 'Please vote atleast one candidate';
		}
		else{
			$_SESSION['post'] = $_POST;
			$sql = "SELECT * FROM categories WHERE election_id = 1";
			$query = $conn->query($sql);
			$error = false;
			$sql_array = array();
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
								$sql_array[] = "INSERT INTO votes (voters_id, candidate_id, category_id, organization) VALUES ('".$voter['id']."', '$values', '$pos_id', '".$voter['organization']."')";
							}

						}
						
					}
					else{
						$candidate = $_POST[$position];
						$sql_array[] = "INSERT INTO votes (voters_id, candidate_id, category_id, organization) VALUES ('".$voter['id']."', '$candidate', '$pos_id', '".$voter['organization']."')";
					}

				}
				
			}

			if(!$error){
				foreach($sql_array as $sql_row){
					$conn->query($sql_row);
				}

				unset($_SESSION['post']);
				$_SESSION['success'] = 'Ballot Submitted';
				
			}

		}

	}
	else{
		$_SESSION['error'][] = 'Select candidates to vote first';
	}
	header("location: jpcs_home.php");
exit();
?>
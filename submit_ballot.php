<?php
include 'includes/session.php';
include 'includes/slugify.php';

if (isset($_POST['vote'])) {
    if (count($_POST) == 1) {
        $_SESSION['error'][] = 'Please vote at least one candidate';
    } else {
        $_SESSION['post'] = $_POST;
        $sql = "SELECT * FROM categories WHERE election_id = 20";
        $query = $conn->query($sql);
        $error = false;
        $sql_array = array();
        $votes_info = ''; // String to store the votes information

        while ($row = $query->fetch_assoc()) {
            $position = slugify($row['name']);
            $pos_id = $row['id'];

            if (isset($_POST[$position])) {
                if ($row['max_vote'] > 1) {
                    // Multiple selection handling
                    if (count($_POST[$position]) > $row['max_vote']) {
                        $error = true;
                        $_SESSION['error'][] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['name'];
                    } else {
                        foreach ($_POST[$position] as $value) {
                            // Fetch candidate name from database
                            $candidate_sql = "SELECT firstname, lastname FROM candidates WHERE id = '$value'";
                            $candidate_query = $conn->query($candidate_sql);
                            $candidate_row = $candidate_query->fetch_assoc();
                            $candidate_name = $candidate_row['firstname'] . ' ' . $candidate_row['lastname'];

                            $sql_array[] = "INSERT INTO votes_csc (voters_id, election_id, candidate_id, category_id, organization) VALUES ('" . $voter['id'] . "', '1', '$value', '$pos_id', 'JPCS')";
                            $votes_info .= "Position: " . $row['name'] . "\n";
                            $votes_info .= "Candidate: $candidate_name \n\n";
                        }
                    }
                } else {
                    // Single selection handling
                    $candidate = is_array($_POST[$position]) ? $_POST[$position][0] : $_POST[$position]; // Ensure it's a single value

                    // Fetch candidate name from database
                    $candidate_sql = "SELECT firstname, lastname FROM candidates WHERE id = '$candidate'";
                    $candidate_query = $conn->query($candidate_sql);
                    $candidate_row = $candidate_query->fetch_assoc();
                    $candidate_name = $candidate_row['firstname'] . ' ' . $candidate_row['lastname'];

                    $sql_array[] = "INSERT INTO votes_csc (voters_id, election_id, candidate_id, category_id, organization) VALUES ('" . $voter['id'] . "', '1', '$candidate', '$pos_id', 'JPCS')";
                    $votes_info .= "Position: " . $row['name'] . "\n";
                    $votes_info .= "Candidate: $candidate_name \n\n";
                }
            }
        }

        if (!$error) {
            foreach ($sql_array as $sql_row) {
                $conn->query($sql_row);
            }

            unset($_SESSION['post']);
            $_SESSION['success'] = 'Ballot Submitted';

            // Sending email to the voter
            $to = $voter['email']; // Voter's email address
            $subject = 'Your Voting Confirmation';
            $message = "Dear Voter,\n\n";
            $message .= "Thank you for casting your vote. Below are the details of your votes:\n\n";
            $message .= $votes_info; // Append the votes information
            $message .= "\n\nThank you,\nJPCS Election Committee";

            // Send email
            mail($to, $subject, $message);
        }
    }
} else {
    $_SESSION['error'][] = 'Select candidates to vote first';
}

header("location: home.php");
exit();
?>
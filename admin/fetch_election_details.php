<?php
include 'includes/session.php';

if (isset($_POST['election_id'])) {
    // Retrieve election_id from POST request
    $election_id = $_POST['election_id'];

    // Query the election details
    $sql = "SELECT * FROM election WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $election_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $election = $result->fetch_assoc();

        // Query the candidates and calculate their votes from the 'votes' table
        $sql_candidates = "
            SELECT c.id, c.name, COUNT(v.id) AS total_votes
            FROM candidates c
            LEFT JOIN votes v ON c.id = v.candidate_id
            WHERE c.election_id = ?
            GROUP BY c.id, c.name
            ORDER BY total_votes DESC
        ";
        $stmt_candidates = $conn->prepare($sql_candidates);
        $stmt_candidates->bind_param('i', $election_id);
        $stmt_candidates->execute();
        $candidates_result = $stmt_candidates->get_result();

        $candidates = [];
        while ($row = $candidates_result->fetch_assoc()) {
            $candidates[] = $row;
        }

        // Determine the winners (candidates with the highest votes)
        $winners = [];
        if (count($candidates) > 0) {
            $max_votes = $candidates[0]['total_votes'];
            foreach ($candidates as $candidate) {
                if ($candidate['total_votes'] == $max_votes) {
                    $winners[] = $candidate['name'];
                }
            }
        }

        // Prepare and return the response as JSON
        $response = [
            'election' => $election,
            'candidates' => $candidates,
            'winners' => $winners
        ];
        echo json_encode($response);
    } else {
        // If election is not found
        echo json_encode(['error' => 'Election not found']);
    }
} else {
    // If no election_id is provided in the POST request
    echo json_encode(['error' => 'Invalid request']);
}
?>

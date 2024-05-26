<?php
include 'includes/session.php';

if(isset($_POST['election_id'])){
    $election_id = $_POST['election_id'];

    // Fetching results for different positions
    $positions = [
        'President' => 'president',
        'Vice President' => 'vice_president',
        'Secretary' => 'secretary',
        'Treasurer' => 'treasurer',
        'P.R.O' => 'pro', // Changed to P.R.O
        'Business Manager' => 'business_manager',
        'BEED Rep' => 'beed_rep', // Changed to BEED Rep
        'BSED Rep' => 'bsed_rep', // Changed to BSED Rep
        'BSHM Rep' => 'bshm_rep', // Changed to BSHM Rep
        'BSOAD Rep' => 'bsoad_rep', // Changed to BSOAD Rep
        'BSCRIM Rep' => 'bscrim_rep', // Changed to BSCRIM Rep
        'BSIT Rep' => 'bsit_rep' // Changed to BSIT Rep
    ];

    $results = [];

    foreach($positions as $position => $id) {
        $sql = "SELECT candidates.name AS candidate, COUNT(votes.id) AS vote_count
                FROM votes
                LEFT JOIN candidates ON candidates.id = votes.candidate_id
                LEFT JOIN positions ON positions.id = candidates.position_id
                WHERE positions.description = '$position' AND votes.election_id = '$election_id'
                GROUP BY candidates.name
                ORDER BY vote_count DESC";

        $query = $conn->query($sql);
        $data = [];

        while($row = $query->fetch_assoc()) {
            $data[] = [
                'candidate' => $row['candidate'],
                'vote_count' => $row['vote_count']
            ];
        }

        $results[$id] = $data;
    }

    echo json_encode($results);
}
?>

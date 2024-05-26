<?php
include 'includes/session.php';

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

function fetchVotes($conn, $category, $organizationFilter = "") {
    $data = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes_csc.candidate_id), 0) AS vote_count, 
            candidates.photo AS candidate_image
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes_csc ON candidates.id = votes_csc.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes_csc.voters_id 
            WHERE voters1.organization != '' AND categories.name = '$category'
            $organizationFilter
            GROUP BY candidates.id";

    $query = $conn->query($sql);
    if (!$query) {
        return $data;
    }

    while ($row = $query->fetch_assoc()) {
        $imagePath = !empty($row['candidate_image']) ? '../images/' . $row['candidate_image'] : '../images/profile.jpg';
        if (!file_exists($imagePath)) {
            $imagePath = '../images/profile.jpg';
        }

        $data[] = array(
            "y" => intval($row['vote_count']),
            "label" => $row['candidate_name'],
            "image" => $imagePath
        );
    }
    return $data;
}

$categories = [
    'president', 'vice president', 'secretary', 'treasurer', 'auditor',
    'p.r.o', 'businessManager', 'beedRep', 'bsedRep', 'bshmRep',
    'bsoadRep', 'bs crimRep', 'bsitRep'
];

$response = array();
foreach ($categories as $category) {
    $response[$category] = fetchVotes($conn, ucfirst(str_replace(['Rep', 'Manager', 'P.R.O'], [' Rep', ' Manager', ' P.R.O'], $category)));
}

echo "data: " . json_encode($response) . "\n\n";
flush();

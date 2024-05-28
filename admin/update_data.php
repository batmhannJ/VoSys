<?php
include 'includes/session.php';

// Function to fetch votes data for a specific category
function fetchVotes($conn, $category) {
    $data = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                   COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization = 'CSC' AND categories.name = '$category'
            GROUP BY candidates.id";
    $query = $conn->query($sql);

    while ($row = $query->fetch_assoc()) {
        $data[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    return $data;
}

$categories = [
    'president' => 'President',
    'vicePresident' => 'Vice President',
    'secretary' => 'Secretary',
    'treasurer' => 'Treasurer',
    'auditor' => 'Auditor',
    'pro' => 'P.R.O',
    'businessManager' => 'Business Manager',
    'beedRep' => 'BEED Representative',
    'bsedRep' => 'BSED Representative',
    'bshmRep' => 'BSHM Representative',
    'bsoadRep' => 'BSOAD Representative',
    'bscrimRep' => 'BS Crim Representative',
    'bsitRep' => 'BSIT Representative'
];

$response = [];

// Fetch votes data for each category
foreach ($categories as $key => $category) {
    $response[$key] = fetchVotes($conn, $category);
}

header('Content-Type: application/json');
echo json_encode($response);
?>

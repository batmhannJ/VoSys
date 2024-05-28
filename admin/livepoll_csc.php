<?php
include 'includes/session.php';

function fetchVotes($conn, $category) {
    $data = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            WHERE categories.name = '$category'
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $data[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    return $data;
}

$response = array();
$response['president'] = fetchVotes($conn, 'President');
$response['vicePresident'] = fetchVotes($conn, 'Vice President');
$response['secretary'] = fetchVotes($conn, 'Secretary');
$response['treasurer'] = fetchVotes($conn, 'Treasurer');
$response['auditor'] = fetchVotes($conn, 'Auditor');
$response['pro'] = fetchVotes($conn, 'P.R.O');
$response['businessManager'] = fetchVotes($conn, 'Business Manager');
$response['beedRep'] = fetchVotes($conn, 'BEED Rep');
$response['bsedRep'] = fetchVotes($conn, 'BSED Rep');
$response['bshmRep'] = fetchVotes($conn, 'BSHM Rep');
$response['bsoadRep'] = fetchVotes($conn, 'BSOAD Rep');
$response['bsCrimRep'] = fetchVotes($conn, 'BS Crim Rep');
$response['bsitRep'] = fetchVotes($conn, 'BSIT Rep');

header('Content-Type: application/json');
echo json_encode($response);
?>

<?php
include 'includes/session.php';

$organizationFilter = "";
if (!empty($_GET['organization'])) {
    $organizationFilter = " AND voters1.organization = '" . $_GET['organization'] . "'";
}

// Function to fetch votes data
function fetchVotes($conn, $category, $organizationFilter) {
    $data = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = '$category'
            $organizationFilter
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $data[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    return $data;
}

$response = array();
$response['president'] = fetchVotes($conn, 'President', $organizationFilter);
$response['vicePresidentInternal'] = fetchVotes($conn, 'Vice President for Internal Affairs', $organizationFilter);
$response['vicePresidentExternal'] = fetchVotes($conn, 'Vice President for External Affairs', $organizationFilter);
$response['secretary'] = fetchVotes($conn, 'Secretary', $organizationFilter);

header('Content-Type: application/json');
echo json_encode($response);
?>

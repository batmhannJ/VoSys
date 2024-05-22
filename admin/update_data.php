<?php
include 'includes/session.php';

$organization = $_GET['organization'] ?? '';
$organizationFilter = $organization ? "AND voters1.organization = '$organization'" : '';

function getVotes($category, $organizationFilter, $conn) {
    $data = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM candidates
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE candidates.category = '$category'
            $organizationFilter
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $data[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    return $data;
}

$response = array(
    "president" => getVotes('President', $organizationFilter, $conn),
    "vicePresidentInternal" => getVotes('Vice President for Internal Affairs', $organizationFilter, $conn),
    "vicePresidentExternal" => getVotes('Vice President for External Affairs', $organizationFilter, $conn),
    "secretary" => getVotes('Secretary', $organizationFilter, $conn),
);

echo json_encode($response);
?>

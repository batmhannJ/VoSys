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
$response['treasurer'] = fetchVotes($conn, 'Treasurer', $organizationFilter);
$response['auditor'] = fetchVotes($conn, 'Auditor', $organizationFilter);
$response['pro'] = fetchVotes($conn, 'P.R.O', $organizationFilter);
$response['dirMembership'] = fetchVotes($conn, 'Dir. for Membership', $organizationFilter);
$response['dirSpecialProject'] = fetchVotes($conn, 'Dir. for Special Project', $organizationFilter);
$response['blockA1stYearRep'] = fetchVotes($conn, 'Block A 1st Year Representative', $organizationFilter);
$response['blockB1stYearRep'] = fetchVotes($conn, 'Block B 1st Year Representative', $organizationFilter);
$response['blockA2ndYearRep'] = fetchVotes($conn, 'Block A 2nd Year Representative', $organizationFilter);
$response['blockB2ndYearRep'] = fetchVotes($conn, 'Block B 2nd Year Representative', $organizationFilter);
$response['blockA3rdYearRep'] = fetchVotes($conn, 'Block A 3rd Year Representative', $organizationFilter);
$response['blockB3rdYearRep'] = fetchVotes($conn, 'Block B 3rd Year Representative', $organizationFilter);
$response['blockA4thYearRep'] = fetchVotes($conn, 'Block A 4th Year Representative', $organizationFilter);
$response['blockB4thYearRep'] = fetchVotes($conn, 'Block B 4th Year Representative', $organizationFilter);

header('Content-Type: application/json');
echo json_encode($response);
?>

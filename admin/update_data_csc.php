<?php
include 'includes/session.php';

$organizationFilter = "";
if (!empty($_GET['organization'])) {
    $organizationFilter = " AND voters1.organization = '" . $_GET['organization'] . "'";
}

// Function to fetch votes data with candidate images
function fetchVotes($conn, $category, $organizationFilter) {
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
    while($row = $query->fetch_assoc()) {
        $imagePath = !empty($row['candidate_image']) ? '../images/' . $row['candidate_image'] : '../images/profile.jpg';

        // Debugging: Check if the file exists and log the path
        if (!file_exists($imagePath)) {
            error_log("Image not found: " . $imagePath);
        }

        $data[] = array(
            "y" => intval($row['vote_count']), 
            "label" => $row['candidate_name'],
            "image" => $imagePath
        );
    }
    return $data;
}

$response = array();
$response['president'] = fetchVotes($conn, 'President', $organizationFilter);
$response['vicePresident'] = fetchVotes($conn, 'Vice President', $organizationFilter);
$response['secretary'] = fetchVotes($conn, 'Secretary', $organizationFilter);
$response['treasurer'] = fetchVotes($conn, 'Treasurer', $organizationFilter);
$response['auditor'] = fetchVotes($conn, 'Auditor', $organizationFilter);
$response['publicInformationOfficer'] = fetchVotes($conn, 'Public Information Officer (P.R.O)', $organizationFilter);
$response['businessManager'] = fetchVotes($conn, 'Business Manager', $organizationFilter);
$response['beedRepresentative'] = fetchVotes($conn, 'BEED Representative', $organizationFilter);
$response['bsedRepresentative'] = fetchVotes($conn, 'BSED Representative', $organizationFilter);
$response['bshmRepresentative'] = fetchVotes($conn, 'BSHM Representative', $organizationFilter);
$response['bsoadRepresentative'] = fetchVotes($conn, 'BSOAD Representative', $organizationFilter);
$response['bsCrimRepresentative'] = fetchVotes($conn, 'BS CRIM Representative', $organizationFilter);
$response['bsitRepresentative'] = fetchVotes($conn, 'BSIT Representative', $organizationFilter);

header('Content-Type: application/json');
$responseJson = json_encode($response);
echo $responseJson;
error_log($responseJson);

?>

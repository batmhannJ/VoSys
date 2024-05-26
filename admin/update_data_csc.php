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
$response['pro'] = fetchVotes($conn, 'P.R.O', $organizationFilter); // Changed to P.R.O
$response['beedRep'] = fetchVotes($conn, 'BEED Rep', $organizationFilter); // Changed to BEED Rep
$response['bsedRep'] = fetchVotes($conn, 'BSED Rep', $organizationFilter); // Changed to BSED Rep
$response['bshmRep'] = fetchVotes($conn, 'BSHM Rep', $organizationFilter); // Changed to BSHM Rep
$response['bsoadRep'] = fetchVotes($conn, 'BSOAD Representative', $organizationFilter); // Changed to BSOAD Rep
$response['bsCrimRep'] = fetchVotes($conn, 'BS CRIM Rep', $organizationFilter); // Changed to BS CRIM Rep
$response['bsitRep'] = fetchVotes($conn, 'BSIT Rep', $organizationFilter); // Changed to BSIT Rep

header('Content-Type: application/json');
$responseJson = json_encode($response);
echo $responseJson;
error_log($responseJson);
?>

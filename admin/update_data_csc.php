<?php
include 'includes/session.php';

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
            WHERE voters1.organization != '' AND categories.name = ? 
            $organizationFilter
            GROUP BY candidates.id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
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
    $stmt->close();
    return $data;
}

$organizationFilter = "";
if (!empty($_GET['organization'])) {
    $organization = $_GET['organization'];
    $organizationFilter = " AND voters1.organization = ?";
}

$response = array();
$categories = [
    'President', 'vice president', 'secretary', 'treasurer', 'auditor', 
    'Public Information Officer (P.R.O)', 'Business Manager', 'beedRep', 
    'BSED Rep', 'BSHM Rep', 'BSOAD Rep', 'BS CRIM Rep', 'BSIT Rep'
];

foreach ($categories as $category) {
    $response[strtolower(str_replace(' ', '', $category))] = fetchVotes($conn, $category, $organizationFilter);
}

header('Content-Type: application/json');
$responseJson = json_encode($response);
echo $responseJson;
error_log($responseJson);
?>

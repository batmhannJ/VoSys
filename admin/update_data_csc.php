<?php
include 'includes/session.php';

$organizationFilter = "";
if (!empty($_GET['organization'])) {
    $organizationFilter = " AND voters1.organization = '" . $conn->real_escape_string($_GET['organization']) . "'";
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
            WHERE categories.name = '$category'
            $organizationFilter
            GROUP BY candidates.id";
    
    error_log("SQL Query for $category: " . $sql);

    $query = $conn->query($sql);
    if (!$query) {
        error_log("SQL Error for $category: " . $conn->error);
        return $data;
    }

    while($row = $query->fetch_assoc()) {
        $imagePath = !empty($row['candidate_image']) ? '../images/' . $row['candidate_image'] : '../images/profile.jpg';

        if (!file_exists($imagePath)) {
            error_log("Image not found for $category: " . $imagePath);
            $imagePath = '../images/profile.jpg';  // Default image if not found
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
$positions = [
    'President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor',
    'P.R.O', 'Business Manager', 'BEED Rep', 'BSED Rep', 'BSHM Rep',
    'BSOAD Rep', 'BS CRIM Rep', 'BSIT Rep'
];

foreach ($positions as $position) {
    $response[strtolower(str_replace([' ', '.'], '', $position))] = fetchVotes($conn, $position, $organizationFilter);
}

error_log("Response: " . json_encode($response));

header('Content-Type: application/json');
echo json_encode($response);
?>

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
            WHERE voters1.organization != '' AND categories.name = '$category'
            $organizationFilter
            GROUP BY candidates.id";

    // Debugging: Log the query for inspection
    error_log("SQL Query for $category: " . $sql);

    $query = $conn->query($sql);
    if (!$query) {
        // Log SQL error if query fails
        error_log("SQL Error for $category: " . $conn->error);
        return $data;
    }

    while($row = $query->fetch_assoc()) {
        $imagePath = !empty($row['candidate_image']) ? '../images/' . $row['candidate_image'] : '../images/profile.jpg';

        // Debugging: Check if the file exists and log the path
        if (!file_exists($imagePath)) {
            error_log("Image not found: " . $imagePath);
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
$categories = [
    'president', 'vicePresident', 'secretary', 'treasurer', 'auditor',
    'p.r.o', 'businessManager', 'beedRep', 'bsedRep', 'bshmRep',
    'bsoadRep', 'bscrimRep', 'bsitRep'
];

foreach ($categories as $category) {
    // Adjust category names to match database entries
    $categoryName = ucfirst(str_replace(['Rep', 'Manager', 'P.R.O'], [' Rep', ' Manager', ' P.R.O'], $category));
    $response[$category] = fetchVotes($conn, $categoryName, $organizationFilter);
}

// Debugging: Log the final response
error_log(json_encode($response));

header('Content-Type: application/json');
echo json_encode($response);
?>

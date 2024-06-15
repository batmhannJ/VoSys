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
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count, 
            candidates.photo AS candidate_image
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != '' AND categories.name = '$category'
            $organizationFilter
            GROUP BY candidates.id";
    
    // Debugging: Log the query for inspection
    error_log("SQL Query: " . $sql);

    $query = $conn->query($sql);
    if (!$query) {
        // Log SQL error if query fails
        error_log("SQL Error: " . $conn->error);
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
    'president', 'vp for internal affairs', 'vp for external affairs', 'secretary', 'treasurer', 'auditor',
    'p.r.o', 'dir. for membership','dir. for special project', '2-ARep', '2-BRep', '3-ARep',
    '3-BRep', '4-ARep', '4-BRep'
];

foreach ($categories as $category) {
    $response[$category] = fetchVotes($conn, ucfirst(str_replace(['Internal Affairs', 'External Affairs', 'Rep', 'Manager', 'P.R.O'], ['Internal Affairs', 'External Affairs', ' Rep', ' Manager', ' P.R.O'], $category)), $organizationFilter);
}

// Debugging: Log the final response
error_log(json_encode($response));

header('Content-Type: application/json');
echo json_encode($response);
?>

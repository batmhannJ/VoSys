<?php
include 'includes/session.php';

// Set headers for SSE
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

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
    if (!$query) {
        // Log SQL error if query fails
        error_log("SQL Error: " . $conn->error);
        return $data;
    }

    while($row = $query->fetch_assoc()) {
        $imagePath = !empty($row['candidate_image']) ? '../images/' . $row['candidate_image'] : '../images/profile.jpg';

        // Check if the file exists and log the path
        if (!file_exists($imagePath)) {
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

$organizationFilter = "";
if (!empty($_GET['organization'])) {
    $organizationFilter = " AND voters1.organization = '" . $conn->real_escape_string($_GET['organization']) . "'";
}

while (true) {
    $response = array();
    $categories = [
        'president', 'vice president', 'secretary', 'treasurer', 'auditor',
        'p.r.o', 'businessManager', 'beedRep', 'bsedRep', 'bshmRep',
        'bsoadRep', 'bs crimRep', 'bsitRep'
    ];

    foreach ($categories as $category) {
        $response[$category] = fetchVotes($conn, ucfirst(str_replace(['Rep', 'Manager', 'P.R.O'], [' Rep', ' Manager', ' P.R.O'], $category)), $organizationFilter);
    }

    // Send the latest vote data to the client
    echo "data: " . json_encode($response) . "\n\n";
    ob_flush();
    flush();

    // Sleep for a second before checking for new data
    sleep(1);
}
?>

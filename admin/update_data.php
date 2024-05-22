<?php
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$presidentData = array();
$vicePresidentData = array();

// Get the selected organization from the GET request
$organization = isset($_GET['organization']) ? $_GET['organization'] : '';

// Prepare SQL condition for organization filter
$organizationCondition = $organization ? "AND voters1.organization = '$organization'" : '';

// Fetch updated data for president candidates based on categories and selected organization
$sqlPresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM categories
                LEFT JOIN candidates ON categories.id = candidates.category_id
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE categories.description = 'President' $organizationCondition
                GROUP BY candidates.id";
$queryPresident = $conn->query($sqlPresident);
if ($queryPresident) {
    while ($row = $queryPresident->fetch_assoc()) {
        $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching president data: " . $conn->error;
}

// Fetch updated data for vice president candidates based on categories and selected organization
$sqlVicePresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                    COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                    FROM categories
                    LEFT JOIN candidates ON categories.id = candidates.category_id
                    LEFT JOIN votes ON candidates.id = votes.candidate_id
                    LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                    WHERE categories.description = 'Vice President' $organizationCondition
                    GROUP BY candidates.id";
$queryVicePresident = $conn->query($sqlVicePresident);
if ($queryVicePresident) {
    while ($row = $queryVicePresident->fetch_assoc()) {
        $vicePresidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching vice president data: " . $conn->error;
}

// Close database connection
$conn->close();

// Combine the updated data into a single array
$response = array(
    'presidentData' => $presidentData,
    'vicePresidentData' => $vicePresidentData
);

// Return the updated data as JSON
echo json_encode($response);
?>

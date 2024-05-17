<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$presidentData = array();
$vpInternalAffairsData = array();
$vpExternalAffairsData = array();

// Fetch updated data for President candidates
$sqlPresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM candidates 
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN category ON candidates.category_id = category.id
                WHERE category.description = 'President'
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

// Fetch updated data for Vice President for Internal Affairs candidates
$sqlVPInternalAffairs = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                        FROM candidates 
                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                        LEFT JOIN positions ON candidates.position_id = positions.id
                        WHERE positions.description = 'Vice President for Internal Affairs'
                        GROUP BY candidates.id";
$queryVPInternalAffairs = $conn->query($sqlVPInternalAffairs);
if ($queryVPInternalAffairs) {
    while ($row = $queryVPInternalAffairs->fetch_assoc()) {
        $vpInternalAffairsData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Vice President for Internal Affairs data: " . $conn->error;
}

// Fetch updated data for Vice President for External Affairs candidates
$sqlVPExternalAffairs = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                        FROM candidates 
                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                        LEFT JOIN positions ON candidates.position_id = positions.id
                        WHERE positions.description = 'Vice President for External Affairs'
                        GROUP BY candidates.id";
$queryVPExternalAffairs = $conn->query($sqlVPExternalAffairs);
if ($queryVPExternalAffairs) {
    while ($row = $queryVPExternalAffairs->fetch_assoc()) {
        $vpExternalAffairsData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Vice President for External Affairs data: " . $conn->error;
}

// Close database connection
$conn->close();

// Combine the updated data into a single array
$response = array(
    'presidentData' => $presidentData,
    'vpInternalAffairsData' => $vpInternalAffairsData,
    'vpExternalAffairsData' => $vpExternalAffairsData
);

// Return the updated data as JSON
echo json_encode($response);
?>

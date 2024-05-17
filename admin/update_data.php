<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$presidentData = array();
$vpInternalAffairsData = array(); // New array for VP Internal Affairs
$vpExternalAffairsData = array(); // New array for VP External Affairs

// Fetch updated data for president candidates
$sqlPresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'President'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
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

// Fetch updated data for vice president for internal affairs candidates
$sqlVPInternalAffairs = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                        FROM positions 
                        LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President for Internal Affairs'
                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                        WHERE voters1.organization != ''
                        GROUP BY candidates.id";
$queryVPInternalAffairs = $conn->query($sqlVPInternalAffairs);
if ($queryVPInternalAffairs) {
    while ($row = $queryVPInternalAffairs->fetch_assoc()) {
        $vpInternalAffairsData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching VP Internal Affairs data: " . $conn->error;
}

// Fetch updated data for vice president for external affairs candidates
$sqlVPExternalAffairs = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                        FROM positions 
                        LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President for External Affairs'
                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                        WHERE voters1.organization != ''
                        GROUP BY candidates.id";
$queryVPExternalAffairs = $conn->query($sqlVPExternalAffairs);
if ($queryVPExternalAffairs) {
    while ($row = $queryVPExternalAffairs->fetch_assoc()) {
        $vpExternalAffairsData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching VP External Affairs data: " . $conn->error;
}

// Close database connection
$conn->close();

// Combine the updated data into a single array
$response = array(
    'presidentData' => $presidentData,
    'vpInternalAffairsData' => $vpInternalAffairsData, // Add VP Internal Affairs data to response
    'vpExternalAffairsData' => $vpExternalAffairsData // Add VP External Affairs data to response
);

// Return the updated data as JSON
echo json_encode($response);
?>

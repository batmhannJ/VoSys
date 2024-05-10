<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data for each position
$presidentData = array();
$vicePresidentInternalData = array();
$vicePresidentExternalData = array();
$secretaryData = array();
$treasurerData = array();
$auditorData = array();
$proData = array();
$membershipDirectorData = array();
$specialProjectDirectorData = array();
$blockA1stYearRepData = array();
// Add arrays for other positions here

// Fetch updated data for President candidates
$sqlPresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'President'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''
                GROUP BY candidates.id";
$queryPresident = $conn->query($sqlPresident);
while ($row = $queryPresident->fetch_assoc()) {
    $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
}

// Fetch updated data for Vice President (Internal Affairs) candidates
$sqlVicePresidentInternal = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                            FROM positions 
                            LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President (Internal Affairs)'
                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                            WHERE voters1.organization != ''
                            GROUP BY candidates.id";
$queryVicePresidentInternal = $conn->query($sqlVicePresidentInternal);
while ($row = $queryVicePresidentInternal->fetch_assoc()) {
    $vicePresidentInternalData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
}

// Fetch updated data for Vice President (External Affairs) candidates
$sqlVicePresidentExternal = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                            FROM positions 
                            LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President (External Affairs)'
                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                            WHERE voters1.organization != ''
                            GROUP BY candidates.id";
$queryVicePresidentExternal = $conn->query($sqlVicePresidentExternal);
while ($row = $queryVicePresidentExternal->fetch_assoc()) {
    $vicePresidentExternalData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
}

// Fetch updated data for Secretary candidates
// Add similar queries for other positions

// Close database connection
$conn->close();

// Combine the updated data into a single array
$response = array(
    'presidentData' => $presidentData,
    'vicePresidentInternalData' => $vicePresidentInternalData,
    'vicePresidentExternalData' => $vicePresidentExternalData,
    // Add data for other positions here
);

// Return the updated data as JSON
echo json_encode($response);
?>

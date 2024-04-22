<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$presidentData = array();
$vicePresidentData = array();

// Fetch organization from GET parameter
$organization = isset($_GET['organization']) ? $_GET['organization'] : '';

// Prepare SQL queries with organization filter
$sqlPresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM positions 
                LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'President'
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                WHERE voters1.organization != ''";
$sqlVicePresident = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                    COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                    FROM positions 
                    LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = 'Vice President'
                    LEFT JOIN votes ON candidates.id = votes.candidate_id
                    LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                    WHERE voters1.organization != ''";

// Add organization filter if organization is specified
if (!empty($organization)) {
    $sqlPresident .= " AND voters1.organization = '$organization'";
    $sqlVicePresident .= " AND voters1.organization = '$organization'";
}

// Group by candidate ID and fetch data for president candidates
$sqlPresident .= " GROUP BY candidates.id";
$queryPresident = $conn->query($sqlPresident);
while ($row = $queryPresident->fetch_assoc()) {
    $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
}

// Group by candidate ID and fetch data for vice president candidates
$sqlVicePresident .= " GROUP BY candidates.id";
$queryVicePresident = $conn->query($sqlVicePresident);
while ($row = $queryVicePresident->fetch_assoc()) {
    $vicePresidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
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

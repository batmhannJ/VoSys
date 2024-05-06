<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$candidatesData = array();

// Fetch organization from GET parameter
$organization = isset($_GET['organization']) ? $_GET['organization'] : '';

// Prepare SQL query with organization filter for President and Vice President
$sqlCandidates = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                  positions.description AS position,
                  COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                  FROM positions 
                  LEFT JOIN candidates ON positions.id = candidates.position_id
                  LEFT JOIN votes ON candidates.id = votes.candidate_id
                  LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                  WHERE positions.description IN ('President', 'Vice President')
                  AND voters1.organization != ''";

// Add organization filter if organization is specified
if (!empty($organization)) {
    $sqlCandidates .= " AND voters1.organization = '$organization'";
}

// Group by candidate ID and position and fetch data for President and Vice President candidates
$sqlCandidates .= " GROUP BY candidates.id, positions.description";
$queryCandidates = $conn->query($sqlCandidates);
while ($row = $queryCandidates->fetch_assoc()) {
    $candidatesData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name'] . ' (' . $row['position'] . ')');
}

// Close database connection
$conn->close();

// Return the updated data as JSON
echo json_encode($candidatesData);
?>

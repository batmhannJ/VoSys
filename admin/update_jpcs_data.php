<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize array to store updated data
$presidentData = array();

// Fetch organization from GET parameter
$organization = isset($_GET['organization']) ? $_GET['organization'] : '';

// Prepare SQL query with organization filter
$sql = "SELECT candidates.firstname, candidates.lastname, COUNT(votes.id) AS vote_count
        FROM candidates
        LEFT JOIN votes ON candidates.id = votes.candidate_id
        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
        WHERE candidates.position = 'President' AND voters1.organization = '$organization'
        GROUP BY candidates.id";

// Execute SQL query
$result = $conn->query($sql);

// Process query results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Construct candidate name
        $candidateName = $row['firstname'] . ' ' . $row['lastname'];
        // Store data in the array
        $presidentData[] = array("y" => intval($row['vote_count']), "label" => $candidateName);
    }
}

// Close database connection
$conn->close();

// Return JSON response
echo json_encode($presidentData);
?>

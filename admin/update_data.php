<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data for each position
$positionData = array();

// Define an array of positions
$positions = array(
    'President',
    'Vice President for Internal Affairs',
    'Vice President for External Affairs',
    'Secretary',
    'Treasurer',
    'Auditor',
    'P.R.O',
    'Dir. for Membership',
    'Dir. for Special Project',
    'Block A 1st Year Representative',
    'Block B 1st Year Representative',
    'Block A 2nd Year Representative',
    'Block B 2nd Year Representative',
    'Block A 3rd Year Representative',
    'Block B 3rd Year Representative',
    'Block A 4th Year Representative',
    'Block B 4th Year Representative'
);

// Fetch updated data for each position
foreach ($positions as $position) {
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM positions 
            LEFT JOIN candidates ON positions.id = candidates.position_id AND positions.description = '$position'
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != ''
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    if ($query) {
        while ($row = $query->fetch_assoc()) {
            // Store position data in the positionData array
            $positionData[$position][] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
        }
    } else {
        // Handle query error
        echo "Error fetching $position data: " . $conn->error;
    }
}

// Close database connection
$conn->close();

// Return the updated data as JSON
echo json_encode($positionData);
?>

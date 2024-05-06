<?php
// Include your database connection file or establish a database connection here
include 'includes/db_connection.php'; // Change this to your actual database connection file

// Assuming you have a function to fetch vote counts for JPCS from your database
// Fetch vote counts for President, Vice President, and Secretary
$query = "SELECT candidate_name, vote_count FROM election_results WHERE organization = 'JPCS' AND position IN ('President', 'Vice President', 'Secretary')";
$result = $conn->query($query);

$data = array(
    'presidentData' => array(),
    'vicePresidentData' => array(),
    'secretaryData' => array()
);

// Process the query result
if ($result) {
    while ($row = $result->fetch_assoc()) {
        switch ($row['position']) {
            case 'President':
                $data['presidentData'][] = array('y' => intval($row['vote_count']), 'label' => 'President');
                break;
            case 'Vice President':
                $data['vicePresidentData'][] = array('y' => intval($row['vote_count']), 'label' => 'Vice President');
                break;
            case 'Secretary':
                $data['secretaryData'][] = array('y' => intval($row['vote_count']), 'label' => 'Secretary');
                break;
        }
    }
}

// Output the JSON-encoded data
header('Content-Type: application/json');
echo json_encode($data);
?>

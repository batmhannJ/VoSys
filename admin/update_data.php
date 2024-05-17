<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$presidentData = array();
$vpInternalAffairsData = array();
$vpExternalAffairsData = array();

// Function to fetch data based on position description and category id
function fetchData($positionDescription, $categoryId, &$data, $conn) {
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                FROM candidates 
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                LEFT JOIN positions ON candidates.position_id = positions.id
                WHERE positions.description = '$positionDescription'
                AND candidates.category_id = $categoryId
                GROUP BY candidates.id";

    $query = $conn->query($sql);
    if ($query) {
        while ($row = $query->fetch_assoc()) {
            $data[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
        }
    } else {
        // Handle query error
        echo "Error fetching data for $positionDescription: " . $conn->error;
    }
}

// Fetch data for President candidates with category_id 1
fetchData('President', 1, $presidentData, $conn);

// Fetch data for Vice President for Internal Affairs candidates with category_id 2
fetchData('Vice President for Internal Affairs', 3, $vpInternalAffairsData, $conn);

// Fetch data for Vice President for External Affairs candidates with category_id 3
fetchData('Vice President for External Affairs', 4, $vpExternalAffairsData, $conn);

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

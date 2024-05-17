<?php
// Include necessary files and initialize database connection
include 'includes/session.php';
include 'includes/db.php';

// Initialize arrays to store updated data
$presidentData = array();
$vpInternalAffairsData = array();
$vpExternalAffairsData = array();

// Fetch updated data for President candidates
$sqlPresident = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS candidate_name, 
                COALESCE(COUNT(v.candidate_id), 0) AS vote_count
                FROM candidates c
                LEFT JOIN votes v ON c.id = v.candidate_id
                WHERE c.category_id = 1
                GROUP BY c.id";
$queryPresident = $conn->prepare($sqlPresident);
$queryPresident->execute();
$resultPresident = $queryPresident->get_result();

if ($resultPresident) {
    while ($row = $resultPresident->fetch_assoc()) {
        $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching president data: " . $conn->error;
}

// Fetch updated data for Vice President for Internal Affairs candidates
$sqlVicePresidentInternalAffairs = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS candidate_name, 
                        COALESCE(COUNT(v.candidate_id), 0) AS vote_count
                        FROM candidates c
                        LEFT JOIN votes v ON c.id = v.candidate_id
                        WHERE c.category_id = 3
                        GROUP BY c.id";
$queryVPInternalAffairs = $conn->prepare($sqlVPInternalAffairs);
$queryVPInternalAffairs->execute();
$resultVPInternalAffairs = $queryVPInternalAffairs->get_result();

if ($resultVPInternalAffairs) {
    while ($row = $resultVPInternalAffairs->fetch_assoc()) {
        $vpInternalAffairsData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Vice President for Internal Affairs data: " . $conn->error;
}

// Fetch updated data for Vice President for External Affairs candidates
$sqlVPExternalAffairs = "SELECT CONCAT(c.firstname, ' ', c.lastname) AS candidate_name, 
                        COALESCE(COUNT(v.candidate_id), 0) AS vote_count
                        FROM candidates c
                        LEFT JOIN votes v ON c.id = v.candidate_id
                        WHERE c.category_id = 4
                        GROUP BY c.id";
$queryVPExternalAffairs = $conn->prepare($sqlVPExternalAffairs);
$queryVPExternalAffairs->execute();
$resultVPExternalAffairs = $queryVPExternalAffairs->get_result();

if ($resultVPExternalAffairs) {
    while ($row = $resultVPExternalAffairs->fetch_assoc()) {
        $vpExternalAffairsData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
} else {
    // Handle query error
    echo "Error fetching Vice President for External Affairs data: " . $conn->error;
}

// Close prepared statements
$queryPresident->close();
$queryVPInternalAffairs->close();
$queryVPExternalAffairs->close();

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

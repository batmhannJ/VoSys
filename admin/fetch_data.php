<?php
// Include your database connection file
include 'includes/db_connection.php';

// Check if organization is provided
if (isset($_GET['organization'])) {
    $organization = $_GET['organization'];

    // Example query: Change this to match your database schema
    $query = "SELECT label, president_votes AS presidentVotes, vice_president_votes AS vicePresidentVotes FROM voting_data WHERE organization = '$organization'";

    // Execute the query
    $result = mysqli_query($connection, $query);

    if ($result) {
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'Failed to fetch data'));
    }
} else {
    echo json_encode(array('error' => 'Organization parameter not provided'));
}
?>

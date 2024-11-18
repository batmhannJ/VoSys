<?php
include 'includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the status from the POST data
    $newStatus = $_POST["status"];

    // Perform the database update (replace this with your actual database update code)
    $updateQuery = "UPDATE elcetion SET status = '$newStatus' WHERE id = ?";
    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        // Return a success response to the client
        echo "success";
    } else {
        // Return an error response to the client
        echo "error";
    }
} else {
    // Invalid request method
    echo "invalid";
}
?>

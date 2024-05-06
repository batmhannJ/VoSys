<?php
include 'includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Update the archived field in the database
    $sql = "UPDATE admin SET archived = FALSE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Success"; // Return success message to the client
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>

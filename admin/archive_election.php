<?php
// include database connection file
include_once("includes/session.php");

if(isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "UPDATE election SET archived = TRUE WHERE id = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($query);
    
    // Bind parameters
    $stmt->bind_param("i", $id);
    
    // Execute statement
    if($stmt->execute()) {
        echo "Election archived successfully.";
    } else {
        echo "Error archiving election: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

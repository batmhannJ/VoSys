<?php
include 'includes/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Get the admin ID from the POST data
    $admin_id = $_POST['id'];

    // Update the database to mark the admin as archived
    $sql = "UPDATE admin SET archived = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        // If the query was successful, set a success message
        $_SESSION['success'] = "Admin archived successfully.";
    } else {
        // If the query failed, set an error message
        $_SESSION['error'] = "Error archiving admin: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();

    // Redirect to archive.php after archiving the admin
    header("Location: archive.php");
    exit();
} else {
    // If the request method is not POST or ID is not set, set an error message
    $_SESSION['error'] = "Invalid request.";

    // Redirect back to the sub_admin.php page
    header("Location: sub_admin.php");
    exit();
}
?>

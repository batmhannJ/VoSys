<?php
// Start the session
session_start();

// Include session and database connection files
include 'includes/session.php';
include 'includes/conn.php'; // Ensure the connection file is included

// Check if necessary POST data is set
if (isset($_POST['election_id']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
    // Get the POST data
    $electionId = $_POST['election_id'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Validate and sanitize input data (you can customize validation based on your needs)
    $electionId = filter_var($electionId, FILTER_VALIDATE_INT);
    if (!$electionId) {
        echo json_encode(['success' => false, 'error' => 'Invalid election ID.']);
        exit;
    }

    // Ensure start and end times are in the correct format (YYYY-MM-DD HH:MM:SS)
    $startTime = DateTime::createFromFormat('Y-m-d\TH:i', $startTime);
    $endTime = DateTime::createFromFormat('Y-m-d\TH:i', $endTime);

    if (!$startTime || !$endTime) {
        echo json_encode(['success' => false, 'error' => 'Invalid date format.']);
        exit;
    }

    // Format the datetime objects into the correct MySQL format (YYYY-MM-DD HH:MM:SS)
    $startTime = $startTime->format('Y-m-d H:i:s');
    $endTime = $endTime->format('Y-m-d H:i:s');

    // Prepare SQL query to update election status and times in the database
    $stmt = $conn->prepare("UPDATE election SET status = 1, starttime = ?, endtime = ? WHERE id = ?");
    $stmt->bind_param('ssi', $startTime, $endTime, $electionId);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update election.']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters.']);
}

// Close the database connection
$conn->close();
?>
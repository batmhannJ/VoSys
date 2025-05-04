<?php
include 'includes/session.php';

// Set JSON header
header('Content-Type: application/json');

// Disable PHP warnings and notices to prevent JSON corruption
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize input

    if ($id <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid election ID.'
        ]);
        exit;
    }

    // Check if the election exists
    $sql_check = "SELECT * FROM election WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $stmt_check->close();

    if ($result_check->num_rows > 0) {
        // Start transaction to ensure data consistency
        $conn->begin_transaction();

        try {
            // Archive and deactivate the election
            $sql = "UPDATE election SET archived = TRUE, status = 0 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $election_updated = $stmt->execute();
            $stmt->close();

            if (!$election_updated) {
                throw new Exception('Failed to archive election.');
            }

            // Archive candidates related to the election
            $sql_archive_candidates = "UPDATE candidates SET archived = TRUE WHERE election_id = ?";
            $stmt_candidates = $conn->prepare($sql_archive_candidates);
            $stmt_candidates->bind_param('i', $id);
            $candidates_updated = $stmt_candidates->execute();
            $stmt_candidates->close();

            if (!$candidates_updated) {
                throw new Exception('Failed to archive candidates.');
            }

            // Archive categories related to the election
            $sql_archive_categories = "UPDATE categories SET archived = TRUE WHERE election_id = ?";
            $stmt_categories = $conn->prepare($sql_archive_categories);
            $stmt_categories->bind_param('i', $id);
            $categories_updated = $stmt_categories->execute();
            $stmt_categories->close();

            if (!$categories_updated) {
                throw new Exception('Failed to archive categories.');
            }

            // Commit transaction
            $conn->commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Election, candidates, and categories archived and deactivated successfully.'
            ]);
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Election ID not found.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Select election to archive first.'
    ]);
}

$conn->close();
exit;
?>
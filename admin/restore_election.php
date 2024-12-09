<?php
include 'includes/session.php';
include 'includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Restore election
    $sql_restore_election = "UPDATE election SET archived = FALSE WHERE id = ?";
    $stmt_election = $conn->prepare($sql_restore_election);
    $stmt_election->bind_param('i', $id);

    if ($stmt_election->execute()) {
        // Restore candidates associated with the election
        $sql_restore_candidates = "UPDATE candidates SET archived = FALSE WHERE election_id = ?";
        $stmt_candidates = $conn->prepare($sql_restore_candidates);
        $stmt_candidates->bind_param('i', $id);
        
        if ($stmt_candidates->execute()) {
            // Restore categories associated with the election
            $sql_restore_categories = "UPDATE categories SET archived = FALSE WHERE election_id = ?";
            $stmt_categories = $conn->prepare($sql_restore_categories);
            $stmt_categories->bind_param('i', $id);

            if ($stmt_categories->execute()) {
                $_SESSION['success'] = "Election, candidates, and categories successfully restored!";
                header("Location: archive.php?type=election");
                exit();
            } else {
                $_SESSION['error'] = "Error restoring categories: " . $conn->error;
                header("Location: archive.php?type=election");
                exit();
            }
        } else {
            $_SESSION['error'] = "Error restoring candidates: " . $conn->error;
            header("Location: archive.php?type=election");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error restoring election: " . $conn->error;
        header("Location: archive.php?type=election");
        exit();
    }
} else {
    header("Location: archive.php?type=election");
    exit();
}

?>

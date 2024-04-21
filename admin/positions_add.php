<?php
include 'includes/session.php';

// Check if form data is submitted
if(isset($_POST['add'])){
    // Sanitize and validate input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $max_vote = filter_var($_POST['max_vote'], FILTER_VALIDATE_INT);

    // Check if input is valid
    if($name && $max_vote !== false && $max_vote > 0) {
        // Fetch highest priority category
        $sql_fetch_priority = "SELECT priority FROM categories WHERE election_id = 1 ORDER BY priority DESC LIMIT 1";
        $stmt_fetch_priority = $conn->prepare($sql_fetch_priority);
        $stmt_fetch_priority->execute();
        $result_priority = $stmt_fetch_priority->get_result();

        if($result_priority->num_rows > 0) {
            $row = $result_priority->fetch_assoc();
            $priority = $row['priority'] + 1;
        } else {
            // If no category found, set priority as 1
            $priority = 1;
        }

        // Insert new category
        $sql_insert_category = "INSERT INTO categories (name, max_vote, priority) VALUES (?, ?, ?)";
        $stmt_insert_category = $conn->prepare($sql_insert_category);
        $stmt_insert_category->bind_param("sii", $name, $max_vote, $priority);
        
        if($stmt_insert_category->execute()){
            $_SESSION['success'] = 'Position added successfully';
        } else {
            $_SESSION['error'] = 'Error adding position: ' . $stmt_insert_category->error;
        }

        $stmt_insert_category->close();
        $stmt_fetch_priority->close();
    } else {
        $_SESSION['error'] = 'Invalid input. Please provide a valid name and maximum vote count.';
    }
} else {
    $_SESSION['error'] = 'Fill up add form first';
}

$stmt_fetch_priority->close();
$conn->close();

header('location: positions_jpcs.php');
?>

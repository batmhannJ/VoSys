<?php
include 'includes/session.php';

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $voters = $_POST['voters'];
    $start = $_POST['starttime'];
    $end = $_POST['endtime'];

    // Assuming the name attribute of your select element is "status"
    $status = ($_POST['status'] === 'Active') ? 1 : 0;

    // Use a prepared statement for SELECT
    $sqlSelect = "SELECT * FROM election WHERE id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();
    $row = $result->fetch_assoc();
    $stmtSelect->close();

    if ($row) {
        // Use a prepared statement for UPDATE
        $sqlUpdate = "UPDATE election SET title = ?, voters = ?, starttime = ?, endtime = ?, status = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sssssi", $title, $voters, $start, $end, $status, $id);

        if ($stmtUpdate->execute()) {
            $_SESSION['success'] = 'Election updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating election: ' . $stmtUpdate->error;
        }

        $stmtUpdate->close();
    } else {
        $_SESSION['error'] = 'No record found for the specified ID';
    }
} else {
    $_SESSION['error'] = 'Fill up edit form first';
}

header('location: elections_jpcs.php');
?>

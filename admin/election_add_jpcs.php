<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['title'], $_POST['academic_yr'])) {
    $title = $_POST['title'];
    $organization = "JPCS"; // Default value
    $voters = "JPCS Students"; // Default value
    $academic_yr = $_POST['academic_yr']; // Academic Year from the dropdown

    // Prepared statement para maiwasan ang SQL injection
    $stmt = $conn->prepare("INSERT INTO election (title, voters, status, organization, academic_yr) VALUES (?, ?, 0, ?, ?)");
    $stmt->bind_param("ssss", $title, $voters, $organization, $academic_yr);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Election added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add election!";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid input data!";
}

$conn->close();
header('Location: elections_jpcs.php');
exit();

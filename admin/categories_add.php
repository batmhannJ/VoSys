<?php
include 'includes/session.php';

if(isset($_POST['addCategory'])){
    $name = $_POST['categoryName'];
    
    $added_by = $_SESSION['admin'];
    
    $stmt = $conn->prepare("INSERT INTO categories (name, added_by) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $added_by);

    if($stmt->execute()){
        $_SESSION['success'] = 'Category added successfully';
    } else {
        $_SESSION['error'] = 'Failed to add category: ' . $conn->error;
    }

    $stmt->close();
}
else{
    $_SESSION['error'] = 'Fill up add form first';
}

header('location: categories.php');
?>

<?php
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("UPDATE election SET archived=1 WHERE id=:id");
        $stmt->execute(['id'=>$id]);

        $_SESSION['success'] = 'Election successfully archived';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
else{
    $_SESSION['error'] = 'Select election to archive first';
}

header('location: election_csc.php');
?>

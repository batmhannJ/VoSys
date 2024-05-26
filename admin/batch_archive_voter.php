<?php
include 'includes/session.php';

if(isset($_POST['ids'])){
  $ids = $_POST['ids'];
  foreach($ids as $id){
    $sql = "UPDATE voters SET archived = TRUE WHERE id = '$id'";
    $conn->query($sql);
  }
  $_SESSION['success'] = 'Voters archived successfully';
} else {
  $_SESSION['error'] = 'No voters selected for archiving';
}

header('location: voters.php');
?>

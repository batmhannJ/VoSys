<?php
include 'includes/session.php';

if(isset($_POST['id'])) {
  $id = $_POST['id'];
  foreach($ids as $id) {
    if($_GET['type'] === 'voters') {
      $sql = "UPDATE voters SET archived = FALSE WHERE id = '$id'";
    } elseif($_GET['type'] === 'admin') {
      $sql = "UPDATE admin SET archived = FALSE WHERE id = '$id'";
    } elseif($_GET['type'] === 'election') {
      $sql = "UPDATE election SET archived = FALSE WHERE id = '$id'";
    }
    $conn->query($sql);
  }
}
?>

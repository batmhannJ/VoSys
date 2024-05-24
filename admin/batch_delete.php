<?php
include 'includes/session.php';

if(isset($_POST['ids'])) {
  $ids = $_POST['ids'];
  foreach($ids as $id) {
    if($_GET['type'] === 'voters') {
      $sql = "DELETE FROM voters WHERE id = '$id'";
    } elseif($_GET['type'] === 'admin') {
      $sql = "DELETE FROM admin WHERE id = '$id'";
    } elseif($_GET['type'] === 'election') {
      $sql = "DELETE FROM election WHERE id = '$id'";
    }
    $conn->query($sql);
  }
}
?>

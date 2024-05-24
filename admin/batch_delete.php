<?php
include 'includes/session.php';

if(isset($_POST['id'])) {
  $id = $_POST['id'];
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

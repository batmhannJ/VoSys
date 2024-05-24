<?php
include 'includes/session.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'];
$ids = $data['ids'];

if ($action == 'restore') {
  foreach ($ids as $id) {
    if (isset($_GET['type']) && $_GET['type'] === 'voters') {
      $sql = "UPDATE voters SET archived = FALSE WHERE id = $id";
    } elseif (isset($_GET['type']) && $_GET['type'] === 'admin') {
      $sql = "UPDATE admin SET archived = FALSE WHERE id = $id";
    } elseif (isset($_GET['type']) && $_GET['type'] === 'election') {
      $sql = "UPDATE election SET archived = FALSE WHERE id = $id";
    }
    $conn->query($sql);
  }
  echo json_encode(['success' => true]);
} elseif ($action == 'delete') {
  foreach ($ids as $id) {
    if (isset($_GET['type']) && $_GET['type'] === 'voters') {
      $sql = "DELETE FROM voters WHERE id = $id";
    } elseif (isset($_GET['type']) && $_GET['type'] === 'admin') {
      $sql = "DELETE FROM admin WHERE id = $id";
    } elseif (isset($_GET['type']) && $_GET['type'] === 'election') {
      $sql = "DELETE FROM election WHERE id = $id";
    }
    $conn->query($sql);
  }
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>

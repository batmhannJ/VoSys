<?php
include 'includes/session.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data['action'] == 'restore' && !empty($data['ids'])) {
  $ids = implode(',', array_map('intval', $data['ids']));
  if ($_GET['type'] === 'voters') {
    $sql = "UPDATE voters SET archived = FALSE WHERE id IN ($ids)";
  } elseif ($_GET['type'] === 'admin') {
    $sql = "UPDATE admin SET archived = FALSE WHERE id IN ($ids)";
  } elseif ($_GET['type'] === 'election') {
    $sql = "UPDATE election SET archived = FALSE WHERE id IN ($ids)";
  }
  if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false]);
  }
} elseif ($data['action'] == 'delete' && !empty($data['ids'])) {
  $ids = implode(',', array_map('intval', $data['ids']));
  if ($_GET['type'] === 'voters') {
    $sql = "DELETE FROM voters WHERE id IN ($ids)";
  } elseif ($_GET['type'] === 'admin') {
    $sql = "DELETE FROM admin WHERE id IN ($ids)";
  } elseif ($_GET['type'] === 'election') {
    $sql = "DELETE FROM election WHERE id IN ($ids)";
  }
  if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false]);
  }
} else {
  echo json_encode(['success' => false]);
}
?>

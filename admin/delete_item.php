<?php
include 'includes/session.php';

if (isset($_POST['id']) && isset($_POST['user'])) {
    $id = $_POST['id'];
    $user = $_POST['user'];

    try {
        if ($user == 'voters') {
            $sql = "DELETE FROM voters WHERE id = ?";
        } elseif($user === 'admin') {
            $query = "DELETE FROM admin WHERE id = '$id'";
        } elseif ($user == 'election') {
            $sql = "DELETE FROM election WHERE id = ?";
        } elseif ($user == 'candidates') {
            $sql = "DELETE FROM candidates WHERE id = ?";
        } else {
            throw new Exception("Invalid user type");
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Item deleted successfully';
        } else {
            $_SESSION['error'] = 'Something went wrong while deleting';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'Select item to delete first';
}

header('location: ' . $_SERVER['HTTP_REFERER']);
?>


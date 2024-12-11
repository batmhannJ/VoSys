<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = shell_exec('python3 C:\Users\Almira\Desktop\VoSys\admin 2>&1'); // Adjust the path
    echo json_encode(['status' => 'success', 'output' => $output]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>

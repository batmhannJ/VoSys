<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = shell_exec('python /home/u247141684/domains/vosys.org/public_html/admin/scan_ballot.py 2>&1'); // Adjust the path
    echo json_encode(['status' => 'success', 'output' => $output]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>

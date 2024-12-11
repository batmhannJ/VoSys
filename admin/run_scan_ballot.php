<?php
// Execute the Python script
$output = shell_exec('python3 /C:/Users/Almira/Desktop/VoSys/admin/scan_ballot.py 2>&1');
echo json_encode(['output' => $output]);
?>

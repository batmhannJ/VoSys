<?php
// Corrected path without leading "/"
$pythonPath = 'C:/Users/Almira/AppData/Local/Programs/Python/Python313/python.exe';
$scriptPath = 'C:/Users/Almira/Desktop/VoSys/admin/scan_ballot.py';
$command = "$pythonPath $scriptPath 2>&1";

$output = shell_exec($command);
echo json_encode(['output' => $output]);
?>

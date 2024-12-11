<?php
// Full path to Python executable
$pythonPath = 'C:/Path/To/Python/python.exe';
// Full path to the script
$scriptPath = 'C:/Users/Almira/Desktop/VoSys/admin/scan_ballot.py';

// Execute the Python script
$output = shell_exec("$pythonPath $scriptPath 2>&1");
echo json_encode(['output' => $output]);
?>

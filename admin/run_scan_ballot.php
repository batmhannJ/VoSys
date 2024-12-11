<?php
$pythonPath = 'C:/Users/Almira/AppData/Local/Programs/Python/Python313/python.exe';
$scriptPath = 'C:/Users/Almira/Desktop/VoSys/admin/scan_ballot.py';
$command = "$pythonPath $scriptPath 2>&1";

// Log the command for debugging
file_put_contents('debug_command.txt', $command);

$output = shell_exec($command);

// Log the output for debugging
file_put_contents('debug_output.txt', $output);

echo json_encode(['output' => $output]);
?>

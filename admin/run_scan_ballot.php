<?php
$pythonPath = 'C:\\Users\\Almira\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
$scriptPath = 'C:\\Users\\Almira\\Desktop\\VoSys\\admin\\scan_ballot.py';
$command = "cmd /c \"$pythonPath\" \"$scriptPath\"";

$output = shell_exec($command);
echo json_encode(['output' => $output]);
?>

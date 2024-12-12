<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set the command to change directory and run the Python script
    $command = "cd admin && python3 scan_ballot.py";

    // Execute the command
    $output = [];
    $return_var = 0;
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo json_encode([
            'message' => 'Scan executed successfully.',
            'output' => implode("\n", $output)
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'message' => 'Failed to execute scan.',
            'error' => implode("\n", $output)
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Path to Python script
    $command = "cd /home/u247141684/domains/vosys.org/public_html/admin && python scan_ballot.py 2>&1";
    exec($command, $output, $return_var);

    // Log the output and error for debugging
    file_put_contents('scan_ballot_log.txt', "Command executed: $command\n", FILE_APPEND);
    file_put_contents('scan_ballot_log.txt', implode("\n", $output), FILE_APPEND);
    file_put_contents('scan_ballot_log.txt', "Return Code: " . $return_var . "\n", FILE_APPEND);

    if ($return_var === 0) {
        echo json_encode([
            'message' => 'Scan executed successfully.',
            'output' => implode("\n", $output)
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'message' => 'Failed to execute scan.',
            'error' => implode("\n", $output),
            'return_var' => $return_var
        ]);
    }

} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>

<?php
// Example in PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/run-scan-ballot') {
    // Handle your logic here
    echo json_encode(['message' => 'Endpoint working']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
?>

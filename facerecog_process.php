<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $imageData = $data['image'];

    // Decode the base64 image
    $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $decodedImage = base64_decode($imageData);

    // Save the image to the server (optional, for testing)
    $filename = 'captured_face.jpg';
    file_put_contents($filename, $decodedImage);

    // Call the Python script to process the image
    $command = escapeshellcmd("python facerecog.py " . $filename);
    $output = shell_exec($command);

    // Return the result to the frontend
    if (strpos($output, 'success') !== false) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>

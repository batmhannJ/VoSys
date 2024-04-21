<?php
session_start();

$captchaCode = $_SESSION['captcha'];

// Set the content type
header('Content-type: image/png');

// Create an image with a white background
$image = imagecreatetruecolor(120, 40);
$background_color = imagecolorallocate($image, 255, 255, 255);
imagefilledrectangle($image, 0, 0, 120, 40, $background_color);

// Set the text color to black
$text_color = imagecolorallocate($image, 0, 0, 0);

// Add the CAPTCHA code to the image
imagestring($image, 5, 20, 10, $captchaCode, $text_color);

// Output the image
imagepng($image);

// Clean up
imagedestroy($image);
?>

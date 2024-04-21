<?php
session_start();

function generateCaptchaCode($length = 6)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $captchaCode = '';
    for ($i = 0; $i < $length; $i++) {
        $captchaCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $captchaCode;
}

// Generate a new CAPTCHA code and store it in the session
$captchaCode = generateCaptchaCode();
$_SESSION['captcha'] = $captchaCode;

// Set the content type to PNG image
header('Content-type: image/png');

// Create an image with a white background
$image = imagecreatetruecolor(120, 40);
$bgColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgColor);

// Add the CAPTCHA code to the image
$textColor = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 10, 10, $captchaCode, $textColor);

// Output the image as PNG
imagepng($image);

// Clean up resources
imagedestroy($image);
?>

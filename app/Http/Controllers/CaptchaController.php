<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    /**
     * Generate a visual CAPTCHA image and store the code in session.
     */
    public function generate(Request $request)
    {
        // Generate 5 alphanumeric characters (uppercase + digits, no ambiguous chars like 0,O,I,1)
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // Store in session
        $request->session()->put('captcha_code', strtolower($code));

        // Image dimensions
        $width  = 160;
        $height = 50;

        $image = imagecreatetruecolor($width, $height);

        // Background: light gradient effect using random light colors
        $bgColor = imagecolorallocate($image, 240, 242, 255);
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Add noise lines
        for ($i = 0; $i < 6; $i++) {
            $lineColor = imagecolorallocate($image,
                random_int(150, 210),
                random_int(150, 210),
                random_int(150, 210)
            );
            imageline($image,
                random_int(0, $width),
                random_int(0, $height),
                random_int(0, $width),
                random_int(0, $height),
                $lineColor
            );
        }

        // Add noise dots
        for ($i = 0; $i < 60; $i++) {
            $dotColor = imagecolorallocate($image,
                random_int(140, 200),
                random_int(140, 200),
                random_int(140, 200)
            );
            imagesetpixel($image, random_int(0, $width), random_int(0, $height), $dotColor);
        }

        // Draw each character with slight random offset and color variation
        $x = 15;
        for ($i = 0; $i < strlen($code); $i++) {
            $textColor = imagecolorallocate($image,
                random_int(20, 80),
                random_int(20, 80),
                random_int(20, 130)
            );
            $y = random_int(10, 20);
            // Use built-in font (size 5 is largest built-in)
            imagechar($image, 5, $x, $y, $code[$i], $textColor);
            $x += random_int(25, 30);
        }

        // Output as PNG
        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData, 200)->header('Content-Type', 'image/png');
    }
}

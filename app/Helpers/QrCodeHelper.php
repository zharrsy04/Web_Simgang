<?php

namespace App\Helpers;

use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Common\ErrorCorrectionLevel;

class QrCodeHelper
{
    /**
     * Generate a crisp PNG QR Code encoded as base64 Data URI.
     * Fully compatible with Dompdf and web browsers without requiring imagick extension.
     *
     * @param string $url The text or URL to encode
     * @param int $targetSize Target size in pixels (default 200)
     * @param int $margin Quiet zone in modules (default 2)
     * @return string Data URI (data:image/png;base64,...)
     */
    public static function generatePngBase64(string $url, int $targetSize = 200, int $margin = 2): string
    {
        $qrCode = Encoder::encode($url, ErrorCorrectionLevel::M());
        $matrix = $qrCode->getMatrix();
        $matrixWidth = $matrix->getWidth();
        $matrixHeight = $matrix->getHeight();

        $scale = max(2, (int) floor($targetSize / ($matrixWidth + 2 * $margin)));
        $imgWidth = ($matrixWidth + 2 * $margin) * $scale;
        $imgHeight = ($matrixHeight + 2 * $margin) * $scale;

        $img = imagecreatetruecolor($imgWidth, $imgHeight);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        imagefilledrectangle($img, 0, 0, $imgWidth - 1, $imgHeight - 1, $white);

        for ($y = 0; $y < $matrixHeight; $y++) {
            for ($x = 0; $x < $matrixWidth; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    imagefilledrectangle(
                        $img,
                        ($x + $margin) * $scale,
                        ($y + $margin) * $scale,
                        ($x + $margin + 1) * $scale - 1,
                        ($y + $margin + 1) * $scale - 1,
                        $black
                    );
                }
            }
        }

        ob_start();
        imagepng($img);
        $pngData = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode($pngData);
    }
}

<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Feature;

use Aicrion\QRCode\QRCode;
use Aicrion\QRCode\ValueObjects\Color;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;
use PHPUnit\Framework\TestCase;

/**
 * Ensures colored (non black/white) QR codes generate correctly and can
 * still be reliably read back by the decoder.
 */
final class ColoredQRCodeTest extends TestCase
{
    public function test_generates_colored_png_and_reads_it_back(): void
    {
        $qr = QRCode::make();
        $payload = 'https://aicrion.dev/colored';

        $options = (new QRCodeOptions())
            ->withSize(500)
            ->withColors(Color::fromHex('#0f172a'), Color::fromHex('#f8fafc'));

        $path = sys_get_temp_dir() . '/aicrion_colored_' . uniqid() . '.png';
        $qr->generateToFile($payload, $path, $options);

        $result = $qr->readFromPath($path);

        self::assertSame($payload, $result->content);

        unlink($path);
    }

    public function test_colored_qrcode_pixels_reflect_custom_colors(): void
    {
        $qr = QRCode::make();
        $options = (new QRCodeOptions())
            ->withSize(300)
            ->withColors(Color::fromHex('#ff0000'), Color::fromHex('#00ff00'));

        $bytes = $qr->generate('color test', $options);
        $image = imagecreatefromstring($bytes);

        self::assertNotFalse($image);

        $foundRed = false;
        $foundGreen = false;

        for ($x = 0; $x < imagesx($image) && (! $foundRed || ! $foundGreen); $x++) {
            for ($y = 0; $y < imagesy($image) && (! $foundRed || ! $foundGreen); $y++) {
                $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));

                if ($rgb['red'] > 200 && $rgb['green'] < 50) {
                    $foundRed = true;
                }

                if ($rgb['green'] > 200 && $rgb['red'] < 50) {
                    $foundGreen = true;
                }
            }
        }

        self::assertTrue($foundRed, 'Expected to find red foreground pixels.');
        self::assertTrue($foundGreen, 'Expected to find green background pixels.');

        imagedestroy($image);
    }
}

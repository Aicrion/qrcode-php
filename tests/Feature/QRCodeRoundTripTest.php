<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Feature;

use Aicrion\QRCode\QRCode;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;
use PHPUnit\Framework\TestCase;

/**
 * End-to-end test: generate a QR code, then read it back and assert
 * the decoded content matches the original payload.
 */
final class QRCodeRoundTripTest extends TestCase
{
    public function test_generated_qrcode_can_be_read_back(): void
    {
        $qr = QRCode::make();
        $payload = 'https://github.com/aicrion/qrcode-php';

        $path = sys_get_temp_dir() . '/aicrion_roundtrip_' . uniqid() . '.png';
        $qr->generateToFile($payload, $path, (new QRCodeOptions())->withSize(400));

        $result = $qr->readFromPath($path);

        self::assertSame($payload, $result->content);

        unlink($path);
    }

    public function test_generated_data_uri_round_trip(): void
    {
        $qr = QRCode::make();
        $payload = 'Aicrion QRCode Library';

        $dataUri = $qr->generateDataUri($payload, new QRCodeOptions());
        $result = $qr->readFromBase64($dataUri);

        self::assertSame($payload, $result->content);
    }
}

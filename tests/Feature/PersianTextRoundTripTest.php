<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Feature;

use Aicrion\QRCode\QRCode;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;
use PHPUnit\Framework\TestCase;

/**
 * Ensures UTF-8 multi-byte content (e.g. Persian/Farsi text) is correctly
 * encoded when generating and correctly decoded when reading back.
 */
final class PersianTextRoundTripTest extends TestCase
{
    public function test_generates_and_reads_back_persian_text(): void
    {
        $qr = QRCode::make();
        $payload = 'سلام دنیا! این یک آزمایش فارسی برای Aicrion QRCode است.';

        $path = sys_get_temp_dir() . '/aicrion_farsi_' . uniqid() . '.png';
        $qr->generateToFile($payload, $path, (new QRCodeOptions())->withSize(500));

        $result = $qr->readFromPath($path);

        self::assertSame($payload, $result->content);

        unlink($path);
    }

    public function test_generates_svg_with_persian_text_data_uri(): void
    {
        $qr = QRCode::make();
        $payload = 'شرکت آیکریون - کد کیوآر فارسی';

        $bytes = $qr->generate($payload, new QRCodeOptions());

        self::assertNotEmpty($bytes);
    }
}

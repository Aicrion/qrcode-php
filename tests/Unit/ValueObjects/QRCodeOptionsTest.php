<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Unit\ValueObjects;

use Aicrion\QRCode\Enums\ErrorCorrectionLevel;
use Aicrion\QRCode\Enums\OutputFormat;
use Aicrion\QRCode\ValueObjects\Color;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;
use PHPUnit\Framework\TestCase;

final class QRCodeOptionsTest extends TestCase
{
    public function test_default_options(): void
    {
        $options = new QRCodeOptions();

        self::assertSame(300, $options->size);
        self::assertSame(OutputFormat::PNG, $options->format);
        self::assertSame(ErrorCorrectionLevel::MEDIUM, $options->errorCorrectionLevel);
    }

    public function test_with_format_returns_new_immutable_instance(): void
    {
        $options = new QRCodeOptions();
        $svgOptions = $options->withFormat(OutputFormat::SVG);

        self::assertSame(OutputFormat::PNG, $options->format);
        self::assertSame(OutputFormat::SVG, $svgOptions->format);
        self::assertNotSame($options, $svgOptions);
    }

    public function test_with_size(): void
    {
        $options = (new QRCodeOptions())->withSize(500);

        self::assertSame(500, $options->size);
    }

    public function test_with_colors(): void
    {
        $options = (new QRCodeOptions())->withColors(Color::white(), Color::black());

        self::assertSame('#ffffff', $options->foregroundColor->toHex());
        self::assertSame('#000000', $options->backgroundColor->toHex());
    }

    public function test_with_error_correction(): void
    {
        $options = (new QRCodeOptions())->withErrorCorrection(ErrorCorrectionLevel::HIGH);

        self::assertSame(ErrorCorrectionLevel::HIGH, $options->errorCorrectionLevel);
    }
}

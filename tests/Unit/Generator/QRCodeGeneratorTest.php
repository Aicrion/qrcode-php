<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Unit\Generator;

use Aicrion\QRCode\Enums\OutputFormat;
use Aicrion\QRCode\Generator\QRCodeGenerator;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;
use PHPUnit\Framework\TestCase;

final class QRCodeGeneratorTest extends TestCase
{
    private QRCodeGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new QRCodeGenerator();
    }

    public function test_generates_png_bytes(): void
    {
        $bytes = $this->generator->generate('https://aicrion.dev', new QRCodeOptions());

        self::assertNotEmpty($bytes);
        self::assertStringStartsWith("\x89PNG", $bytes);
    }

    public function test_generates_svg_markup(): void
    {
        $options = (new QRCodeOptions())->withFormat(OutputFormat::SVG);
        $svg = $this->generator->generate('https://aicrion.dev', $options);

        self::assertStringContainsString('<svg', $svg);
    }

    public function test_generates_data_uri(): void
    {
        $uri = $this->generator->generateDataUri('hello world', new QRCodeOptions());

        self::assertStringStartsWith('data:image/png;base64,', $uri);
    }

    public function test_generates_to_file(): void
    {
        $path = sys_get_temp_dir() . '/aicrion_test_' . uniqid() . '.png';

        $this->generator->generateToFile('file test', $path, new QRCodeOptions());

        self::assertFileExists($path);
        unlink($path);
    }

    public function test_respects_custom_size(): void
    {
        $optionsSmall = (new QRCodeOptions())->withSize(150);
        $optionsLarge = (new QRCodeOptions())->withSize(600);

        $small = $this->generator->generate('size test', $optionsSmall);
        $large = $this->generator->generate('size test', $optionsLarge);

        self::assertLessThan(strlen($large), strlen($small));
    }
}

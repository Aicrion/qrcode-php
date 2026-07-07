<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Unit\ValueObjects;

use Aicrion\QRCode\ValueObjects\Color;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ColorTest extends TestCase
{
    public function test_creates_color_from_hex(): void
    {
        $color = Color::fromHex('#ff0000');

        self::assertSame(255, $color->red);
        self::assertSame(0, $color->green);
        self::assertSame(0, $color->blue);
    }

    public function test_creates_color_from_short_hex(): void
    {
        $color = Color::fromHex('#f00');

        self::assertSame(255, $color->red);
        self::assertSame(0, $color->green);
        self::assertSame(0, $color->blue);
    }

    public function test_throws_on_invalid_hex(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Color::fromHex('not-a-color');
    }

    public function test_throws_on_out_of_range_channel(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Color(300, 0, 0);
    }

    public function test_converts_to_hex(): void
    {
        $color = new Color(0, 255, 0);

        self::assertSame('#00ff00', $color->toHex());
    }

    public function test_black_and_white_factories(): void
    {
        self::assertSame('#000000', Color::black()->toHex());
        self::assertSame('#ffffff', Color::white()->toHex());
    }
}

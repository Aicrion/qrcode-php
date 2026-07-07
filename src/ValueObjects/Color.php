<?php

declare(strict_types=1);

namespace Aicrion\QRCode\ValueObjects;

use InvalidArgumentException;

/**
 * Immutable RGB(A) color value object used for QR code foreground/background.
 */
final class Color
{
    public function __construct(
        public readonly int $red,
        public readonly int $green,
        public readonly int $blue,
        public readonly int $alpha = 0
    ) {
        foreach ([$red, $green, $blue] as $channel) {
            if ($channel < 0 || $channel > 255) {
                throw new InvalidArgumentException('Color channels must be between 0 and 255.');
            }
        }

        if ($alpha < 0 || $alpha > 127) {
            throw new InvalidArgumentException('Alpha channel must be between 0 and 127.');
        }
    }

    public static function fromHex(string $hex): self
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = implode('', array_map(static fn (string $c) => str_repeat($c, 2), str_split($hex)));
        }

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            throw new InvalidArgumentException("Invalid hex color: {$hex}");
        }

        return new self(
            (int) hexdec(substr($hex, 0, 2)),
            (int) hexdec(substr($hex, 2, 2)),
            (int) hexdec(substr($hex, 4, 2)),
        );
    }

    public static function black(): self
    {
        return new self(0, 0, 0);
    }

    public static function white(): self
    {
        return new self(255, 255, 255);
    }

    public function toHex(): string
    {
        return sprintf('#%02x%02x%02x', $this->red, $this->green, $this->blue);
    }

    public function toArray(): array
    {
        return ['r' => $this->red, 'g' => $this->green, 'b' => $this->blue, 'a' => $this->alpha];
    }

    public function equals(Color $other): bool
    {
        return $this->red === $other->red
            && $this->green === $other->green
            && $this->blue === $other->blue
            && $this->alpha === $other->alpha;
    }
}

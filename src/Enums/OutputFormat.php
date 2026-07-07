<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Enums;

/**
 * Supported output formats for generated QR codes.
 */
enum OutputFormat: string
{
    case PNG = 'png';
    case SVG = 'svg';
    case EPS = 'eps';
    case WEBP = 'webp';

    public function mimeType(): string
    {
        return match ($this) {
            self::PNG => 'image/png',
            self::SVG => 'image/svg+xml',
            self::EPS => 'application/postscript',
            self::WEBP => 'image/webp',
        };
    }

    public function extension(): string
    {
        return $this->value;
    }
}

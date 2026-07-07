<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Enums;

/**
 * Supported data encoding modes for QR code payload.
 */
enum EncodingMode: string
{
    case NUMERIC = 'numeric';
    case ALPHANUMERIC = 'alphanumeric';
    case BYTE = 'byte';
    case KANJI = 'kanji';
}

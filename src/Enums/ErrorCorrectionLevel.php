<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Enums;

/**
 * QR code error correction levels (per ISO/IEC 18004).
 */
enum ErrorCorrectionLevel: string
{
    case LOW = 'L';        // ~7%
    case MEDIUM = 'M';     // ~15%
    case QUARTILE = 'Q';   // ~25%
    case HIGH = 'H';       // ~30%
}

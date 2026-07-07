<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Enums;

/**
 * WiFi network authentication/encryption types supported in WiFi QR payloads.
 */
enum WifiEncryption: string
{
    case WPA = 'WPA';
    case WEP = 'WEP';
    case NONE = 'nopass';
    case WPA2_EAP = 'WPA2-EAP';
}

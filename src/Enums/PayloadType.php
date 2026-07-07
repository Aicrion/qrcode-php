<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Enums;

/**
 * Recognized structured QR code payload types, used when parsing decoded content.
 */
enum PayloadType: string
{
    case TEXT = 'text';
    case URL = 'url';
    case WIFI = 'wifi';
    case CONTACT = 'contact';
    case PHONE = 'phone';
    case SMS = 'sms';
    case EMAIL = 'email';
    case GEO = 'geo';
    case EVENT = 'event';
}

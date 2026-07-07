<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

/**
 * Contract for structured QR code payloads (WiFi, Contact, Phone, etc.).
 * Each payload knows how to render itself into the raw string format
 * expected by QR code scanners.
 */
interface PayloadInterface
{
    public function toPayloadString(): string;
}

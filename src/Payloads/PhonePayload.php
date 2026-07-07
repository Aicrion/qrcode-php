<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

/**
 * Represents a QR code payload that dials a phone number when scanned.
 */
final class PhonePayload implements PayloadInterface
{
    public function __construct(
        public readonly string $phoneNumber
    ) {
    }

    public function toPayloadString(): string
    {
        return 'tel:' . $this->phoneNumber;
    }
}

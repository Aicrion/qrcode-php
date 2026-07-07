<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

/**
 * Represents a QR code payload that opens a URL when scanned.
 */
final class UrlPayload implements PayloadInterface
{
    public function __construct(
        public readonly string $url
    ) {
    }

    public function toPayloadString(): string
    {
        return $this->url;
    }
}

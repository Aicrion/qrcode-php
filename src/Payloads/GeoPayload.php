<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

/**
 * Represents a QR code payload encoding a geographic coordinate (opens maps app).
 */
final class GeoPayload implements PayloadInterface
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly ?float $altitude = null
    ) {
    }

    public function toPayloadString(): string
    {
        if ($this->altitude !== null) {
            return sprintf('geo:%s,%s,%s', $this->latitude, $this->longitude, $this->altitude);
        }

        return sprintf('geo:%s,%s', $this->latitude, $this->longitude);
    }

    public static function fromPayloadString(string $payload): self
    {
        $payload = trim($payload);

        if (str_starts_with($payload, 'geo:')) {
            $payload = substr($payload, 4);
        }

        $coords = explode(',', $payload);

        return new self(
            latitude: (float) ($coords[0] ?? 0),
            longitude: (float) ($coords[1] ?? 0),
            altitude: isset($coords[2]) && $coords[2] !== '' ? (float) $coords[2] : null,
        );
    }
}

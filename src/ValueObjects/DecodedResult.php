<?php

declare(strict_types=1);

namespace Aicrion\QRCode\ValueObjects;

use Aicrion\QRCode\Enums\PayloadType;

/**
 * Immutable result returned after successfully decoding a QR code.
 */
final class DecodedResult
{
    /**
     * @param array<int, array{x: float, y: float}> $points
     * @param array<string, mixed>|string $parsed
     */
    public function __construct(
        public readonly string $content,
        public readonly array $points = [],
        public readonly ?string $format = 'QR_CODE',
        public readonly PayloadType $type = PayloadType::TEXT,
        public readonly array|string $parsed = '',
    ) {
    }

    public function isEmpty(): bool
    {
        return trim($this->content) === '';
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'points' => $this->points,
            'format' => $this->format,
            'type' => $this->type->value,
            'parsed' => $this->parsed,
        ];
    }
}

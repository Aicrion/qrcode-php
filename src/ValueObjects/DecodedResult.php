<?php

declare(strict_types=1);

namespace Aicrion\QRCode\ValueObjects;

/**
 * Immutable result returned after successfully decoding a QR code.
 */
final class DecodedResult
{
    /**
     * @param array<int, array{x: float, y: float}> $points
     */
    public function __construct(
        public readonly string $content,
        public readonly array $points = [],
        public readonly ?string $format = 'QR_CODE',
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
        ];
    }
}

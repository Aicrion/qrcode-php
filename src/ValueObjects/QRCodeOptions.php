<?php

declare(strict_types=1);

namespace Aicrion\QRCode\ValueObjects;

use Aicrion\QRCode\Enums\ErrorCorrectionLevel;
use Aicrion\QRCode\Enums\OutputFormat;

/**
 * Immutable configuration object describing how a QR code should be generated.
 */
final class QRCodeOptions
{
    public function __construct(
        public readonly int $size = 300,
        public readonly int $margin = 10,
        public readonly ErrorCorrectionLevel $errorCorrectionLevel = ErrorCorrectionLevel::MEDIUM,
        public readonly OutputFormat $format = OutputFormat::PNG,
        public readonly Color $foregroundColor = new Color(0, 0, 0),
        public readonly Color $backgroundColor = new Color(255, 255, 255),
        public readonly ?string $logoPath = null,
        public readonly int $logoSizeRatio = 20,
        public readonly ?string $label = null,
    ) {
    }

    public function withFormat(OutputFormat $format): self
    {
        return new self(
            $this->size,
            $this->margin,
            $this->errorCorrectionLevel,
            $format,
            $this->foregroundColor,
            $this->backgroundColor,
            $this->logoPath,
            $this->logoSizeRatio,
            $this->label,
        );
    }

    public function withSize(int $size): self
    {
        return new self(
            $size,
            $this->margin,
            $this->errorCorrectionLevel,
            $this->format,
            $this->foregroundColor,
            $this->backgroundColor,
            $this->logoPath,
            $this->logoSizeRatio,
            $this->label,
        );
    }

    public function withColors(Color $foreground, Color $background): self
    {
        return new self(
            $this->size,
            $this->margin,
            $this->errorCorrectionLevel,
            $this->format,
            $foreground,
            $background,
            $this->logoPath,
            $this->logoSizeRatio,
            $this->label,
        );
    }

    public function withLogo(string $logoPath, int $sizeRatio = 20): self
    {
        return new self(
            $this->size,
            $this->margin,
            $this->errorCorrectionLevel,
            $this->format,
            $this->foregroundColor,
            $this->backgroundColor,
            $logoPath,
            $sizeRatio,
            $this->label,
        );
    }

    public function withErrorCorrection(ErrorCorrectionLevel $level): self
    {
        return new self(
            $this->size,
            $this->margin,
            $level,
            $this->format,
            $this->foregroundColor,
            $this->backgroundColor,
            $this->logoPath,
            $this->logoSizeRatio,
            $this->label,
        );
    }
}

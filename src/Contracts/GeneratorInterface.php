<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Contracts;

use Aicrion\QRCode\ValueObjects\QRCodeOptions;

/**
 * Contract for classes capable of generating QR codes from raw data.
 */
interface GeneratorInterface
{
    /**
     * Generate raw binary/string output (e.g. PNG bytes or SVG markup) for the given data.
     */
    public function generate(string $data, QRCodeOptions $options): string;

    /**
     * Generate and persist the QR code to a file path. Returns the saved file path.
     */
    public function generateToFile(string $data, string $path, QRCodeOptions $options): string;

    /**
     * Generate and return a Base64-encoded data URI (e.g. "data:image/png;base64,...").
     */
    public function generateDataUri(string $data, QRCodeOptions $options): string;
}

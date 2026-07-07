<?php

declare(strict_types=1);

namespace Aicrion\QRCode;

use Aicrion\QRCode\Contracts\GeneratorInterface;
use Aicrion\QRCode\Contracts\ReaderInterface;
use Aicrion\QRCode\Generator\QRCodeGenerator;
use Aicrion\QRCode\Reader\QRCodeReader;
use Aicrion\QRCode\ValueObjects\DecodedResult;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;

/**
 * Main entry-point facade for the Aicrion QRCode library.
 *
 * Provides a simple, fluent API to generate and read QR codes
 * without needing to instantiate internal collaborators directly.
 */
final class QRCode
{
    public function __construct(
        private readonly GeneratorInterface $generator = new QRCodeGenerator(),
        private readonly ReaderInterface $reader = new QRCodeReader(),
    ) {
    }

    public static function make(): self
    {
        return new self();
    }

    // ---- Generation ----

    public function generate(string $data, ?QRCodeOptions $options = null): string
    {
        return $this->generator->generate($data, $options ?? new QRCodeOptions());
    }

    public function generateToFile(string $data, string $path, ?QRCodeOptions $options = null): string
    {
        return $this->generator->generateToFile($data, $path, $options ?? new QRCodeOptions());
    }

    public function generateDataUri(string $data, ?QRCodeOptions $options = null): string
    {
        return $this->generator->generateDataUri($data, $options ?? new QRCodeOptions());
    }

    // ---- Reading ----

    public function read(mixed $source): DecodedResult
    {
        return match (true) {
            $source instanceof \GdImage => $this->reader->readFromGdImage($source),
            is_resource($source) => $this->reader->readFromResource($source),
            is_string($source) && preg_match('#^https?://#i', $source) === 1 => $this->reader->readFromUrl($source),
            is_string($source) && preg_match('#^data:image/[a-zA-Z]+;base64,#', $source) === 1 => $this->reader->readFromBase64($source),
            is_string($source) && is_file($source) => $this->reader->readFromPath($source),
            is_string($source) => $this->reader->readFromBinary($source),
            default => throw new \Aicrion\QRCode\Exceptions\InvalidSourceException('Unsupported source type.'),
        };
    }

    public function readFromPath(string $path): DecodedResult
    {
        return $this->reader->readFromPath($path);
    }

    public function readFromBinary(string $binary): DecodedResult
    {
        return $this->reader->readFromBinary($binary);
    }

    public function readFromBase64(string $base64): DecodedResult
    {
        return $this->reader->readFromBase64($base64);
    }

    public function readFromUrl(string $url): DecodedResult
    {
        return $this->reader->readFromUrl($url);
    }
}

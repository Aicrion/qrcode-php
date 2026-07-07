<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Reader;

use Aicrion\QRCode\Contracts\ReaderInterface;
use Aicrion\QRCode\Contracts\SourceResolverInterface;
use Aicrion\QRCode\Exceptions\DecodingException;
use Aicrion\QRCode\Support\PayloadParser;
use Aicrion\QRCode\Support\SourceResolver;
use Aicrion\QRCode\ValueObjects\DecodedResult;
use Zxing\QrReader;

/**
 * Default QR code reader implementation, built on top of
 * khanamiryan/qrcode-detector-decoder (a PHP port of ZXing).
 */
final class QRCodeReader implements ReaderInterface
{
    public function __construct(
        private readonly SourceResolverInterface $sourceResolver = new SourceResolver(),
        private readonly PayloadParser $payloadParser = new PayloadParser()
    ) {
    }

    public function readFromPath(string $path): DecodedResult
    {
        return $this->decode($this->sourceResolver->resolve($path));
    }

    public function readFromBinary(string $binary): DecodedResult
    {
        return $this->decode($binary);
    }

    public function readFromBase64(string $base64): DecodedResult
    {
        return $this->decode($this->sourceResolver->resolve($base64));
    }

    public function readFromUrl(string $url): DecodedResult
    {
        return $this->decode($this->sourceResolver->resolve($url));
    }

    public function readFromResource($resource): DecodedResult
    {
        return $this->decode($this->sourceResolver->resolve($resource));
    }

    public function readFromGdImage(\GdImage $image): DecodedResult
    {
        return $this->decode($this->sourceResolver->resolve($image));
    }

    private function decode(string $binary): DecodedResult
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'qrcode_');

        if ($tmpFile === false) {
            throw new DecodingException('Unable to create a temporary file for decoding.');
        }

        try {
            file_put_contents($tmpFile, $binary);

            $reader = new QrReader($tmpFile);
            $text = $reader->text();

            if ($text === false || $text === null) {
                throw new DecodingException('No QR code could be detected in the given image.');
            }

            $points = $this->extractResultPoints($reader);
            $type = $this->payloadParser->detectType((string) $text);
            $parsed = $this->payloadParser->parse((string) $text);

            return new DecodedResult(
                content: (string) $text,
                points: $points,
                type: $type,
                parsed: $parsed,
            );
        } finally {
            @unlink($tmpFile);
        }
    }

    /**
     * Best-effort extraction of detected marker points. Different versions of the
     * underlying ZXing port expose this data differently (or not at all), so we
     * probe for it defensively instead of relying on a fixed method signature.
     *
     * @return array<int, array{x: float, y: float}>
     */
    private function extractResultPoints(QrReader $reader): array
    {
        if (! method_exists($reader, 'getResultPoints')) {
            return [];
        }

        $rawPoints = $reader->getResultPoints();

        if (! is_iterable($rawPoints)) {
            return [];
        }

        $points = [];
        foreach ($rawPoints as $point) {
            if (is_object($point) && property_exists($point, 'x') && property_exists($point, 'y')) {
                $points[] = ['x' => (float) $point->x, 'y' => (float) $point->y];
            }
        }

        return $points;
    }
}

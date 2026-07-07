<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Support;

use Aicrion\QRCode\Contracts\SourceResolverInterface;
use Aicrion\QRCode\Exceptions\InvalidSourceException;

/**
 * Resolves heterogeneous QR code input sources (file path, binary, base64, URL,
 * stream resource, GD image) into a single raw binary string representation.
 */
final class SourceResolver implements SourceResolverInterface
{
    public function resolve(mixed $source): string
    {
        return match (true) {
            $source instanceof \GdImage => $this->fromGdImage($source),
            is_resource($source) => $this->fromResource($source),
            is_string($source) && $this->looksLikePath($source) => $this->fromPath($source),
            is_string($source) && $this->looksLikeUrl($source) => $this->fromUrl($source),
            is_string($source) && $this->looksLikeBase64($source) => $this->fromBase64($source),
            is_string($source) => $source,
            default => throw new InvalidSourceException('Unsupported QR code source type: ' . get_debug_type($source)),
        };
    }

    private function fromPath(string $path): string
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new InvalidSourceException("File not found or unreadable: {$path}");
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new InvalidSourceException("Unable to read file: {$path}");
        }

        return $contents;
    }

    private function fromUrl(string $url): string
    {
        $contents = @file_get_contents($url);

        if ($contents === false) {
            throw new InvalidSourceException("Unable to fetch content from URL: {$url}");
        }

        return $contents;
    }

    private function fromBase64(string $base64): string
    {
        $clean = preg_replace('#^data:image/[a-zA-Z]+;base64,#', '', $base64) ?? $base64;
        $decoded = base64_decode($clean, true);

        if ($decoded === false) {
            throw new InvalidSourceException('Invalid Base64 QR code source.');
        }

        return $decoded;
    }

    /**
     * @param resource $resource
     */
    private function fromResource($resource): string
    {
        $contents = stream_get_contents($resource);

        if ($contents === false) {
            throw new InvalidSourceException('Unable to read from the given resource/stream.');
        }

        return $contents;
    }

    private function fromGdImage(\GdImage $image): string
    {
        ob_start();
        imagepng($image);
        $contents = (string) ob_get_clean();

        return $contents;
    }

    private function looksLikePath(string $source): bool
    {
        return ! $this->looksLikeUrl($source)
            && ! $this->looksLikeBase64($source)
            && (str_contains($source, '/') || str_contains($source, '\\'))
            && strlen($source) < 4096
            && is_file($source);
    }

    private function looksLikeUrl(string $source): bool
    {
        return (bool) preg_match('#^https?://#i', $source);
    }

    private function looksLikeBase64(string $source): bool
    {
        return (bool) preg_match('#^data:image/[a-zA-Z]+;base64,#', $source);
    }
}

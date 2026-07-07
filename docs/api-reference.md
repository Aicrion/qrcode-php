---
title: API Reference
layout: default
---

# API Reference

[← Back to index](index.md)

## `Aicrion\QRCode\QRCode`

Facade combining generation and reading.

- `static make(): self`
- `generate(string $data, ?QRCodeOptions $options = null): string`
- `generateToFile(string $data, string $path, ?QRCodeOptions $options = null): string`
- `generateDataUri(string $data, ?QRCodeOptions $options = null): string`
- `read(mixed $source): DecodedResult`
- `readFromPath(string $path): DecodedResult`
- `readFromBinary(string $binary): DecodedResult`
- `readFromBase64(string $base64): DecodedResult`
- `readFromUrl(string $url): DecodedResult`

## `Aicrion\QRCode\Contracts\GeneratorInterface`
## `Aicrion\QRCode\Contracts\ReaderInterface`
## `Aicrion\QRCode\Contracts\SourceResolverInterface`

## Enums

- `Enums\OutputFormat`: PNG, SVG, EPS, WEBP
- `Enums\ErrorCorrectionLevel`: LOW, MEDIUM, QUARTILE, HIGH
- `Enums\EncodingMode`: NUMERIC, ALPHANUMERIC, BYTE, KANJI
- `Enums\SourceType`: FILE_PATH, BINARY_STRING, BASE64, URL, RESOURCE, GD_IMAGE

## Value Objects

- `ValueObjects\Color`
- `ValueObjects\QRCodeOptions`
- `ValueObjects\DecodedResult`

## Exceptions

- `Exceptions\QRCodeException`
- `Exceptions\GenerationException`
- `Exceptions\DecodingException`
- `Exceptions\InvalidSourceException`
- `Exceptions\UnsupportedFormatException`

Next: [Contributing](contributing.md)

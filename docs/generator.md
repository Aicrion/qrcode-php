---
title: Generating QR Codes
layout: default
---

# Generating QR Codes

[← Back to index](index.md)

The `QRCodeGenerator` class (accessible via the `QRCode` facade) implements `GeneratorInterface` and supports:

## Output Formats

| Format | Enum value | Notes |
|---|---|---|
| PNG | `OutputFormat::PNG` | Default, requires GD |
| SVG | `OutputFormat::SVG` | Vector, no GD required |
| EPS | `OutputFormat::EPS` | Vector, print-ready |
| WEBP | `OutputFormat::WEBP` | Requires GD with WEBP support |

```php
use Aicrion\QRCode\Enums\OutputFormat;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;

$options = (new QRCodeOptions())->withFormat(OutputFormat::SVG);
$svg = $qr->generate('Hello', $options);
```

## Custom Colors

```php
use Aicrion\QRCode\ValueObjects\Color;

$options = (new QRCodeOptions())->withColors(
    foreground: Color::fromHex('#1a1a2e'),
    background: Color::white()
);
```

## Embedding a Logo

```php
$options = (new QRCodeOptions())->withLogo(__DIR__ . '/logo.png', sizeRatio: 25);
```

## Error Correction Level

```php
use Aicrion\QRCode\Enums\ErrorCorrectionLevel;

$options = (new QRCodeOptions())->withErrorCorrection(ErrorCorrectionLevel::HIGH);
```

## Available Methods

- `generate(string $data, ?QRCodeOptions $options = null): string`
- `generateToFile(string $data, string $path, ?QRCodeOptions $options = null): string`
- `generateDataUri(string $data, ?QRCodeOptions $options = null): string`

Next: [Reading QR Codes](reader.md)

---
title: Examples
layout: default
---

# Examples

[← Back to index](index.md)

## Generate a Branded QR Code with Logo and Label

```php
use Aicrion\QRCode\QRCode;
use Aicrion\QRCode\ValueObjects\{QRCodeOptions, Color};
use Aicrion\QRCode\Enums\ErrorCorrectionLevel;

$options = (new QRCodeOptions())
    ->withSize(600)
    ->withColors(Color::fromHex('#0f172a'), Color::white())
    ->withErrorCorrection(ErrorCorrectionLevel::HIGH)
    ->withLogo(__DIR__ . '/assets/logo.png', sizeRatio: 22);

QRCode::make()->generateToFile('https://aicrion.dev', __DIR__ . '/output.png', $options);
```

## Read a QR Code Uploaded via HTTP Request

```php
use Aicrion\QRCode\QRCode;

$uploadedFilePath = $_FILES['qrcode']['tmp_name'];
$result = QRCode::make()->readFromPath($uploadedFilePath);

echo $result->content;
```

## Generate an SVG for Web Embedding

```php
use Aicrion\QRCode\Enums\OutputFormat;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;

$svg = QRCode::make()->generate(
    'https://aicrion.dev',
    (new QRCodeOptions())->withFormat(OutputFormat::SVG)
);

echo $svg; // inline <svg> markup
```

Next: [API Reference](api-reference.md)

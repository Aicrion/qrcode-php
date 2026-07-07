---
title: Quick Start
layout: default
---

# Quick Start

[← Back to index](index.md)

## Generating a QR Code

```php
use Aicrion\QRCode\QRCode;

$qr = QRCode::make();

// Save directly to a file
$qr->generateToFile('https://github.com/aicrion/qrcode-php', __DIR__ . '/qrcode.png');

// Or get raw bytes
$bytes = $qr->generate('Hello World');

// Or get a data URI (ideal for embedding in HTML <img>)
$uri = $qr->generateDataUri('Hello World');
```

## Reading a QR Code

```php
use Aicrion\QRCode\QRCode;

$qr = QRCode::make();

$result = $qr->readFromPath(__DIR__ . '/qrcode.png');

echo $result->content; // decoded text
```

The `read()` method also auto-detects the source type:

```php
$qr->read(__DIR__ . '/qrcode.png');      // file path
$qr->read('https://example.com/qr.png'); // URL
$qr->read($base64DataUri);               // Base64 data URI
$qr->read($binaryImageString);           // raw binary
```

Next: [Generating QR Codes](generator.md)

## Persian / Farsi & Unicode Support

Aicrion QRCode fully supports UTF-8 multi-byte text, including **Persian (فارسی)**, Arabic, and other non-Latin scripts.

```php
use Aicrion\QRCode\QRCode;

$qr = QRCode::make();
$qr->generateToFile('سلام دنیا! این یک کد کیوآر فارسی است.', __DIR__ . '/farsi-qr.png');

$result = $qr->readFromPath(__DIR__ . '/farsi-qr.png');
echo $result->content; // سلام دنیا! این یک کد کیوآر فارسی است.
```

> **Note:** Internally, the generator explicitly encodes payloads as `UTF-8` (instead of the underlying library's default `ISO-8859-1`), and the reader decodes bytes back to UTF-8 automatically — so Persian, Arabic, Chinese, and emoji all round-trip correctly.

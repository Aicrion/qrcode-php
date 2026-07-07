<p align="center">
  <h1 align="center">📱 Aicrion QRCode</h1>
  <p align="center">A modern, professional PHP 8.2+ library to generate and read QR codes from multiple sources and in multiple formats.</p>
</p>

<p align="center">
  <a href="https://github.com/aicrion/qrcode-php/actions"><img src="https://github.com/aicrion/qrcode-php/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
  <a href="https://packagist.org/packages/aicrion/qrcode"><img src="https://img.shields.io/packagist/v/aicrion/qrcode.svg" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/aicrion/qrcode"><img src="https://img.shields.io/packagist/dt/aicrion/qrcode.svg" alt="Total Downloads"></a>
  <a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
  <img src="https://img.shields.io/badge/PHP-%3E%3D8.2-777bb4.svg" alt="PHP Version">
</p>

<p align="center">
  📖 <a href="https://aicrion.github.io/qrcode-php/">Full Documentation</a>
</p>

---

## ✨ Features

- 🧱 **Modern architecture** — interfaces, immutable value objects, enums, `readonly` properties (PHP 8.2+)
- 🖼️ **Multiple output formats** — PNG, SVG, EPS, WEBP
- 📥 **Multiple input sources for reading** — file path, binary string, Base64, URL, GD image, stream resource
- 🎨 **Full customization** — size, margin, colors, error correction level, embedded logo, caption label
- 🧪 **Fully tested** — unit and feature tests with PHPUnit
- 📦 **Zero-config Composer install** — sane, production-ready defaults out of the box
- 🧯 **Rich exception hierarchy** — precise, catchable error types for every failure mode

## 📦 Installation

Requires PHP 8.2+ and the `gd` extension.

```bash
composer require aicrion/qrcode
```

## 🚀 Quick Start

### Generate a QR Code

```php
use Aicrion\QRCode\QRCode;

$qr = QRCode::make();

// Save directly to a file
$qr->generateToFile('https://github.com/aicrion/qrcode-php', __DIR__ . '/qrcode.png');

// Get raw bytes
$bytes = $qr->generate('Hello World');

// Get a data URI (ideal for <img src="...">)
$uri = $qr->generateDataUri('Hello World');
```

### Read a QR Code

```php
use Aicrion\QRCode\QRCode;

$qr = QRCode::make();

$result = $qr->readFromPath(__DIR__ . '/qrcode.png');

echo $result->content; // decoded text
```

Or let the library auto-detect the source type:

```php
$qr->read(__DIR__ . '/qrcode.png');      // file path
$qr->read('https://example.com/qr.png'); // URL
$qr->read($base64DataUri);               // Base64 data URI
$qr->read($binaryImageString);           // raw binary
```

## 🎨 Customization

```php
use Aicrion\QRCode\Enums\{OutputFormat, ErrorCorrectionLevel};
use Aicrion\QRCode\ValueObjects\{QRCodeOptions, Color};

$options = (new QRCodeOptions())
    ->withSize(600)
    ->withFormat(OutputFormat::SVG)
    ->withColors(Color::fromHex('#0f172a'), Color::white())
    ->withErrorCorrection(ErrorCorrectionLevel::HIGH)
    ->withLogo(__DIR__ . '/assets/logo.png', sizeRatio: 22);

$svg = QRCode::make()->generate('https://aicrion.dev', $options);
```

## 📚 Supported Formats & Sources

| Capability | Supported Options |
|---|---|
| **Output formats** | PNG, SVG, EPS, WEBP |
| **Error correction** | LOW (L), MEDIUM (M), QUARTILE (Q), HIGH (H) |
| **Read sources** | File path, binary string, Base64/data URI, URL, GD image, stream resource |

## 🇮🇷 Persian / Farsi & Unicode Support

Full UTF-8 support out of the box — Persian, Arabic, Chinese, emoji, etc.

```php
$qr->generateToFile('سلام دنیا! این یک کد کیوآر فارسی است.', __DIR__ . '/farsi-qr.png');

$result = $qr->readFromPath(__DIR__ . '/farsi-qr.png');
echo $result->content; // سلام دنیا! این یک کد کیوآر فارسی است.
```

## 🧯 Exception Handling

All exceptions extend `Aicrion\QRCode\Exceptions\QRCodeException`:

```php
use Aicrion\QRCode\Exceptions\QRCodeException;

try {
    $result = QRCode::make()->readFromPath('/missing/file.png');
} catch (QRCodeException $e) {
    // Handles InvalidSourceException, DecodingException, GenerationException, etc.
    logger()->error($e->getMessage());
}
```

## 🧪 Testing

```bash
composer install
composer test
```

## 📖 Documentation

Full documentation, including the complete API reference, is available at:

**[https://aicrion.github.io/qrcode-php/](https://aicrion.github.io/qrcode-php/)**

Table of contents: Installation · Quick Start · Generating QR Codes · Reading QR Codes · Configuration Options · Exception Handling · Examples · API Reference · Contributing · Changelog

## 🤝 Contributing

Contributions are welcome! Please open an issue or submit a pull request on [GitHub](https://github.com/aicrion/qrcode-php).

1. Fork the repository
2. Create a feature branch
3. Run `composer test` and `composer stan`
4. Submit your pull request

---

## 📜 License

Created with ❤️ by Aicrion. Licensed under the [MIT License](LICENSE.md). Free to use, modify, and distribute!

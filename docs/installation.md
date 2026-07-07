---
title: Installation
layout: default
---

# Installation

[← Back to index](index.md)

## Requirements

- PHP 8.2 or higher
- `ext-gd` extension enabled (for raster/PNG/WEBP generation and reading)

## Install via Composer

```bash
composer require aicrion/qrcode
```

The package automatically pulls in its two core dependencies:

- `bacon/bacon-qr-code` — used internally for QR code **generation**
- `khanamiryan/qrcode-detector-decoder` — used internally for QR code **reading/decoding**

## Verifying the Installation

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Aicrion\QRCode\QRCode;

$qr = QRCode::make();
echo $qr->generateDataUri('Hello, Aicrion!');
```

If this prints a `data:image/png;base64,...` string, the library is correctly installed.

Next: [Quick Start](quick-start.md)

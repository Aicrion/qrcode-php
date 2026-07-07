---
title: Colors & Styling
layout: default
---

# Colors & Styling

[← Back to index](index.md)

Aicrion QRCode supports fully custom foreground and background colors for **every** output format — PNG, WEBP, SVG, and EPS — as well as reliable reading of colored QR codes.

## Generating a Colored QR Code

```php
use Aicrion\QRCode\ValueObjects\{QRCodeOptions, Color};

$options = (new QRCodeOptions())
    ->withColors(
        foreground: Color::fromHex('#0f172a'),
        background: Color::fromHex('#f8fafc')
    );

$qr->generateToFile('https://aicrion.dev', __DIR__ . '/branded-qr.png', $options);
```

You can build colors from hex strings or raw RGB channels:

```php
Color::fromHex('#ff0000');
new Color(red: 255, green: 0, blue: 0);
Color::black();
Color::white();
```

## How It Works Internally

- **Vector formats (SVG, EPS):** colors are applied natively during rendering.
- **Raster formats (PNG, WEBP):** the QR code is rendered in black/white using GD, then a fast pixel-remapping pass recolors dark modules to your foreground color and light modules to your background color. Visually the result is identical to native color rendering.

## Reading Colored QR Codes

No special configuration is needed — `readFromPath()`, `read()`, and all other reader methods automatically handle colored QR codes exactly like black-and-white ones, as long as there is sufficient contrast between foreground and background colors (a general QR code scanning requirement, not specific to this library).

```php
$result = $qr->readFromPath(__DIR__ . '/branded-qr.png');
echo $result->content; // decoded correctly regardless of color
```

> **Tip:** For best scan reliability, keep a contrast ratio similar to black-on-white (dark foreground, light background). Very light foregrounds or very dark backgrounds may reduce scanner reliability on some devices — this is a general QR code physical limitation, not a library limitation.

Next: [API Reference](api-reference.md)

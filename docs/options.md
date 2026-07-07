---
title: Configuration Options
layout: default
---

# Configuration Options

[← Back to index](index.md)

`QRCodeOptions` is an immutable value object. Every `with*()` method returns a **new** instance, leaving the original untouched.

| Property | Type | Default | Description |
|---|---|---|---|
| `size` | int | 300 | Output width/height in pixels |
| `margin` | int | 10 | Quiet zone margin |
| `errorCorrectionLevel` | `ErrorCorrectionLevel` | `MEDIUM` | L / M / Q / H |
| `format` | `OutputFormat` | `PNG` | PNG / SVG / EPS / WEBP |
| `foregroundColor` | `Color` | black | QR module color |
| `backgroundColor` | `Color` | white | Background color |
| `logoPath` | ?string | null | Optional logo overlay path |
| `logoSizeRatio` | int | 20 | Logo size as % of QR width |
| `label` | ?string | null | Optional caption below the QR code |

## Fluent Modifiers

```php
$options = (new QRCodeOptions())
    ->withSize(500)
    ->withFormat(OutputFormat::PNG)
    ->withColors(Color::fromHex('#000'), Color::fromHex('#fff'))
    ->withErrorCorrection(ErrorCorrectionLevel::QUARTILE)
    ->withLogo(__DIR__ . '/logo.png');
```

Next: [Exception Handling](exceptions.md)


> **Implementation note (v1.2+):** Raster formats (PNG/WEBP) are rendered via `GDLibRenderer`
> (pure black/white output), then custom foreground/background colors are applied as a
> fast pixel-remapping post-process step. Vector formats (SVG/EPS) apply colors natively
> during rendering. Visually, results are identical either way.

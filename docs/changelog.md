---
title: Changelog
layout: default
---

# Changelog

[← Back to index](index.md)

## v1.3.0

- Fixed: `Call to undefined method Zxing\QrReader::getResultPoints()` on some versions of the decoder library
- Reader now defensively checks for `getResultPoints()` availability before calling it, falling back to an empty points array

## v1.2.0

- Fixed: `Class "BaconQrCode\Renderer\Image\GdImageBackEnd" not found` error during raster generation
- Raster (PNG/WEBP) generation now uses `BaconQrCode\Renderer\GDLibRenderer` correctly
- Custom foreground/background colors for raster formats now applied via color remapping post-process
- Added `Color::equals()` helper method

## v1.0.0

- Initial release
- QR code generation: PNG, SVG, EPS, WEBP
- QR code reading: file path, binary, Base64, URL, GD image, stream resource
- Custom colors, size, margin, error correction level
- Optional logo overlay and text label
- Full PHPUnit test suite (unit + feature)

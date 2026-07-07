---
title: Changelog
layout: default
---

# Changelog

[← Back to index](index.md)

## v1.4.0

- Added structured QR payload support: WiFi, Contact (vCard), Phone, SMS, Email, Geo location, Calendar event, URL
- New `Payloads\*` classes implementing `PayloadInterface`, each with `toPayloadString()` (and `fromPayloadString()` where applicable)
- New facade methods: `generateWifi()`, `generateContact()`, `generatePhone()`, `generateSms()`, `generateEmail()`, `generateGeo()`, `generateEvent()`, `generateUrl()`, `generateFromPayload()`, `generatePayloadToFile()`, `generatePayloadDataUri()`
- `DecodedResult` now exposes `type` (`PayloadType` enum) and `parsed` (structured array) for automatic payload detection when reading
- New `Support\PayloadParser` for detecting and parsing structured payload types from decoded text
- Added `Enums\PayloadType` and `Enums\WifiEncryption`
- Verified and tested colored QR code generation and reading (foreground/background customization works reliably end-to-end)
- New docs pages: Structured Payloads, Colors & Styling

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

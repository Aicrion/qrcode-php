---
title: Reading QR Codes
layout: default
---

# Reading QR Codes

[← Back to index](index.md)

The `QRCodeReader` class implements `ReaderInterface` and supports decoding from six source types.

| Method | Source Type |
|---|---|
| `readFromPath(string $path)` | Local file path |
| `readFromBinary(string $binary)` | Raw binary image string |
| `readFromBase64(string $base64)` | Base64 / data URI string |
| `readFromUrl(string $url)` | Remote HTTP(S) URL |
| `readFromResource($resource)` | PHP stream resource |
| `readFromGdImage(\GdImage $image)` | In-memory GD image |

```php
$result = $qr->readFromPath('/path/to/qrcode.png');

echo $result->content;   // decoded string
print_r($result->points); // corner detection points
```

## Auto-Detection

Use the facade's generic `read()` method when the source type is dynamic — it inspects the value and routes to the correct reader method automatically.

```php
$result = $qr->read($anySupportedSource);
```

## The DecodedResult Object

`DecodedResult` is an immutable value object exposing:

- `content: string` — the decoded text
- `points: array` — detected marker coordinates
- `format: ?string` — detected barcode format (currently always `QR_CODE`)
- `isEmpty(): bool` and `toArray(): array` helper methods

Next: [Configuration Options](options.md)

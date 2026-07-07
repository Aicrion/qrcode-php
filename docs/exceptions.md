---
title: Exception Handling
layout: default
---

# Exception Handling

[← Back to index](index.md)

All exceptions extend the base `QRCodeException` (itself extending `RuntimeException`), so you can catch broadly or narrowly.

| Exception | Thrown when |
|---|---|
| `GenerationException` | QR code generation fails |
| `DecodingException` | No QR code detected, or decoding fails |
| `InvalidSourceException` | The input source is missing, unreadable, or unsupported |
| `UnsupportedFormatException` | Requested output/input format isn't supported by the environment |

```php
use Aicrion\QRCode\Exceptions\QRCodeException;

try {
    $result = $qr->readFromPath('/missing/file.png');
} catch (QRCodeException $e) {
    // handles InvalidSourceException, DecodingException, etc.
    logger()->error($e->getMessage());
}
```

Next: [Examples](examples.md)

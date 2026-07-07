---
title: API Reference
layout: default
---

# API Reference

[← Back to index](index.md)

## `Aicrion\QRCode\QRCode`

Facade combining generation and reading.

- `static make(): self`
- `generate(string $data, ?QRCodeOptions $options = null): string`
- `generateToFile(string $data, string $path, ?QRCodeOptions $options = null): string`
- `generateDataUri(string $data, ?QRCodeOptions $options = null): string`
- `read(mixed $source): DecodedResult`
- `readFromPath(string $path): DecodedResult`
- `readFromBinary(string $binary): DecodedResult`
- `readFromBase64(string $base64): DecodedResult`
- `readFromUrl(string $url): DecodedResult`
- `generateFromPayload(PayloadInterface $payload, ?QRCodeOptions $options = null): string`
- `generatePayloadToFile(PayloadInterface $payload, string $path, ?QRCodeOptions $options = null): string`
- `generatePayloadDataUri(PayloadInterface $payload, ?QRCodeOptions $options = null): string`
- `generateUrl(string $url, ?QRCodeOptions $options = null): string`
- `generateWifi(string $ssid, string $password, WifiEncryption $encryption, bool $hidden, ?QRCodeOptions $options = null): string`
- `generateContact(...): string`
- `generatePhone(string $phoneNumber, ?QRCodeOptions $options = null): string`
- `generateSms(string $phoneNumber, string $message, ?QRCodeOptions $options = null): string`
- `generateEmail(string $to, string $subject, string $body, ?QRCodeOptions $options = null): string`
- `generateGeo(float $latitude, float $longitude, ?float $altitude, ?QRCodeOptions $options = null): string`
- `generateEvent(string $title, DateTimeInterface $start, DateTimeInterface $end, string $location, string $description, ?QRCodeOptions $options = null): string`

## `Aicrion\QRCode\Contracts\GeneratorInterface`
## `Aicrion\QRCode\Contracts\ReaderInterface`
## `Aicrion\QRCode\Contracts\SourceResolverInterface`

## Enums

- `Enums\OutputFormat`: PNG, SVG, EPS, WEBP
- `Enums\ErrorCorrectionLevel`: LOW, MEDIUM, QUARTILE, HIGH
- `Enums\EncodingMode`: NUMERIC, ALPHANUMERIC, BYTE, KANJI
- `Enums\SourceType`: FILE_PATH, BINARY_STRING, BASE64, URL, RESOURCE, GD_IMAGE

## Value Objects

- `ValueObjects\Color`
- `ValueObjects\QRCodeOptions`
- `ValueObjects\DecodedResult` (now includes `type: PayloadType` and `parsed: array|string`)

## Payloads

- `Payloads\PayloadInterface`
- `Payloads\UrlPayload`
- `Payloads\WifiPayload`
- `Payloads\ContactPayload`
- `Payloads\PhonePayload`
- `Payloads\SmsPayload`
- `Payloads\EmailPayload`
- `Payloads\GeoPayload`
- `Payloads\EventPayload`

## Support

- `Support\PayloadParser` — `detectType(string $content): PayloadType`, `parse(string $content): array|string`
- `Support\SourceResolver`

## Exceptions

- `Exceptions\QRCodeException`
- `Exceptions\GenerationException`
- `Exceptions\DecodingException`
- `Exceptions\InvalidSourceException`
- `Exceptions\UnsupportedFormatException`

Next: [Contributing](contributing.md)

---
title: Structured Payloads
layout: default
---

# Structured Payloads

[← Back to index](index.md)

Beyond plain text, Aicrion QRCode can generate and read several **structured** QR code payload types: URLs, WiFi credentials, contact cards, phone numbers, SMS, email, and geographic coordinates.

## Generating Structured QR Codes

```php
use Aicrion\QRCode\QRCode;
use Aicrion\QRCode\Enums\WifiEncryption;

$qr = QRCode::make();

// WiFi network
$qr->generateWifi(ssid: 'MyNetwork', password: 'secret123', encryption: WifiEncryption::WPA);

// Contact card (vCard)
$qr->generateContact(firstName: 'Hadi', lastName: 'Akbarzadeh', phone: '+989120000000', email: 'hadi@elatel.ir');

// Phone number
$qr->generatePhone('+989120000000');

// SMS with pre-filled message
$qr->generateSms('+989120000000', 'Hello from Aicrion!');

// Email with subject/body
$qr->generateEmail('hello@aicrion.dev', 'Subject here', 'Body text here');

// Geographic location
$qr->generateGeo(latitude: 35.6892, longitude: 51.3890);

// Calendar event
$qr->generateEvent('Team Meeting', new DateTime('2026-08-01 10:00'), new DateTime('2026-08-01 11:00'));

// Plain URL (shortcut)
$qr->generateUrl('https://github.com/aicrion/qrcode-php');
```

All of the above accept an optional trailing `?QRCodeOptions $options` argument, just like `generate()`.

## Using Payload Objects Directly

Every structured type has a dedicated immutable Payload class implementing `PayloadInterface`, useful when you want to build the payload separately from generation:

```php
use Aicrion\QRCode\Payloads\WifiPayload;
use Aicrion\QRCode\Enums\WifiEncryption;

$payload = new WifiPayload('MyNetwork', 'secret123', WifiEncryption::WPA);

$qr->generateFromPayload($payload);
$qr->generatePayloadToFile($payload, __DIR__ . '/wifi.png');
$dataUri = $qr->generatePayloadDataUri($payload);
```

Available payload classes: `UrlPayload`, `WifiPayload`, `ContactPayload`, `PhonePayload`, `SmsPayload`, `EmailPayload`, `GeoPayload`, `EventPayload`.

## Reading Structured QR Codes

The reader **automatically detects** the payload type and parses it into a structured array — no extra configuration needed.

```php
$result = $qr->readFromPath(__DIR__ . '/wifi.png');

echo $result->type->value;   // "wifi"
print_r($result->parsed);    // ['ssid' => 'MyNetwork', 'password' => 'secret123', 'encryption' => 'WPA', 'hidden' => false]
```

## Supported Payload Types

| Type | Enum (`PayloadType`) | Parsed shape |
|---|---|---|
| Plain text | `TEXT` | raw string |
| URL | `URL` | raw string |
| WiFi | `WIFI` | `['ssid', 'password', 'encryption', 'hidden']` |
| Contact (vCard) | `CONTACT` | `['firstName', 'lastName', 'phone', 'email', 'company', 'title', 'address', 'website']` |
| Phone | `PHONE` | `['phoneNumber']` |
| SMS | `SMS` | `['phoneNumber', 'message']` |
| Email | `EMAIL` | `['to', 'subject', 'body']` |
| Geo location | `GEO` | `['latitude', 'longitude', 'altitude']` |
| Calendar event | `EVENT` | raw vEvent string |

Next: [Colors & Styling](colors.md)

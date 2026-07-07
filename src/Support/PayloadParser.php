<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Support;

use Aicrion\QRCode\Enums\PayloadType;
use Aicrion\QRCode\Payloads\ContactPayload;
use Aicrion\QRCode\Payloads\WifiPayload;

/**
 * Detects and parses structured payload types (WiFi, Contact, URL, Phone, SMS,
 * Email, Geo) out of raw decoded QR code text.
 */
final class PayloadParser
{
    public function detectType(string $content): PayloadType
    {
        return match (true) {
            str_starts_with($content, 'WIFI:') => PayloadType::WIFI,
            str_starts_with($content, 'BEGIN:VCARD') => PayloadType::CONTACT,
            str_starts_with($content, 'BEGIN:VEVENT') => PayloadType::EVENT,
            str_starts_with($content, 'tel:') => PayloadType::PHONE,
            str_starts_with($content, 'sms:') || str_starts_with($content, 'smsto:') => PayloadType::SMS,
            str_starts_with($content, 'mailto:') => PayloadType::EMAIL,
            str_starts_with($content, 'geo:') => PayloadType::GEO,
            (bool) preg_match('#^https?://#i', $content) => PayloadType::URL,
            default => PayloadType::TEXT,
        };
    }

    /**
     * Parses the raw content into a structured array representation based on
     * its detected type. For TEXT/URL types, returns the raw string as-is.
     */
    public function parse(string $content): array|string
    {
        $type = $this->detectType($content);

        return match ($type) {
            PayloadType::WIFI => $this->parseWifi($content),
            PayloadType::CONTACT => $this->parseContact($content),
            PayloadType::PHONE => ['phoneNumber' => substr($content, 4)],
            PayloadType::SMS => $this->parseSms($content),
            PayloadType::EMAIL => $this->parseEmail($content),
            PayloadType::GEO => $this->parseGeo($content),
            PayloadType::URL, PayloadType::TEXT, PayloadType::EVENT => $content,
        };
    }

    private function parseWifi(string $content): array
    {
        $wifi = WifiPayload::fromPayloadString($content);

        return [
            'ssid' => $wifi->ssid,
            'password' => $wifi->password,
            'encryption' => $wifi->encryption->value,
            'hidden' => $wifi->hidden,
        ];
    }

    private function parseContact(string $content): array
    {
        $contact = ContactPayload::fromPayloadString($content);

        return [
            'firstName' => $contact->firstName,
            'lastName' => $contact->lastName,
            'phone' => $contact->phone,
            'email' => $contact->email,
            'company' => $contact->company,
            'title' => $contact->title,
            'address' => $contact->address,
            'website' => $contact->website,
        ];
    }

    private function parseSms(string $content): array
    {
        $withoutScheme = preg_replace('/^smsto:|^sms:/', '', $content) ?? $content;
        [$number, $query] = array_pad(explode('?', $withoutScheme, 2), 2, '');

        $message = '';
        if ($query !== '') {
            parse_str($query, $parsed);
            $message = (string) ($parsed['body'] ?? '');
        }

        return ['phoneNumber' => $number, 'message' => $message];
    }

    private function parseEmail(string $content): array
    {
        $withoutScheme = substr($content, 7);
        [$to, $query] = array_pad(explode('?', $withoutScheme, 2), 2, '');

        $subject = '';
        $body = '';
        if ($query !== '') {
            parse_str($query, $parsed);
            $subject = (string) ($parsed['subject'] ?? '');
            $body = (string) ($parsed['body'] ?? '');
        }

        return ['to' => $to, 'subject' => $subject, 'body' => $body];
    }

    private function parseGeo(string $content): array
    {
        $coords = explode(',', substr($content, 4));

        return [
            'latitude' => (float) ($coords[0] ?? 0),
            'longitude' => (float) ($coords[1] ?? 0),
            'altitude' => isset($coords[2]) ? (float) $coords[2] : null,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

use Aicrion\QRCode\Enums\WifiEncryption;

/**
 * Represents a QR code payload that joins a WiFi network when scanned.
 *
 * Follows the de-facto standard format: WIFI:T:<type>;S:<ssid>;P:<password>;H:<hidden>;;
 */
final class WifiPayload implements PayloadInterface
{
    public function __construct(
        public readonly string $ssid,
        public readonly string $password = '',
        public readonly WifiEncryption $encryption = WifiEncryption::WPA,
        public readonly bool $hidden = false
    ) {
    }

    public function toPayloadString(): string
    {
        return sprintf(
            'WIFI:T:%s;S:%s;P:%s;H:%s;;',
            $this->encryption->value,
            $this->escape($this->ssid),
            $this->escape($this->password),
            $this->hidden ? 'true' : 'false'
        );
    }

    private function escape(string $value): string
    {
        return preg_replace('/([\\;,":])/', '\\$1', $value) ?? $value;
    }

    public static function fromPayloadString(string $payload): self
    {
        $matches = [];
        preg_match('/^WIFI:(.*);;$/', trim($payload), $matches);
        $body = $matches[1] ?? '';

        $fields = [];
        foreach (preg_split('/(?<!\\\\);/', $body) ?: [] as $pair) {
            [$key, $value] = array_pad(explode(':', $pair, 2), 2, '');
            $fields[$key] = str_replace(['\\;', '\\,', '\\"', '\\:', '\\\\'], [';', ',', '"', ':', '\\'], $value);
        }

        $type = WifiEncryption::tryFrom($fields['T'] ?? 'WPA') ?? WifiEncryption::WPA;

        return new self(
            ssid: $fields['S'] ?? '',
            password: $fields['P'] ?? '',
            encryption: $type,
            hidden: ($fields['H'] ?? 'false') === 'true'
        );
    }
}

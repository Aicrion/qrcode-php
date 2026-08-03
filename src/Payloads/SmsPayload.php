<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

/**
 * Represents a QR code payload that pre-fills an SMS message when scanned.
 */
final class SmsPayload implements PayloadInterface
{
    public function __construct(
        public readonly string $phoneNumber,
        public readonly string $message = ''
    ) {
    }

    public function toPayloadString(): string
    {
        if ($this->message === '') {
            return 'sms:' . $this->phoneNumber;
        }

        return sprintf('sms:%s?body=%s', $this->phoneNumber, rawurlencode($this->message));
    }

    public static function fromPayloadString(string $payload): self
    {
        $payload = trim($payload);
        $withoutScheme = preg_replace('/^smsto:|^sms:/', '', $payload) ?? $payload;
        [$number, $query] = array_pad(explode('?', $withoutScheme, 2), 2, '');

        $message = '';
        if ($query !== '') {
            parse_str($query, $parsed);
            $message = (string) ($parsed['body'] ?? '');
        }

        return new self(phoneNumber: $number, message: $message);
    }
}

<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

/**
 * Represents a QR code payload that pre-fills an email message when scanned.
 */
final class EmailPayload implements PayloadInterface
{
    public function __construct(
        public readonly string $to,
        public readonly string $subject = '',
        public readonly string $body = ''
    ) {
    }

    public function toPayloadString(): string
    {
        $query = array_filter([
            'subject' => $this->subject,
            'body' => $this->body,
        ]);

        $queryString = http_build_query($query);

        return $queryString === ''
            ? 'mailto:' . $this->to
            : sprintf('mailto:%s?%s', $this->to, $queryString);
    }

    public static function fromPayloadString(string $payload): self
    {
        $payload = trim($payload);

        if (str_starts_with($payload, 'mailto:')) {
            $payload = substr($payload, 7);
        }

        [$to, $query] = array_pad(explode('?', $payload, 2), 2, '');

        $subject = '';
        $body = '';
        if ($query !== '') {
            parse_str($query, $parsed);
            $subject = (string) ($parsed['subject'] ?? '');
            $body = (string) ($parsed['body'] ?? '');
        }

        return new self(to: $to, subject: $subject, body: $body);
    }
}

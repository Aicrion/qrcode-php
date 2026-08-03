<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

use DateTime;
use DateTimeInterface;

/**
 * Represents a QR code payload encoding a calendar event (vEvent/iCalendar format).
 */
final class EventPayload implements PayloadInterface
{
    public function __construct(
        public readonly string $title,
        public readonly DateTimeInterface $start,
        public readonly DateTimeInterface $end,
        public readonly string $location = '',
        public readonly string $description = '',
    ) {
    }

    public function toPayloadString(): string
    {
        $lines = [
            'BEGIN:VEVENT',
            'SUMMARY:' . $this->title,
            'DTSTART:' . $this->start->format('Ymd\\THis'),
            'DTEND:' . $this->end->format('Ymd\\THis'),
        ];

        if ($this->location !== '') {
            $lines[] = 'LOCATION:' . $this->location;
        }

        if ($this->description !== '') {
            $lines[] = 'DESCRIPTION:' . $this->description;
        }

        $lines[] = 'END:VEVENT';

        return implode("\r\n", $lines);
    }

    public static function fromPayloadString(string $payload): self
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($payload)) ?: [];

        $title = '';
        $start = new DateTime();
        $end = new DateTime();
        $location = '';
        $description = '';

        foreach ($lines as $line) {
            if (str_starts_with($line, 'SUMMARY:')) {
                $title = substr($line, 8);
            } elseif (str_starts_with($line, 'DTSTART:')) {
                $start = DateTime::createFromFormat('Ymd\\THis', substr($line, 8)) ?: new DateTime();
            } elseif (str_starts_with($line, 'DTEND:')) {
                $end = DateTime::createFromFormat('Ymd\\THis', substr($line, 6)) ?: new DateTime();
            } elseif (str_starts_with($line, 'LOCATION:')) {
                $location = substr($line, 9);
            } elseif (str_starts_with($line, 'DESCRIPTION:')) {
                $description = substr($line, 12);
            }
        }

        return new self(
            title: $title,
            start: $start,
            end: $end,
            location: $location,
            description: $description,
        );
    }
}

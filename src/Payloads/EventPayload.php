<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

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
            'DTSTART:' . $this->start->format('Ymd\THis'),
            'DTEND:' . $this->end->format('Ymd\THis'),
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
}

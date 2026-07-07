<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Payloads;

/**
 * Represents a QR code payload encoding a contact card (vCard 3.0 format),
 * commonly used to add a person directly to the phone's address book.
 */
final class ContactPayload implements PayloadInterface
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName = '',
        public readonly string $phone = '',
        public readonly string $email = '',
        public readonly string $company = '',
        public readonly string $title = '',
        public readonly string $address = '',
        public readonly string $website = '',
    ) {
    }

    public function toPayloadString(): string
    {
        $lines = ['BEGIN:VCARD', 'VERSION:3.0'];

        $lines[] = sprintf('N:%s;%s;;;', $this->lastName, $this->firstName);
        $lines[] = sprintf('FN:%s', trim($this->firstName . ' ' . $this->lastName));

        if ($this->company !== '') {
            $lines[] = 'ORG:' . $this->company;
        }

        if ($this->title !== '') {
            $lines[] = 'TITLE:' . $this->title;
        }

        if ($this->phone !== '') {
            $lines[] = 'TEL;TYPE=CELL:' . $this->phone;
        }

        if ($this->email !== '') {
            $lines[] = 'EMAIL:' . $this->email;
        }

        if ($this->address !== '') {
            $lines[] = 'ADR:;;' . $this->address . ';;;;';
        }

        if ($this->website !== '') {
            $lines[] = 'URL:' . $this->website;
        }

        $lines[] = 'END:VCARD';

        return implode("\r\n", $lines);
    }

    public static function fromPayloadString(string $payload): self
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($payload)) ?: [];

        $firstName = '';
        $lastName = '';
        $phone = '';
        $email = '';
        $company = '';
        $title = '';
        $address = '';
        $website = '';

        foreach ($lines as $line) {
            if (str_starts_with($line, 'N:')) {
                $parts = explode(';', substr($line, 2));
                $lastName = $parts[0] ?? '';
                $firstName = $parts[1] ?? '';
            } elseif (str_starts_with($line, 'ORG:')) {
                $company = substr($line, 4);
            } elseif (str_starts_with($line, 'TITLE:')) {
                $title = substr($line, 6);
            } elseif (str_starts_with($line, 'TEL')) {
                $phone = substr($line, (int) strpos($line, ':') + 1);
            } elseif (str_starts_with($line, 'EMAIL')) {
                $email = substr($line, (int) strpos($line, ':') + 1);
            } elseif (str_starts_with($line, 'ADR')) {
                $addrParts = explode(';', substr($line, (int) strpos($line, ':') + 1));
                $address = $addrParts[2] ?? '';
            } elseif (str_starts_with($line, 'URL:')) {
                $website = substr($line, 4);
            }
        }

        return new self(
            firstName: $firstName,
            lastName: $lastName,
            phone: $phone,
            email: $email,
            company: $company,
            title: $title,
            address: $address,
            website: $website,
        );
    }
}

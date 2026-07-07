<?php

declare(strict_types=1);

namespace Aicrion\QRCode;

use Aicrion\QRCode\Contracts\GeneratorInterface;
use Aicrion\QRCode\Contracts\ReaderInterface;
use Aicrion\QRCode\Generator\QRCodeGenerator;
use Aicrion\QRCode\Reader\QRCodeReader;
use Aicrion\QRCode\Payloads\ContactPayload;
use Aicrion\QRCode\Payloads\EmailPayload;
use Aicrion\QRCode\Payloads\EventPayload;
use Aicrion\QRCode\Payloads\GeoPayload;
use Aicrion\QRCode\Payloads\PayloadInterface;
use Aicrion\QRCode\Payloads\PhonePayload;
use Aicrion\QRCode\Payloads\SmsPayload;
use Aicrion\QRCode\Payloads\UrlPayload;
use Aicrion\QRCode\Payloads\WifiPayload;
use Aicrion\QRCode\ValueObjects\DecodedResult;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;

/**
 * Main entry-point facade for the Aicrion QRCode library.
 *
 * Provides a simple, fluent API to generate and read QR codes
 * without needing to instantiate internal collaborators directly.
 */
final class QRCode
{
    public function __construct(
        private readonly GeneratorInterface $generator = new QRCodeGenerator(),
        private readonly ReaderInterface $reader = new QRCodeReader(),
    ) {
    }

    public static function make(): self
    {
        return new self();
    }

    // ---- Generation ----

    public function generate(string $data, ?QRCodeOptions $options = null): string
    {
        return $this->generator->generate($data, $options ?? new QRCodeOptions());
    }

    public function generateToFile(string $data, string $path, ?QRCodeOptions $options = null): string
    {
        return $this->generator->generateToFile($data, $path, $options ?? new QRCodeOptions());
    }

    public function generateDataUri(string $data, ?QRCodeOptions $options = null): string
    {
        return $this->generator->generateDataUri($data, $options ?? new QRCodeOptions());
    }

    // ---- Structured Payload Generation ----

    public function generateFromPayload(PayloadInterface $payload, ?QRCodeOptions $options = null): string
    {
        return $this->generate($payload->toPayloadString(), $options);
    }

    public function generatePayloadToFile(PayloadInterface $payload, string $path, ?QRCodeOptions $options = null): string
    {
        return $this->generateToFile($payload->toPayloadString(), $path, $options);
    }

    public function generatePayloadDataUri(PayloadInterface $payload, ?QRCodeOptions $options = null): string
    {
        return $this->generateDataUri($payload->toPayloadString(), $options);
    }

    public function generateUrl(string $url, ?QRCodeOptions $options = null): string
    {
        return $this->generateFromPayload(new UrlPayload($url), $options);
    }

    public function generateWifi(
        string $ssid,
        string $password = '',
        \Aicrion\QRCode\Enums\WifiEncryption $encryption = \Aicrion\QRCode\Enums\WifiEncryption::WPA,
        bool $hidden = false,
        ?QRCodeOptions $options = null
    ): string {
        return $this->generateFromPayload(new WifiPayload($ssid, $password, $encryption, $hidden), $options);
    }

    public function generateContact(
        string $firstName,
        string $lastName = '',
        string $phone = '',
        string $email = '',
        string $company = '',
        string $title = '',
        string $address = '',
        string $website = '',
        ?QRCodeOptions $options = null
    ): string {
        return $this->generateFromPayload(
            new ContactPayload($firstName, $lastName, $phone, $email, $company, $title, $address, $website),
            $options
        );
    }

    public function generatePhone(string $phoneNumber, ?QRCodeOptions $options = null): string
    {
        return $this->generateFromPayload(new PhonePayload($phoneNumber), $options);
    }

    public function generateSms(string $phoneNumber, string $message = '', ?QRCodeOptions $options = null): string
    {
        return $this->generateFromPayload(new SmsPayload($phoneNumber, $message), $options);
    }

    public function generateEmail(
        string $to,
        string $subject = '',
        string $body = '',
        ?QRCodeOptions $options = null
    ): string {
        return $this->generateFromPayload(new EmailPayload($to, $subject, $body), $options);
    }

    public function generateGeo(float $latitude, float $longitude, ?float $altitude = null, ?QRCodeOptions $options = null): string
    {
        return $this->generateFromPayload(new GeoPayload($latitude, $longitude, $altitude), $options);
    }

    public function generateEvent(
        string $title,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        string $location = '',
        string $description = '',
        ?QRCodeOptions $options = null
    ): string {
        return $this->generateFromPayload(new EventPayload($title, $start, $end, $location, $description), $options);
    }

    // ---- Reading ----

    public function read(mixed $source): DecodedResult
    {
        return match (true) {
            $source instanceof \GdImage => $this->reader->readFromGdImage($source),
            is_resource($source) => $this->reader->readFromResource($source),
            is_string($source) && preg_match('#^https?://#i', $source) === 1 => $this->reader->readFromUrl($source),
            is_string($source) && preg_match('#^data:image/[a-zA-Z]+;base64,#', $source) === 1 => $this->reader->readFromBase64($source),
            is_string($source) && is_file($source) => $this->reader->readFromPath($source),
            is_string($source) => $this->reader->readFromBinary($source),
            default => throw new \Aicrion\QRCode\Exceptions\InvalidSourceException('Unsupported source type.'),
        };
    }

    public function readFromPath(string $path): DecodedResult
    {
        return $this->reader->readFromPath($path);
    }

    public function readFromBinary(string $binary): DecodedResult
    {
        return $this->reader->readFromBinary($binary);
    }

    public function readFromBase64(string $base64): DecodedResult
    {
        return $this->reader->readFromBase64($base64);
    }

    public function readFromUrl(string $url): DecodedResult
    {
        return $this->reader->readFromUrl($url);
    }
}

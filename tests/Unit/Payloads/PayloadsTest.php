<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Unit\Payloads;

use Aicrion\QRCode\Enums\WifiEncryption;
use Aicrion\QRCode\Payloads\ContactPayload;
use Aicrion\QRCode\Payloads\EmailPayload;
use Aicrion\QRCode\Payloads\GeoPayload;
use Aicrion\QRCode\Payloads\PhonePayload;
use Aicrion\QRCode\Payloads\SmsPayload;
use Aicrion\QRCode\Payloads\UrlPayload;
use Aicrion\QRCode\Payloads\WifiPayload;
use PHPUnit\Framework\TestCase;

final class PayloadsTest extends TestCase
{
    public function test_url_payload(): void
    {
        self::assertSame('https://aicrion.dev', (new UrlPayload('https://aicrion.dev'))->toPayloadString());
    }

    public function test_phone_payload(): void
    {
        self::assertSame('tel:+989120000000', (new PhonePayload('+989120000000'))->toPayloadString());
    }

    public function test_sms_payload_without_message(): void
    {
        self::assertSame('sms:+989120000000', (new SmsPayload('+989120000000'))->toPayloadString());
    }

    public function test_sms_payload_with_message(): void
    {
        $payload = new SmsPayload('+989120000000', 'Hello');
        self::assertSame('sms:+989120000000?body=Hello', $payload->toPayloadString());
    }

    public function test_email_payload(): void
    {
        $payload = new EmailPayload('test@aicrion.dev', 'Hi', 'Body text');
        self::assertStringStartsWith('mailto:test@aicrion.dev?', $payload->toPayloadString());
        self::assertStringContainsString('subject=Hi', $payload->toPayloadString());
    }

    public function test_geo_payload(): void
    {
        $payload = new GeoPayload(35.6892, 51.3890);
        self::assertSame('geo:35.6892,51.389', $payload->toPayloadString());
    }

    public function test_wifi_payload_round_trip(): void
    {
        $payload = new WifiPayload('MySSID', 'secret123', WifiEncryption::WPA, true);
        $encoded = $payload->toPayloadString();

        self::assertStringContainsString('WIFI:T:WPA;S:MySSID;P:secret123;H:true;;', $encoded);

        $decoded = WifiPayload::fromPayloadString($encoded);
        self::assertSame('MySSID', $decoded->ssid);
        self::assertSame('secret123', $decoded->password);
        self::assertTrue($decoded->hidden);
        self::assertSame(WifiEncryption::WPA, $decoded->encryption);
    }

    public function test_contact_payload_round_trip(): void
    {
        $payload = new ContactPayload(
            firstName: 'Hadi',
            lastName: 'Akbarzadeh',
            phone: '+989120000000',
            email: 'hadi@elatel.ir',
            company: 'Aicrion',
            title: 'Developer',
            website: 'https://elatel.ir'
        );

        $encoded = $payload->toPayloadString();
        self::assertStringContainsString('BEGIN:VCARD', $encoded);
        self::assertStringContainsString('FN:Hadi Akbarzadeh', $encoded);

        $decoded = ContactPayload::fromPayloadString($encoded);
        self::assertSame('Hadi', $decoded->firstName);
        self::assertSame('Akbarzadeh', $decoded->lastName);
        self::assertSame('+989120000000', $decoded->phone);
        self::assertSame('hadi@elatel.ir', $decoded->email);
        self::assertSame('Aicrion', $decoded->company);
    }
}

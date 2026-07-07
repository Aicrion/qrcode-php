<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Tests\Unit\Support;

use Aicrion\QRCode\Enums\PayloadType;
use Aicrion\QRCode\Support\PayloadParser;
use PHPUnit\Framework\TestCase;

final class PayloadParserTest extends TestCase
{
    private PayloadParser $parser;

    protected function setUp(): void
    {
        $this->parser = new PayloadParser();
    }

    public function test_detects_url(): void
    {
        self::assertSame(PayloadType::URL, $this->parser->detectType('https://aicrion.dev'));
    }

    public function test_detects_phone(): void
    {
        self::assertSame(PayloadType::PHONE, $this->parser->detectType('tel:+989120000000'));
    }

    public function test_detects_wifi(): void
    {
        self::assertSame(PayloadType::WIFI, $this->parser->detectType('WIFI:T:WPA;S:Test;P:pass;H:false;;'));
    }

    public function test_detects_contact(): void
    {
        self::assertSame(PayloadType::CONTACT, $this->parser->detectType("BEGIN:VCARD\r\nVERSION:3.0\r\nEND:VCARD"));
    }

    public function test_detects_plain_text(): void
    {
        self::assertSame(PayloadType::TEXT, $this->parser->detectType('just some text'));
    }

    public function test_parses_wifi_to_array(): void
    {
        $parsed = $this->parser->parse('WIFI:T:WPA;S:MyNet;P:1234;H:false;;');

        self::assertIsArray($parsed);
        self::assertSame('MyNet', $parsed['ssid']);
        self::assertSame('1234', $parsed['password']);
    }

    public function test_parses_geo_to_array(): void
    {
        $parsed = $this->parser->parse('geo:35.6892,51.389');

        self::assertIsArray($parsed);
        self::assertSame(35.6892, $parsed['latitude']);
        self::assertSame(51.389, $parsed['longitude']);
    }
}

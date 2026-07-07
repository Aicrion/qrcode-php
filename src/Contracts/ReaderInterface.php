<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Contracts;

use Aicrion\QRCode\ValueObjects\DecodedResult;

/**
 * Contract for classes capable of reading/decoding QR codes from various sources.
 */
interface ReaderInterface
{
    public function readFromPath(string $path): DecodedResult;

    public function readFromBinary(string $binary): DecodedResult;

    public function readFromBase64(string $base64): DecodedResult;

    public function readFromUrl(string $url): DecodedResult;

    /**
     * @param resource $resource
     */
    public function readFromResource($resource): DecodedResult;

    /**
     * @param \GdImage $image
     */
    public function readFromGdImage(\GdImage $image): DecodedResult;
}

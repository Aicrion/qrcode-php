<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Contracts;

/**
 * Contract for resolving arbitrary input sources into raw binary image data.
 */
interface SourceResolverInterface
{
    public function resolve(mixed $source): string;
}

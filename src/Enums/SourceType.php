<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Enums;

/**
 * Supported input sources when reading QR codes.
 */
enum SourceType: string
{
    case FILE_PATH = 'file_path';
    case BINARY_STRING = 'binary_string';
    case BASE64 = 'base64';
    case URL = 'url';
    case RESOURCE = 'resource';
    case GD_IMAGE = 'gd_image';
}

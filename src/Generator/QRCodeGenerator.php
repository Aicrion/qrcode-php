<?php

declare(strict_types=1);

namespace Aicrion\QRCode\Generator;

use Aicrion\QRCode\Contracts\GeneratorInterface;
use Aicrion\QRCode\Enums\ErrorCorrectionLevel as ECL;
use Aicrion\QRCode\Enums\OutputFormat;
use Aicrion\QRCode\Exceptions\GenerationException;
use Aicrion\QRCode\Exceptions\UnsupportedFormatException;
use Aicrion\QRCode\ValueObjects\Color;
use Aicrion\QRCode\ValueObjects\QRCodeOptions;
use BaconQrCode\Encoder\ErrorCorrectionLevel;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Throwable;

/**
 * Default QR code generator implementation, built on top of bacon/bacon-qr-code.
 *
 * Supports PNG, SVG, EPS and WEBP output, custom colors, margins,
 * error correction levels and optional embedded logo overlay (raster formats only).
 */
final class QRCodeGenerator implements GeneratorInterface
{
    public function generate(string $data, QRCodeOptions $options): string
    {
        try {
            $format = $options->format;

            if ($format === OutputFormat::SVG) {
                return $this->renderVector($data, $options, new SvgImageBackEnd());
            }

            if ($format === OutputFormat::EPS) {
                return $this->renderVector($data, $options, new EpsImageBackEnd());
            }

            // PNG / WEBP are rendered as PNG bytes first, then converted if needed.
            $pngBytes = $this->renderRaster($data, $options);

            return $format === OutputFormat::WEBP
                ? $this->convertPngToWebp($pngBytes)
                : $pngBytes;
        } catch (UnsupportedFormatException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new GenerationException(
                'Failed to generate QR code: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    public function generateToFile(string $data, string $path, QRCodeOptions $options): string
    {
        $bytes = $this->generate($data, $options);

        $directory = dirname($path);
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new GenerationException("Unable to create directory: {$directory}");
        }

        if (file_put_contents($path, $bytes) === false) {
            throw new GenerationException("Unable to write QR code to file: {$path}");
        }

        return $path;
    }

    public function generateDataUri(string $data, QRCodeOptions $options): string
    {
        $bytes = $this->generate($data, $options);
        $mime = $options->format->mimeType();

        return sprintf('data:%s;base64,%s', $mime, base64_encode($bytes));
    }

    private function renderVector(string $data, QRCodeOptions $options, ImageBackEndInterface $backEnd): string
    {
        $style = $this->buildStyle($options);
        $renderer = new ImageRenderer($style, $backEnd);
        $writer = new Writer($renderer);

        return $writer->writeString($data, 'UTF-8');
    }

    private function renderRaster(string $data, QRCodeOptions $options): string
    {
        if (! extension_loaded('gd')) {
            throw new GenerationException('The GD extension is required to generate raster QR codes.');
        }

        // GDLibRenderer is the only raster-capable renderer bundled with bacon/bacon-qr-code
        // that does not require the Imagick extension. It renders in pure black/white;
        // custom colors for raster formats are therefore applied as a post-processing
        // step (color remapping) below, keeping the public API consistent across all formats.
        $margin = max(0, intdiv($options->margin, 4));
        $renderer = new GDLibRenderer($options->size, $margin);

        $writer = new Writer($renderer);
        $bytes = $writer->writeString($data, 'UTF-8');

        if (! $options->foregroundColor->equals(Color::black()) || ! $options->backgroundColor->equals(Color::white())) {
            $bytes = $this->remapColors($bytes, $options);
        }

        if ($options->logoPath !== null) {
            $bytes = $this->overlayLogo($bytes, $options);
        }

        if ($options->label !== null) {
            $bytes = $this->overlayLabel($bytes, $options);
        }

        return $bytes;
    }

    private function remapColors(string $pngBytes, QRCodeOptions $options): string
    {
        $image = imagecreatefromstring($pngBytes);

        if ($image === false) {
            return $pngBytes;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $output = imagecreatetruecolor($width, $height);
        $fg = imagecolorallocate(
            $output,
            $options->foregroundColor->red,
            $options->foregroundColor->green,
            $options->foregroundColor->blue
        );
        $bg = imagecolorallocate(
            $output,
            $options->backgroundColor->red,
            $options->backgroundColor->green,
            $options->backgroundColor->blue
        );

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($image, $x, $y);
                $colors = imagecolorsforindex($image, $rgb);
                $isDark = ($colors['red'] + $colors['green'] + $colors['blue']) < 384;
                imagesetpixel($output, $x, $y, $isDark ? $fg : $bg);
            }
        }

        ob_start();
        imagepng($output);
        $result = (string) ob_get_clean();

        imagedestroy($image);
        imagedestroy($output);

        return $result;
    }

    private function buildStyle(QRCodeOptions $options): RendererStyle
    {
        $fill = Fill::uniformColor(
            $this->toRgb($options->backgroundColor),
            $this->toRgb($options->foregroundColor)
        );

        return new RendererStyle(
            $options->size,
            max(0, intdiv($options->margin, 4)),
            fill: $fill,
        );
    }

    private function toRgb(\Aicrion\QRCode\ValueObjects\Color $color): Rgb
    {
        return new Rgb($color->red, $color->green, $color->blue);
    }

    private function overlayLogo(string $pngBytes, QRCodeOptions $options): string
    {
        $qr = imagecreatefromstring($pngBytes);
        $logo = @imagecreatefromstring((string) file_get_contents((string) $options->logoPath));

        if ($qr === false || $logo === false) {
            return $pngBytes;
        }

        $qrWidth = imagesx($qr);
        $qrHeight = imagesy($qr);

        $logoSize = (int) ($qrWidth * ($options->logoSizeRatio / 100));
        $logoX = (int) (($qrWidth - $logoSize) / 2);
        $logoY = (int) (($qrHeight - $logoSize) / 2);

        imagecopyresampled(
            $qr,
            $logo,
            $logoX,
            $logoY,
            0,
            0,
            $logoSize,
            $logoSize,
            imagesx($logo),
            imagesy($logo)
        );

        ob_start();
        imagepng($qr);
        $result = (string) ob_get_clean();

        imagedestroy($qr);
        imagedestroy($logo);

        return $result;
    }

    private function overlayLabel(string $pngBytes, QRCodeOptions $options): string
    {
        $qr = imagecreatefromstring($pngBytes);

        if ($qr === false) {
            return $pngBytes;
        }

        $width = imagesx($qr);
        $height = imagesy($qr);
        $labelHeight = 24;

        $canvas = imagecreatetruecolor($width, $height + $labelHeight);
        $bg = imagecolorallocate(
            $canvas,
            $options->backgroundColor->red,
            $options->backgroundColor->green,
            $options->backgroundColor->blue
        );
        imagefill($canvas, 0, 0, $bg);
        imagecopy($canvas, $qr, 0, 0, 0, 0, $width, $height);

        $textColor = imagecolorallocate(
            $canvas,
            $options->foregroundColor->red,
            $options->foregroundColor->green,
            $options->foregroundColor->blue
        );

        $text = (string) $options->label;
        $textWidth = imagefontwidth(3) * strlen($text);
        $x = (int) (($width - $textWidth) / 2);
        imagestring($canvas, 3, max(0, $x), $height + 4, $text, $textColor);

        ob_start();
        imagepng($canvas);
        $result = (string) ob_get_clean();

        imagedestroy($qr);
        imagedestroy($canvas);

        return $result;
    }

    private function convertPngToWebp(string $pngBytes): string
    {
        if (! function_exists('imagewebp')) {
            throw new UnsupportedFormatException('WEBP conversion requires GD built with WEBP support.');
        }

        $image = imagecreatefromstring($pngBytes);

        if ($image === false) {
            throw new GenerationException('Unable to decode intermediate PNG for WEBP conversion.');
        }

        ob_start();
        imagewebp($image);
        $webp = (string) ob_get_clean();

        imagedestroy($image);

        return $webp;
    }
}

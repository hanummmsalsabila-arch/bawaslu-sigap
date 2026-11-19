<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class FotoKompres
{
    private const SISI_MAKS = 1280;
    private const KUALITAS  = 70;

    /** Kompres, beri cap hari + jam, simpan, lalu kembalikan path-nya. */
    public static function simpan(UploadedFile $file): string
    {
        ini_set('memory_limit', '512M');

        $path = $file->getRealPath();
        $info = @getimagesize($path);

        if (! $info) {
            throw new RuntimeException('File bukan gambar.');
        }

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => imagecreatefrompng($path),
            IMAGETYPE_WEBP => imagecreatefromwebp($path),
            default        => throw new RuntimeException('Format gambar tidak didukung.'),
        };

        // Putar sesuai orientasi kamera HP
        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif  = @exif_read_data($path);
            $sudut = match ($exif['Orientation'] ?? 1) {
                3 => 180,
                6 => -90,
                8 => 90,
                default => 0,
            };
            if ($sudut !== 0) {
                $src = imagerotate($src, $sudut, 0) ?: $src;
            }
        }

        $w     = imagesx($src);
        $h     = imagesy($src);
        $rasio = min(1, self::SISI_MAKS / max($w, $h));
        $nw    = max(1, (int) round($w * $rasio));
        $nh    = max(1, (int) round($h * $rasio));

        $dst = imagecreatetruecolor($nw, $nh);
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        self::cap($dst, $nw, $nh);

        ob_start();
        imagejpeg($dst, null, self::KUALITAS);
        $data = ob_get_clean();

        $nama = 'laporan/'.now()->format('Ymd').'/'.Str::uuid().'.jpg';
        Storage::disk('public')->put($nama, $data);

        return $nama;
    }

    private static function cap($img, int $w, int $h): void
    {
        $teks  = now()->locale('id')->translatedFormat('l, d M Y H:i').' WIB';
        $font  = 5;
        $pad   = 4;
        $tw    = imagefontwidth($font) * strlen($teks);
        $th    = imagefontheight($font);

        $strip = imagecreatetruecolor($tw + $pad * 2, $th + $pad * 2);
        imagefill($strip, 0, 0, imagecolorallocate($strip, 0, 0, 0));
        imagestring($strip, $font, $pad, $pad, $teks, imagecolorallocate($strip, 255, 255, 255));

        $skala  = max(1, (int) round($w / 600));
        $sw     = imagesx($strip) * $skala;
        $sh     = imagesy($strip) * $skala;
        $margin = 8 * $skala;

        imagecopyresized(
            $img, $strip,
            $margin, max(0, $h - $sh - $margin),
            0, 0,
            $sw, $sh,
            imagesx($strip), imagesy($strip)
        );
    }
}
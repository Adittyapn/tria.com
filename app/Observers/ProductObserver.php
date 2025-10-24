<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// Gunakan import 'ImageManagerStatic' untuk v2
use Intervention\Image\ImageManagerStatic as Image;

class ProductObserver
{
    /**
     * Handle the Product "saved" event.
     */
    public function saved(Product $product): void
    {
        // quick hit log to confirm observer is invoked
        try {
            Log::info('ProductObserver: hit', [
                'product_id' => $product->id ?? null,
                'featured_image' => $product->featured_image ?? null,
            ]);
        } catch (\Throwable $_) {
            // ignore logging errors
        }

        if (empty($product->featured_image)) {
            try { Log::info('ProductObserver: skip - no featured_image', ['product_id' => $product->id ?? null]); } catch (\Throwable $_) {}
            return;
        }

                $disk = 'public'; // Filament stores files in public disk
        $path = $product->featured_image; // relative path stored by Filament

        if (!Storage::disk($disk)->exists($path)) {
            try { Log::info('ProductObserver: skip - file not found on disk', ['path' => $path, 'disk' => $disk]); } catch (\Throwable $_) {}
            return;
        }

        $fullPath = Storage::disk($disk)->path($path);

        try {
            $size = filesize($fullPath);
        } catch (\Throwable $e) {
            try { Log::info('ProductObserver: skip - filesize failed', ['path' => $fullPath, 'error' => $e->getMessage()]); } catch (\Throwable $_) {}
            return;
        }

        // --- PERUBAHAN LOGIKA DIMULAI DI SINI ---

        // Kita akan kompres JIKA file > 5MB, sesuai permintaan user
        $maxBytes = 5 * 1024 * 1024; // 5 MB

        Log::info('ProductObserver: checking size', [
            'product_id' => $product->id ?? null,
            'path' => $path,
            'size_bytes' => $size,
            'max_bytes' => $maxBytes,
            'will_compress' => $size > $maxBytes
        ]);

        // Kita tetap resize gambar besar, tapi kita juga akan kompres gambar
        // yang ukurannya sedang (misal 4MB)
        if ($size <= $maxBytes) {
             // Jika file sudah di bawah 5MB, kita tidak perlu kompresi.
            Log::info('ProductObserver: skip - file too small (under 5MB)', [
                'product_id' => $product->id ?? null,
                'path' => $path,
                'size_bytes' => $size
            ]);
            return;
        }

        // --- Ini adalah kode v2 yang BENAR untuk library Anda ---
        try {
            // Jika driver tidak ada, lewati
            if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
                Log::info('ProductObserver: skip - no image drivers', [
                    'product_id' => $product->id ?? null,
                    'path' => $path,
                    'gd_loaded' => extension_loaded('gd'),
                    'imagick_loaded' => extension_loaded('imagick')
                ]);
                return;
            }

            // Konfigurasi driver v2
            Image::configure(['driver' => extension_loaded('gd') ? 'gd' : 'imagick']);

            // Gunakan 'make()' untuk v2
            $img = Image::make($fullPath);

            // Logging: informasi sebelum proses (ukuran asli sudah dihitung di atas)
            $driverUsed = extension_loaded('imagick') ? 'imagick' : (extension_loaded('gd') ? 'gd' : 'none');
            try {
                $mime = function_exists('mime_content_type') ? mime_content_type($fullPath) : null;
            } catch (\Throwable $e) {
                $mime = null;
            }
            // ambil dimensi awal untuk debugging
            try {
                $origWidth = $img->width();
                $origHeight = $img->height();
            } catch (\Throwable $e) {
                $origWidth = null;
                $origHeight = null;
            }
            Log::info('ProductObserver: compress start', [
                'product_id' => $product->id ?? null,
                'path' => $path,
                'driver' => $driverUsed,
                'original_size_bytes' => $size,
                'mime' => $mime,
                'width' => $origWidth,
                'height' => $origHeight,
            ]);

            // --- LOGIKA RESIZE LEBIH AGRESIF ---
            // 1. Tentukan batas dimensi terpanjang (1600px lebih agresif)
            $maxDimension = 1600;

            // 2. Cek apakah LEBAR atau TINGGI melebihi batas
            if ($img->width() > $maxDimension || $img->height() > $maxDimension) {
                
                // 3. Resize gambar ke 1920px di sisi terpanjang, jaga rasio
                $img->resize($maxDimension, $maxDimension, function ($constraint) {
                    $constraint->aspectRatio(); // Jaga rasio
                    $constraint->upsize(); // Jangan perbesar gambar yang sudah kecil
                });
            }
            // --- SELESAI LOGIKA RESIZE ---


            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $format = in_array($extension, ['jpg', 'jpeg']) ? 'jpg' : (in_array($extension, ['png']) ? 'png' : 'jpg');

            // Pastikan orientasi benar (exif) sebelum encode
            try {
                $img->orientate();
            } catch (\Throwable $e) {
                // ignore - EXIF mungkin tidak supported
                Log::info('ProductObserver: orientate skipped', [
                    'product_id' => $product->id ?? null,
                    'error' => $e->getMessage()
                ]);
            }

            // Encode v2

            if ($format === 'png') {
                // Kompresi PNG (lossless, jadi tidak akan sekecil jpg)
                $img->encode('png', 8); // Level 0-9
            } else {
                // --- KUALITAS JPEG LEBIH RENDAH ---
                // 4. Ubah kualitas menjadi lebih agresif dan strip metadata bila memungkinkan
                $quality = 55;
                if ($driverUsed === 'imagick') {
                    try {
                        $core = $img->getCore();
                        // Use method checks instead of instanceof to avoid static analysis issues
                        if (is_object($core) && method_exists($core, 'stripImage')) {
                            try { $core->stripImage(); } catch (\Throwable $_) { }
                        }
                        if (is_object($core) && method_exists($core, 'setImageCompressionQuality')) {
                            try { $core->setImageCompressionQuality($quality); } catch (\Throwable $_) { }
                        }
                        if (defined('Imagick::INTERLACE_PLANE') && is_object($core) && method_exists($core, 'setInterlaceScheme')) {
                            try { $core->setInterlaceScheme(constant('Imagick::INTERLACE_PLANE')); } catch (\Throwable $_) { }
                        }
                    } catch (\Throwable $e) {
                        // ignore - fallback ke encode di bawah
                    }
                }

                $img->encode('jpg', $quality);
            }

            $img->save($fullPath);

            // Logging: ukuran setelah proses
            clearstatcache(true, $fullPath);
            try {
                $newSize = filesize($fullPath);
            } catch (\Throwable $e) {
                $newSize = null;
            }
            Log::info('ProductObserver: compress finished', [
                'product_id' => $product->id ?? null,
                'path' => $path,
                'original_size_bytes' => $size,
                'new_size_bytes' => $newSize,
                'driver' => $driverUsed,
            ]);

        } catch (\Throwable $e) {
            // Jangan hentikan proses jika gagal
            report($e); // Laporkan error agar Anda tahu
            return;
        }
        // --- Selesai blok kode v2 ---
    }
}
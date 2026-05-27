<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Seo
{
    public static function imageAlt(?string $path, ?string $fallback = null): string
    {
        if (! $path) {
            return (string) ($fallback ?: 'Image');
        }

        $path = parse_url($path, PHP_URL_PATH) ?: $path;
        $name = pathinfo($path, PATHINFO_FILENAME);
        $name = preg_replace('/-[a-z0-9]{8,12}$/i', '', (string) $name);
        $name = preg_replace('/-\d{8,}$/', '', (string) $name);
        $name = str_replace(['-', '_'], ' ', (string) $name);
        $name = trim(preg_replace('/\s+/', ' ', $name));

        return $name !== '' ? Str::title($name) : (string) ($fallback ?: 'Image');
    }

    public static function storeImage(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: Str::random(8);
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = $baseName.'-'.Str::lower(Str::random(8)).'.'.$extension;

        return $file->storeAs($directory, $filename, $disk);
    }

    public static function url(?string $path, string $fallback = 'assets/images/product-placeholder.svg'): string
    {
        if (! $path) {
            return asset($fallback);
        }

        return str_starts_with($path, 'http') ? $path : asset('storage/'.$path);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SchoolSetting extends Model
{
    protected $table = 'school_settings'; // sesuaikan dengan tabel yang ada di database
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     */
    public static function get(string $key, string $default = ''): string
    {
        return Cache::remember("school_setting_{$key}", 3600, function () use ($key, $default) {
            return static::where('key', $key)->first()?->value ?? $default;
        });
    }

    /**
     * Set / update nilai setting.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("school_setting_{$key}");
    }

    /**
     * URL publik logo sekolah.
     */
    public static function logoUrl(): ?string
    {
        $path = static::get('logo_path');
        if (!$path) return null;
        return asset('storage/' . $path);
    }

    /**
     * URL publik favicon.
     */
    public static function faviconUrl(): ?string
    {
        $path = static::get('favicon_path');
        if (!$path) return null;
        return asset('storage/' . $path);
    }
}


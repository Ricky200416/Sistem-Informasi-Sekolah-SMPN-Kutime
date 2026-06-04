<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PageContent extends Model
{
    protected $fillable = [
        'section',
        'key',
        'value',
        'value_2',
        'hero_media_tipe',
        'hero_media_files',
        'hero_media_link',
        'hero_slide_interval',
        'sambutan_foto',
        'kontak_telepon',
        'kontak_email',
        'kontak_alamat',
        'kontak_maps_embed',
        'sosmed_instagram',
        'sosmed_facebook',
        'sosmed_youtube',
        'sosmed_twitter',
        'stat_siswa',
        'stat_guru',
        'stat_prestasi',
        'stat_ekskul',
    ];

    protected $casts = [
        'hero_slide_interval' => 'integer',
    ];

    // ── Static helpers ──────────────────────────────────────────

    public static function getValue(string $key, string $default = ''): string
    {
        $record = static::where('key', $key)->first();
        return $record?->value ?? $default;
    }

    public static function getHeroMedia(): ?self
    {
        return static::where('key', 'hero_media_settings')->first();
    }

    /**
     * Ambil record kontak (row dengan key = 'kontak_settings').
     */
    public static function getKontak(): ?self
    {
        return static::where('key', 'kontak_settings')->first();
    }

    /**
     * Ambil record statistik sekolah.
     */
    public static function getStats(): ?self
    {
        return static::where('key', 'stats_settings')->first();
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getHeroFilesArrayAttribute(): array
    {
        if (empty($this->hero_media_files)) return [];
        $decoded = json_decode($this->hero_media_files, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getHeroFilesUrlsAttribute(): array
    {
        return array_map(fn($path) => asset('storage/' . $path), $this->heroFilesArray);
    }

    public function getHeroYoutubeEmbedAttribute(): ?string
    {
        if ($this->hero_media_tipe !== 'youtube' || !$this->hero_media_link) return null;

        $patterns = [
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/[?&]v=([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
        ];
        foreach ($patterns as $p) {
            if (preg_match($p, $this->hero_media_link, $m)) {
                return "https://www.youtube.com/embed/{$m[1]}?autoplay=1&mute=1&loop=1&playlist={$m[1]}&controls=0&rel=0&modestbranding=1";
            }
        }
        return null;
    }

    public function getSambutanFotoUrlAttribute(): ?string
    {
        if (empty($this->sambutan_foto)) return null;
        return asset('storage/' . $this->sambutan_foto);
    }

    // ── Mutation helper ───────────────────────────────────────

    public function setHeroFilesArray(array $paths): void
    {
        $this->hero_media_files = json_encode(array_values($paths));
    }
}
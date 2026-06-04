<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'tipe',
        'file_path', 'link_url', 'thumbnail',
        'kategori', 'status', 'urutan', 'user_id',
    ];

    // ── Relasi ──────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeTerurut($query)
    {
        return $query->orderBy('urutan')->orderBy('created_at', 'desc');
    }

    // ── Accessors ───────────────────────────────────────────────

    /** URL thumbnail untuk ditampilkan di grid */
    public function getThumbnailUrlAttribute(): string
    {
        // Thumbnail manual (upload)
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        // Auto thumbnail dari YouTube
        if ($this->tipe === 'link_youtube' && $this->link_url) {
            $id = $this->getYoutubeId();
            if ($id) {
                return "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
            }
        }
        // File foto langsung
        if ($this->tipe === 'photo' && $this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        // Fallback placeholder
        return asset('images/placeholder-media.jpg');
    }

    /** URL file upload langsung */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /** Embed URL untuk iframe YouTube */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->tipe === 'link_youtube') {
            $id = $this->getYoutubeId();
            return $id ? "https://www.youtube.com/embed/{$id}?autoplay=1&rel=0" : null;
        }
        if ($this->tipe === 'link_facebook') {
            return "https://www.facebook.com/plugins/video.php?href=" . urlencode($this->link_url) . "&autoplay=true";
        }
        return null;
    }

    /** Apakah item ini adalah video (upload maupun link) */
    public function getIsVideoAttribute(): bool
    {
        return in_array($this->tipe, ['video', 'link_youtube', 'link_facebook']);
    }

    /** Label tipe untuk UI */
    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'photo'          => '🖼️ Foto',
            'video'          => '🎥 Video',
            'link_youtube'   => '▶️ YouTube',
            'link_facebook'  => '📘 Facebook',
            default          => 'Media',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>',
            'draf'  => '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">Draf</span>',
            default => '',
        };
    }

    // ── Helpers ─────────────────────────────────────────────────

    /** Ekstrak YouTube video ID dari berbagai format URL */
    public function getYoutubeId(): ?string
    {
        if (!$this->link_url) return null;

        $patterns = [
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->link_url, $m)) {
                return $m[1];
            }
        }
        return null;
    }

    // ── Statics ──────────────────────────────────────────────────
    public static function kategoriOptions(): array
    {
        return [
            'kegiatan'  => 'Kegiatan Sekolah',
            'akademik'  => 'Akademik',
            'prestasi'  => 'Prestasi',
            'lainnya'   => 'Lainnya',
        ];
    }

    public static function tipeOptions(): array
    {
        return [
            'photo'         => '🖼️ Upload Foto',
            'video'         => '🎥 Upload Video',
            'link_youtube'  => '▶️ Link YouTube',
            'link_facebook' => '📘 Link Facebook',
        ];
    }
}
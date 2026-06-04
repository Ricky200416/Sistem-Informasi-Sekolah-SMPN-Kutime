<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'slug', 'ringkasan', 'isi',
        'media_tipe', 'media_file', 'media_link', 'media_thumbnail',
        'kategori', 'is_penting', 'status', 'tanggal_publish', 'user_id',
    ];

    protected $casts = [
        'is_penting'      => 'boolean',
        'tanggal_publish' => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
                     ->whereNotNull('tanggal_publish')
                     ->where('tanggal_publish', '<=', now());
    }

    public function scopePenting($query)    { return $query->where('is_penting', true); }
    public function scopeBerita($query)     { return $query->where('kategori', 'berita'); }
    public function scopePengumuman($query) { return $query->where('kategori', 'pengumuman'); }

    // ── Accessors ────────────────────────────────────────────────

    /**
     * URL file yang diupload (foto atau video).
     * Mengembalikan null jika tidak ada file.
     */
    public function getMediaFileUrlAttribute(): ?string
    {
        if (!$this->media_file) return null;
        return asset('storage/' . $this->media_file);
    }

    /**
     * Thumbnail untuk ditampilkan di grid/card.
     * Urutan prioritas:
     * 1. Thumbnail kustom yang diupload
     * 2. Auto-thumbnail YouTube dari video ID
     * 3. Foto itu sendiri jika tipe photo
     * 4. Null (tidak ada thumbnail)
     */
    public function getMediaThumbnailUrlAttribute(): ?string
    {
        // 1. Thumbnail kustom
        if ($this->media_thumbnail) {
            return asset('storage/' . $this->media_thumbnail);
        }

        // 2. Auto-thumbnail YouTube
        if ($this->media_tipe === 'link_youtube') {
            $id = $this->youtubeId;
            if ($id) {
                return "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
            }
        }

        // 3. Foto upload langsung
        if ($this->media_tipe === 'photo' && $this->media_file) {
            return asset('storage/' . $this->media_file);
        }

        return null;
    }

    /**
     * URL embed untuk iframe YouTube / Facebook.
     * Mengembalikan null jika bukan tipe link atau link tidak valid.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->media_tipe === 'link_youtube') {
            $id = $this->youtubeId;
            if ($id) {
                return "https://www.youtube.com/embed/{$id}?rel=0&modestbranding=1";
            }
        }

        if ($this->media_tipe === 'link_facebook' && $this->media_link) {
            return "https://www.facebook.com/plugins/video.php?href="
                . urlencode($this->media_link)
                . "&show_text=false&width=734";
        }

        return null;
    }

    /**
     * Apakah berita ini memiliki media (bukan 'none' dan bukan null).
     */
    public function getHasMediaAttribute(): bool
    {
        return !empty($this->media_tipe)
            && $this->media_tipe !== 'none';
    }

    /**
     * Apakah media ini berupa video (upload atau link).
     */
    public function getIsVideoAttribute(): bool
    {
        return in_array($this->media_tipe, ['video', 'link_youtube', 'link_facebook']);
    }

    /**
     * Label tipe media untuk badge UI.
     */
    public function getMediaTipeLabelAttribute(): string
    {
        return match ($this->media_tipe) {
            'photo'          => '🖼️ Foto',
            'video'          => '🎥 Video',
            'link_youtube'   => '▶️ YouTube',
            'link_facebook'  => '📘 Facebook',
            default          => '',
        };
    }

    /**
     * HTML badge status untuk tabel admin.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>',
            'draf'  => '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">Draf</span>',
            default => '',
        };
    }

    // ── Helpers ──────────────────────────────────────────────────

    /**
     * Ekstrak YouTube video ID dari berbagai format URL.
     * Diakses sebagai property: $berita->youtubeId
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->media_link) return null;

        $patterns = [
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/[?&]v=([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->media_link, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    // ── Boot ─────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Berita $b) {
            if (empty($b->slug)) {
                $b->slug = Str::slug($b->judul);
            }
            if (empty($b->ringkasan) && !empty($b->isi)) {
                $b->ringkasan = Str::limit(strip_tags($b->isi), 160);
            }
            // Default media_tipe ke 'none' jika tidak diisi
            if (empty($b->media_tipe)) {
                $b->media_tipe = 'none';
            }
        });

        static::updating(function (Berita $b) {
            if ($b->isDirty('judul')) {
                $b->slug = Str::slug($b->judul);
            }
            if (empty($b->ringkasan) && !empty($b->isi)) {
                $b->ringkasan = Str::limit(strip_tags($b->isi), 160);
            }
        });
    }
}
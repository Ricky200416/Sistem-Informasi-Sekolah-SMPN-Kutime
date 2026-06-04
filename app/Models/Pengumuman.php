<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CATATAN PENTING — fileUrl()
    |--------------------------------------------------------------------------
    | Gunakan asset('storage/...') bukan Storage::url().
    | Storage::url() mengembalikan path relatif (/storage/xxx) yang pada beberapa
    | konfigurasi tidak bekerja sebagai src="..." di <img>.
    | asset() selalu menghasilkan URL absolut dengan domain lengkap.
    |
    | Syarat: php artisan storage:link sudah dijalankan.
    |--------------------------------------------------------------------------
    */

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'target_audience',
        'tipe_konten',
        'file_path',
        'file_name',
        'file_mime',
        'link_url',
        'link_label',
        'is_active',
        'show_di_dashboard',
        'tanggal_mulai',
        'tanggal_selesai',
        'created_by',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'show_di_dashboard' => 'boolean',
        'tanggal_mulai'     => 'datetime',
        'tanggal_selesai'   => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now());
            });
    }

    public function scopeUntuk($query, string $role)
    {
        if ($role === 'semua') {
            return $query;
        }
        return $query->whereIn('target_audience', [$role, 'semua']);
    }

    public function scopeDashboard($query)
    {
        return $query->where('show_di_dashboard', 1);
    }

    // ─── Static Helper untuk Widget Dashboard ─────────────────────────

    public static function untukDashboard(string $role, int $limit = 4)
    {
        return static::where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now());
            })
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', [$role, 'semua'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    // ─── Helpers ──────────────────────────────────────────────────────

    /**
     * URL publik file — selalu menggunakan asset() untuk URL absolut.
     */
    public function fileUrl(): ?string
    {
        if (! $this->file_path) {
            return null;
        }
        return asset('storage/' . $this->file_path);
    }

    /**
     * Ekstensi file dalam huruf kapital.
     */
    public function fileExtension(): string
    {
        if ($this->file_mime) {
            return match (true) {
                str_contains($this->file_mime, 'pdf')        => 'PDF',
                str_contains($this->file_mime, 'word')       => 'DOC',
                str_contains($this->file_mime, 'presentati') => 'PPT',
                str_contains($this->file_mime, 'excel')      => 'XLS',
                str_contains($this->file_mime, 'sheet')      => 'XLS',
                str_contains($this->file_mime, 'jpeg'),
                str_contains($this->file_mime, 'jpg')        => 'JPG',
                str_contains($this->file_mime, 'png')        => 'PNG',
                str_contains($this->file_mime, 'gif')        => 'GIF',
                str_contains($this->file_mime, 'image')      => 'IMG',
                str_contains($this->file_mime, 'zip')        => 'ZIP',
                str_contains($this->file_mime, 'text')       => 'TXT',
                default => strtoupper(pathinfo($this->file_name ?? '', PATHINFO_EXTENSION)),
            };
        }
        if ($this->file_name) {
            return strtoupper(pathinfo($this->file_name, PATHINFO_EXTENSION));
        }
        return '';
    }

    public function audienceLabel(): string
    {
        return match ($this->target_audience) {
            'guru'  => 'Guru',
            'siswa' => 'Siswa',
            default => 'Semua',
        };
    }

    public function audienceBadgeColor(): string
    {
        return match ($this->target_audience) {
            'guru'  => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300',
            'siswa' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
            default => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
        };
    }

    public function tipeIcon(): string
    {
        return match ($this->tipe_konten) {
            'gambar'  => '🖼️',
            'dokumen' => '📄',
            'link'    => '🔗',
            default   => '📝',
        };
    }
}
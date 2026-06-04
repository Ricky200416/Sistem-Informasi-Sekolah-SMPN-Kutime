<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Absensi — mencatat kehadiran siswa harian.
 *
 * ⚠ PENTING: nama tabel harus sesuai dengan yang ada di database.
 *
 * Cek dengan: php artisan tinker → Schema::hasTable('absensi')
 *                                 → Schema::hasTable('absensis')
 *
 * Jika tabel bernama 'absensi'  → protected $table = 'absensi';   (default di bawah)
 * Jika tabel bernama 'absensis' → protected $table = 'absensis';
 * Jika tabel bernama 'absensi_siswas' → ganti sesuai
 */
class AbsensiSiswa extends Model
{
    use HasFactory;

    /*
    |----------------------------------------------------------------------
    | Nama tabel — sesuaikan dengan migration yang sudah dijalankan.
    |
    | Dari migration yang kamu buat: Schema::create('absensi_siswas', ...)
    | → nama tabel = 'absensi_siswas'
    |----------------------------------------------------------------------
    */
    protected $table = 'absensi_siswas';

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'tanggal',
        'hari',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ── Relasi ────────────────────────────────────────────────────────────

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePadaTanggal($query, string $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    public function scopeBulanIni($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)
                     ->whereYear('tanggal', $tahun);
    }

    public function scopeUntukSiswa($query, int $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeKelas($query, int $kelasId)
    {
        return $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId));
    }

    // ── Accessor ──────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin'  => 'Izin',
            'alpha' => 'Alpha',
            default => ucfirst($this->status ?? ''),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'success',
            'sakit' => 'warning',
            'izin'  => 'info',
            'alpha' => 'danger',
            default => 'secondary',
        };
    }

    public function getIsHadirAttribute(): bool
    {
        return $this->status === 'hadir';
    }
}
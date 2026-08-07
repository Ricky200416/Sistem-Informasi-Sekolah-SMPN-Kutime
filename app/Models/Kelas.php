<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    /**
     * WAJIB: 'id' harus ada di fillable
     * supaya forceFill / updateOrCreate bisa memaksa ID yang sama
     * dengan study_groups.id
     */
    protected $fillable = [
        'id',               // ← PENTING! Jangan dihapus
        'nama',
        'tingkat',
        'rombel',
        'tahun_ajaran',
        'semester',
        'guru_id',          // FK ke tabel gurus (bukan users)
        'ruang',
        'kapasitas',
    ];

    protected $casts = [
        'semester'  => 'integer',
        'kapasitas' => 'integer',
    ];

    /**
     * Wali kelas → relasi ke tabel gurus
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Siswa-siswa di kelas ini
     */
    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'tingkat',
        'rombel',
        'tahun_ajaran',
        'guru_id',
        'semester',       // ← semester (1 atau 2)
        'guru_id',        // ← wali kelas (FK ke users)
        'ruang', 
        'kapasitas',
    ];

    protected $casts = [
        'semester'  => 'integer',
        'kapasitas' => 'integer',
    ];

    /** Wali kelas → relasi ke tabel gurus */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /** Siswa-siswa di kelas ini */
    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
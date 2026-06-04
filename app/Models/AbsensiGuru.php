<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $table = 'absensi_gurus';

    protected $fillable = [
        'guru_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ── Relasi: absensi milik seorang guru ──────────────────────────
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
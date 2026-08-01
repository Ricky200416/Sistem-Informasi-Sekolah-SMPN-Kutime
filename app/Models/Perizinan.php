<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    protected $fillable = [
        'guru_id',
        'nama',
        'jabatan',
        'no_hp',
        'tujuan',
        'alasan',
        'lama_izin',
        'tanggal_izin',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_izin' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
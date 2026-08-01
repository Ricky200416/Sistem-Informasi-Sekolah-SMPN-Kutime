<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'nidn',
        'kelas_id',
        // Data diri lengkap
        'nik',
        'nama',
        'jk',
        'tempat_lahir',
        'tgl_lahir',
        'agama',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kecamatan',
        'kode_pos',
        'jenis_tinggal',
        'jalan_transportasi',
        'no_telp',
        'shkun',
        'penerima_kps',
        'no_kps',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(AbsensiSiswa::class, 'siswa_id', 'id');
    }
    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class, 'study_group_id');
    }

    public function alumni()
{
    return $this->hasOne(\App\Models\Alumni::class);
}

public function scopeAktif($query)
{
    return $query->where('status', 'aktif');
}
}
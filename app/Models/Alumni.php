<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'siswa_id', 'user_id', 'nama', 'email', 'nidn', 'nik', 'jk', 'agama',
        'tempat_lahir', 'tgl_lahir', 'no_telp', 'alamat', 'rt', 'rw', 'dusun',
        'kecamatan', 'kode_pos', 'jenis_tinggal', 'jalan_transportasi',
        'penerima_kps', 'no_kps', 'foto', 'kelas_terakhir', 'tahun_lulus',
        'tanggal_lulus', 'no_ijazah', 'catatan',
    ];

    protected $casts = [
        'tgl_lahir'     => 'date',
        'tanggal_lulus' => 'date',
        'penerima_kps'  => 'boolean',
    ];

    /** Relasi ke data siswa asal (bisa null jika siswa sudah dihapus) */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /** Relasi ke akun user asal (bisa null jika akun sudah dihapus) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getJkLabelAttribute(): string
    {
        return $this->jk === 'L' ? 'Laki-laki' : ($this->jk === 'P' ? 'Perempuan' : '—');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }

    /** Scope: filter berdasarkan tahun lulus */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_lulus', $tahun);
    }

    /** Scope: pencarian nama / NISN / NIK / no ijazah */
    public function scopeCari($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('nama', 'like', "%{$term}%")
              ->orWhere('nidn', 'like', "%{$term}%")
              ->orWhere('nik', 'like', "%{$term}%")
              ->orWhere('no_ijazah', 'like', "%{$term}%");
        });
    }
}
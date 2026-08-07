<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    /**
     * Jumlah hari admin diberi waktu untuk mengedit data kelulusan
     * setelah proses "Luluskan Siswa" dijalankan. Setelah lewat dari
     * batas ini, data terkunci dan hanya bisa dilihat.
     */
    public const EDIT_WINDOW_DAYS = 2;

    protected $fillable = [
        'siswa_id',
        'user_id',
        'password_snapshot',
        'kelas_id_snapshot',
        'nama',
        'email',
        'nidn',
        'nik',
        'jk',
        'agama',
        'tempat_lahir',
        'tgl_lahir',
        'no_telp',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kecamatan',
        'kode_pos',
        'jenis_tinggal',
        'jalan_transportasi',
        'penerima_kps',
        'no_kps',
        'foto',
        'kelas_terakhir',
        'tahun_lulus',
        'tanggal_lulus',
        'no_ijazah',
        'catatan',
    ];

    protected $casts = [
        'tgl_lahir'     => 'date',
        'tanggal_lulus' => 'date',
        'created_at'    => 'datetime',
    ];

    /**
     * Sembunyikan snapshot password dari output JSON (dipakai modal
     * detail/edit) — data ini murni internal untuk fitur "Batalkan".
     */
    protected $hidden = [
        'password_snapshot',
    ];

    // ── Relasi (opsional — bisa null setelah siswa/user dihapus permanen) ──

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Accessors ───────────────────────────────────────────────────

    public function getJkLabelAttribute(): string
    {
        return match ($this->jk) {
            'L'     => 'Laki-laki',
            'P'     => 'Perempuan',
            default => '-',
        };
    }

    /**
     * Batas waktu terakhir data ini masih boleh diedit admin.
     */
    public function getEditDeadlineAttribute(): Carbon
    {
        return $this->created_at->copy()->addDays(self::EDIT_WINDOW_DAYS);
    }

    /**
     * Apakah data kelulusan ini masih dalam window edit (2 hari sejak
     * diproses). Setelah lewat, admin hanya bisa melihat detail.
     */
    public function getIsEditableAttribute(): bool
    {
        return now()->lessThanOrEqualTo($this->edit_deadline);
    }

    /**
     * Sisa waktu yang bisa dibaca manusia, untuk ditampilkan di UI
     * ("Bisa diedit selama 1 hari 4 jam lagi", dsb).
     */
    public function getEditTimeLeftLabelAttribute(): ?string
    {
        if (!$this->is_editable) {
            return null;
        }

        return now()->diffForHumans($this->edit_deadline, [
            'parts'  => 2,
            'syntax' => Carbon::DIFF_ABSOLUTE,
        ]);
    }

    // ── Scopes (dipertahankan dari implementasi sebelumnya) ───────────

    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        if (!$keyword) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', "%{$keyword}%")
              ->orWhere('nidn', 'like', "%{$keyword}%")
              ->orWhere('nik', 'like', "%{$keyword}%")
              ->orWhere('no_ijazah', 'like', "%{$keyword}%");
        });
    }

    public function scopeTahun(Builder $query, $tahun): Builder
    {
        return $query->where('tahun_lulus', $tahun);
    }
}
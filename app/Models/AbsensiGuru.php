<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $table = 'absensi_gurus';

    /**
     * Status yang valid untuk kolom `status`.
     * P = Hadir, A = Alpha, S = Sakit, I = Izin, L = Terlambat, W = WFH
     */
    public const STATUS_HADIR   = 'P';
    public const STATUS_ALPHA   = 'A';
    public const STATUS_SAKIT   = 'S';
    public const STATUS_IZIN    = 'I';
    public const STATUS_TELAT   = 'L';
    public const STATUS_WFH     = 'W';

    /**
     * Tipe absensi untuk absensi berbasis foto.
     */
    public const TIPE_MENGAJAR = 'mengajar';
    public const TIPE_KANTOR   = 'kantor';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'tanggal',
        'status',
        'keterangan',
        'foto_masuk',
        'foto_pulang',
        'jam_masuk',
        'jam_pulang',
        'tipe_absensi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Nilai default agar absensi baru (tanpa foto, absensi manual dari admin
     * lama / import) tetap konsisten.
     */
    protected $attributes = [
        'status' => self::STATUS_HADIR,
    ];

    // ── Relasi ──────────────────────────────────────────────────────

    /**
     * Absensi ini milik satu guru.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Kelas yang diajar guru pada absensi ini (hanya relevan untuk
     * tipe_absensi = 'mengajar'). Nullable — absensi kantor tidak
     * punya kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // ── Accessors ───────────────────────────────────────────────────

    /**
     * Apakah absensi ini punya bukti foto (dari halaman Absensi Foto guru),
     * berbeda dengan absensi manual yang diisi lewat cara lain (mis. import).
     */
    public function getHasFotoAttribute(): bool
    {
        return !empty($this->foto_masuk);
    }

    /**
     * Apakah absensi mengajar ini sudah lengkap (foto masuk + foto pulang).
     * Untuk tipe kantor, dianggap lengkap begitu foto_masuk terisi.
     */
    public function getLengkapAttribute(): bool
    {
        if ($this->tipe_absensi === self::TIPE_KANTOR) {
            return !empty($this->foto_masuk);
        }

        return !empty($this->foto_masuk) && !empty($this->foto_pulang);
    }

    /**
     * Label tipe absensi yang enak dibaca di UI.
     */
    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe_absensi) {
            self::TIPE_KANTOR   => 'Absensi Kantor',
            self::TIPE_MENGAJAR => 'Mengajar',
            default             => '—',
        };
    }

    /**
     * Label status huruf tunggal → nama lengkap.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_ALPHA => 'Alpha',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_IZIN  => 'Izin',
            self::STATUS_TELAT => 'Terlambat',
            self::STATUS_WFH   => 'WFH',
            default            => '—',
        };
    }

    // ── Scopes ──────────────────────────────────────────────────────

    /**
     * Scope: absensi pada tanggal tertentu (default hari ini).
     */
    public function scopeTanggal(Builder $query, ?string $tanggal = null): Builder
    {
        return $query->whereDate('tanggal', $tanggal ?? now()->toDateString());
    }

    /**
     * Scope: absensi milik guru tertentu.
     */
    public function scopeMilikGuru(Builder $query, int $guruId): Builder
    {
        return $query->where('guru_id', $guruId);
    }

    /**
     * Scope: hanya absensi yang punya bukti foto.
     */
    public function scopeDenganFoto(Builder $query): Builder
    {
        return $query->whereNotNull('foto_masuk');
    }

    /**
     * Scope: absensi pada bulan & tahun tertentu.
     */
    public function scopePeriode(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    // ── Helper statis ───────────────────────────────────────────────

    /**
     * Cek apakah guru sudah melakukan absensi (apapun jenisnya) pada
     * tanggal tertentu. Dipakai sebagai guard sebelum insert baru,
     * supaya guru tidak bisa absen dua kali dalam satu hari.
     */
    public static function sudahAbsen(int $guruId, ?string $tanggal = null): bool
    {
        return static::milikGuru($guruId)->tanggal($tanggal)->exists();
    }
}
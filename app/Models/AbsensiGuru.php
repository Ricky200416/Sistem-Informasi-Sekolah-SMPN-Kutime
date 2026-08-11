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
    public const STATUS_HADIR = 'P';
    public const STATUS_ALPHA = 'A';
    public const STATUS_SAKIT = 'S';
    public const STATUS_IZIN  = 'I';
    public const STATUS_TELAT = 'L';
    public const STATUS_WFH   = 'W';

    /**
     * Tipe absensi untuk absensi berbasis foto.
     */
    public const TIPE_MENGAJAR = 'mengajar';
    public const TIPE_KANTOR   = 'kantor';

    protected $fillable = [
        'guru_id',
        'timetable_id',
        'kelas_id',
        'tanggal',
        'status',
        'keterangan',
        'foto_masuk',
        'foto_pulang',
        'jam_masuk',
        'jam_pulang',
        'tipe_absensi',

        // ── Lokasi GPS saat absen masuk ──
        'latitude_masuk',
        'longitude_masuk',
        'jarak_masuk',
        'lokasi_valid_masuk',

        // ── Lokasi GPS saat absen pulang ──
        'latitude_pulang',
        'longitude_pulang',
        'jarak_pulang',
        'lokasi_valid_pulang',

        // ── Keterlambatan ──
        'keterlambatan_menit',
    ];

    protected $casts = [
        'tanggal'             => 'date',
        'lokasi_valid_masuk'  => 'boolean',
        'lokasi_valid_pulang' => 'boolean',
        'latitude_masuk'      => 'decimal:7',
        'longitude_masuk'     => 'decimal:7',
        'latitude_pulang'     => 'decimal:7',
        'longitude_pulang'    => 'decimal:7',
    ];

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
     * Sesi jadwal mengajar yang menjadi dasar absensi ini (khusus
     * tipe_absensi = 'mengajar'). Nullable — absensi kantor tidak
     * terhubung ke jadwal manapun.
     */
    public function timetable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id');
    }

    /**
     * @deprecated Sejak absensi dihubungkan ke Jadwal Mengajar, kelas
     * diambil dari relasi timetable()->studyGroup. Relasi ini dibiarkan
     * untuk kompatibilitas data lama.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // ── Accessors ───────────────────────────────────────────────────

    public function getHasFotoAttribute(): bool
    {
        return !empty($this->foto_masuk);
    }

    public function getLengkapAttribute(): bool
    {
        if ($this->tipe_absensi === self::TIPE_KANTOR) {
            return !empty($this->foto_masuk);
        }

        return !empty($this->foto_masuk) && !empty($this->foto_pulang);
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe_absensi) {
            self::TIPE_KANTOR   => 'Absensi Kantor',
            self::TIPE_MENGAJAR => 'Mengajar',
            default             => '—',
        };
    }

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

    /**
     * Nama mata pelajaran yang diajar pada absensi ini, diambil dari
     * jadwal (timetable) yang dipilih guru saat absen masuk.
     */
    public function getMapelNamaAttribute(): ?string
    {
        return $this->timetable?->studySubject?->name;
    }

    /**
     * Nama kelas yang diajar pada absensi ini, diambil dari jadwal.
     */
    public function getKelasNamaAttribute(): ?string
    {
        return $this->timetable?->studyGroup?->name;
    }

    /**
     * Label ringkas lokasi saat absen masuk, untuk ditampilkan di
     * tabel/modal admin. Contoh: "Valid · 38m" atau "Di luar area · 1.250m".
     */
    public function getLokasiMasukLabelAttribute(): ?string
    {
        if ($this->jarak_masuk === null) {
            return null;
        }

        $status = $this->lokasi_valid_masuk ? 'Valid' : 'Di luar area';

        return "{$status} · {$this->jarak_masuk}m";
    }

    /**
     * Label ringkas lokasi saat absen pulang.
     */
    public function getLokasiPulangLabelAttribute(): ?string
    {
        if ($this->jarak_pulang === null) {
            return null;
        }

        $status = $this->lokasi_valid_pulang ? 'Valid' : 'Di luar area';

        return "{$status} · {$this->jarak_pulang}m";
    }

    // ── Scopes ──────────────────────────────────────────────────────

    public function scopeTanggal(Builder $query, ?string $tanggal = null): Builder
    {
        return $query->whereDate('tanggal', $tanggal ?? now()->toDateString());
    }

    public function scopeMilikGuru(Builder $query, int $guruId): Builder
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeDenganFoto(Builder $query): Builder
    {
        return $query->whereNotNull('foto_masuk');
    }

    public function scopePeriode(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    /**
     * Filter absensi dengan status terlambat.
     */
    public function scopeTerlambat(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TELAT);
    }

    /**
     * Filter absensi yang lokasinya tidak valid (di luar radius sekolah)
     * saat absen masuk maupun pulang.
     */
    public function scopeLokasiTidakValid(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->where('lokasi_valid_masuk', false)
              ->orWhere('lokasi_valid_pulang', false);
        });
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
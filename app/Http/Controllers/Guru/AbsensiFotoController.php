<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\SchoolSetting;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class AbsensiFotoController extends Controller
{
    /** Batas maksimal jarak dari sekolah (meter) jika school_settings belum diisi. */
    private const DEFAULT_RADIUS_METER = 100;

    /** Toleransi keterlambatan (menit) jika school_settings belum diisi. */
    private const DEFAULT_TOLERANSI_MENIT = 15;

    private array $mapHari = [
        0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
        4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
    ];

    /* ============================================================
     * HALAMAN UTAMA "ABSENSI SAYA"
     * ============================================================ */
    public function index()
    {
        $guru   = Auth::user()->guru;
        $guruId = $guru->id;
        $userId = Auth::id();

        $today       = Carbon::today();
        $hariIniNama = $this->mapHari[$today->dayOfWeek];

        $absensiHariIni = AbsensiGuru::with(['timetable.studySubject', 'timetable.studyGroup'])
            ->where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayat = AbsensiGuru::with(['timetable.studySubject', 'timetable.studyGroup'])
            ->where('guru_id', $guruId)
            ->whereNotNull('foto_masuk')
            ->orderByDesc('tanggal')
            ->limit(14)
            ->get();

        $jadwalHariIni = Timetable::with(['studySubject', 'studyGroup'])
            ->where('teacher_id', $userId)
            ->where('is_active', true)
            ->where('day_of_week', $hariIniNama)
            ->orderBy('start_time')
            ->get();

        $lokasiSekolah = $this->lokasiSekolah();

        return view('guru.absensi-foto.index', compact(
            'absensiHariIni', 'riwayat', 'jadwalHariIni', 'hariIniNama', 'lokasiSekolah'
        ));
    }

    /* ============================================================
     * ABSEN MASUK (mengajar ATAU kantor) — via wizard AJAX
     * ============================================================ */
    public function storeMasuk(Request $request): JsonResponse
    {
        $guru   = Auth::user()->guru;
        $guruId = $guru->id;
        $userId = Auth::id();

        $today       = Carbon::today();
        $hariIniNama = $this->mapHari[$today->dayOfWeek];

        if ($this->sudahAbsenHariIni($guruId, $today->toDateString())) {
            return response()->json([
                'ok'      => false,
                'message' => 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.',
            ], 422);
        }

        $request->validate([
            'foto'         => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'tipe'         => 'required|in:mengajar,kantor',
            'timetable_id' => 'nullable|required_if:tipe,mengajar|integer|exists:timetables,id',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'accuracy'     => 'nullable|numeric',
        ], [
            'foto.required'              => 'Foto wajib diambil sebelum absen.',
            'timetable_id.required_if'   => 'Pilih jadwal kelas yang akan Anda ajar.',
            'timetable_id.exists'        => 'Jadwal yang dipilih tidak ditemukan.',
            'latitude.required'          => 'Lokasi Anda tidak terdeteksi. Aktifkan GPS lalu coba lagi.',
            'longitude.required'         => 'Lokasi Anda tidak terdeteksi. Aktifkan GPS lalu coba lagi.',
        ]);

        // ── Validasi lokasi ──────────────────────────────────
        $cekLokasi = $this->validasiLokasi((float) $request->latitude, (float) $request->longitude);
        if (!$cekLokasi['valid']) {
            return response()->json([
                'ok'      => false,
                'message' => "Anda berada di luar area sekolah (jarak {$cekLokasi['jarak']} meter, batas {$cekLokasi['radius']} meter).",
                'jarak'   => $cekLokasi['jarak'],
                'radius'  => $cekLokasi['radius'],
            ], 422);
        }

        $jadwal    = null;
        $namaKelas = null;
        $namaMapel = null;
        $tipe      = $request->tipe;

        if ($tipe === AbsensiGuru::TIPE_MENGAJAR) {
            $jadwal = Timetable::where('id', $request->timetable_id)
                ->where('teacher_id', $userId)
                ->where('is_active', true)
                ->where('day_of_week', $hariIniNama)
                ->first();

            if (!$jadwal) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Jadwal yang dipilih tidak valid atau bukan jadwal Anda hari ini.',
                ], 422);
            }

            $namaKelas = $jadwal->studyGroup->name ?? '-';
            $namaMapel = $jadwal->studySubject->name ?? '-';
        }

        $path = $request->file('foto')->store('absensi-guru/masuk', 'public');
        $now  = now();

        // ── Deteksi status Hadir / Terlambat otomatis ───────────
        $status            = AbsensiGuru::STATUS_HADIR;
        $keterlambatanMenit = null;

        if ($jadwal) {
            $batasHadir = Carbon::parse($jadwal->start_time)
                ->addMinutes($this->toleransiMenit());

            if ($now->format('H:i:s') > $batasHadir->format('H:i:s')) {
                $status             = AbsensiGuru::STATUS_TERLAMBAT;
                $keterlambatanMenit = Carbon::parse($jadwal->start_time)->diffInMinutes($now);
            }
        }

        try {
            AbsensiGuru::create([
                'guru_id'             => $guruId,
                'timetable_id'        => $jadwal?->id,
                'tanggal'             => $today->toDateString(),
                'status'              => $status,
                'keterlambatan_menit' => $keterlambatanMenit,
                'foto_masuk'          => $path,
                'jam_masuk'           => $now->format('H:i:s'),
                'tipe_absensi'        => $tipe,
                'latitude_masuk'      => $request->latitude,
                'longitude_masuk'     => $request->longitude,
                'jarak_masuk'         => $cekLokasi['jarak'],
                'lokasi_valid_masuk'  => true,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'Anda sudah melakukan absensi hari ini. Absensi hanya dapat dilakukan satu kali per hari.',
            ], 422);
        }

        return response()->json([
            'ok'      => true,
            'message' => $tipe === AbsensiGuru::TIPE_MENGAJAR
                ? "Absensi masuk mengajar {$namaMapel} di kelas {$namaKelas} berhasil disimpan."
                : 'Absensi kehadiran di kantor berhasil disimpan.',
            'data' => [
                'jam_masuk' => $now->format('H:i'),
                'status'    => $status,
                'jarak'     => $cekLokasi['jarak'],
            ],
        ]);
    }

    /* ============================================================
     * ABSEN PULANG — hanya untuk tipe 'mengajar'
     * ============================================================ */
    public function storePulang(Request $request): JsonResponse
    {
        $guruId = Auth::user()->guru->id;
        $today  = Carbon::today()->toDateString();

        $absensi = AbsensiGuru::where('guru_id', $guruId)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi || $absensi->tipe_absensi !== AbsensiGuru::TIPE_MENGAJAR || !$absensi->foto_masuk) {
            return response()->json([
                'ok' => false, 'message' => 'Anda harus melakukan absen masuk (mengajar) terlebih dahulu.',
            ], 422);
        }

        if ($absensi->foto_pulang) {
            return response()->json([
                'ok' => false, 'message' => 'Anda sudah mengisi absensi pulang hari ini.',
            ], 422);
        }

        $request->validate([
            'foto'      => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ], [
            'foto.required' => 'Foto wajib diambil setelah selesai mengajar.',
        ]);

        $cekLokasi = $this->validasiLokasi((float) $request->latitude, (float) $request->longitude);
        if (!$cekLokasi['valid']) {
            return response()->json([
                'ok'      => false,
                'message' => "Anda berada di luar area sekolah (jarak {$cekLokasi['jarak']} meter, batas {$cekLokasi['radius']} meter).",
            ], 422);
        }

        $path = $request->file('foto')->store('absensi-guru/pulang', 'public');

        $absensi->update([
            'foto_pulang'         => $path,
            'jam_pulang'          => now()->format('H:i:s'),
            'latitude_pulang'     => $request->latitude,
            'longitude_pulang'    => $request->longitude,
            'jarak_pulang'        => $cekLokasi['jarak'],
            'lokasi_valid_pulang' => true,
        ]);

        return response()->json([
            'ok'      => true,
            'message' => 'Absensi pulang berhasil disimpan. Terima kasih!',
            'data'    => ['jam_pulang' => now()->format('H:i')],
        ]);
    }

    /* ============================================================
     * Helper
     * ============================================================ */
    private function sudahAbsenHariIni(int $guruId, string $today): bool
    {
        return AbsensiGuru::sudahAbsen($guruId, $today);
    }

    private function lokasiSekolah(): array
    {
        $setting = SchoolSetting::query()->first();

        return [
            'latitude'  => $setting?->latitude,
            'longitude' => $setting?->longitude,
            'radius'    => $setting?->radius_meter ?? self::DEFAULT_RADIUS_METER,
        ];
    }

    private function toleransiMenit(): int
    {
        $setting = SchoolSetting::query()->first();
        return $setting?->toleransi_telat_menit ?? self::DEFAULT_TOLERANSI_MENIT;
    }

    /**
     * Validasi apakah koordinat guru berada dalam radius sekolah.
     * Mengembalikan ['valid' => bool, 'jarak' => int (meter), 'radius' => int (meter)]
     */
    private function validasiLokasi(float $lat, float $lng): array
    {
        $sekolah = $this->lokasiSekolah();
        $radius  = (int) $sekolah['radius'];

        // Jika titik sekolah belum diatur admin, absensi tetap diloloskan
        // (jarak 0) supaya guru tidak terkunci — tapi tandai agar admin
        // segera mengisi koordinat sekolah di menu Pengaturan.
        if (is_null($sekolah['latitude']) || is_null($sekolah['longitude'])) {
            return ['valid' => true, 'jarak' => 0, 'radius' => $radius];
        }

        $jarak = $this->hitungJarakMeter(
            (float) $sekolah['latitude'],
            (float) $sekolah['longitude'],
            $lat,
            $lng
        );

        return [
            'valid'  => $jarak <= $radius,
            'jarak'  => (int) round($jarak),
            'radius' => $radius,
        ];
    }

    /** Rumus Haversine — jarak antar dua koordinat dalam meter. */
    private function hitungJarakMeter(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
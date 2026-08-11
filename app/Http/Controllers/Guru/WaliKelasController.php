<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\AbsensiSiswa;

class WaliKelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        /* ── Default: guru belum punya data / kelas ── */
        if (!$guru) {
            return view('guru.wali-kelas.index', [
                'kelas' => null,
                'siswa' => collect(),
            ]);
        }

        /* ══════════════════════════════════════════════════════
           KELAS WALI
           FIX UTAMA:
           Sumber kebenaran yang sebenarnya dipakai di seluruh
           aplikasi (lihat admin/users/_table_guru.blade.php)
           adalah StudyGroup.homeroom_teacher_id via relasi
           $user->homeroomGroups(). Field guru->kelas_id / Kelas
           model lama TIDAK PERNAH di-set oleh form "Edit Kelas"
           admin, jadi sebelumnya $kelas selalu null di sini.

           Urutan pencarian:
           1) StudyGroup via $user->homeroomGroups() (SUMBER UTAMA)
           2) Fallback ke sistem Kelas lama (tetap dipertahankan
              untuk kompatibilitas, TIDAK dihapus)
        ══════════════════════════════════════════════════════ */
        $kelas = null;

        try {
            if (method_exists($user, 'homeroomGroups')) {
                $kelas = $user->homeroomGroups()->first();
            }
        } catch (\Exception $e) {
            $kelas = null;
        }

        // ── Fallback ke sistem lama (Kelas) — dipertahankan apa adanya ──
        if (!$kelas) {
            try {
                // Opsi 1: guru punya kelas_id langsung
                if ($guru->kelas_id) {
                    $kelas = $guru->kelas;
                }

                // Opsi 2: relasi waliKelas di model Guru
                if (!$kelas && method_exists($guru, 'waliKelas')) {
                    $wk    = $guru->waliKelas;
                    $kelas = $wk?->kelas ?? $wk ?? null;
                }

                // Opsi 3: method isWaliKelas + cari dari tabel kelas
                if (!$kelas) {
                    $kelas = \App\Models\Kelas::where('wali_guru_id', $guru->id)->first()
                          ?? \App\Models\Kelas::where('wali_kelas_id', $guru->id)->first();
                }
            } catch (\Exception $e) {
                $kelas = null;
            }
        }

        if (!$kelas) {
            return view('guru.wali-kelas.index', [
                'kelas' => null,
                'siswa' => collect(),
            ]);
        }

        /* ══════════════════════════════════════════════════════
           PERIODE
        ══════════════════════════════════════════════════════ */
        $today         = Carbon::today();
        $bulanIni      = Carbon::now()->month;
        $tahunIni      = Carbon::now()->year;

        /* ══════════════════════════════════════════════════════
           AMBIL SISWA + HITUNG STATISTIK
           FIX: tambahkan pengecekan relasi 'students' terlebih
           dahulu (nama relasi khas StudyGroup), sebelum fallback
           ke relasi/nama lama 'siswas' / 'siswa' / query manual.
        ══════════════════════════════════════════════════════ */
        $siswa = collect();

        try {
            if (method_exists($kelas, 'students')) {
                $siswaRaw = $kelas->students()->with('user')->get();
            } elseif (method_exists($kelas, 'siswas')) {
                $siswaRaw = $kelas->siswas()->with('user')->get();
            } elseif (method_exists($kelas, 'siswa')) {
                $siswaRaw = $kelas->siswa()->with('user')->get();
            } else {
                $siswaRaw = \App\Models\Siswa::where('kelas_id', $kelas->id)
                    ->with('user')->get();
            }
        } catch (\Exception $e) {
            $siswaRaw = collect();
        }

        /* ── Absensi hari ini — ambil sekaligus untuk semua siswa ── */
        $siswaIds      = $siswaRaw->pluck('id');
        $absensiToday  = collect();
        $absensiBulan  = collect();

        if ($siswaIds->isNotEmpty()) {
            try {
                // Absensi hari ini
                $absensiToday = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereDate('tanggal', $today)
                    ->get()
                    ->keyBy('siswa_id');

                // Absensi bulan ini — aggregate per siswa
                $absensiBulan = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereMonth('tanggal', $bulanIni)
                    ->whereYear('tanggal', $tahunIni)
                    ->selectRaw("
                        siswa_id,
                        SUM(CASE WHEN status='hadir'     THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN status='sakit'     THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN status='izin'      THEN 1 ELSE 0 END) as izin,
                        SUM(CASE WHEN status='alpha'     THEN 1 ELSE 0 END) as alpha,
                        SUM(CASE WHEN status='terlambat' THEN 1 ELSE 0 END) as terlambat,
                        COUNT(*) as total
                    ")
                    ->groupBy('siswa_id')
                    ->get()
                    ->keyBy('siswa_id');
            } catch (\Exception $e) {
                // AbsensiSiswa mungkin tidak punya kolom terlambat — coba tanpa terlambat
                try {
                    $absensiToday = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                        ->whereDate('tanggal', $today)
                        ->get()
                        ->keyBy('siswa_id');

                    $absensiBulan = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                        ->whereMonth('tanggal', $bulanIni)
                        ->whereYear('tanggal', $tahunIni)
                        ->selectRaw("
                            siswa_id,
                            SUM(CASE WHEN status='hadir' THEN 1 ELSE 0 END) as hadir,
                            SUM(CASE WHEN status='sakit' THEN 1 ELSE 0 END) as sakit,
                            SUM(CASE WHEN status='izin'  THEN 1 ELSE 0 END) as izin,
                            SUM(CASE WHEN status='alpha' THEN 1 ELSE 0 END) as alpha,
                            0 as terlambat,
                            COUNT(*) as total
                        ")
                        ->groupBy('siswa_id')
                        ->get()
                        ->keyBy('siswa_id');
                } catch (\Exception $e2) {}
            }
        }

        /* ── Map siswa dengan statistik lengkap ── */
        $siswa = $siswaRaw->map(function ($s) use ($absensiToday, $absensiBulan) {

            /* ── Nama & tampilan ── */
            $nama    = $s->nama ?? $s->user?->name ?? '—';
            $inisial = strtoupper(mb_substr($nama, 0, 1));
            $foto    = null;

            if ($s->user && $s->user->photo) {
                $foto = str_starts_with($s->user->photo, 'http')
                    ? $s->user->photo
                    : Storage::url($s->user->photo);
            } elseif (!empty($s->foto)) {
                $foto = str_starts_with($s->foto, 'http')
                    ? $s->foto
                    : Storage::url($s->foto);
            }

            /* ── Status hari ini ── */
            $todayRec    = $absensiToday->get($s->id);
            $statusToday = $todayRec?->status ?? null;

            /* ── Statistik bulan ini ── */
            $bulanRec    = $absensiBulan->get($s->id);
            $hadirBln    = (int)($bulanRec?->hadir     ?? 0);
            $sakitBln    = (int)($bulanRec?->sakit     ?? 0);
            $izinBln     = (int)($bulanRec?->izin      ?? 0);
            $alphaBln    = (int)($bulanRec?->alpha      ?? 0);
            $terlambatBln= (int)($bulanRec?->terlambat  ?? 0);
            $totalBln    = (int)($bulanRec?->total      ?? 0);

            // Kehadiran % bulan ini
            $kehadiranPct = $totalBln > 0 ? round($hadirBln / $totalBln * 100) : 0;

            /* ── Set semua field ke model ── */
            $s->nama_tampil            = $nama;
            $s->inisial                = $inisial;
            $s->foto                   = $foto;
            $s->status_today           = $statusToday;
            $s->hadir_bulan            = $hadirBln;
            $s->sakit_bulan            = $sakitBln;
            $s->izin_bulan             = $izinBln;
            $s->alpha_bulan            = $alphaBln;
            $s->terlambat_count        = $terlambatBln;
            $s->total_absensi_bulan    = $totalBln;
            $s->kehadiran_pct          = $kehadiranPct;

            return $s;
        })->sortBy('nama_tampil')->values();

        return view('guru.wali-kelas.index', compact('kelas', 'siswa'));
    }

    /**
     * ── BARU ──────────────────────────────────────────────────────────────
     * Rekap SEMUA mata pelajaran / semua guru yang mengajar di kelas
     * yang diwalikan — HANYA untuk wali kelas. Query TIDAK difilter
     * guru_id, sehingga wali kelas melihat data absensi dari SEMUA guru
     * mata pelajaran yang mengajar di kelas tersebut.
     * ────────────────────────────────────────────────────────────────────
     */
    public function rekapSemuaMapel(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        /* ── Cari kelas yang diwalikan (logika sama seperti index()) ── */
        $kelas = null;

        try {
            if (method_exists($user, 'homeroomGroups')) {
                $kelas = $user->homeroomGroups()->first();
            }
        } catch (\Exception $e) {
            $kelas = null;
        }

        if (!$kelas && $guru) {
            try {
                if ($guru->kelas_id) {
                    $kelas = $guru->kelas;
                }
                if (!$kelas && method_exists($guru, 'waliKelas')) {
                    $wk    = $guru->waliKelas;
                    $kelas = $wk?->kelas ?? $wk ?? null;
                }
                if (!$kelas) {
                    $kelas = \App\Models\Kelas::where('wali_guru_id', $guru->id)->first()
                          ?? \App\Models\Kelas::where('wali_kelas_id', $guru->id)->first();
                }
            } catch (\Exception $e) {
                $kelas = null;
            }
        }

        if (!$kelas) {
            abort(403, 'Anda bukan wali kelas untuk kelas manapun.');
        }

        $tanggal = $request->input('tanggal', now()->toDateString());
        $bulan   = (int) $request->input('bulan', now()->month);
        $tahun   = (int) $request->input('tahun', now()->year);
        $mode    = $request->input('mode', 'harian'); // 'harian' | 'bulanan'

        /* ── Ambil ID siswa di kelas ini ── */
        $siswaIds = collect();
        try {
            if (method_exists($kelas, 'students')) {
                $siswaIds = $kelas->students()->pluck('id');
            } elseif (method_exists($kelas, 'siswas')) {
                $siswaIds = $kelas->siswas()->pluck('id');
            } elseif (method_exists($kelas, 'siswa')) {
                $siswaIds = $kelas->siswa()->pluck('id');
            } else {
                $siswaIds = \App\Models\Siswa::where('kelas_id', $kelas->id)->pluck('id');
            }
        } catch (\Exception $e) {
            $siswaIds = collect();
        }

        /*
        |------------------------------------------------------------------
        | HAK KHUSUS WALI KELAS: tidak difilter guru_id — ambil SEMUA
        | absensi dari SEMUA guru mapel untuk siswa di kelas ini.
        |------------------------------------------------------------------
        */
        $absensiHarian  = collect();
        $absensiBulanan = collect();

        if ($siswaIds->isNotEmpty()) {
            if ($mode === 'bulanan') {
                $absensiBulanan = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->with(['siswa.user', 'guru'])
                    ->orderBy('tanggal')
                    ->orderBy('mata_pelajaran')
                    ->get()
                    ->groupBy(fn($a) => $a->mata_pelajaran ?: '(Tanpa Mapel)');
            } else {
                $absensiHarian = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereDate('tanggal', $tanggal)
                    ->with(['siswa.user', 'guru'])
                    ->orderBy('mata_pelajaran')
                    ->get()
                    ->groupBy(fn($a) => $a->mata_pelajaran ?: '(Tanpa Mapel)');
            }
        }

        $bulanList = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
        ];

        // ── BARU: Daftar SEMUA mata pelajaran (untuk dropdown filter) ──
        $daftarMapel = $this->getDaftarMapel($siswaIds);

        return view('guru.wali-kelas.rekap-mapel', compact(
            'kelas', 'tanggal', 'bulan', 'tahun', 'bulanList', 'mode',
            'absensiHarian', 'absensiBulanan', 'daftarMapel'
        ));
    }

    /**
     * ── BARU ──────────────────────────────────────────────────────────────
     * Ambil daftar SEMUA mata pelajaran untuk dropdown filter di halaman
     * rekap wali kelas.
     *
     * Prioritas sumber data:
     * 1) Tabel master mata pelajaran, jika model tersedia:
     *    - App\Models\MataPelajaran  (kolom nama: 'nama' atau 'nama_mapel')
     *    - App\Models\Mapel          (fallback nama model alternatif)
     *    - App\Models\Subject        (fallback nama model alternatif)
     * 2) Fallback: mata_pelajaran unik dari absensi siswa DI KELAS INI saja
     *    (supaya relevan dengan kelas yang sedang diwalikan).
     * 3) Fallback terakhir: mata_pelajaran unik dari SEMUA data absensi
     *    di seluruh sistem.
     * ────────────────────────────────────────────────────────────────────
     */
    private function getDaftarMapel($siswaIds = null)
    {
        // ── 1) Coba dari tabel master mata pelajaran ──
        $kandidatModel = [
            \App\Models\MataPelajaran::class,
            \App\Models\Mapel::class,
            \App\Models\Subject::class,
        ];

        foreach ($kandidatModel as $modelClass) {
            try {
                if (!class_exists($modelClass)) {
                    continue;
                }

                $model = new $modelClass();

                $kolomKandidat = ['nama', 'nama_mapel', 'name', 'mata_pelajaran'];
                $query = $modelClass::query();

                foreach ($kolomKandidat as $kolom) {
                    if (Schema::hasColumn($model->getTable(), $kolom)) {
                        $hasil = $query->orderBy($kolom)->pluck($kolom);
                        if ($hasil->isNotEmpty()) {
                            return $hasil;
                        }
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        // ── 2) Fallback: mapel unik dari absensi di kelas ini saja ──
        if ($siswaIds !== null && $siswaIds->isNotEmpty()) {
            try {
                $mapelKelas = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereNotNull('mata_pelajaran')
                    ->where('mata_pelajaran', '!=', '')
                    ->distinct()
                    ->orderBy('mata_pelajaran')
                    ->pluck('mata_pelajaran');

                if ($mapelKelas->isNotEmpty()) {
                    return $mapelKelas;
                }
            } catch (\Throwable $e) {
                // lanjut ke fallback berikutnya
            }
        }

        // ── 3) Fallback terakhir: mapel unik dari SEMUA data absensi ──
        try {
            return AbsensiSiswa::whereNotNull('mata_pelajaran')
                ->where('mata_pelajaran', '!=', '')
                ->distinct()
                ->orderBy('mata_pelajaran')
                ->pluck('mata_pelajaran');
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
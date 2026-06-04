<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
           Coba berbagai relasi/kolom yang mungkin ada
        ══════════════════════════════════════════════════════ */
        $kelas = null;

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
        ══════════════════════════════════════════════════════ */
        $siswa = collect();

        try {
            // Relasi siswas() atau siswa() di model Kelas
            if (method_exists($kelas, 'siswas')) {
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
}
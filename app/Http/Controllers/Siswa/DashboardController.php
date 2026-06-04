<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Timetable;
use App\Models\StudyGroup;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user->siswa;

        /*
         * ── Cari Study Group (Kelas) Siswa ──────────────────────────
         * Prioritas:
         *   1. siswa->studyGroup  (relasi langsung ke tabel study_groups)
         *   2. siswa->kelas       (relasi ke tabel kelas yang JUGA dipakai
         *                          sebagai study_group di Timetable)
         * Timetable.study_group_id menunjuk ke tabel yang sama dengan
         * salah satu dari dua relasi di atas.
         */
        $studyGroup = null;

        if ($siswa) {
            // Coba relasi studyGroup terlebih dahulu
            if (method_exists($siswa, 'studyGroup') && $siswa->studyGroup) {
                $studyGroup = $siswa->studyGroup;
            }
            // Fallback ke relasi kelas
            elseif (method_exists($siswa, 'kelas') && $siswa->kelas) {
                $studyGroup = $siswa->kelas;
            }
            // Fallback manual via kelas_id
            elseif ($siswa->kelas_id) {
                // Coba di StudyGroup dulu, kalau tidak ada coba Kelas
                $studyGroup = StudyGroup::find($siswa->kelas_id)
                           ?? Kelas::find($siswa->kelas_id);
            }
        }

        /* ── Pengumuman ──────────────────────────────────────────── */
        $widgetPengumuman = collect();
        try {
            $widgetPengumuman = Pengumuman::where('is_active', 1)
                ->where('show_di_dashboard', 1)
                ->whereIn('target_audience', ['siswa', 'semua'])
                ->latest()
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            // tabel belum ada / model belum dibuat
        }

        /* ── Hari Ini ────────────────────────────────────────────── */
        Carbon::setLocale('id');
        $hariIni   = Carbon::now()->isoFormat('dddd'); // Senin, Selasa, dst
        $hariIniDb = $hariIni;

        /* ── Ambil Jadwal ────────────────────────────────────────── */
        $allTimetables    = collect();
        $jadwalByDay      = collect();
        $jadwalHariIni    = collect();
        $jadwalBerikutnya = collect();
        $hariBerikutnya   = null;

        if ($studyGroup) {
            $allTimetables = Timetable::with(['studySubject', 'teacher'])
                ->where('study_group_id', $studyGroup->id)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            $jadwalByDay = $allTimetables
                ->groupBy('day_of_week')
                ->map(fn($items) => $items->sortBy('start_time'));

            $jadwalHariIni = $jadwalByDay[$hariIniDb] ?? collect();

            /* Jadwal hari berikutnya yang ada jadwal */
            $hariList   = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $idxHariIni = array_search($hariIniDb, $hariList);

            if ($idxHariIni !== false) {
                for ($i = 1; $i <= 6; $i++) {
                    $kandidat = $hariList[($idxHariIni + $i) % count($hariList)];
                    if (!empty($jadwalByDay[$kandidat]) && $jadwalByDay[$kandidat]->isNotEmpty()) {
                        $hariBerikutnya   = $kandidat;
                        $jadwalBerikutnya = $jadwalByDay[$kandidat];
                        break;
                    }
                }
            }
        }

        /* ── KPI ─────────────────────────────────────────────────── */
        $totalJadwal       = $allTimetables->count();
        $totalMapel        = $allTimetables->pluck('study_subject_id')->unique()->count();
        $totalGuru         = $allTimetables->pluck('teacher_id')->unique()->count();
        $hariAktif         = $jadwalByDay->filter(fn($j) => $j->isNotEmpty())->count();
        $totalJamPerMinggu = 0;

        foreach ($allTimetables as $tt) {
            if ($tt->start_time && $tt->end_time) {
                $start = Carbon::parse($tt->start_time);
                $end   = Carbon::parse($tt->end_time);
                $totalJamPerMinggu += $start->diffInMinutes($end);
            }
        }
        $totalJamPerMinggu = round($totalJamPerMinggu / 60, 1);

        return view('siswa.dashboard', compact(
            'widgetPengumuman',
            'studyGroup',
            'jadwalByDay',
            'jadwalHariIni',
            'jadwalBerikutnya',
            'hariBerikutnya',
            'hariIni',
            'hariIniDb',
            'allTimetables',
            'totalJadwal',
            'totalMapel',
            'totalGuru',
            'hariAktif',
            'totalJamPerMinggu'
        ));
    }
}
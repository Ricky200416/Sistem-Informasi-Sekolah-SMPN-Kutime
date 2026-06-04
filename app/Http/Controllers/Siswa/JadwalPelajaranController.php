<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\StudySubject;
use App\Models\StudyGroup;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalPelajaranController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user->siswa;

        /*
         * ── Cari Study Group (Kelas) Siswa ──────────────────────────
         * Sama dengan DashboardController: coba tiga sumber.
         */
        $studyGroup = null;

        if ($siswa) {
            if (method_exists($siswa, 'studyGroup') && $siswa->studyGroup) {
                $studyGroup = $siswa->studyGroup;
            } elseif (method_exists($siswa, 'kelas') && $siswa->kelas) {
                $studyGroup = $siswa->kelas;
            } elseif ($siswa->kelas_id) {
                $studyGroup = StudyGroup::find($siswa->kelas_id)
                           ?? Kelas::find($siswa->kelas_id);
            }
        }

        /* Siswa belum punya kelas */
        if (!$studyGroup) {
            return view('siswa.jadwal-pelajaran.index', [
                'studyGroup'    => null,
                'jadwalByDay'   => collect(),
                'allTimetables' => collect(),
                'mataPelajaran' => collect(),
                'hariIni'       => Carbon::now()->locale('id')->isoFormat('dddd'),
                'hariIniDb'     => Carbon::now()->locale('id')->isoFormat('dddd'),
                'totalJadwal'   => 0,
                'totalMapel'    => 0,
                'totalGuru'     => 0,
                'hariAktif'     => 0,
            ]);
        }

        /* ── Semua Jadwal Kelas ──────────────────────────────────── */
        $allTimetables = Timetable::with(['studySubject', 'teacher'])
            ->where('study_group_id', $studyGroup->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $jadwalByDay = $allTimetables->groupBy('day_of_week');

        /* ── Mata Pelajaran Unik ─────────────────────────────────── */
        $subjectIds    = $allTimetables->pluck('study_subject_id')->unique()->filter();
        $mataPelajaran = $subjectIds->isNotEmpty()
            ? StudySubject::whereIn('id', $subjectIds)->get()
            : collect();

        /* ── KPI ─────────────────────────────────────────────────── */
        Carbon::setLocale('id');
        $hariIni   = Carbon::now()->isoFormat('dddd');
        $hariIniDb = $hariIni;

        $totalJadwal = $allTimetables->count();
        $totalMapel  = $mataPelajaran->count();
        $totalGuru   = $allTimetables->pluck('teacher_id')->unique()->count();
        $hariAktif   = $jadwalByDay->keys()->count();

        return view('siswa.jadwal-pelajaran.index', compact(
            'studyGroup',
            'jadwalByDay',
            'allTimetables',
            'mataPelajaran',
            'hariIni',
            'hariIniDb',
            'totalJadwal',
            'totalMapel',
            'totalGuru',
            'hariAktif'
        ));
    }
}
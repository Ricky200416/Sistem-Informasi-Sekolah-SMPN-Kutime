<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pengumuman;
use App\Models\StudyGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Dashboard Admin.
     */
    public function index()
    {
        // ── 1. Pengumuman Widget ─────────────────────────────────
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['guru', 'siswa', 'semua'])
            ->latest()
            ->limit(5)
            ->get();

        // ── 2. Statistik Ringkasan Dashboard ─────────────────────
        $stats = [
            'total_guru'  => User::where('role', 'guru')->count(),
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_kelas' => StudyGroup::count(),
            'guru_hadir'  => $this->guruHadirHariIni(),
        ];

        // ── 3. Jadwal Hari Ini (Sinkronisasi dari tabel timetables)
        $jadwalHariIni = $this->getJadwalHariIni();

        // ── 4. Log Aktivitas (12 jam terakhir) ────────────────────
        $activityLogs = ActivityLog::with('user')
            ->where('created_at', '>=', now()->subHours(12))
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'widgetPengumuman',
            'stats',
            'jadwalHariIni',
            'activityLogs'
        ));
    }

    /**
     * Helper: Mengambil data jadwal mengajar yang berlangsung pada hari ini.
     * Digunakan untuk menyuplai data ke dalam widget schedule.blade.php
     */
    private function getJadwalHariIni()
    {
        // Petakan nama hari sistem (English) ke dalam format Bahasa Indonesia (kolom day_of_week)
        $daftarHari = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        
        $hariIniEng = Carbon::now()->format('l');
        $hariIniIndo = $daftarHari[$hariIniEng] ?? 'Senin';

        // Mengambil data dengan join table agar relasi nama guru, kelas, dan mapel terbaca sempurna
        return DB::table('timetables')
            ->join('users', 'timetables.teacher_id', '=', 'users.id')
            ->join('study_groups', 'timetables.study_group_id', '=', 'study_groups.id')
            ->join('study_subjects', 'timetables.study_subject_id', '=', 'study_subjects.id')
            ->select([
                'timetables.id',
                'timetables.teacher_id as guru_id',
                'users.name as guru_nama',
                'users.nip as guru_pns_nip',
                DB::raw('NULL as guru_foto'),
                'study_groups.name as kelas_nama',
                'study_subjects.name as mapel_nama', // Kita seragamkan menggunakan mapel_nama
                'study_subjects.color as mapel_color',
                'timetables.start_time',
                'timetables.end_time',
                'timetables.day_of_week',
                'timetables.room',
                'timetables.session_type',
                'timetables.academic_year',
                'timetables.semester'
            ])
            ->where('timetables.day_of_week', $hariIniIndo)
            ->where('timetables.is_active', true)
            ->orderBy('timetables.start_time', 'asc')
            ->get();
    }

    /**
     * Helper: Menghitung jumlah unik guru yang telah melakukan absensi masuk hari ini.
     */
    private function guruHadirHariIni(): int
    {
        try {
            return DB::table('guru_absensis')
                ->where('tanggal', today())
                ->whereIn('status', ['P', 'L']) // P = Present/Hadir, L = Late/Terlambat
                ->distinct('guru_id')
                ->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // API ENDPOINTS (HANDLERS UNTUK ASYNC REFRESH / AJAX)
    // ─────────────────────────────────────────────────────────────

    /** * Endpoint API JSON untuk memuat ulang data jadwal hari ini via AJAX 
     * GET /admin/dashboard/jadwal-hari-ini
     */
    public function jadwalHariIni(): \Illuminate\Http\JsonResponse
    {
        $data = $this->getJadwalHariIni()->map(fn ($j) => [
            'guru_nama'    => $j->guru_nama   ?? '—',
            'kelas_nama'   => $j->kelas_nama  ?? '—',
            'mapel_nama'   => $j->mapel_nama  ?? '—', // Dipastikan mengambil properti mapel_nama
            'mapel_color'  => $j->mapel_color ?? '#6366f1',
            'room'         => $j->room        ?? '',
            'session_type' => $j->session_type ?? '',
            'start_time'   => Carbon::parse($j->start_time)->format('H:i'),
            'end_time'     => Carbon::parse($j->end_time)->format('H:i'),
            'is_now'       => now()->format('H:i') >= Carbon::parse($j->start_time)->format('H:i')
                           && now()->format('H:i') <  Carbon::parse($j->end_time)->format('H:i'),
        ]);

        return response()->json(['success' => true, 'data' => $data]);
    }

    /** * Endpoint API JSON untuk memuat data statistik ringkas via AJAX 
     * GET /admin/dashboard/stats
     */
    public function stats(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'total_guru'  => User::where('role', 'guru')->count(),
                'total_siswa' => User::where('role', 'siswa')->count(),
                'total_kelas' => StudyGroup::count(),
                'guru_hadir'  => $this->guruHadirHariIni(),
            ]
        ]);
    }
}
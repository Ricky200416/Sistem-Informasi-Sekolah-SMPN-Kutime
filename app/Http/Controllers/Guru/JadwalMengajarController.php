<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\StudySubject;
use App\Models\StudyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalMengajarController extends Controller
{
    /**
     * Tampilkan jadwal mengajar guru yang login.
     */
    public function index()
    {
        $guru = Auth::user();

        // Semua jadwal milik guru ini
        $allTimetables = Timetable::with(['studySubject', 'studyGroup'])
            ->where('teacher_id', $guru->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Kelompokkan per hari
        $jadwalByDay = $allTimetables->groupBy('day_of_week');

        // KPI Statistik Ringkas
        $totalJadwal      = $allTimetables->count();
        $totalKelas       = $allTimetables->unique('study_group_id')->count();
        $totalMapel       = $allTimetables->unique('study_subject_id')->count();

        // Hitung total jam per minggu
        $totalJamPerMinggu = $allTimetables->sum(function ($t) {
            $start = \Carbon\Carbon::createFromFormat('H:i:s', $t->start_time);
            $end = \Carbon\Carbon::createFromFormat('H:i:s', $t->end_time);
            return $start->diffInHours($end);
        });

        // Ambil data master untuk ditaruh di modal dropdown Form Guru
        $studySubjects = StudySubject::where('is_active', true)->get();
        $studyGroups = StudyGroup::where('is_active', true)->orderBy('name')->get();

        return view('guru.jadwal-mengajar.index', compact(
            'allTimetables', 'jadwalByDay', 'totalJadwal', 'totalKelas', 
            'totalMapel', 'totalJamPerMinggu', 'studySubjects', 'studyGroups'
        ));
    }

    /**
     * Simpan jadwal baru dari guru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'study_subject_id' => 'required|exists:study_subjects,id',
            'study_group_id'   => 'required|exists:study_groups,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'session_type'     => 'required|in:teori,praktikum',
            'academic_year'    => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        // Otomatis assign ke diri guru sendiri agar terhubung sempurna
        $validated['teacher_id'] = Auth::id();
        $validated['is_active']  = true;

        Timetable::create($validated);

        return redirect()->route('guru.jadwal-mengajar.index')
            ->with('success', 'Jadwal berhasil ditambahkan dan disinkronkan ke Admin.');
    }

    /**
     * Update jadwal milik guru.
     */
    public function update(Request $request, Timetable $jadwalMengajar)
    {
        if ($jadwalMengajar->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengedit jadwal ini.');
        }

        $validated = $request->validate([
            'study_subject_id' => 'required|exists:study_subjects,id',
            'study_group_id'   => 'required|exists:study_groups,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'session_type'     => 'required|in:teori,praktikum',
            'academic_year'    => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        $jadwalMengajar->update($validated);

        return redirect()->route('guru.jadwal-mengajar.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal milik guru.
     */
    public function destroy(Timetable $jadwalMengajar)
    {
        if ($jadwalMengajar->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menghapus jadwal ini.');
        }

        $jadwalMengajar->delete();

        return redirect()->route('guru.jadwal-mengajar.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
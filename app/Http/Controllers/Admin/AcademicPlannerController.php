<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\StudyGroup;
use App\Models\StudySubject;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcademicPlannerController extends Controller
{
    // =========================================================================
    // DASHBOARD
    // =========================================================================

    public function index(): View
    {
        $stats = [
            'total_groups'     => StudyGroup::where('is_active', true)->count(),
            'total_subjects'   => StudySubject::where('is_active', true)->count(),
            'total_timetables' => Timetable::where('is_active', true)->count(),
            'total_teachers'   => User::where('role', 'guru')->count(),
        ];

        $groupsByGrade = StudyGroup::where('is_active', true)
            ->with('homeroomTeacher')
            ->orderBy('grade')
            ->orderBy('section')
            ->get()
            ->groupBy('grade');

        return view('admin.academic-planner.index', compact('stats', 'groupsByGrade'));
    }

    // =========================================================================
    // STUDY GROUPS — SHOW
    // =========================================================================

    public function show(int $id): View
    {
        return $this->showStudyGroup($id);
    }

    public function showStudyGroup(int $id): View
    {
        $studyGroup = StudyGroup::with([
            'homeroomTeacher',
            'timetables.studySubject',
            'timetables.teacher',
        ])->findOrFail($id);

        $timetables = $studyGroup->timetables->where('is_active', true);

        $days           = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $timetableByDay = [];

        foreach ($days as $day) {
            $timetableByDay[$day] = $timetables
                ->where('day_of_week', $day)
                ->sortBy('start_time')
                ->values();
        }

        $studySubjects = StudySubject::where('is_active', true)->orderBy('name')->get();
        $teachers = User::where('role', 'guru')->orderBy('name')->get();

        return view(
            'admin.academic-planner.show-study-group',
            compact('studyGroup', 'timetables', 'timetableByDay', 'days', 'studySubjects', 'teachers')
        );
    }

    // =========================================================================
    // ADMIN TIMETABLE CRUD (Sinkronisasi Otomatis ke Guru)
    // =========================================================================

    public function storeJadwal(Request $request, int $groupId): RedirectResponse
    {
        $validated = $request->validate([
            'study_subject_id' => 'required|exists:study_subjects,id',
            'teacher_id'       => 'required|exists:users,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'session_type'     => 'required|in:teori,praktikum',
            'academic_year'    => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        $validated['study_group_id'] = $groupId;
        $validated['is_active'] = true;

        Timetable::create($validated);

        return redirect()->back()->with('success', 'Jadwal mengajar berhasil ditambahkan oleh Admin.');
    }

    public function updateJadwal(Request $request, int $id): RedirectResponse
    {
        $timetable = Timetable::findOrFail($id);

        $validated = $request->validate([
            'study_subject_id' => 'required|exists:study_subjects,id',
            'teacher_id'       => 'required|exists:users,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:50',
            'session_type'     => 'required|in:teori,praktikum',
            'academic_year'    => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'         => 'required|in:1,2',
            'notes'            => 'nullable|string|max:500',
        ]);

        $timetable->update($validated);

        return redirect()->back()->with('success', 'Jadwal mengajar berhasil diperbarui oleh Admin.');
    }

    public function destroyJadwal(int $id): RedirectResponse
    {
        $timetable = Timetable::findOrFail($id);
        $timetable->delete();

        return redirect()->back()->with('success', 'Jadwal mengajar berhasil dihapus.');
    }

    // =========================================================================
    // STUDY GROUPS — STORE
    // =========================================================================

    public function storeStudyGroup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|integer|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:50',
            'academic_year'       => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester'            => 'required|in:1,2',
            'is_active'           => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated) {
            $group = StudyGroup::create($validated);
            $this->syncToKelasTable($group);
        });

        return redirect()
            ->route('admin.academic-planner.index')
            ->with('success', "Kelas {$validated['name']} berhasil ditambahkan.");
    }

    private function syncToKelasTable(StudyGroup $group): void
    {
        $kelasName = $group->name ?: ((string) $group->grade . ($group->section ?? ''));
        $tahunAjaran = (!empty($group->academic_year)) ? $group->academic_year : $this->getDefaultAcademicYear();

        Kelas::updateOrCreate(
            ['id' => $group->id],
            [
                'nama'         => $kelasName,
                'tingkat'      => (string) $group->grade,
                'rombel'       => $group->section ?? null,
                'tahun_ajaran' => $tahunAjaran,
                'guru_id'      => $group->homeroom_teacher_id ?? null,
            ]
        );
    }

    private function getDefaultAcademicYear(): string
    {
        $bulan = now()->month;
        return $bulan >= 7 ? now()->year . '/' . (now()->year + 1) : (now()->year - 1) . '/' . now()->year;
    }

    // =========================================================================
    // STUDY SUBJECTS — CRUD
    // =========================================================================

    /**
     * Menampilkan daftar mata pelajaran (Study Subjects)
     */
    public function indexStudySubject(): View
    {
        $subjects = StudySubject::orderBy('name')->paginate(10);
        return view('admin.academic-planner.study-subjects', compact('subjects'));
    }

    /**
     * Menyimpan mata pelajaran baru
     */
    public function storeStudySubject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'code'          => 'required|string|max:10|unique:study_subjects,code',
            'credit_hours'  => 'required|integer|min:1|max:6',
            'type'          => 'required|in:core,elective',
            'description'   => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->has('is_active');

        StudySubject::create($validated);

        return redirect()
            ->route('admin.academic-planner.study-subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Memperbarui data mata pelajaran
     */
    public function updateStudySubject(Request $request, int $id): RedirectResponse
    {
        $subject = StudySubject::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'code'          => 'required|string|max:10|unique:study_subjects,code,' . $id,
            'credit_hours'  => 'required|integer|min:1|max:6',
            'type'          => 'required|in:core,elective',
            'description'   => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subject->update($validated);

        return redirect()
            ->route('admin.academic-planner.study-subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus mata pelajaran (Fitur Utama perbaikan)
     */
    public function destroyStudySubject(int $id): RedirectResponse
    {
        try {
            $subject = StudySubject::findOrFail($id);
            
            // Opsional: Cek jika subjek ini dipakai di tabel jadwal (timetable)
            $isUsed = Timetable::where('study_subject_id', $id)->exists();
            if ($isUsed) {
                return redirect()
                    ->route('admin.academic-planner.study-subjects.index')
                    ->with('error', 'Mata pelajaran tidak bisa dihapus karena sedang digunakan dalam jadwal pelajaran.');
            }

            $subject->delete();

            return redirect()
                ->route('admin.academic-planner.study-subjects.index')
                ->with('success', 'Mata pelajaran berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus mata pelajaran: ' . $e->getMessage());
            return redirect()
                ->route('admin.academic-planner.study-subjects.index')
                ->with('error', 'Terjadi kesalahan saat menghapus mata pelajaran.');
        }
    }
}
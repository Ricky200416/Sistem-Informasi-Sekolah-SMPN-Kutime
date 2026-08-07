<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class KelasController extends Controller
{
    // =========================================================================
    // INDEX
    // =========================================================================

    public function index(): View
    {
        $kelas = StudyGroup::with(['homeroomTeacher', 'timetables'])
            ->withCount('timetables')
            ->orderBy('grade')
            ->orderBy('section')
            ->orderBy('name')
            ->get();

        $gurus = User::whereIn('role', ['guru', 'kepala_sekolah'])
            ->with('guru')
            ->orderBy('name')
            ->get();

        return view('admin.kelas.index', compact('kelas', 'gurus'));
    }

    // =========================================================================
    // STORE — Tambah Kelas Baru
    // =========================================================================

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|integer|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:150',
            'academic_year'       => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester'            => 'required|in:1,2',
            'capacity'            => 'nullable|integer|min:1',
            'is_active'           => 'nullable|in:0,1',
        ], [
            'name.required'                   => 'Nama kelas wajib diisi.',
            'grade.required'                  => 'Tingkat kelas wajib diisi.',
            'grade.in'                        => 'Tingkat hanya boleh 7, 8, atau 9.',
            'academic_year.required'          => 'Tahun ajaran wajib diisi.',
            'academic_year.regex'             => 'Format tahun ajaran harus YYYY/YYYY, contoh: 2025/2026.',
            'semester.required'               => 'Semester wajib diisi.',
            'semester.in'                     => 'Semester hanya boleh 1 atau 2.',
            'homeroom_teacher_id.exists'      => 'Guru yang dipilih tidak ditemukan.',
        ]);

        $isActive = $request->has('is_active') ? true : false;

        DB::beginTransaction();
        try {
            // 1. Simpan ke tabel study_groups (sumber utama)
            $studyGroup = StudyGroup::create([
                'name'                => $validated['name'],
                'grade'               => (int) $validated['grade'],
                'section'             => $validated['section']             ?? null,
                'homeroom_teacher_id' => $validated['homeroom_teacher_id'] ?? null,
                'room'                => $validated['room']                ?? null,
                'academic_year'       => $validated['academic_year'],
                'semester'            => (int) $validated['semester'],
                'capacity'            => $validated['capacity']            ?? 32,
                'is_active'           => $isActive,
            ]);

            // 2. Sinkron ke tabel kelas (dengan mapping users.id → gurus.id)
            $this->syncToKelasTable($studyGroup);

            // 3. Assign wali kelas
            if (!empty($validated['homeroom_teacher_id'])) {
                $this->assignHomeroomTeacher($studyGroup->id, (int) $validated['homeroom_teacher_id']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KelasController@store error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan kelas: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', "Kelas {$validated['name']} berhasil ditambahkan.");
    }

    // =========================================================================
    // UPDATE — Edit Kelas
    // =========================================================================

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:50',
            'grade'               => 'required|integer|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:150',
            'academic_year'       => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester'            => 'required|in:1,2',
            'capacity'            => 'nullable|integer|min:1',
            'is_active'           => 'nullable|in:0,1',
        ], [
            'name.required'                   => 'Nama kelas wajib diisi.',
            'grade.required'                  => 'Tingkat kelas wajib diisi.',
            'grade.in'                        => 'Tingkat hanya boleh 7, 8, atau 9.',
            'academic_year.required'          => 'Tahun ajaran wajib diisi.',
            'academic_year.regex'             => 'Format tahun ajaran harus YYYY/YYYY, contoh: 2025/2026.',
            'semester.required'               => 'Semester wajib diisi.',
            'semester.in'                     => 'Semester hanya boleh 1 atau 2.',
            'homeroom_teacher_id.exists'      => 'Guru yang dipilih tidak ditemukan.',
        ]);

        $isActive = $request->has('is_active') ? true : false;

        DB::beginTransaction();
        try {
            $studyGroup    = StudyGroup::findOrFail($id);
            $oldHomeroomId = $studyGroup->homeroom_teacher_id;

            $studyGroup->update([
                'name'                => $validated['name'],
                'grade'               => (int) $validated['grade'],
                'section'             => $validated['section']             ?? null,
                'homeroom_teacher_id' => $validated['homeroom_teacher_id'] ?? null,
                'room'                => $validated['room']                ?? null,
                'academic_year'       => $validated['academic_year'],
                'semester'            => (int) $validated['semester'],
                'capacity'            => $validated['capacity']            ?? 32,
                'is_active'           => $isActive,
            ]);

            // Sinkron ke tabel kelas
            $this->syncToKelasTable($studyGroup->fresh());

            $newHomeroomId = $validated['homeroom_teacher_id'] ?? null;

            // Lepas wali kelas lama jika berubah
            if ($oldHomeroomId && (int) $oldHomeroomId !== (int) $newHomeroomId) {
                $this->removeHomeroomTeacher($oldHomeroomId, $studyGroup->id);
            }

            // Set wali kelas baru
            if ($newHomeroomId) {
                $this->assignHomeroomTeacher($studyGroup->id, (int) $newHomeroomId);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KelasController@update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui kelas: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', "Kelas {$validated['name']} berhasil diperbarui.");
    }

    // =========================================================================
    // DESTROY — Hapus Kelas
    // =========================================================================

    public function destroy(int $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $studyGroup = StudyGroup::findOrFail($id);
            $name       = $studyGroup->name;

            if ($studyGroup->homeroom_teacher_id) {
                $this->removeHomeroomTeacher($studyGroup->homeroom_teacher_id, $id);
            }

            Kelas::where('id', $id)->delete();

            if ($studyGroup->timetables()) {
                $studyGroup->timetables()->delete();
            }
            $studyGroup->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KelasController@destroy error: ' . $e->getMessage());

            return back()->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', "Kelas {$name} berhasil dihapus.");
    }

    // =========================================================================
    // PRIVATE HELPER: Sinkron StudyGroup → tabel Kelas
    // =========================================================================

    private function syncToKelasTable(StudyGroup $group): void
    {
        $kelasName   = $group->name ?: ((string) $group->grade . ($group->section ?? ''));
        $tahunAjaran = !empty($group->academic_year) ? $group->academic_year : $this->getDefaultAcademicYear();

        // ★ PERBAIKAN UTAMA: Map users.id → gurus.id
        $guruId = null;
        if ($group->homeroom_teacher_id) {
            $user   = User::with('guru')->find($group->homeroom_teacher_id);
            $guruId = $user?->guru?->id; // ambil gurus.id, bukan users.id
        }

        Kelas::updateOrCreate(
            ['id' => $group->id],
            [
                'nama'         => $kelasName,
                'tingkat'      => (string) $group->grade,
                'rombel'       => $group->section    ?? null,
                'tahun_ajaran' => $tahunAjaran,
                'semester'     => $group->semester    ?? null,
                'guru_id'      => $guruId,            // sekarang sudah benar (gurus.id)
                'ruang'        => $group->room        ?? null,
            ]
        );

        Log::info("syncToKelasTable: study_group id={$group->id} nama={$kelasName} guru_id={$guruId} disinkron.");
    }

    // =========================================================================
    // PRIVATE HELPER: Assign wali kelas → update guru.kelas_id
    // =========================================================================

    private function assignHomeroomTeacher(int $kelasId, int $teacherId): void
    {
        $user = User::find($teacherId);
        if ($user && $user->guru) {
            $user->guru()->update(['kelas_id' => $kelasId]);
        }
    }

    // =========================================================================
    // PRIVATE HELPER: Lepas wali kelas lama
    // =========================================================================

    private function removeHomeroomTeacher(int $teacherId, int $kelasId): void
    {
        $user = User::find($teacherId);
        if ($user && $user->guru && $user->guru->kelas_id === $kelasId) {
            $user->guru()->update(['kelas_id' => null]);
        }
    }

    // =========================================================================
    // PRIVATE HELPER: Tahun ajaran default otomatis
    // =========================================================================

    private function getDefaultAcademicYear(): string
    {
        $bulan = now()->month;

        return $bulan >= 7
            ? now()->year . '/' . (now()->year + 1)
            : (now()->year - 1) . '/' . now()->year;
    }

    // =========================================================================
    // BULK DESTROY — Hapus Banyak Kelas
    // =========================================================================

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:kelas,id',
        ]);

        Kelas::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' kelas berhasil dihapus.');
    }
}
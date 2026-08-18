<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
    // STORE
    // =========================================================================

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                // Nama BOLEH sama (mis. "SMP 1"), asalkan kombinasi dengan
                // section, tahun ajaran, dan semester berbeda.
                // Contoh yang DIPERBOLEHKAN: SMP 1 + A, SMP 1 + B (section beda).
                // Contoh yang DITOLAK: SMP 1 + A + 2026/2027 + semester 1 dua kali.
                Rule::unique('study_groups', 'name')->where(function ($query) use ($request) {
                    return $query->where('section', $request->input('section'))
                        ->where('academic_year', $request->input('academic_year'))
                        ->where('semester', $request->input('semester'));
                }),
                Rule::unique('kelas', 'nama')->where(function ($query) use ($request) {
                    return $query->where('rombel', $request->input('section'))
                        ->where('tahun_ajaran', $request->input('academic_year'))
                        ->where('semester', $request->input('semester'));
                }),
            ],
            'grade'               => 'required|integer|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:150',
            'academic_year'       => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester'            => 'required|in:1,2',
            'capacity'            => 'nullable|integer|min:1',
            'is_active'           => 'nullable|in:0,1',
        ], [
            'name.required'              => 'Nama kelas wajib diisi.',
            'name.unique'                => 'Kelas ":input" dengan rombel, tahun ajaran, dan semester yang sama sudah ada. Gunakan rombel/section yang berbeda (mis. A/B) jika ini kelas paralel.',
            'grade.required'             => 'Tingkat kelas wajib diisi.',
            'grade.in'                   => 'Tingkat hanya boleh 7, 8, atau 9.',
            'academic_year.required'     => 'Tahun ajaran wajib diisi.',
            'academic_year.regex'        => 'Format tahun ajaran harus YYYY/YYYY.',
            'semester.required'          => 'Semester wajib diisi.',
            'semester.in'                => 'Semester hanya boleh 1 atau 2.',
            'homeroom_teacher_id.exists' => 'Guru yang dipilih tidak ditemukan.',
        ]);

        $isActive = $request->boolean('is_active');

        DB::beginTransaction();
        try {
            // 1. Simpan ke study_groups
            $studyGroup = StudyGroup::create([
                'name'                => $validated['name'],
                'grade'               => (int) $validated['grade'],
                'section'             => $validated['section'] ?? null,
                'homeroom_teacher_id' => $validated['homeroom_teacher_id'] ?? null,
                'room'                => $validated['room'] ?? null,
                'academic_year'       => $validated['academic_year'],
                'semester'            => (int) $validated['semester'],
                'capacity'            => $validated['capacity'] ?? 32,
                'is_active'           => $isActive,
            ]);

            // 2. Sinkron ke tabel kelas (WAJIB berhasil sebelum assign wali kelas)
            $kelas = $this->syncToKelasTable($studyGroup);

            // 3. Assign wali kelas (gunakan ID yang benar-benar ada di tabel kelas)
            if (!empty($validated['homeroom_teacher_id'])) {
                $this->assignHomeroomTeacher($kelas->id, (int) $validated['homeroom_teacher_id']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KelasController@store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan kelas: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', "Kelas {$validated['name']} berhasil ditambahkan.");
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                // Abaikan (ignore) baris dengan id ini sendiri, supaya menyimpan
                // ulang data yang sama (nama+section+tahun+semester) tidak
                // dianggap duplikat dengan dirinya sendiri.
                Rule::unique('study_groups', 'name')->where(function ($query) use ($request) {
                    return $query->where('section', $request->input('section'))
                        ->where('academic_year', $request->input('academic_year'))
                        ->where('semester', $request->input('semester'));
                })->ignore($id),
                Rule::unique('kelas', 'nama')->where(function ($query) use ($request) {
                    return $query->where('rombel', $request->input('section'))
                        ->where('tahun_ajaran', $request->input('academic_year'))
                        ->where('semester', $request->input('semester'));
                })->ignore($id),
            ],
            'grade'               => 'required|integer|in:7,8,9',
            'section'             => 'nullable|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'room'                => 'nullable|string|max:150',
            'academic_year'       => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester'            => 'required|in:1,2',
            'capacity'            => 'nullable|integer|min:1',
            'is_active'           => 'nullable|in:0,1',
        ], [
            'name.required'              => 'Nama kelas wajib diisi.',
            'name.unique'                => 'Kelas ":input" dengan rombel, tahun ajaran, dan semester yang sama sudah dipakai kelas lain. Gunakan rombel/section yang berbeda (mis. A/B) jika ini kelas paralel.',
            'grade.required'             => 'Tingkat kelas wajib diisi.',
            'grade.in'                   => 'Tingkat hanya boleh 7, 8, atau 9.',
            'academic_year.required'     => 'Tahun ajaran wajib diisi.',
            'academic_year.regex'        => 'Format tahun ajaran harus YYYY/YYYY.',
            'semester.required'          => 'Semester wajib diisi.',
            'semester.in'                => 'Semester hanya boleh 1 atau 2.',
            'homeroom_teacher_id.exists' => 'Guru yang dipilih tidak ditemukan.',
        ]);

        $isActive = $request->boolean('is_active');

        DB::beginTransaction();
        try {
            $studyGroup    = StudyGroup::findOrFail($id);
            $oldHomeroomId = $studyGroup->homeroom_teacher_id;

            $studyGroup->update([
                'name'                => $validated['name'],
                'grade'               => (int) $validated['grade'],
                'section'             => $validated['section'] ?? null,
                'homeroom_teacher_id' => $validated['homeroom_teacher_id'] ?? null,
                'room'                => $validated['room'] ?? null,
                'academic_year'       => $validated['academic_year'],
                'semester'            => (int) $validated['semester'],
                'capacity'            => $validated['capacity'] ?? 32,
                'is_active'           => $isActive,
            ]);

            // Sinkron ke tabel kelas
            $kelas = $this->syncToKelasTable($studyGroup->fresh());

            $newHomeroomId = $validated['homeroom_teacher_id'] ?? null;

            // Lepas wali kelas lama
            if ($oldHomeroomId && (int) $oldHomeroomId !== (int) $newHomeroomId) {
                $this->removeHomeroomTeacher($oldHomeroomId, $kelas->id);
            }

            // Set wali kelas baru
            if ($newHomeroomId) {
                $this->assignHomeroomTeacher($kelas->id, (int) $newHomeroomId);
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
    // DESTROY
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

            // Hapus dari tabel kelas dulu
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
    // PRIVATE: Sinkron StudyGroup → Kelas (RETURN Kelas model)
    // =========================================================================

    private function syncToKelasTable(StudyGroup $group): Kelas
    {
        $kelasName   = $group->name ?: ((string) $group->grade . ($group->section ?? ''));
        $tahunAjaran = !empty($group->academic_year)
            ? $group->academic_year
            : $this->getDefaultAcademicYear();

        // Mapping users.id → gurus.id
        $guruId = null;
        if ($group->homeroom_teacher_id) {
            $user   = User::with('guru')->find($group->homeroom_teacher_id);
            $guruId = $user?->guru?->id;
        }

        // Gunakan forceFill agar ID bisa di-set meski tidak fillable
        $kelas = Kelas::find($group->id);

        try {
            if ($kelas) {
                $kelas->forceFill([
                    'nama'         => $kelasName,
                    'tingkat'      => (string) $group->grade,
                    'rombel'       => $group->section ?? null,
                    'tahun_ajaran' => $tahunAjaran,
                    'semester'     => $group->semester ?? null,
                    'guru_id'      => $guruId,
                    'ruang'        => $group->room ?? null,
                ])->save();
            } else {
                $kelas = new Kelas();
                $kelas->forceFill([
                    'id'           => $group->id,          // paksa ID sama
                    'nama'         => $kelasName,
                    'tingkat'      => (string) $group->grade,
                    'rombel'       => $group->section ?? null,
                    'tahun_ajaran' => $tahunAjaran,
                    'semester'     => $group->semester ?? null,
                    'guru_id'      => $guruId,
                    'ruang'        => $group->room ?? null,
                ]);
                $kelas->save();
            }
        } catch (QueryException $e) {
            // Jaring pengaman terakhir: jika masih lolos validasi (mis. race
            // condition dua request bersamaan) tapi tetap bentrok di DB level,
            // ubah jadi pesan yang ramah, bukan SQL mentah.
            if ((int) $e->getCode() === 23000) {
                $rombelInfo = $group->section ? " rombel {$group->section}" : '';
                throw new \Exception(
                    "Kelas \"{$kelasName}\"{$rombelInfo} untuk tahun ajaran {$tahunAjaran} semester {$group->semester} sudah ada. Silakan gunakan rombel/section yang berbeda."
                );
            }

            throw $e;
        }

        Log::info("syncToKelasTable sukses", [
            'study_group_id' => $group->id,
            'kelas_id'       => $kelas->id,
            'guru_id'        => $guruId,
        ]);

        return $kelas;
    }

    // =========================================================================
    // PRIVATE: Assign wali kelas
    // =========================================================================

    private function assignHomeroomTeacher(int $kelasId, int $teacherUserId): void
    {
        // Pastikan kelas benar-benar ada
        if (!Kelas::where('id', $kelasId)->exists()) {
            throw new \Exception("Kelas dengan ID {$kelasId} tidak ditemukan saat assign wali kelas.");
        }

        $user = User::with('guru')->find($teacherUserId);

        if (!$user || !$user->guru) {
            Log::warning("User ID {$teacherUserId} tidak memiliki relasi guru.");
            return;
        }

        $user->guru()->update(['kelas_id' => $kelasId]);
    }

    // =========================================================================
    // PRIVATE: Lepas wali kelas
    // =========================================================================

    private function removeHomeroomTeacher(int $teacherUserId, int $kelasId): void
    {
        $user = User::with('guru')->find($teacherUserId);

        if ($user && $user->guru && (int) $user->guru->kelas_id === (int) $kelasId) {
            $user->guru()->update(['kelas_id' => null]);
        }
    }

    // =========================================================================
    // PRIVATE: Tahun ajaran default
    // =========================================================================

    private function getDefaultAcademicYear(): string
    {
        $bulan = now()->month;

        return $bulan >= 7
            ? now()->year . '/' . (now()->year + 1)
            : (now()->year - 1) . '/' . now()->year;
    }

    // =========================================================================
    // BULK DESTROY
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
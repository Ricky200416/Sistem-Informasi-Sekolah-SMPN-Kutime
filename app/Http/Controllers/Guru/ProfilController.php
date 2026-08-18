<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfilController extends Controller
{
    /* ═══════════════════════════════════════════════════════════
       HELPER — Ambil kelas wali dari StudyGroup.
       INI SATU-SATUNYA SUMBER KEBENARAN, sama persis dengan yang
       dipakai admin saat assign wali kelas lewat "Kelola Kelas"
       (StudyGroup::homeroom_teacher_id -> FK ke users.id).
       Tidak ada lagi tebak-tebak kolom di tabel 'kelas' yang lama.
    ═══════════════════════════════════════════════════════════ */
    private function resolveKelasWali($user): ?StudyGroup
    {
        if (!$user) return null;

        try {
            return StudyGroup::where('homeroom_teacher_id', $user->id)
                ->orderBy('academic_year', 'desc')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /* ═══════════════════════════════════════════════════════════
       SHOW — Halaman profil (read-only + modal edit)
    ═══════════════════════════════════════════════════════════ */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Eager-load relasi guru saja; kelas wali di-resolve terpisah
        // dari StudyGroup (bukan dari relasi guru->kelas yang lama).
        $user->loadMissing(['guru']);

        /* ── Resolve kelas wali dari StudyGroup (sumber kebenaran) ── */
        $kelasWali = $this->resolveKelasWali($user);

        /* ── Daftar kelas untuk dropdown modal ── */
        $kelasList = $this->getKelasList();

        return view('guru.profil', [
            'user'      => $user,
            'kelasList' => $kelasList,
            'kelasWali' => $kelasWali,   // dipakai Blade sebagai Prioritas 2
        ]);
    }

    /* ═══════════════════════════════════════════════════════════
       EDIT — Halaman edit terpisah (jika ada)
    ═══════════════════════════════════════════════════════════ */
    public function edit(): View
    {
        $user = Auth::user();
        $user->loadMissing(['guru']);

        $kelasWali = $this->resolveKelasWali($user);
        $kelasList = $this->getKelasList();

        return view('guru.profil-edit', compact('user', 'kelasList', 'kelasWali'));
    }

    /* ═══════════════════════════════════════════════════════════
       UPDATE — Proses simpan semua section
    ═══════════════════════════════════════════════════════════ */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $guru = $user->guru ?? $user->guru()->firstOrCreate([]);

        /* ── Validasi ── */
        $rules = [
            // Akun
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
                        'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'max:2048'],

            // Identitas & tugas
            'nip'        => ['nullable', 'string', 'max:30',
                             'unique:guru,nip,' . $guru->id],  // tabel bisa 'guru' atau 'gurus'
            'wali_kelas' => ['nullable', 'exists:study_groups,id'],

            // Data diri
            'nama'                => ['nullable', 'string', 'max:255'],
            'tempat_lahir'        => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'       => ['nullable', 'date'],
            'jk'                  => ['nullable', 'in:L,P'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:100'],

            // Kepegawaian
            'status_pegawai'    => ['nullable', 'string', 'max:100'],
            'pangkat_gol_ruang' => ['nullable', 'string', 'max:100'],
            'no_sk_pertama'     => ['nullable', 'string', 'max:150'],
            'no_sk_terakhir'    => ['nullable', 'string', 'max:150'],
        ];

        // Validasi unik NIP: fallback jika tabel bernama 'gurus'
        try {
            if (Schema::hasTable('gurus')) {
                $rules['nip'] = ['nullable', 'string', 'max:30',
                                 'unique:gurus,nip,' . $guru->id];
            }
        } catch (\Exception $e) {}

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'confirmed',
                                  Password::defaults()];
        }

        $data = $request->validate($rules);

        /* ── Upload foto ── */
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        /* ── Update password ── */
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        /* ── Update data user ── */
        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->save();

        /* ── Siapkan data guru ── */
        $guruData = [
            'nip'                 => $data['nip']                ?? $guru->nip,
            'nama'                => $data['nama']               ?? $guru->nama,
            'tempat_lahir'        => $data['tempat_lahir']        ?? $guru->tempat_lahir,
            'tanggal_lahir'       => $data['tanggal_lahir']       ?? $guru->tanggal_lahir,
            'jk'                  => $data['jk']                  ?? $guru->jk,
            'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? $guru->pendidikan_terakhir,
            'status_pegawai'      => $data['status_pegawai']      ?? $guru->status_pegawai,
            'pangkat_gol_ruang'   => $data['pangkat_gol_ruang']   ?? $guru->pangkat_gol_ruang,
            'no_sk_pertama'       => $data['no_sk_pertama']       ?? $guru->no_sk_pertama,
            'no_sk_terakhir'      => $data['no_sk_terakhir']      ?? $guru->no_sk_terakhir,
        ];

        $guru->update($guruData);

        /* ── Simpan wali kelas ke StudyGroup (sumber kebenaran) ──
         *  homeroom_teacher_id adalah FK ke users.id (BUKAN guru.id),
         *  jadi disimpan pakai $user->id, sama seperti yang dipakai
         *  admin saat assign wali kelas di "Kelola Kelas".
         */
        $newKelasId = $data['wali_kelas'] ?? null; // null = bukan wali kelas
        $this->updateWaliOnStudyGroups($user->id, $newKelasId);

        /* ── Flash section agar modal bisa re-open jika error ── */
        return redirect()
            ->route('guru.profil')
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('_section', $request->input('_section', 'akun'));
    }

    /* ═══════════════════════════════════════════════════════════
       PRIVATE HELPERS
    ═══════════════════════════════════════════════════════════ */

    /** Daftar kelas untuk dropdown, diambil dari StudyGroup */
    private function getKelasList()
    {
        try {
            return StudyGroup::query()
                ->select('id', 'name', 'grade', 'academic_year')
                ->orderBy('academic_year', 'desc')
                ->orderBy('grade')
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Lepas guru ini dari kelas wali lamanya (jika ada), lalu
     * pasang sebagai wali di kelas baru (jika dipilih).
     * Satu guru = satu kelas wali, ditegakkan di sini.
     */
    private function updateWaliOnStudyGroups(int $userId, ?int $newGroupId): void
    {
        try {
            // Kosongkan kelas lama yang punya guru ini sebagai wali
            StudyGroup::where('homeroom_teacher_id', $userId)
                ->update(['homeroom_teacher_id' => null]);

            // Pasang di kelas baru (jika dipilih)
            if ($newGroupId) {
                StudyGroup::where('id', $newGroupId)
                    ->update(['homeroom_teacher_id' => $userId]);
            }
        } catch (\Exception $e) {
            // Diam — jangan crash halaman profil
        }
    }
}
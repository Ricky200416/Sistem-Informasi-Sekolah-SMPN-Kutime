<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfilController extends Controller
{
    /* ═══════════════════════════════════════════════════════════
       HELPER — Ambil kelas wali dari StudyGroup.
       Satu-satunya sumber kebenaran, sama persis dengan yang
       dipakai admin saat assign wali kelas lewat "Kelola Kelas"
       (StudyGroup::homeroom_teacher_id -> FK ke users.id).
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

        // PENTING: fresh() memaksa ambil ulang dari DB, bukan dari
        // instance $user yang mungkin sudah di-cache Auth guard
        // sejak awal request (misal oleh middleware lain).
        $user = $user->fresh();
        $user->loadMissing(['guru']);

        $kelasWali = $this->resolveKelasWali($user);
        $kelasList = $this->getKelasList();

        return view('guru.profil', [
            'user'      => $user,
            'kelasList' => $kelasList,
            'kelasWali' => $kelasWali,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════
       EDIT — Halaman edit terpisah (jika ada)
    ═══════════════════════════════════════════════════════════ */
    public function edit(): View
    {
        $user = Auth::user()->fresh();
        $user->loadMissing(['guru']);

        $kelasWali = $this->resolveKelasWali($user);
        $kelasList = $this->getKelasList();

        return view('guru.profil-edit', compact('user', 'kelasList', 'kelasWali'));
    }

    /* ═══════════════════════════════════════════════════════════
       UPDATE — Proses simpan, DIPECAH PER-SECTION.
       Tiap modal di Blade hanya mengirim field miliknya sendiri
       (lihat <input type="hidden" name="_section"> di tiap <form>),
       jadi validasi & penyimpanan mengikuti $_section yang benar-
       benar dikirim, bukan divalidasi sekaligus semua field.
    ═══════════════════════════════════════════════════════════ */
    public function update(Request $request): RedirectResponse
    {
        $user    = $request->user();
        $guru    = $user->guru ?? $user->guru()->firstOrCreate([]);
        $section = $request->input('_section', 'akun');

        // DEBUG SEMENTARA — hapus setelah masalah nama terkonfirmasi
        // beres. Cek isi baris ini di storage/logs/laravel.log.
        Log::info('[ProfilController@update] mulai', [
            'user_id'      => $user->id,
            'guru_id'      => $guru->id,
            'guru_user_id' => $guru->user_id,
            'section'      => $section,
            'payload'      => $request->except(['password', 'password_confirmation', 'photo']),
        ]);

        match ($section) {
            'akun'         => $this->updateAkun($request, $user, $guru),
            'identitas'    => $this->updateIdentitas($request, $user, $guru),
            'pribadi'      => $this->updatePribadi($request, $user, $guru),
            'kepegawaian'  => $this->updateKepegawaian($request, $guru),
            default        => $this->updateAkun($request, $user, $guru),
        };

        // DEBUG SEMENTARA — konfirmasi nilai akhir yang benar-benar
        // tersimpan di database setelah save().
        Log::info('[ProfilController@update] selesai', [
            'user_name_final' => $user->fresh()->name,
            'guru_nama_final' => $guru->fresh()->nama,
        ]);

        return redirect()
            ->route('guru.profil')
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('_section', $section);
    }

    /* ── SECTION: Profil Akun (nama akun, email, password, foto) ── */
    private function updateAkun(Request $request, $user, $guru): void
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',
                        'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'confirmed', Password::defaults()];
        }

        $data = $request->validate($rules);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->save();

        /* ── FIX UTAMA: sinkronkan ke tabel guru ──
         * Tabel admin (_table_guru.blade.php) menampilkan
         * $g->nama dengan prioritas di atas $user->name, jadi
         * kolom ini WAJIB ikut ter-update.
         */
        $guru->nama = $data['name'];
        $guru->save();
    }

    /* ── SECTION: Identitas & Tugas (NIP, wali kelas) ── */
    private function updateIdentitas(Request $request, $user, $guru): void
    {
        $rules = [
            'nip'        => ['nullable', 'string', 'max:30',
                             'unique:guru,nip,' . $guru->id],
            'wali_kelas' => ['nullable', 'exists:study_groups,id'],
        ];

        try {
            if (Schema::hasTable('gurus')) {
                $rules['nip'] = ['nullable', 'string', 'max:30',
                                 'unique:gurus,nip,' . $guru->id];
            }
        } catch (\Exception $e) {}

        $data = $request->validate($rules);

        $guru->nip = $data['nip'] ?? $guru->nip;
        $guru->save();

        /* Simpan wali kelas ke StudyGroup — homeroom_teacher_id
         * adalah FK ke users.id, jadi pakai $user->id, sama
         * seperti mekanisme yang dipakai admin di "Kelola Kelas".
         */
        $newKelasId = $data['wali_kelas'] ?? null;
        $this->updateWaliOnStudyGroups($user->id, $newKelasId);
    }

    /* ── SECTION: Data Pribadi (nama lengkap, JK, TTL, pendidikan) ── */
    private function updatePribadi(Request $request, $user, $guru): void
    {
        $data = $request->validate([
            'nama'                => ['nullable', 'string', 'max:255'],
            'tempat_lahir'        => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'       => ['nullable', 'date'],
            'jk'                  => ['nullable', 'in:L,P'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:100'],
        ]);

        $guru->nama                = $data['nama']                ?? $guru->nama;
        $guru->tempat_lahir        = $data['tempat_lahir']        ?? $guru->tempat_lahir;
        $guru->tanggal_lahir       = $data['tanggal_lahir']       ?? $guru->tanggal_lahir;
        $guru->jk                  = $data['jk']                  ?? $guru->jk;
        $guru->pendidikan_terakhir = $data['pendidikan_terakhir'] ?? $guru->pendidikan_terakhir;
        $guru->save();

        /* ── Sinkron balik ke users.name ──
         * Supaya "Nama Akun" tetap konsisten dengan Nama Lengkap
         * yang baru diubah di sini.
         */
        if (!empty($data['nama'])) {
            $user->name = $data['nama'];
            $user->save();
        }
    }

    /* ── SECTION: Data Kepegawaian ── */
    private function updateKepegawaian(Request $request, $guru): void
    {
        $data = $request->validate([
            'status_pegawai'    => ['nullable', 'string', 'max:100'],
            'pangkat_gol_ruang' => ['nullable', 'string', 'max:100'],
            'no_sk_pertama'     => ['nullable', 'string', 'max:150'],
            'no_sk_terakhir'    => ['nullable', 'string', 'max:150'],
        ]);

        $guru->status_pegawai    = $data['status_pegawai']    ?? $guru->status_pegawai;
        $guru->pangkat_gol_ruang = $data['pangkat_gol_ruang'] ?? $guru->pangkat_gol_ruang;
        $guru->no_sk_pertama     = $data['no_sk_pertama']     ?? $guru->no_sk_pertama;
        $guru->no_sk_terakhir    = $data['no_sk_terakhir']    ?? $guru->no_sk_terakhir;
        $guru->save();
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
            StudyGroup::where('homeroom_teacher_id', $userId)
                ->update(['homeroom_teacher_id' => null]);

            if ($newGroupId) {
                StudyGroup::where('id', $newGroupId)
                    ->update(['homeroom_teacher_id' => $userId]);
            }
        } catch (\Exception $e) {
            // Diam — jangan crash halaman profil
        }
    }
}
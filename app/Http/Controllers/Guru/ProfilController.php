<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
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
       HELPER — Deteksi kelas wali dari guru menggunakan
       multi-strategi (sama persis dengan DashboardController
       agar hasilnya konsisten di seluruh aplikasi).
    ═══════════════════════════════════════════════════════════ */
    private function resolveKelasWali($guru, $user): ?Kelas
    {
        if (!$guru) return null;

        $kelasWali = null;

        try {
            $kelasColumns = Schema::getColumnListing('kelas');

            // Strategi A: wali_guru_id di tabel kelas
            if (!$kelasWali && in_array('wali_guru_id', $kelasColumns)) {
                $kelasWali = Kelas::where('wali_guru_id', $guru->id)->first();
            }

            // Strategi B: wali_kelas_id di tabel kelas
            if (!$kelasWali && in_array('wali_kelas_id', $kelasColumns)) {
                $kelasWali = Kelas::where('wali_kelas_id', $guru->id)->first();
            }

            // Strategi C: guru_id + flag is_wali
            if (!$kelasWali && in_array('guru_id', $kelasColumns) && in_array('is_wali', $kelasColumns)) {
                $kelasWali = Kelas::where('guru_id', $guru->id)->where('is_wali', true)->first();
            }

            // Strategi D: wali_id
            if (!$kelasWali && in_array('wali_id', $kelasColumns)) {
                $kelasWali = Kelas::where('wali_id', $guru->id)->first();
            }

            // Strategi E: kolom kelas_id di tabel guru (guru menyimpan FK-nya sendiri)
            if (!$kelasWali) {
                $guruColumns = Schema::getColumnListing('guru');
                if (in_array('kelas_id', $guruColumns) && !empty($guru->kelas_id)) {
                    $kelasWali = Kelas::find($guru->kelas_id);
                }
            }

            // Strategi F: relasi waliKelas() di model Guru
            if (!$kelasWali && method_exists($guru, 'waliKelas')) {
                try {
                    $wk = $guru->waliKelas()->first();
                    $kelasWali = $wk?->kelas ?? $wk ?? null;
                } catch (\Exception $e) {}
            }

            // Strategi G: relasi kelas() langsung di model Guru
            if (!$kelasWali && method_exists($guru, 'kelas')) {
                try {
                    $kelasWali = $guru->kelas ?? $guru->kelas()->first() ?? null;
                } catch (\Exception $e) {}
            }

        } catch (\Exception $e) {
            $kelasWali = null;
        }

        return ($kelasWali instanceof Kelas) ? $kelasWali : null;
    }

    /* ═══════════════════════════════════════════════════════════
       SHOW — Halaman profil (read-only + modal edit)
    ═══════════════════════════════════════════════════════════ */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Eager-load semua relasi yang mungkin dipakai di view
        $user->loadMissing(['guru', 'guru.kelas']);

        $guru = $user->guru;

        /* ── Resolve kelas wali dengan multi-strategi ── */
        $kelasWali = $this->resolveKelasWali($guru, $user);

        // Inject ke object $guru agar view bisa akses $g->kelas_wali_resolved
        // tanpa mengubah struktur view yang sudah ada
        if ($guru && $kelasWali) {
            $guru->kelas           = $kelasWali; // overwrite / set relasi agar $g->kelas ada
            $guru->kelas_id        = $kelasWali->id;
            $guru->kelas_wali_id   = $kelasWali->id; // alias cadangan
        }

        /* ── Daftar kelas untuk dropdown modal ── */
        $kelasList = $this->getKelasList();

        return view('guru.profil', [
            'user'      => $user,
            'kelasList' => $kelasList,
            'kelasWali' => $kelasWali,   // variabel eksplisit tambahan
        ]);
    }

    /* ═══════════════════════════════════════════════════════════
       EDIT — Halaman edit terpisah (jika ada)
    ═══════════════════════════════════════════════════════════ */
    public function edit(): View
    {
        $user = Auth::user();
        $user->loadMissing(['guru', 'guru.kelas']);

        $guru      = $user->guru;
        $kelasWali = $this->resolveKelasWali($guru, $user);
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
            'wali_kelas' => ['nullable', 'exists:kelas,id'],

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

        /* ── Simpan wali kelas ──
         *  Strategi: coba simpan di kolom kelas_id tabel guru (paling umum).
         *  Jika struktur DB berbeda (kolom di tabel kelas), tangani di sini.
         */
        $newKelasId = $data['wali_kelas'] ?? null; // null = bukan wali kelas

        $guruColumns = Schema::getColumnListing(
            Schema::hasTable('guru') ? 'guru' : 'gurus'
        );

        if (in_array('kelas_id', $guruColumns)) {
            // FK ada di tabel guru — simpan langsung
            $guruData['kelas_id'] = $newKelasId;
        } else {
            // FK ada di tabel kelas (kolom wali_guru_id / wali_kelas_id / wali_id)
            $this->updateWaliOnKelasTable($guru->id, $newKelasId);
        }

        $guru->update($guruData);

        /* ── Flash section agar modal bisa re-open jika error ── */
        return redirect()
            ->route('guru.profil')
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('_section', $request->input('_section', 'akun'));
    }

    /* ═══════════════════════════════════════════════════════════
       PRIVATE HELPERS
    ═══════════════════════════════════════════════════════════ */

    /** Daftar kelas untuk dropdown, diurutkan & dengan label lengkap */
    private function getKelasList()
    {
        try {
            return Kelas::query()
                ->select('id', 'nama', 'tingkat', 'tahun_ajaran')
                ->orderBy('tahun_ajaran', 'desc')
                ->orderBy('tingkat')
                ->orderBy('nama')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Jika FK wali kelas ada di tabel kelas (bukan di tabel guru),
     * update baris lama (kosongkan) lalu set baris baru.
     */
    private function updateWaliOnKelasTable(int $guruId, ?int $newKelasId): void
    {
        try {
            $kelasColumns = Schema::getColumnListing('kelas');

            $waliCol = null;
            foreach (['wali_guru_id', 'wali_kelas_id', 'wali_id', 'guru_id'] as $col) {
                if (in_array($col, $kelasColumns)) { $waliCol = $col; break; }
            }

            if (!$waliCol) return;

            // Kosongkan kelas lama yang punya guru ini sebagai wali
            Kelas::where($waliCol, $guruId)->update([$waliCol => null]);

            // Set kelas baru
            if ($newKelasId) {
                Kelas::where('id', $newKelasId)->update([$waliCol => $guruId]);
            }
        } catch (\Exception $e) {
            // Diam — jangan crash halaman profil
        }
    }
}
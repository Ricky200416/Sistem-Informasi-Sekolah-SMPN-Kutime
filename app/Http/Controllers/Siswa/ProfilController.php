<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $user->load(['siswa.studyGroup', 'siswa.kelas']);

        $siswa      = $user->siswa;
        $studyGroup = $siswa?->studyGroup ?? $siswa?->kelas;

        /* ── Data Jadwal ─────────────────────────────── */
        $hariIni   = Carbon::now()->locale('id')->isoFormat('dddd');
        $hariIniDb = $hariIni;

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

            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $idx      = array_search($hariIniDb, $hariList);
            if ($idx !== false) {
                for ($i = 1; $i <= 6; $i++) {
                    $next = $hariList[($idx + $i) % 6];
                    if (isset($jadwalByDay[$next]) && $jadwalByDay[$next]->isNotEmpty()) {
                        $hariBerikutnya   = $next;
                        $jadwalBerikutnya = $jadwalByDay[$next];
                        break;
                    }
                }
            }
        }

        /* ── KPI ─────────────────────────────── */
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

        /* ── Pengumuman ─────────────────────────────── */
        $widgetPengumuman = Pengumuman::where('is_active', 1)
            ->where('show_di_dashboard', 1)
            ->whereIn('target_audience', ['siswa', 'semua'])
            ->latest()
            ->limit(4)
            ->get();

        return view('siswa.profil', compact(
            'user',
            'studyGroup',
            'jadwalHariIni',
            'jadwalByDay',
            'jadwalBerikutnya',
            'hariBerikutnya',
            'hariIni',
            'hariIniDb',
            'totalJadwal',
            'totalMapel',
            'totalGuru',
            'hariAktif',
            'totalJamPerMinggu',
            'widgetPengumuman'
        ));
    }

    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load(['siswa.studyGroup', 'siswa.kelas']);

        $studyGroup = $user->siswa?->studyGroup ?? $user->siswa?->kelas;

        return view('siswa.profil', [
            'user'       => $user,
            'studyGroup' => $studyGroup,
            'kelasList'  => Kelas::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user    = $request->user();
        $section = $request->input('_section', 'akun');

        // Pastikan relasi siswa ada
        $siswa = $user->siswa ?? $user->siswa()->create([
            'penerima_kps' => 0,
            'jk'           => 'L',
        ]);

        // ══════════════════════════════════════════════════════
        // SECTION: AKUN  (nama akun, email, foto, password)
        // ══════════════════════════════════════════════════════
        if ($section === 'akun') {
            $rules = [
                'name'  => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'photo' => ['nullable', 'image', 'max:2048'],
            ];

            if ($request->filled('password')) {
                $rules['password'] = ['required', 'string', 'confirmed', Password::defaults()];
            }

            $data = $request->validate($rules);

            // Upload Foto
            if ($request->hasFile('photo')) {
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }
                $user->photo = $request->file('photo')->store('photos', 'public');
                $user->save();
            }

            // Update Password
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
                $user->save();
            }

            // Update User
            $user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);

            return redirect()->route('siswa.profil')
                ->with('success', 'Profil akun berhasil diperbarui.');
        }

        // ══════════════════════════════════════════════════════
        // SECTION: IDENTITAS AKADEMIK
        // ══════════════════════════════════════════════════════
        if ($section === 'identitas') {
            $data = $request->validate([
                'nama'  => ['nullable', 'string', 'max:255'],
                'nidn'  => ['nullable', 'string', 'max:30', 'unique:siswas,nidn,' . $siswa->id],
                'nik'   => ['nullable', 'string', 'max:20'],
                'jk'    => ['nullable', 'in:L,P'],
                'shkun' => ['nullable', 'string', 'max:50'],
                'no_telp' => ['nullable', 'string', 'max:20'],
            ]);

            $siswa->update([
                'nama'    => $data['nama']    ?? $siswa->nama,
                'nidn'    => $data['nidn']    ?? $siswa->nidn,
                'nik'     => $data['nik']     ?? $siswa->nik,
                'jk'      => $data['jk']      ?? $siswa->jk,
                'shkun'   => $data['shkun']   ?? $siswa->shkun,
                'no_telp' => $data['no_telp'] ?? $siswa->no_telp,
            ]);

            return redirect()->route('siswa.profil')
                ->with('success', 'Identitas akademik berhasil diperbarui.');
        }

        // ══════════════════════════════════════════════════════
        // SECTION: DATA PRIBADI
        // ══════════════════════════════════════════════════════
        if ($section === 'pribadi') {
            $data = $request->validate([
                'jk'           => ['nullable', 'in:L,P'],
                'agama'        => ['nullable', 'string', 'max:20'],
                'tempat_lahir' => ['nullable', 'string', 'max:100'],
                'tgl_lahir'    => ['nullable', 'date'],
            ]);

            $siswa->update([
                'jk'           => $data['jk']           ?? $siswa->jk,
                'agama'        => $data['agama']        ?? $siswa->agama,
                'tempat_lahir' => $data['tempat_lahir'] ?? $siswa->tempat_lahir,
                'tgl_lahir'    => $data['tgl_lahir']    ?? $siswa->tgl_lahir,
            ]);

            return redirect()->route('siswa.profil')
                ->with('success', 'Data pribadi berhasil diperbarui.');
        }

        // ══════════════════════════════════════════════════════
        // SECTION: ALAMAT & BANTUAN
        // ══════════════════════════════════════════════════════
        if ($section === 'alamat') {
            $data = $request->validate([
                'alamat'             => ['nullable', 'string'],
                'rt'                 => ['nullable', 'string', 'max:10'],
                'rw'                 => ['nullable', 'string', 'max:10'],
                'dusun'              => ['nullable', 'string', 'max:100'],
                'kecamatan'          => ['nullable', 'string', 'max:100'],
                'kode_pos'           => ['nullable', 'string', 'max:10'],
                'jenis_tinggal'      => ['nullable', 'string', 'max:50'],
                'jalan_transportasi' => ['nullable', 'string', 'max:100'],
                'penerima_kps'       => ['nullable', 'in:Ya,Tidak'],
                'no_kps'             => ['nullable', 'string', 'max:50'],
            ]);

            $penerimaKps = ($data['penerima_kps'] ?? 'Tidak') === 'Ya' ? 1 : 0;

            $siswa->update([
                'alamat'             => $data['alamat']             ?? $siswa->alamat,
                'rt'                 => $data['rt']                 ?? $siswa->rt,
                'rw'                 => $data['rw']                 ?? $siswa->rw,
                'dusun'              => $data['dusun']              ?? $siswa->dusun,
                'kecamatan'          => $data['kecamatan']          ?? $siswa->kecamatan,
                'kode_pos'           => $data['kode_pos']           ?? $siswa->kode_pos,
                'jenis_tinggal'      => $data['jenis_tinggal']      ?? $siswa->jenis_tinggal,
                'jalan_transportasi' => $data['jalan_transportasi'] ?? $siswa->jalan_transportasi,
                'penerima_kps'       => $penerimaKps,
                'no_kps'             => $data['no_kps']             ?? $siswa->no_kps,
            ]);

            return redirect()->route('siswa.profil')
                ->with('success', 'Alamat & bantuan berhasil diperbarui.');
        }

        // Fallback jika _section tidak dikenal
        return redirect()->route('siswa.profil')
            ->with('error', 'Terjadi kesalahan, silakan coba lagi.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AlumniExport;
use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Pdf;
use Illuminate\Support\Str;

class AlumniController extends Controller
{
    /**
     * Halaman utama Data Alumni: daftar, pencarian, filter tahun lulus,
     * plus beberapa KPI ringkas untuk admin.
     */
    public function index(Request $request)
    {
        $query = Alumni::query()
            ->cari($request->get('q'))
            ->when($request->filled('tahun_lulus'), fn ($q) => $q->tahun($request->get('tahun_lulus')));

        $alumni = $query->orderByDesc('tahun_lulus')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $tahunList = Alumni::select('tahun_lulus')
            ->distinct()
            ->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        $totalAlumni     = Alumni::count();
        $totalTahunIni   = Alumni::where('tahun_lulus', date('Y'))->count();
        $siswaAktifCount = Siswa::where('status', 'aktif')->count();

        return view('admin.alumni.index', compact(
            'alumni', 'tahunList', 'totalAlumni', 'totalTahunIni', 'siswaAktifCount'
        ));
    }

    /**
     * Detail satu data alumni (dipanggil via fetch/AJAX untuk mengisi
     * modal detail). Menyertakan status is_editable & sisa waktu edit.
     */
    public function show(Alumni $alumni)
    {
        return response()->json(array_merge($alumni->toArray(), [
            'is_editable'         => $alumni->is_editable,
            'edit_deadline'       => $alumni->edit_deadline->toDateTimeString(),
            'edit_time_left_label'=> $alumni->edit_time_left_label,
        ]));
    }

    /**
     * Data alumni untuk mengisi MODAL EDIT. Ditolak (403) kalau window
     * edit 2 hari sudah lewat — cegah bypass lewat request langsung.
     */
    public function edit(Alumni $alumni)
    {
        if (!$alumni->is_editable) {
            return response()->json([
                'message' => 'Batas waktu 2 hari untuk mengedit data kelulusan telah berakhir. Data ini hanya dapat dilihat.',
            ], 403);
        }

        return response()->json($alumni);
    }

    /**
     * Simpan perubahan data alumni. Hanya diproses jika masih dalam
     * window edit 2 hari sejak proses kelulusan dijalankan.
     */
    public function update(Request $request, Alumni $alumni)
    {
        if (!$alumni->is_editable) {
            return back()->with('error', 'Batas waktu 2 hari untuk mengedit data kelulusan sudah berakhir. Data alumni ini tidak dapat diubah lagi, hanya dapat dilihat.');
        }

        $validated = $request->validate([
            'nama'           => ['required', 'string', 'max:255'],
            'nidn'           => ['nullable', 'string', 'max:50'],
            'nik'            => ['nullable', 'string', 'max:50'],
            'jk'             => ['nullable', 'in:L,P'],
            'agama'          => ['nullable', 'string', 'max:50'],
            'tempat_lahir'   => ['nullable', 'string', 'max:100'],
            'tgl_lahir'      => ['nullable', 'date'],
            'no_telp'        => ['nullable', 'string', 'max:30'],
            'alamat'         => ['nullable', 'string', 'max:500'],
            'rt'             => ['nullable', 'string', 'max:10'],
            'rw'             => ['nullable', 'string', 'max:10'],
            'dusun'          => ['nullable', 'string', 'max:100'],
            'kecamatan'      => ['nullable', 'string', 'max:100'],
            'kelas_terakhir' => ['nullable', 'string', 'max:50'],
            'tahun_lulus'    => ['required', 'digits:4'],
            'tanggal_lulus'  => ['required', 'date'],
            'no_ijazah'      => ['nullable', 'string', 'max:100'],
            'catatan'        => ['nullable', 'string', 'max:500'],
        ]);

        $alumni->update($validated);

        return back()->with('success', 'Data alumni berhasil diperbarui.');
    }

    /**
     * Daftar siswa yang masih berstatus aktif, dikelompokkan per kelas,
     * untuk modal "Luluskan Siswa". Admin bisa memilih satu kelas penuh,
     * beberapa siswa lintas kelas, atau semua siswa sekaligus.
     */
    public function daftarSiswaAktif(Request $request)
    {
        $query = Siswa::with(['user', 'kelas'])->where('status', 'aktif');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->get('kelas_id'));
        }

        $siswa = $query->get()
            ->sortBy(fn ($s) => $s->kelas?->nama . '-' . ($s->nama ?? $s->user?->name))
            ->map(fn ($s) => [
                'id'       => $s->id,
                'nama'     => $s->nama ?? $s->user?->name,
                'nidn'     => $s->nidn,
                'kelas'    => $s->kelas?->nama ?? '—',
                'kelas_id' => $s->kelas_id,
            ])->values();

        $kelasList = Kelas::orderBy('nama')->get(['id', 'nama']);

        return response()->json(['siswa' => $siswa, 'kelas' => $kelasList]);
    }

    /**
     * Proses meluluskan sekumpulan siswa sekaligus:
     * - Data siswa disalin (snapshot) ke tabel alumni, termasuk hash
     *   password dan kelas, supaya bisa dipulihkan penuh jika dibatalkan.
     * - Baris Siswa & User dihapus PERMANEN, sehingga nama mereka otomatis
     *   hilang dari daftar Kelola User dan tidak bisa login lagi.
     */
public function graduate(Request $request)
{
    $validated = $request->validate([
        'siswa_ids'        => ['required', 'array', 'min:1'],
        'siswa_ids.*'      => ['integer', 'exists:siswas,id'],
        'tahun_lulus'      => ['required', 'digits:4'],
        'tanggal_lulus'    => ['required', 'date'],
        'no_ijazah_prefix' => ['nullable', 'string', 'max:50'],
        'catatan'          => ['nullable', 'string', 'max:500'],
    ]);

    $siswaList = Siswa::with(['user', 'kelas'])
        ->whereIn('id', $validated['siswa_ids'])
        ->where('status', 'aktif')
        ->get();

    if ($siswaList->isEmpty()) {
        return back()->with('error', 'Tidak ada siswa aktif yang valid untuk diluluskan.');
    }

    DB::transaction(function () use ($siswaList, $validated) {
        foreach ($siswaList as $siswa) {
            Alumni::create([
                'siswa_id'           => $siswa->id,
                'user_id'            => $siswa->user_id,
                'password_snapshot'  => $siswa->user?->password,
                'kelas_id_snapshot'  => $siswa->kelas_id,
                'nama'               => $siswa->nama ?? $siswa->user?->name,
                'email'              => $siswa->user?->email,
                'nidn'               => $siswa->nidn,
                'nik'                => $siswa->nik,
                'jk'                 => $siswa->jk,
                'agama'              => $siswa->agama,
                'tempat_lahir'       => $siswa->tempat_lahir,
                'tgl_lahir'          => $siswa->tgl_lahir,
                'no_telp'            => $siswa->no_telp,
                'alamat'             => $siswa->alamat,
                'rt'                 => $siswa->rt,
                'rw'                 => $siswa->rw,
                'dusun'              => $siswa->dusun,
                'kecamatan'          => $siswa->kecamatan,
                'kode_pos'           => $siswa->kode_pos,
                'jenis_tinggal'      => $siswa->jenis_tinggal,
                'jalan_transportasi' => $siswa->jalan_transportasi,
                // Normalisasi eksplisit ke string 'Ya'/'Tidak', menangani
                // kemungkinan data lama tersimpan sebagai boolean/1/0/'yes'.
                'penerima_kps'       => $this->normalizeKps($siswa->penerima_kps),
                'no_kps'             => $siswa->no_kps,
                'foto'               => $siswa->user?->photo,
                'kelas_terakhir'     => $siswa->kelas?->nama,
                'tahun_lulus'        => $validated['tahun_lulus'],
                'tanggal_lulus'      => $validated['tanggal_lulus'],
                'no_ijazah'          => !empty($validated['no_ijazah_prefix'])
                    ? $validated['no_ijazah_prefix'] . '-' . str_pad($siswa->id, 4, '0', STR_PAD_LEFT)
                    : null,
                'catatan'            => $validated['catatan'] ?? null,
            ]);

            $userId = $siswa->user_id;

            $siswa->delete();

            if ($userId) {
                User::where('id', $userId)->delete();
            }
        },
    });

    return redirect()->route('admin.alumni.index')
        ->with('success', count($siswaList) . ' siswa berhasil diluluskan. Data tersimpan di Data Alumni dan akunnya telah dihapus dari Kelola User.');
}

/**
 * Normalisasi berbagai kemungkinan representasi nilai KPS (boolean,
 * angka, atau string) menjadi string konsisten 'Ya' / 'Tidak', supaya
 * cocok dengan tipe kolom penerima_kps di tabel alumni.
 */
private function normalizeKps($value): string
{
    if (is_bool($value)) {
        return $value ? 'Ya' : 'Tidak';
    }

    $normalized = strtolower(trim((string) $value));

    return in_array($normalized, ['ya', 'yes', '1', 'y', 'true'], true) ? 'Ya' : 'Tidak';
}

    /**
     * Membatalkan status alumni: membuat ULANG akun User + Siswa dari
     * snapshot yang tersimpan (karena baris aslinya sudah dihapus
     * permanen saat diluluskan), lalu menghapus baris alumni ini.
     */
    public function batalkan(Alumni $alumni)
    {
        if (User::where('email', $alumni->email)->exists()) {
            return back()->with('error', 'Tidak bisa membatalkan: email ' . $alumni->email . ' sudah dipakai oleh akun lain di sistem. Ubah/hapus akun tersebut terlebih dahulu.');
        }

        DB::transaction(function () use ($alumni) {
            $kelasMasihAda = $alumni->kelas_id_snapshot
                ? Kelas::where('id', $alumni->kelas_id_snapshot)->exists()
                : false;

            $newUser = User::create([
                'name'      => $alumni->nama,
                'email'     => $alumni->email,
                // Password hash lama dipulihkan apa adanya (bukan di-hash
                // ulang) supaya password asli siswa tetap berfungsi.
                'password'  => $alumni->password_snapshot ?? Hash::make(Str::random(16)),
                'role'      => 'siswa',
                'is_active' => true,
            ]);

            $newUser->siswa()->create([
                'nama'               => $alumni->nama,
                'nidn'               => $alumni->nidn,
                'nik'                => $alumni->nik,
                'jk'                 => $alumni->jk,
                'agama'              => $alumni->agama,
                'tempat_lahir'       => $alumni->tempat_lahir,
                'tgl_lahir'          => $alumni->tgl_lahir,
                'no_telp'            => $alumni->no_telp,
                'alamat'             => $alumni->alamat,
                'rt'                 => $alumni->rt,
                'rw'                 => $alumni->rw,
                'dusun'              => $alumni->dusun,
                'kecamatan'          => $alumni->kecamatan,
                'kode_pos'           => $alumni->kode_pos,
                'jenis_tinggal'      => $alumni->jenis_tinggal,
                'jalan_transportasi' => $alumni->jalan_transportasi,
                'penerima_kps'       => $alumni->penerima_kps,
                'no_kps'             => $alumni->no_kps,
                'kelas_id'           => $kelasMasihAda ? $alumni->kelas_id_snapshot : null,
                'status'             => 'aktif',
                'tanggal_lulus'      => null,
            ]);

            $alumni->delete();
        });

        return back()->with('success', 'Status alumni dibatalkan. Akun siswa dipulihkan dan kembali muncul di Kelola User dengan status aktif.');
    }

    /**
     * Hapus permanen satu data alumni dari arsip (tidak memulihkan akun).
     */
    public function destroy(Alumni $alumni)
    {
        $alumni->delete();

        return back()->with('success', 'Data alumni berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $tahun    = $request->get('tahun_lulus');
        $filename = 'Data-Alumni-SMPN-Kutime' . ($tahun ? "-{$tahun}" : '') . '.xlsx';

        return Excel::download(new AlumniExport($tahun), $filename);
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->get('tahun_lulus');

        $alumni = Alumni::query()
            ->when($tahun, fn ($q) => $q->tahun($tahun))
            ->orderByDesc('tahun_lulus')
            ->orderBy('nama')
            ->get();

        $pdf = Pdf::loadView('admin.alumni.pdf', compact('alumni', 'tahun'));

        return $pdf->download('Data-Alumni-SMPN-Kutime' . ($tahun ? "-{$tahun}" : '') . '.pdf');
    }
}
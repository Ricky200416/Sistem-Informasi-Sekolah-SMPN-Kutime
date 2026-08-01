<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AlumniExport;
use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\Pdf;

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

        $totalAlumni    = Alumni::count();
        $totalTahunIni  = Alumni::where('tahun_lulus', date('Y'))->count();
        $siswaAktifCount = Siswa::where('status', 'aktif')->count();

        return view('admin.alumni.index', compact(
            'alumni', 'tahunList', 'totalAlumni', 'totalTahunIni', 'siswaAktifCount'
        ));
    }

    /**
     * Detail satu data alumni (dipanggil via fetch/AJAX untuk mengisi modal).
     */
    public function show(Alumni $alumni)
    {
        return response()->json($alumni);
    }

    /**
     * Daftar siswa yang masih berstatus aktif, untuk dipilih pada
     * modal "Luluskan Siswa". Bisa difilter per kelas.
     */
    public function daftarSiswaAktif(Request $request)
    {
        $query = Siswa::with(['user', 'kelas'])->where('status', 'aktif');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->get('kelas_id'));
        }

        $siswa = $query->get()->map(fn ($s) => [
            'id'    => $s->id,
            'nama'  => $s->nama ?? $s->user?->name,
            'nidn'  => $s->nidn,
            'kelas' => $s->kelas?->nama ?? '—',
        ])->values();

        $kelasList = Kelas::orderBy('name')->get(['id', 'name']);

        return response()->json(['siswa' => $siswa, 'kelas' => $kelasList]);
    }

    /**
     * Proses meluluskan sekumpulan siswa sekaligus:
     * - Data siswa disalin (snapshot) ke tabel alumni
     * - Status siswa diubah menjadi 'lulus'
     * - Akun user siswa dinonaktifkan (is_active = false) supaya
     *   tidak bisa login lagi, tapi datanya tetap ada untuk histori.
     */
    public function graduate(Request $request)
    {
        $validated = $request->validate([
            'siswa_ids'          => ['required', 'array', 'min:1'],
            'siswa_ids.*'        => ['integer', 'exists:siswas,id'],
            'tahun_lulus'        => ['required', 'digits:4'],
            'tanggal_lulus'      => ['required', 'date'],
            'no_ijazah_prefix'   => ['nullable', 'string', 'max:50'],
            'catatan'            => ['nullable', 'string', 'max:500'],
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
                    'penerima_kps'       => $siswa->penerima_kps,
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

                $siswa->update([
                    'status'         => 'lulus',
                    'tanggal_lulus'  => $validated['tanggal_lulus'],
                ]);

                $siswa->user?->update(['is_active' => false]);
            }
        });

        return redirect()->route('admin.alumni.index')
            ->with('success', count($siswaList) . ' siswa berhasil diluluskan dan datanya tersimpan di Data Alumni.');
    }

    /**
     * Membatalkan status alumni: mengembalikan siswa ke status aktif
     * (misal terjadi kesalahan input saat meluluskan).
     */
    public function batalkan(Alumni $alumni)
    {
        if (!$alumni->siswa_id || !$alumni->siswa) {
            return back()->with('error', 'Data siswa asal tidak ditemukan (mungkin sudah dihapus), tidak bisa dibatalkan otomatis.');
        }

        DB::transaction(function () use ($alumni) {
            $alumni->siswa->update([
                'status'        => 'aktif',
                'tanggal_lulus' => null,
            ]);
            $alumni->siswa->user?->update(['is_active' => true]);
            $alumni->delete();
        });

        return back()->with('success', 'Status alumni dibatalkan. Siswa dikembalikan ke status aktif.');
    }

    /**
     * Hapus permanen satu data alumni (tidak memengaruhi data siswa asal).
     */
    public function destroy(Alumni $alumni)
    {
        $alumni->delete();

        return back()->with('success', 'Data alumni berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $tahun = $request->get('tahun_lulus');
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
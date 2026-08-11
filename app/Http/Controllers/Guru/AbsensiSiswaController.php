<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AbsensiSiswaController extends Controller
{
    /**
     * Halaman utama absensi siswa untuk guru.
     *
     * PENTING: absensi yang ditampilkan/diprefill HANYA milik guru yang
     * sedang login (dan mata pelajaran yang sama jika sudah dipilih).
     * Ini supaya guru A tidak melihat/menimpa absensi guru B di kelas
     * & tanggal yang sama.
     */
    public function index(Request $request)
    {
        $tanggal       = $request->input('tanggal', now()->toDateString());
        $kelasId       = $request->input('kelas_id');
        $mataPelajaran = trim((string) $request->input('mata_pelajaran', ''));

        $guru = Auth::user()->guru; // guru yang sedang login

        $kelasList = Kelas::orderBy('nama')->get();

        $siswaList     = collect();
        $absensiHari   = collect();
        $sudahDisimpan = false;
        $ringkasan     = [];

        if ($kelasId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                ->with(['user', 'kelas'])
                ->orderBy('nama')
                ->get();

            if ($siswaList->isEmpty()) {
                $siswaList = Siswa::whereHas('user', fn($q) => $q->where('role', 'siswa'))
                    ->where('kelas_id', $kelasId)
                    ->with(['user', 'kelas'])
                    ->orderBy('nama')
                    ->get();
            }

            $siswaIds = $siswaList->pluck('id');

            /*
            |------------------------------------------------------------------
            | Ambil absensi hari ini HANYA milik guru + mapel yang sedang aktif.
            | Ini "sesi" absensi guru ini sendiri — tidak mencampur punya guru lain.
            |------------------------------------------------------------------
            */
            $absensiQuery = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tanggal)
                ->where('guru_id', $guru?->id);

            if ($mataPelajaran !== '') {
                $absensiQuery->where('mata_pelajaran', $mataPelajaran);
            }

            $absensiHari = $absensiQuery->get()->keyBy('siswa_id');

            $sudahDisimpan = $absensiHari->isNotEmpty();

            /*
            |------------------------------------------------------------------
            | Ringkasan bulan ini — HANYA rekap milik guru yang login sendiri,
            | supaya angka tidak bercampur dengan absensi guru mapel lain.
            |------------------------------------------------------------------
            */
            $bulan = Carbon::parse($tanggal)->month;
            $tahun = Carbon::parse($tanggal)->year;

            $absBulan = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->where('guru_id', $guru?->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $ringkasan = [
                'hadir' => $absBulan->where('status', 'hadir')->count(),
                'sakit' => $absBulan->where('status', 'sakit')->count(),
                'izin'  => $absBulan->where('status', 'izin')->count(),
                'alpha' => $absBulan->where('status', 'alpha')->count(),
            ];
        }

        $hariIni = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');

        // Daftar mapel yang pernah dipakai guru ini (untuk datalist/autocomplete)
        $mapelList = AbsensiSiswa::where('guru_id', $guru?->id)
            ->whereNotNull('mata_pelajaran')
            ->where('mata_pelajaran', '!=', '')
            ->distinct()
            ->orderBy('mata_pelajaran')
            ->pluck('mata_pelajaran');

        return view('guru.absensi-siswa.index', compact(
            'kelasList', 'kelasId', 'tanggal', 'hariIni',
            'siswaList', 'absensiHari', 'sudahDisimpan', 'ringkasan',
            'mataPelajaran', 'mapelList'
        ));
    }

    /**
     * Simpan / update absensi satu sesi (satu guru, satu mapel, satu tanggal).
     * Dipanggil via AJAX dari tombol "Simpan Absensi".
     */
    public function store(Request $request)
    {
        $siswaTable = (new Siswa())->getTable();
        $kelasTable = (new Kelas())->getTable();

        $request->validate([
            'tanggal'              => 'required|date',
            'kelas_id'             => "required|exists:{$kelasTable},id",
            'mata_pelajaran'       => 'required|string|max:100',
            'absensi'              => 'required|array',
            'absensi.*.siswa_id'   => "required|exists:{$siswaTable},id",
            'absensi.*.status'     => 'required|in:hadir,sakit,izin,alpha',
            'absensi.*.keterangan' => 'nullable|string|max:500',
        ]);

        $guru = Auth::user()->guru;

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan data guru. Hubungi admin.',
            ], 403);
        }

        $tanggal       = $request->tanggal;
        $mataPelajaran = $request->mata_pelajaran;
        $hari          = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');

        $saved = 0;
        foreach ($request->absensi as $item) {
            /*
            |------------------------------------------------------------------
            | KUNCI UTAMA: siswa_id + tanggal + guru_id + mata_pelajaran.
            | - Guru yang sama, mapel & tanggal sama -> data DIPERBARUI (update).
            | - Guru lain (atau mapel beda) -> record BARU dibuat, TIDAK
            |   menimpa absensi guru/mapel lain di kelas & tanggal yang sama.
            |------------------------------------------------------------------
            */
            AbsensiSiswa::updateOrCreate(
                [
                    'siswa_id'       => $item['siswa_id'],
                    'tanggal'        => $tanggal,
                    'guru_id'        => $guru->id,
                    'mata_pelajaran' => $mataPelajaran,
                ],
                [
                    'hari'       => $hari,
                    'status'     => $item['status'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]
            );
            $saved++;
        }

        return response()->json([
            'success' => true,
            'message' => "Absensi {$mataPelajaran} tanggal {$tanggal} berhasil disimpan.",
            'total'   => $saved,
        ]);
    }

    /**
     * Rekap absensi per siswa dalam satu bulan — HANYA milik guru yang login
     * (satu guru mapel hanya melihat rekap yang dia input sendiri).
     */
    public function rekap(Request $request)
    {
        $kelasId       = $request->input('kelas_id');
        $bulan         = (int) $request->input('bulan', now()->month);
        $tahun         = (int) $request->input('tahun', now()->year);
        $mataPelajaran = trim((string) $request->input('mata_pelajaran', ''));

        $guru = Auth::user()->guru;

        $bulanList = [
            1=>'Januari',  2=>'Februari', 3=>'Maret',    4=>'April',
            5=>'Mei',      6=>'Juni',     7=>'Juli',      8=>'Agustus',
            9=>'September',10=>'Oktober', 11=>'November', 12=>'Desember',
        ];

        $kelasList  = Kelas::orderBy('nama')->get();
        $siswaList  = collect();
        $rekapData  = [];
        $jumlahHari = Carbon::create($tahun, $bulan, 1)->daysInMonth;

        $mapelList = AbsensiSiswa::where('guru_id', $guru?->id)
            ->whereNotNull('mata_pelajaran')
            ->where('mata_pelajaran', '!=', '')
            ->distinct()
            ->orderBy('mata_pelajaran')
            ->pluck('mata_pelajaran');

        if ($kelasId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                ->with('user')
                ->orderBy('nama')
                ->get();

            if ($siswaList->isNotEmpty()) {
                $query = AbsensiSiswa::whereIn('siswa_id', $siswaList->pluck('id'))
                    ->where('guru_id', $guru?->id) // ← hanya rekap milik guru ini
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);

                if ($mataPelajaran !== '') {
                    $query->where('mata_pelajaran', $mataPelajaran);
                }

                $absensiRaw = $query->get();

                foreach ($siswaList as $s) {
                    $abs = $absensiRaw->where('siswa_id', $s->id);
                    $rekapData[$s->id] = [
                        'hadir' => $abs->where('status', 'hadir')->count(),
                        'sakit' => $abs->where('status', 'sakit')->count(),
                        'izin'  => $abs->where('status', 'izin')->count(),
                        'alpha' => $abs->where('status', 'alpha')->count(),
                    ];
                }
            }
        }

        return view('guru.absensi-siswa.rekap', compact(
            'kelasList', 'kelasId', 'bulan', 'tahun', 'bulanList',
            'siswaList', 'rekapData', 'jumlahHari',
            'mataPelajaran', 'mapelList'
        ));
    }
}
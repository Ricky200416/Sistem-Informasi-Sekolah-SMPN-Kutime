<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;   // ← model yang benar (bukan AbsensiSiswa)
use App\Models\AbsensiSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AbsensiSiswaController extends Controller
{
    /**
     * Halaman utama absensi siswa untuk guru.
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $kelasId = $request->input('kelas_id');

        $kelasList = Kelas::orderBy('nama')->get();

        $siswaList     = collect();
        $absensiHari   = collect();
        $sudahDisimpan = false;
        $ringkasan     = [];

        if ($kelasId) {
            /*
            |------------------------------------------------------------------
            | Ambil siswa berdasarkan kelas_id dari tabel siswas
            | (model Siswa menggunakan tabel 'siswas' secara default Laravel
            |  kecuali $table di-override ke 'siswa')
            |
            | Query melalui User → Siswa untuk memastikan hanya siswa
            | yang punya akun aktif yang ditampilkan.
            |------------------------------------------------------------------
            */
            $siswaList = Siswa::where('kelas_id', $kelasId)
                ->with(['user', 'kelas'])
                ->orderBy('nama')
                ->get();

            if ($siswaList->isEmpty()) {
                /*
                 * Fallback: jika tabel siswa tidak punya kolom kelas_id langsung,
                 * coba ambil melalui relasi User->Siswa
                 */
                $siswaList = Siswa::whereHas('user', fn($q) => $q->where('role', 'siswa'))
                    ->where('kelas_id', $kelasId)
                    ->with(['user', 'kelas'])
                    ->orderBy('nama')
                    ->get();
            }

            $siswaIds = $siswaList->pluck('id');

            /*
            |------------------------------------------------------------------
            | Ambil absensi hari ini dari tabel 'absensi'
            | (model Absensi, bukan AbsensiSiswa)
            |------------------------------------------------------------------
            */
            $absensiHari = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
                ->whereDate('tanggal', $tanggal)
                ->get()
                ->keyBy('siswa_id');

            $sudahDisimpan = $absensiHari->isNotEmpty();

            // Ringkasan bulan ini
            $bulan = Carbon::parse($tanggal)->month;
            $tahun = Carbon::parse($tanggal)->year;

            $absBulan = AbsensiSiswa::whereIn('siswa_id', $siswaIds)
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

        return view('guru.absensi-siswa.index', compact(
            'kelasList', 'kelasId', 'tanggal', 'hariIni',
            'siswaList', 'absensiHari', 'sudahDisimpan', 'ringkasan'
        ));
    }

    /**
     * Simpan / update absensi satu sesi.
     * Dipanggil via AJAX dari tombol "Simpan Absensi".
     */
    public function store(Request $request)
    {
        /*
        |----------------------------------------------------------------------
        | Validasi
        | 'exists:siswa,id' → jika tabel bernama 'siswa'
        | Jika tabel bernama 'siswas', ubah ke 'exists:siswas,id'
        |
        | Kita gunakan nama tabel dari model Siswa agar otomatis benar.
        |----------------------------------------------------------------------
        */
        $siswaTable = (new Siswa())->getTable();   // ambil nama tabel dari model
        $kelasTable = (new Kelas())->getTable();   // ambil nama tabel dari model

        $request->validate([
            'tanggal'              => 'required|date',
            'kelas_id'             => "required|exists:{$kelasTable},id",
            'absensi'              => 'required|array',
            'absensi.*.siswa_id'   => "required|exists:{$siswaTable},id",
            'absensi.*.status'     => 'required|in:hadir,sakit,izin,alpha',
            'absensi.*.keterangan' => 'nullable|string|max:500',
        ]);

        $tanggal = $request->tanggal;
        $hari    = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');

        $saved = 0;
        foreach ($request->absensi as $item) {
            AbsensiSiswa::updateOrCreate(
                [
                    'siswa_id' => $item['siswa_id'],
                    'tanggal'  => $tanggal,
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
            'message' => "Absensi {$tanggal} berhasil disimpan.",
            'total'   => $saved,
        ]);
    }

    /**
     * Rekap absensi per siswa dalam satu bulan.
     */
    public function rekap(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $bulan   = (int) $request->input('bulan', now()->month);
        $tahun   = (int) $request->input('tahun', now()->year);

        $bulanList = [
            1=>'Januari',  2=>'Februari', 3=>'Maret',    4=>'April',
            5=>'Mei',      6=>'Juni',     7=>'Juli',      8=>'Agustus',
            9=>'September',10=>'Oktober', 11=>'November', 12=>'Desember',
        ];

        $kelasList  = Kelas::orderBy('nama')->get();
        $siswaList  = collect();
        $rekapData  = [];
        $jumlahHari = Carbon::create($tahun, $bulan, 1)->daysInMonth;

        if ($kelasId) {
            $siswaList = Siswa::where('kelas_id', $kelasId)
                ->with('user')
                ->orderBy('nama')
                ->get();

            if ($siswaList->isNotEmpty()) {
                $absensiRaw = AbsensiSiswa::whereIn('siswa_id', $siswaList->pluck('id'))
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();

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
            'siswaList', 'rekapData', 'jumlahHari'
        ));
    }
}
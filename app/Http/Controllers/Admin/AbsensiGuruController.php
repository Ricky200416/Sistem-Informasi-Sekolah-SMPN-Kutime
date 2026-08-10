<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AbsensiGuru;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AbsensiGuruExport;

class AbsensiGuruController extends Controller
{
    /**
     * Daftar nama bulan dalam Bahasa Indonesia.
     */
    private array $bulanList = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Mengambil data absensi guru berdasarkan bulan dan tahun.
     */
    private function getAttendanceData(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // Validasi bulan
        if ($bulan < 1 || $bulan > 12) {
            $bulan = now()->month;
        }

        // Validasi tahun
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = now()->year;
        }

        // Jumlah hari dalam bulan
        $jumlahHari = Carbon::create($tahun, $bulan, 1)->daysInMonth;

        /**
         * Ambil daftar guru.
         */
        $daftarGuru = User::where('role', 'guru')
            ->with([
                'guru',
                'guru.kelas',
            ])
            ->orderBy('name')
            ->get();

        /**
         * Ambil data absensi guru.
         */
        $absensiRaw = AbsensiGuru::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        /**
         * Susun data absensi berdasarkan:
         * guru_id -> tanggal/hari.
         */
        $absensiData = [];

        foreach ($absensiRaw as $abs) {
            $hari = (int) Carbon::parse($abs->tanggal)->day;

            $absensiData[$abs->guru_id][$hari] = $abs;
        }

        /**
         * Ringkasan absensi guru.
         */
        $ringkasan = [
            'total' => $absensiRaw->count(),
            'hadir' => $absensiRaw->where('status', 'P')->count(),
            'sakit' => $absensiRaw->where('status', 'S')->count(),
            'izin'  => $absensiRaw->where('status', 'I')->count(),
            'alpha' => $absensiRaw->where('status', 'A')->count(),
            'telat' => $absensiRaw->where('status', 'L')->count(),
        ];

        /**
         * Daftar kelas.
         */
        $daftarKelas = \App\Models\Kelas::orderBy('nama')->get();

        /**
         * Nama hari.
         */
        $namaHari = [
            'Min',
            'Sen',
            'Sel',
            'Rab',
            'Kam',
            'Jum',
            'Sab',
        ];

        return [
            'daftarGuru'  => $daftarGuru,
            'absensiData' => $absensiData,
            'ringkasan'   => $ringkasan,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'bulanList'   => $this->bulanList,
            'jumlahHari'  => $jumlahHari,
            'namaHari'    => $namaHari,
            'daftarKelas' => $daftarKelas,
        ];
    }

    /**
     * Halaman utama absensi guru.
     */
    public function index(Request $request)
    {
        /**
         * Ambil data utama absensi guru.
         */
        $data = $this->getAttendanceData($request);

        /**
         * Data tambahan untuk tab absensi siswa.
         */
        if ($request->input('tab') === 'siswa') {

            $kelasListSiswa = \App\Models\Kelas::orderBy('nama')->get();

            $kelasIdSiswa = $request->input('kelas_id');

            $tanggalSiswa = $request->input(
                'tanggal',
                now()->toDateString()
            );

            /**
             * Default data siswa.
             */
            $siswaList = collect();

            $absensiHariSiswa = collect();

            $ringkasanSiswa = [
                'hadir' => 0,
                'sakit' => 0,
                'izin'  => 0,
                'alpha' => 0,
            ];

            /**
             * Jika kelas dipilih, ambil siswa dan absensinya.
             */
            if ($kelasIdSiswa) {

                $siswaList = \App\Models\Siswa::where(
                    'kelas_id',
                    $kelasIdSiswa
                )
                    ->with('user')
                    ->orderBy('nama')
                    ->get();

                $absensiHariSiswa = \App\Models\AbsensiSiswa::where(
                    'kelas_id',
                 ,   $kelasIdSiswa
                )
                    ->whereDate('tanggal', $tanggalSiswa)
                    ->get()
                    ->keyBy('siswa_id');

                /**
                 * Ringkasan absensi siswa.
                 */
                $ringkasanSiswa = [
                    'hadir' => $absensiHariSiswa
                        ->where('status', 'hadir')
                        ->count(),

                    'sakit' => $absensiHariSiswa
                        ->where('status', 'sakit')
                        ->count(),

                    'izin' => $absensiHariSiswa
                        ->where('status', 'izin')
                        ->count(),

                    'alpha' => $absensiHariSiswa
                        ->where('status', 'alpha')
                        ->count(),
                ];
            }

            /**
             * Gabungkan data siswa dengan data utama.
             */
            $data = array_merge($data, [
                'kelasListSiswa'    => $kelasListSiswa,
                'kelasIdSiswa'      => $kelasIdSiswa,
                'tanggalSiswa'      => $tanggalSiswa,
                'siswaList'         => $siswaList,
                'absensiHariSiswa'  => $absensiHariSiswa,
                'ringkasanSiswa'    => $ringkasanSiswa,
            ]);
        }

        /**
         * Tampilkan halaman absensi guru.
         */
        return view(
            'admin.absensi-guru.index',
            $data
        );
    }

    /**
     * Export data absensi guru ke Excel.
     *
     * Menggunakan PhpSpreadsheet secara langsung.
     */
    public function exportExcel(Request $request)
    {
        /**
         * Ambil data absensi.
         */
        $data = $this->getAttendanceData($request);

        /**
         * Buat spreadsheet baru.
         */
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        /**
         * Nama sheet.
         */
        $sheet->setTitle('Absensi Guru');

        /**
         * Tambahkan logo jika tersedia.
         */
        $logoPath = public_path(
            'images/logo-smpn-kutime.png'
        );

        if (file_exists($logoPath)) {

            $drawing = new Drawing();

            $drawing->setName(
                'Logo SMPN Kutime'
            );

            $drawing->setDescription(
                'Logo Sekolah'
            );

            $drawing->setPath(
                $logoPath
            );

            $drawing->setHeight(80);

            $drawing->setCoordinates(
                'A1'
            );

            $drawing->setOffsetX(10);

            $drawing->setOffsetY(10);

            $drawing->setWorksheet(
                $sheet
            );
        }

        /**
         * Judul laporan.
         */
        $sheet->setCellValue(
            'A3',
            'LAPORAN ABSENSI GURU'
        );

        /**
         * Informasi bulan dan tahun.
         */
        $sheet->setCellValue(
            'A4',
            'Bulan: ' .
            $data['bulanList'][$data['bulan']] .
            ' ' .
            $data['tahun']
        );

        /**
         * Buat object export.
         */
        $export = new AbsensiGuruExport(
            $data['daftarGuru'],
            $data['absensiData'],
            $data['bulan'],
            $data['tahun'],
            $data['jumlahHari'],
            $data['bulanList']
        );

        /**
         * Ambil data dari class export.
         */
        $excelData = $export->getData();

        /**
         * Baris awal data.
         */
        $startRow = 6;

        /**
         * Tulis data ke Excel.
         */
        foreach ($excelData as $rowIndex => $rowData) {

            $colIndex = 1;

            foreach ($rowData as $cellData) {

                $columnLetter =
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                        $colIndex
                    );

                $rowNumber =
                    $startRow + $rowIndex;

                $cellCoordinate =
                    $columnLetter . $rowNumber;

                $sheet->setCellValue(
                    $cellCoordinate,
                    $cellData
                );

                $colIndex++;
            }
        }

        /**
         * Auto-size semua kolom.
         */
        $highestColumn =
            $sheet->getHighestColumn();

        $highestColumnIndex =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                $highestColumn
            );

        for ($column = 1; $column <= $highestColumnIndex; $column++) {

            $columnLetter =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $column
                );

            $sheet
                ->getColumnDimension($columnLetter)
                ->setAutoSize(true);
        }

        /**
         * Styling header.
         */
        $headerRow = $startRow;

        $sheet
            ->getStyle(
                'A' . $headerRow . ':' . $highestColumn . $headerRow
            )
            ->getFont()
            ->setBold(true);

        $sheet
            ->getStyle(
                'A' . $headerRow . ':' . $highestColumn . $headerRow
            )
            ->getFill()
            ->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            );

        $sheet
            ->getStyle(
                'A' . $headerRow . ':' . $highestColumn . $headerRow
            )
            ->getFill()
            ->getStartColor()
            ->setARGB(
                'FFE0E0E0'
            );

        /**
         * Nama file.
         */
        $filename =
            'Absensi-Guru-' .
            $data['bulanList'][$data['bulan']] .
            '-' .
            $data['tahun'] .
            '.xlsx';

        /**
         * Buat writer Excel.
         */
        $writer = new Xlsx(
            $spreadsheet
        );

        /**
         * Bersihkan output buffer
         * agar file Excel tidak corrupt.
         */
        if (ob_get_length()) {
            ob_end_clean();
        }

        /**
         * Header download.
         */
        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment; filename="' .
            $filename .
            '"'
        );

        header(
            'Cache-Control: max-age=0'
        );

        /**
         * Simpan ke browser.
         */
        $writer->save(
            'php://output'
        );

        exit;
    }

    /**
     * Export data absensi guru ke PDF.
     */
    public function exportPdf(Request $request)
    {
        /**
         * Ambil data absensi.
         */
        $data = $this->getAttendanceData(
            $request
        );

        /**
         * Generate PDF dari Blade view.
         */
        $pdf = Pdf::loadView(
            'admin.absensi-guru.pdf',
            $data
        );

        /**
         * Atur ukuran kertas.
         */
        $pdf->setPaper(
            'A4',
            'landscape'
        );

        /**
         * Nama file PDF.
         */
        $filename =
            'Absensi-Guru-' .
            $data['bulanList'][$data['bulan']] .
            '-' .
            $data['tahun'] .
            '.pdf';

        /**
         * Download PDF.
         */
        return $pdf->download(
            $filename
        );
    }

    /**
     * Menyimpan atau memperbarui absensi guru.
     */
    public function store(Request $request)
    {
        /**
         * Validasi input.
         */
        $validated = $request->validate([
            'guru_id' => [
                'required',
                'exists:gurus,id',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:P,A,S,I,L,W',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        /**
         * Buat atau update absensi.
         */
        $absensi = AbsensiGuru::updateOrCreate(
            [
                'guru_id' => $validated['guru_id'],
                'tanggal' => $validated['tanggal'],
            ],
            [
                'status' =>
                    $validated['status'],

                'keterangan' =>
                    $validated['keterangan'] ?? '',
            ]
        );

        /**
         * Response JSON.
         */
        return response()->json([
            'success' => true,
            'id' => $absensi->id,
        ]);
    }

    /**
     * Menghapus data absensi guru.
     */
    public function destroy(
        AbsensiGuru $absensiGuru
    ) {
        /**
         * Hapus data.
         */
        $absensiGuru->delete();

        /**
         * Response JSON.
         */
        return response()->json([
            'success' => true,
        ]);
    }
}
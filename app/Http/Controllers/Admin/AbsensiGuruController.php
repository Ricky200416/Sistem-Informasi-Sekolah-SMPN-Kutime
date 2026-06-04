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
    private array $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    private function getAttendanceData(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $jumlahHari = Carbon::create($tahun, $bulan, 1)->daysInMonth;

        $daftarGuru = User::where('role', 'guru')
            ->with(['guru', 'guru.kelas'])
            ->orderBy('name')
            ->get();

        $absensiRaw = AbsensiGuru::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $absensiData = [];
        foreach ($absensiRaw as $abs) {
            $hari = (int) Carbon::parse($abs->tanggal)->day;
            $absensiData[$abs->guru_id][$hari] = $abs;
        }

        $ringkasan = [
            'total' => $absensiRaw->count(),
            'hadir' => $absensiRaw->where('status', 'P')->count(),
            'sakit' => $absensiRaw->where('status', 'S')->count(),
            'izin'  => $absensiRaw->where('status', 'I')->count(),
            'alpha' => $absensiRaw->where('status', 'A')->count(),
            'telat' => $absensiRaw->where('status', 'L')->count(),
        ];

        $daftarKelas = collect();
        $namaHari = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

        return [
            'daftarGuru'   => $daftarGuru,
            'absensiData'  => $absensiData,
            'ringkasan'    => $ringkasan,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'bulanList'    => $this->bulanList,
            'jumlahHari'   => $jumlahHari,
            'namaHari'     => $namaHari,
            'daftarKelas'  => $daftarKelas,
        ];
    }

    public function index(Request $request)
    {
        $data = $this->getAttendanceData($request);
        return view('admin.absensi-guru.index', $data);
    }

    /**
     * Export ke Excel menggunakan PHPSpreadsheet langsung
     */
    public function exportExcel(Request $request)
    {
        $data = $this->getAttendanceData($request);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add logo if exists
        if (file_exists(public_path('images/logo-smpn-kutime.png'))) {
            $drawing = new Drawing();
            $drawing->setName('Logo SMPN Kutime');
            $drawing->setDescription('Logo Sekolah');
            $drawing->setPath(public_path('images/logo-smpn-kutime.png'));
            $drawing->setHeight(80);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(10);
            $drawing->setWorksheet($sheet);
        }
        
        // Title
        $sheet->setCellValue('A3', 'LAPORAN ABSENSI GURU');
        $sheet->setCellValue('A4', 'Bulan: ' . $data['bulanList'][$data['bulan']] . ' ' . $data['tahun']);
        
        // Get data from export class
        $export = new AbsensiGuruExport(
            $data['daftarGuru'],
            $data['absensiData'],
            $data['bulan'],
            $data['tahun'],
            $data['jumlahHari'],
            $data['bulanList']
        );
        
        $excelData = $export->getData();
        
        // Start from row 6 to leave space for title and logo
        $startRow = 6;
        
        // Write data to sheet
        foreach ($excelData as $rowIndex => $rowData) {
            $colIndex = 1; // Start from column A
            foreach ($rowData as $cellData) {
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . ($startRow + $rowIndex);
                $sheet->setCellValue($cellCoordinate, $cellData);
                $colIndex++;
            }
        }
        
        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Style the header row
        $headerRow = $startRow;
        $sheet->getStyle($headerRow)->getFont()->setBold(true);
        $sheet->getStyle($headerRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($headerRow)->getFill()->getStartColor()->setARGB('FFE0E0E0');
        
        $filename = 'Absensi-Guru-' . $data['bulanList'][$data['bulan']] . '-' . $data['tahun'] . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        // Set headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getAttendanceData($request);
        $pdf = Pdf::loadView('admin.absensi-guru.pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Absensi-Guru-' . $data['bulanList'][$data['bulan']] . '-' . $data['tahun'] . '.pdf');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id'    => 'required|exists:gurus,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:P,A,S,I,L,W',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $absensi = AbsensiGuru::updateOrCreate(
            ['guru_id' => $validated['guru_id'], 'tanggal' => $validated['tanggal']],
            ['status'  => $validated['status'],  'keterangan' => $validated['keterangan'] ?? ''],
        );

        return response()->json(['success' => true, 'id' => $absensi->id]);
    }

    public function destroy(AbsensiGuru $absensiGuru)
    {
        $absensiGuru->delete();
        return response()->json(['success' => true]);
    }
}
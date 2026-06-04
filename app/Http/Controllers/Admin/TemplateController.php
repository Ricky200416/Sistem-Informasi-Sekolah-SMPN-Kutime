<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class TemplateController extends Controller
{
    /**
     * Route: GET /admin/users/template-import?role=guru|siswa
     * Contoh: route('admin.users.template-import', ['role' => 'guru'])
     */
    public function download(Request $request)
    {
        $role = $request->get('role', 'guru');

        return $role === 'siswa'
            ? $this->templateSiswa()
            : $this->templateGuru();
    }

    // =========================================================================
    // TEMPLATE GURU
    // =========================================================================

    private function templateGuru()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Guru');

        // ── Baris 1 — Petunjuk ───────────────────────────────────────────────
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1',
            'PETUNJUK: Hapus baris berwarna kuning ini sebelum upload. ' .
            'Kolom bertanda (*) wajib diisi. ' .
            'Jenis Kelamin: isi L atau P. ' .
            'Tanggal format: dd/mm/yyyy. ' .
            'nama_kelas harus sama persis dengan nama kelas yang ada di sistem.'
        );
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['italic' => true, 'bold' => false, 'color' => ['rgb' => '92400E'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF9C3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FDE68A']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(42);

        // ── Baris 2 — Header kolom ───────────────────────────────────────────
        $headers = [
            'A' => ['label' => 'nama *',                         'width' => 28],
            'B' => ['label' => 'email *',                        'width' => 32],
            'C' => ['label' => 'password *',                     'width' => 18],
            'D' => ['label' => 'nip',                            'width' => 22],
            'E' => ['label' => 'jenis_kelamin (L/P)',            'width' => 20],
            'F' => ['label' => 'tempat_lahir',                   'width' => 20],
            'G' => ['label' => 'tanggal_lahir (dd/mm/yyyy)',     'width' => 24],
            'H' => ['label' => 'pendidikan_terakhir',            'width' => 20],
            'I' => ['label' => 'status_pegawai',                 'width' => 18],
            'J' => ['label' => 'pangkat_gol_ruang',              'width' => 22],
            'K' => ['label' => 'no_sk_pertama',                  'width' => 26],
            'L' => ['label' => 'no_sk_terakhir',                 'width' => 26],
            'M' => ['label' => 'nama_kelas',                     'width' => 16],
        ];

        foreach ($headers as $col => $info) {
            $sheet->setCellValue($col . '2', $info['label']);
            $sheet->getColumnDimension($col)->setWidth($info['width']);
        }

        $sheet->getStyle('A2:M2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '6366F1']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(30);

        // ── Baris 3 — Contoh data ────────────────────────────────────────────
        $contoh = [
            'A' => 'Budi Santoso, S.Pd',
            'B' => 'budi.santoso@sekolah.sch.id',
            'C' => 'password123',
            'D' => '198501012010011001',
            'E' => 'L',
            'F' => 'Jakarta',
            'G' => '01/01/1985',
            'H' => 'S1',
            'I' => 'PNS',
            'J' => 'Penata Muda / III-a',
            'K' => '001/SK/2010',
            'L' => '002/SK/2023',
            'M' => 'Kelas 7A',
        ];

        foreach ($contoh as $col => $val) {
            $sheet->setCellValue($col . '3', $val);
        }

        $sheet->getStyle('A3:M3')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '6366F1'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'C7D2FE']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(22);

        // ── Baris 4..23 — Area data kosong ───────────────────────────────────
        for ($r = 4; $r <= 23; $r++) {
            $sheet->getStyle('A' . $r . ':M' . $r)->applyFromArray([
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($r)->setRowHeight(18);

            // Baris genap diberi warna sangat terang
            if ($r % 2 === 0) {
                $sheet->getStyle('A' . $r . ':M' . $r)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
                ]);
            }
        }

        // ── Freeze pane agar header tetap terlihat saat scroll ───────────────
        $sheet->freezePane('A3');

        // ── Sheet 2: Keterangan kolom ─────────────────────────────────────────
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Keterangan');
        $this->buildInfoSheet($infoSheet, 'guru');

        $spreadsheet->setActiveSheetIndex(0);

        // ── Output ────────────────────────────────────────────────────────────
        $filename = 'Template_Import_Guru.xlsx';
        $writer   = new XlsxWriter($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // =========================================================================
    // TEMPLATE SISWA
    // =========================================================================

    private function templateSiswa()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Import Siswa');

        $lastDataCol = 'V'; // 22 kolom → A-V

        // ── Baris 1 — Petunjuk ───────────────────────────────────────────────
        $sheet->mergeCells('A1:' . $lastDataCol . '1');
        $sheet->setCellValue('A1',
            'PETUNJUK: Hapus baris berwarna kuning ini sebelum upload. ' .
            'Kolom bertanda (*) wajib diisi. ' .
            'Jenis Kelamin: isi L atau P. ' .
            'Tanggal format: dd/mm/yyyy. ' .
            'penerima_kps: isi Ya atau Tidak. ' .
            'nama_kelas harus sama persis dengan nama kelas yang ada di sistem.'
        );
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['italic' => true, 'bold' => false, 'color' => ['rgb' => '92400E'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF9C3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FDE68A']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(42);

        // ── Baris 2 — Header kolom ───────────────────────────────────────────
        $headers = [
            'A' => ['label' => 'nama *',                         'width' => 28],
            'B' => ['label' => 'email *',                        'width' => 32],
            'C' => ['label' => 'password *',                     'width' => 18],
            'D' => ['label' => 'nis_nidn',                       'width' => 16],
            'E' => ['label' => 'nik',                            'width' => 20],
            'F' => ['label' => 'jenis_kelamin (L/P)',            'width' => 20],
            'G' => ['label' => 'tempat_lahir',                   'width' => 18],
            'H' => ['label' => 'tanggal_lahir (dd/mm/yyyy)',     'width' => 24],
            'I' => ['label' => 'agama',                          'width' => 14],
            'J' => ['label' => 'no_telp',                        'width' => 16],
            'K' => ['label' => 'shkun',                          'width' => 18],
            'L' => ['label' => 'nama_kelas',                     'width' => 14],
            'M' => ['label' => 'alamat',                         'width' => 30],
            'N' => ['label' => 'rt',                             'width' =>  8],
            'O' => ['label' => 'rw',                             'width' =>  8],
            'P' => ['label' => 'dusun',                          'width' => 14],
            'Q' => ['label' => 'kecamatan',                      'width' => 18],
            'R' => ['label' => 'kode_pos',                       'width' => 12],
            'S' => ['label' => 'jenis_tinggal',                  'width' => 18],
            'T' => ['label' => 'transportasi',                   'width' => 18],
            'U' => ['label' => 'penerima_kps (Ya/Tidak)',        'width' => 22],
            'V' => ['label' => 'no_kps',                         'width' => 16],
        ];

        foreach ($headers as $col => $info) {
            $sheet->setCellValue($col . '2', $info['label']);
            $sheet->getColumnDimension($col)->setWidth($info['width']);
        }

        $sheet->getStyle('A2:V2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '6366F1']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(30);

        // ── Baris 3 — Contoh data ────────────────────────────────────────────
        $contoh = [
            'A' => 'Ani Rahayu',
            'B' => 'ani.rahayu@sekolah.sch.id',
            'C' => 'password123',
            'D' => '2024001',
            'E' => '3201010101010001',
            'F' => 'P',
            'G' => 'Bogor',
            'H' => '01/01/2008',
            'I' => 'Islam',
            'J' => '08123456789',
            'K' => '123456789',
            'L' => 'Kelas 7A',
            'M' => 'Jl. Merdeka No. 1',
            'N' => '001',
            'O' => '002',
            'P' => 'Cikaret',
            'Q' => 'Cibinong',
            'R' => '16913',
            'S' => 'Bersama Orang Tua',
            'T' => 'Angkot',
            'U' => 'Tidak',
            'V' => '',
        ];

        foreach ($contoh as $col => $val) {
            $sheet->setCellValue($col . '3', $val);
        }

        $sheet->getStyle('A3:V3')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '6366F1'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'C7D2FE']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(22);

        // ── Baris 4..23 — Area data kosong ───────────────────────────────────
        for ($r = 4; $r <= 23; $r++) {
            $sheet->getStyle('A' . $r . ':V' . $r)->applyFromArray([
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($r)->setRowHeight(18);
            if ($r % 2 === 0) {
                $sheet->getStyle('A' . $r . ':V' . $r)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
                ]);
            }
        }

        $sheet->freezePane('A3');

        // ── Sheet 2: Keterangan ───────────────────────────────────────────────
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Keterangan');
        $this->buildInfoSheet($infoSheet, 'siswa');

        $spreadsheet->setActiveSheetIndex(0);

        // ── Output ────────────────────────────────────────────────────────────
        $filename = 'Template_Import_Siswa.xlsx';
        $writer   = new XlsxWriter($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // =========================================================================
    // SHEET KETERANGAN — penjelasan tiap kolom
    // =========================================================================

    private function buildInfoSheet($sheet, string $role): void
    {
        $sheet->getColumnDimension('A')->setWidth(26);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(52);

        // Header tabel keterangan
        $sheet->setCellValue('A1', 'Nama Kolom');
        $sheet->setCellValue('B1', 'Wajib?');
        $sheet->setCellValue('C1', 'Keterangan / Contoh Isi');

        $sheet->getStyle('A1:C1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '6366F1']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(26);

        $keterangan = $role === 'guru'
            ? $this->keteranganGuru()
            : $this->keteranganSiswa();

        $r = 2;
        foreach ($keterangan as $item) {
            $sheet->setCellValue('A' . $r, $item[0]);
            $sheet->setCellValue('B' . $r, $item[1]);
            $sheet->setCellValue('C' . $r, $item[2]);

            $wajib = $item[1] === 'Ya';

            $sheet->getStyle('A' . $r . ':C' . $r)->applyFromArray([
                'font'      => ['size' => 9, 'bold' => $wajib],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $r % 2 === 0 ? 'F8FAFC' : 'FFFFFF']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            ]);

            // Kolom wajib: warna teks merah di kolom B
            if ($wajib) {
                $sheet->getStyle('B' . $r)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'DC2626'], 'bold' => true],
                ]);
            }

            $sheet->getRowDimension($r)->setRowHeight(20);
            $r++;
        }
    }

    private function keteranganGuru(): array
    {
        return [
            ['nama',                     'Ya',  'Nama lengkap guru. Contoh: Budi Santoso, S.Pd'],
            ['email',                    'Ya',  'Email untuk login. Harus unik. Contoh: budi@sekolah.sch.id'],
            ['password',                 'Ya',  'Password awal akun. Min. 8 karakter. Contoh: password123'],
            ['nip',                      'Tidak','Nomor Induk Pegawai. Contoh: 198501012010011001'],
            ['jenis_kelamin',            'Tidak','L untuk Laki-laki, P untuk Perempuan'],
            ['tempat_lahir',             'Tidak','Kota kelahiran. Contoh: Jakarta'],
            ['tanggal_lahir',            'Tidak','Format: dd/mm/yyyy. Contoh: 01/01/1985'],
            ['pendidikan_terakhir',      'Tidak','Contoh: S1, S2, D3, SMA'],
            ['status_pegawai',           'Tidak','Contoh: PNS, PPPK, Honorer, GTT, Kontrak'],
            ['pangkat_gol_ruang',        'Tidak','Contoh: Penata Muda / III-a'],
            ['no_sk_pertama',            'Tidak','Nomor SK pengangkatan pertama'],
            ['no_sk_terakhir',           'Tidak','Nomor SK terakhir/terbaru'],
            ['nama_kelas',               'Tidak','Nama kelas yang diampu sebagai wali. Harus sama persis dengan nama di sistem. Contoh: Kelas 7A'],
        ];
    }

    private function keteranganSiswa(): array
    {
        return [
            ['nama',                     'Ya',  'Nama lengkap siswa. Contoh: Ani Rahayu'],
            ['email',                    'Ya',  'Email untuk login. Harus unik. Contoh: ani@sekolah.sch.id'],
            ['password',                 'Ya',  'Password awal akun. Min. 8 karakter. Contoh: password123'],
            ['nis_nidn',                 'Tidak','Nomor Induk Siswa. Contoh: 2024001'],
            ['nik',                      'Tidak','Nomor Induk Kependudukan (16 digit). Contoh: 3201010101010001'],
            ['jenis_kelamin',            'Tidak','L untuk Laki-laki, P untuk Perempuan'],
            ['tempat_lahir',             'Tidak','Kota kelahiran. Contoh: Bogor'],
            ['tanggal_lahir',            'Tidak','Format: dd/mm/yyyy. Contoh: 01/01/2008'],
            ['agama',                    'Tidak','Contoh: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu'],
            ['no_telp',                  'Tidak','Nomor telepon. Contoh: 08123456789'],
            ['shkun',                    'Tidak','Nomor SKHUN. Contoh: 123456789'],
            ['nama_kelas',               'Tidak','Nama kelas. Harus sama persis dengan nama di sistem. Contoh: Kelas 7A'],
            ['alamat',                   'Tidak','Alamat lengkap jalan. Contoh: Jl. Merdeka No. 1'],
            ['rt',                       'Tidak','Nomor RT. Contoh: 001'],
            ['rw',                       'Tidak','Nomor RW. Contoh: 002'],
            ['dusun',                    'Tidak','Nama dusun/lingkungan. Contoh: Cikaret'],
            ['kecamatan',                'Tidak','Nama kecamatan. Contoh: Cibinong'],
            ['kode_pos',                 'Tidak','Kode pos 5 digit. Contoh: 16913'],
            ['jenis_tinggal',            'Tidak','Contoh: Bersama Orang Tua, Kos, Asrama, Wali'],
            ['transportasi',             'Tidak','Contoh: Jalan Kaki, Angkot, Ojek, Sepeda, Motor'],
            ['penerima_kps',             'Tidak','Isi Ya atau Tidak'],
            ['no_kps',                   'Tidak','Nomor Kartu Perlindungan Sosial (jika ada)'],
        ];
    }
}
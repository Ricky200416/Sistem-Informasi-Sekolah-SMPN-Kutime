<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AbsensiGuruExport
{
    protected $daftarGuru;
    protected $absensiData;
    protected $bulan;
    protected $tahun;
    protected $jumlahHari;
    protected $bulanList;

    public function __construct($daftarGuru, $absensiData, $bulan, $tahun, $jumlahHari, $bulanList)
    {
        $this->daftarGuru   = $daftarGuru;
        $this->absensiData  = $absensiData;
        $this->bulan        = $bulan;
        $this->tahun        = $tahun;
        $this->jumlahHari   = $jumlahHari;
        $this->bulanList    = $bulanList;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo SMPN Kutime');
        $drawing->setDescription('Logo Sekolah');
        $drawing->setPath(public_path('images/logo-smpn-kutime.png')); // GANTI DENGAN PATH LOGO ANDA
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1'); // Posisi di cell A1
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);

        return [$drawing];
    }

    public function getData()
    {
        $namaHari = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        $data = [];
        
        // Header row
        $header = ['Nama Guru', 'NIP'];
        for ($i = 1; $i <= $this->jumlahHari; $i++) {
            $header[] = $i . ' (' . $namaHari[date('w', mktime(0, 0, 0, $this->bulan, $i, $this->tahun))] . ')';
        }
        $header[] = 'Total Hadir';
        $header[] = 'Total Sakit';
        $header[] = 'Total Izin';
        $header[] = 'Total Alpha';
        $data[] = $header;
        
        // Data rows
        foreach ($this->daftarGuru as $guru) {
            $row = [$guru->guru->nama ?? $guru->name, $guru->guru->nip ?? '-'];
            
            $totalHadir = 0;
            $totalSakit = 0;
            $totalIzin = 0;
            $totalAlpha = 0;
            
            for ($i = 1; $i <= $this->jumlahHari; $i++) {
                $tanggal = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $i);
                $status = '-';
                
                if (isset($this->absensiData[$guru->id][$i])) {
                    $absensi = $this->absensiData[$guru->id][$i];
                    $status = $absensi->status ?? '-';
                    
                    switch ($status) {
                        case 'P':
                            $status = 'Hadir';
                            $totalHadir++;
                            break;
                        case 'S':
                            $status = 'Sakit';
                            $totalSakit++;
                            break;
                        case 'I':
                            $status = 'Izin';
                            $totalIzin++;
                            break;
                        case 'A':
                            $status = 'Alpha';
                            $totalAlpha++;
                            break;
                        default:
                            $status = '-';
                            break;
                    }
                }
                
                $row[] = $status;
            }
            
            $row[] = $totalHadir;
            $row[] = $totalSakit;
            $row[] = $totalIzin;
            $row[] = $totalAlpha;
            
            $data[] = $row;
        }
        
        return $data;
    }
}
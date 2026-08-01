<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlumniExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $no = 0;

    public function __construct(protected ?string $tahunLulus = null)
    {
    }

    public function collection()
    {
        return Alumni::query()
            ->when($this->tahunLulus, fn ($q) => $q->tahun($this->tahunLulus))
            ->orderByDesc('tahun_lulus')
            ->orderBy('nama')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Nama', 'NISN/NIDN', 'NIK', 'Jenis Kelamin', 'Kelas Terakhir',
            'Tahun Lulus', 'Tanggal Lulus', 'No. Ijazah', 'No. Telepon', 'Alamat',
        ];
    }

    public function map($alumni): array
    {
        $this->no++;

        return [
            $this->no,
            $alumni->nama,
            $alumni->nidn,
            $alumni->nik,
            $alumni->jk_label,
            $alumni->kelas_terakhir,
            $alumni->tahun_lulus,
            optional($alumni->tanggal_lulus)->format('d-m-Y'),
            $alumni->no_ijazah,
            $alumni->no_telp,
            $alumni->alamat,
        ];
    }
}
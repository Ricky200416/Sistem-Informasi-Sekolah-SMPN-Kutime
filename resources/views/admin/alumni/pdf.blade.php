<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Alumni SMPN Kutime</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; }
        h2 { margin-bottom: 2px; }
        p.sub { margin-top: 0; color: #64748b; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 7px; text-align: left; }
        th { background: #f1f5f9; text-transform: uppercase; font-size: 9px; }
    </style>
</head>
<body>
    <h2>Data Alumni SMP Negeri Kutime</h2>
    <p class="sub">
        {{ $tahun ? "Tahun Lulus: $tahun" : 'Seluruh Tahun Lulus' }}
        &middot; Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NISN/NIDN</th>
                <th>NIK</th>
                <th>JK</th>
                <th>Kelas Terakhir</th>
                <th>Tahun Lulus</th>
                <th>Tgl Lulus</th>
                <th>No. Ijazah</th>
                <th>No. Telp</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumni as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $a->nama }}</td>
                    <td>{{ $a->nidn ?? '-' }}</td>
                    <td>{{ $a->nik ?? '-' }}</td>
                    <td>{{ $a->jk_label }}</td>
                    <td>{{ $a->kelas_terakhir ?? '-' }}</td>
                    <td>{{ $a->tahun_lulus }}</td>
                    <td>{{ optional($a->tanggal_lulus)->format('d-m-Y') }}</td>
                    <td>{{ $a->no_ijazah ?? '-' }}</td>
                    <td>{{ $a->no_telp ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="10" style="text-align:center">Belum ada data alumni.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
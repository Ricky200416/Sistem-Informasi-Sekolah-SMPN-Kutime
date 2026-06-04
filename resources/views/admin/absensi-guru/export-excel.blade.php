<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Guru - {{ $bulanList[$bulan] }} {{ $tahun }}</title>
    <style type="text/css">
        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            color: #1f1f1f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #a6a6a6;
            padding: 6px 8px;
            vertical-align: middle;
        }
        th {
            background-color: #d9e8fb;
            color: #0f4a8a;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }
        .nama-guru-col {
            width: 320px;
            text-align: left !important;
            font-weight: 600;
            background-color: #f5f5f5;
        }
        .date-col {
            width: 45px;
            text-align: center;
        }
        .weekend {
            background-color: #fff2cc !important;
        }
        .status-P { color: #006400; font-weight: bold; }
        .status-A { color: #c00000; font-weight: bold; }
        .status-S { color: #9c6500; font-weight: bold; }
        .status-I { color: #0070c0; font-weight: bold; }
        .status-L { color: #7030a0; font-weight: bold; }
        .status-W { color: #7030a0; font-weight: bold; }
        .header-container {
            padding: 25px 0 20px;
            text-align: center;
        }
        .school-emoji {
            font-size: 52px;
            line-height: 1;
            margin-bottom: 10px;
        }
        .school-title {
            font-size: 24px;
            font-weight: bold;
            color: #002060;
            margin: 0 0 6px 0;
        }
        .school-address {
            font-size: 13px;
            color: #444;
            margin: 0 0 18px 0;
        }
        .doc-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f1f1f;
            margin: 0 0 6px 0;
            text-align: left;
        }
        .doc-period {
            font-size: 14px;
            font-weight: bold;
            color: #444;
            text-align: left;
            margin-bottom: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 45px;
            font-size: 10px;
            color: #555;
            border-top: 1px solid #d0d0d0;
            padding-top: 12px;
        }
        .empty-message {
            padding: 40px 20px;
            text-align: center;
            font-style: italic;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header-container">
        <div class="school-emoji">🏫</div>
        <h1 class="school-title">SMP NEGERI KUTIME</h1>
        <p class="school-address">Kabupaten Tolikara, Provinsi Papua Pegunungan</p>
    </div>

    <div>
        <h2 class="doc-title">REKAP ABSENSI GURU</h2>
        <div class="doc-period">Bulan: {{ $bulanList[$bulan] }} {{ $tahun }}</div>
    </div>

    <!-- Tabel Utama -->
    <table>
        <thead>
            <tr>
                <th class="nama-guru-col">Nama Guru</th>
                @for($d=1; $d<=$jumlahHari; $d++)
                    @php 
                        $dw = (int)date('w', mktime(0,0,0,$bulan,$d,$tahun));
                        $isWeekend = in_array($dw, [0, 6]);
                    @endphp
                    <th class="date-col {{ $isWeekend ? 'weekend' : '' }}">
                        {{ $d }}<br>
                        <small style="font-size:9px;">{{ $namaHari[$dw] }}</small>
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse($daftarGuru as $user)
                @php
                    $guru = $user->guru;
                    $namaTampil = ($guru && $guru->nama) ? $guru->nama : $user->name;
                    $gid = $guru?->id ?? null;
                @endphp
                <tr>
                    <td class="nama-guru-col">{{ $namaTampil }}</td>
                    @for($d=1; $d<=$jumlahHari; $d++)
                        @php 
                            $abs = ($gid && isset($absensiData[$gid][$d])) ? $absensiData[$gid][$d] : null;
                            $status = $abs ? $abs->status : '-';
                            $class = $status !== '-' ? 'status-' . $status : '';
                        @endphp
                        <td class="{{ $class }}">{{ $status }}</td>
                    @endfor
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $jumlahHari + 1 }}" class="empty-message">
                        Tidak ada data absensi guru pada periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Dicetak otomatis • {{ now()->format('d F Y H:i:s') }} • Sistem Absensi SMP Negeri Kutime
    </div>

</body>
</html>
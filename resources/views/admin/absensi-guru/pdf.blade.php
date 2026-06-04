<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Guru {{ $bulanList[$bulan] }} {{ $tahun }}</title>
    <style>
        @page { size: A4 landscape; margin: 12mm 15mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #333; }
        .header { margin-bottom: 20px; }
        .logo-container { text-align: center; margin-bottom: 12px; }
        .logo { width: 80px; height: auto; }
        .school-name { font-size: 16px; font-weight: bold; margin: 4px 0; }
        .school-address { font-size: 11px; color: #555; margin-bottom: 8px; }
        .title-left { text-align: left; margin-bottom: 12px; }
        .title-left h1 { font-size: 18px; margin: 0 0 4px 0; }
        .title-left .periode { font-size: 13px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; font-size: 9.5px; }
        th, td { border: 1px solid #444; padding: 5px 4px; text-align: center; vertical-align: middle; }
        th { background: #f0f4f8; font-weight: bold; font-size: 9px; }
        .nama-guru { text-align: left; font-weight: 600; min-width: 220px; }
        .footer { text-align: center; margin-top: 30px; font-size: 9px; color: #666; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-container">
            @php
                $logoUrl = \App\Models\SchoolSetting::logoUrl();
            @endphp

            @if($logoUrl)
                <img src="{{ public_path(str_replace(asset(''), '', $logoUrl)) }}" alt="Logo SMPN Kutime" class="logo">
            @else
                <div style="font-size: 40px; margin-bottom: 8px;">🏫</div>
            @endif

            <div class="school-name">SMP NEGERI KUTIME</div>
            <div class="school-address">Kabupaten Tolikara, Provinsi Papua Pegunungan</div>
        </div>

        <div class="title-left">
            <h1>REKAP ABSENSI GURU</h1>
            <div class="periode">Bulan: {{ $bulanList[$bulan] }} {{ $tahun }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="nama-guru">Nama Guru</th>
                @for($d=1; $d<=$jumlahHari; $d++)
                    @php $dw = (int)date('w', mktime(0,0,0,$bulan,$d,$tahun)); @endphp
                    <th style="{{ in_array($dw, [0,6]) ? 'background:#fff3cd;' : '' }}">
                        {{ $d }}<br>
                        <small style="font-size:8px;">{{ $namaHari[$dw] }}</small>
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
                    <td class="nama-guru">{{ $namaTampil }}</td>
                    @for($d=1; $d<=$jumlahHari; $d++)
                        @php $abs = ($gid && isset($absensiData[$gid][$d])) ? $absensiData[$gid][$d] : null; @endphp
                        <td>{{ $abs ? $abs->status : '-' }}</td>
                    @endfor
                </tr>
            @empty
                <tr><td colspan="{{ $jumlahHari }}" style="padding:20px; text-align:center; color:#777;">
                    Tidak ada data absensi guru pada periode ini
                </td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }} • Sistem Absensi SMP Negeri Kutime
    </div>

</body>
</html>
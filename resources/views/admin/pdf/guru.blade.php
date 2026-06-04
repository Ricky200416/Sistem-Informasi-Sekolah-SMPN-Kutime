<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Guru</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            color: #1e293b;
            background: #ffffff;
        }

        /* ── Header ─────────────────────────────────────── */
        .page-header {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff;
            padding: 18px 24px;
            margin-bottom: 16px;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-title {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: -0.3px;
        }

        .header-subtitle {
            font-size: 8pt;
            opacity: 0.85;
            margin-top: 3px;
        }

        .header-meta {
            text-align: right;
            font-size: 7.5pt;
            opacity: 0.9;
        }

        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.35);
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 4px;
        }

        /* ── Summary bar ─────────────────────────────────── */
        .summary-bar {
            display: flex;
            gap: 12px;
            margin: 0 24px 14px;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .summary-item {
            flex: 1;
            text-align: center;
        }

        .summary-number {
            font-size: 13pt;
            font-weight: bold;
            color: #4f46e5;
        }

        .summary-label {
            font-size: 7pt;
            color: #64748b;
            margin-top: 1px;
        }

        .summary-divider {
            width: 1px;
            background: #e2e8f0;
        }

        /* ── Table ───────────────────────────────────────── */
        .table-wrap {
            margin: 0 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #4f46e5;
            color: #ffffff;
        }

        thead th {
            padding: 7px 6px;
            text-align: left;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        thead th:first-child { border-radius: 6px 0 0 0; }
        thead th:last-child  { border-radius: 0 6px 0 0; text-align: center; }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #ffffff; }

        tbody td {
            padding: 5.5px 6px;
            font-size: 7.5pt;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        tbody tr:last-child td { border-bottom: none; }

        .td-no { text-align: center; color: #94a3b8; font-size: 7pt; width: 22px; }
        .td-nama { font-weight: 600; color: #1e293b; }
        .td-email { color: #64748b; font-size: 7pt; }
        .td-mono { font-family: 'DejaVu Sans Mono', monospace; font-size: 7pt; }

        .badge {
            display: inline-block;
            padding: 1.5px 6px;
            border-radius: 20px;
            font-size: 6.5pt;
            font-weight: bold;
        }

        .badge-l    { background: #dbeafe; color: #1d4ed8; }
        .badge-p    { background: #fce7f3; color: #be185d; }
        .badge-pns  { background: #d1fae5; color: #065f46; }
        .badge-pppk { background: #ccfbf1; color: #0f766e; }
        .badge-hon  { background: #fef3c7; color: #92400e; }
        .badge-kelas{ background: #ede9fe; color: #6d28d9; }

        /* ── Footer ─────────────────────────────────────── */
        .page-footer {
            margin: 16px 24px 0;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 7pt;
            color: #94a3b8;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="page-header">
        <div class="header-inner">
            <div>
                <div class="header-title">Data Guru</div>
                <div class="header-subtitle">Daftar Tenaga Pendidik — Sistem Informasi Sekolah</div>
            </div>
            <div class="header-meta">
                <div>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
                <div class="header-badge">{{ $users->count() }} Guru Terdaftar</div>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    @php
        $totalPns    = $users->filter(fn($u) => $u->guru?->status_pegawai === 'PNS')->count();
        $totalPppk   = $users->filter(fn($u) => $u->guru?->status_pegawai === 'PPPK')->count();
        $totalHonorer= $users->filter(fn($u) => in_array($u->guru?->status_pegawai, ['Honorer','GTT']))->count();
        $totalLaki   = $users->filter(fn($u) => $u->guru?->jk === 'L')->count();
        $totalPeremp = $users->filter(fn($u) => $u->guru?->jk === 'P')->count();
    @endphp
    <div class="summary-bar">
        <div class="summary-item">
            <div class="summary-number">{{ $users->count() }}</div>
            <div class="summary-label">Total Guru</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number">{{ $totalPns }}</div>
            <div class="summary-label">PNS</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number">{{ $totalPppk }}</div>
            <div class="summary-label">PPPK</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number">{{ $totalHonorer }}</div>
            <div class="summary-label">Honorer/GTT</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number">{{ $totalLaki }}</div>
            <div class="summary-label">Laki-laki</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number">{{ $totalPeremp }}</div>
            <div class="summary-label">Perempuan</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        @if($users->count())
        <table>
            <thead>
                <tr>
                    <th class="td-no">#</th>
                    <th style="min-width:120px;">Nama / Email</th>
                    <th>NIP</th>
                    <th>JK</th>
                    <th>Tempat / Tgl Lahir</th>
                    <th>Pendidikan</th>
                    <th>Status</th>
                    <th>Pangkat/Gol</th>
                    <th>No. SK Pertama</th>
                    <th>No. SK Terakhir</th>
                    <th>Wali Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $user)
                    @php $g = $user->guru; @endphp
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>

                        {{-- Nama + Email --}}
                        <td>
                            <div class="td-nama">{{ $g?->nama ?? $user->name }}</div>
                            <div class="td-email">{{ $user->email }}</div>
                        </td>

                        {{-- NIP --}}
                        <td class="td-mono">{{ $g?->nip ?? '—' }}</td>

                        {{-- JK --}}
                        <td>
                            @if($g?->jk === 'L')
                                <span class="badge badge-l">♂ L</span>
                            @elseif($g?->jk === 'P')
                                <span class="badge badge-p">♀ P</span>
                            @else
                                <span style="color:#cbd5e1;">—</span>
                            @endif
                        </td>

                        {{-- Tempat / Tgl Lahir --}}
                        <td>
                            <div>{{ $g?->tempat_lahir ?? '—' }}</div>
                            @if($g?->tanggal_lahir)
                                <div style="color:#64748b;font-size:7pt;">{{ $g->tanggal_lahir->format('d/m/Y') }}</div>
                            @endif
                        </td>

                        <td>{{ $g?->pendidikan_terakhir ?? '—' }}</td>

                        {{-- Status --}}
                        <td>
                            @if($g?->status_pegawai)
                                @php
                                    $cls = match($g->status_pegawai) {
                                        'PNS'   => 'badge-pns',
                                        'PPPK'  => 'badge-pppk',
                                        default => 'badge-hon',
                                    };
                                @endphp
                                <span class="badge {{ $cls }}">{{ $g->status_pegawai }}</span>
                            @else
                                <span style="color:#cbd5e1;">—</span>
                            @endif
                        </td>

                        <td>{{ $g?->pangkat_gol_ruang ?? '—' }}</td>
                        <td class="td-mono">{{ $g?->no_sk_pertama ?? '—' }}</td>
                        <td class="td-mono">{{ $g?->no_sk_terakhir ?? '—' }}</td>

                        {{-- Kelas --}}
                        <td>
                            @if($g?->kelas?->nama)
                                <span class="badge badge-kelas">{{ $g->kelas->nama }}</span>
                            @else
                                <span style="color:#cbd5e1;">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">Belum ada data guru yang terdaftar.</div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="page-footer">
        <span>Sistem Informasi Sekolah — Data Bersifat Rahasia</span>
        <span>Dicetak oleh Administrator pada {{ now()->format('d/m/Y H:i') }}</span>
    </div>

</body>
</html>
@extends('layouts.app')

@section('title', 'Absensi Guru')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; }
    body { background: #f0f2f8; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; }

    .ag-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px; }
    .ag-title  { font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; ma rgin:0; }
    .ag-title i { color:#4f46e5; font-size:16px; }
    .ag-title .sub { font-size:11px; font-weight:500; color:#94a3b8; }
    .ag-btn-kelola {
        display:inline-flex; align-items:center; gap:6px;
        background:#eef2ff; border:1px solid #c7d2fe; border-radius:7px;
        padding:5px 12px; font-size:11px; font-weight:600; color:#4338ca; text-decoration:none;
        transition:background .15s;
    }
    .ag-btn-kelola:hover { background:#e0e7ff; }
    .ag-btn-kelola .badge { background:#c7d2fe; border-radius:10px; padding:1px 6px; font-size:10px; }

    .ag-filter {
        background:#fff; border:1px solid #e2e8f0; border-radius:10px;
        padding:10px 14px; display:flex; align-items:center;
        gap:8px; flex-wrap:wrap; margin-bottom:12px;
    }
    .ag-filter label { font-size:11px; font-weight:600; color:#64748b; white-space:nowrap; }
    .ag-filter select, .ag-filter input[type="text"] {
        border:1px solid #e2e8f0; border-radius:6px; padding:5px 8px;
        font-size:11.5px; font-family:inherit; color:#1e293b; background:#f8fafc; outline:none; transition:border .15s;
    }
    .ag-filter select:focus, .ag-filter input:focus { border-color:#4f46e5; background:#fff; }
    .ag-btn-filter {
        background:#4f46e5; color:#fff; border:none; border-radius:6px; padding:5px 14px;
        font-size:11.5px; font-weight:600; font-family:inherit; cursor:pointer;
        display:flex; align-items:center; gap:5px; transition:background .15s;
    }
    .ag-btn-filter:hover { background:#4338ca; }

    .ag-stats { display:flex; gap:8px; margin-bottom:12px; flex-wrap:wrap; }
    .ag-stat  { background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:8px 14px; display:flex; align-items:center; gap:8px; flex:1; min-width:90px; }
    .ag-dot   { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .ag-val   { font-size:15px; font-weight:700; color:#1e293b; font-family:'JetBrains Mono',monospace; line-height:1; }
    .ag-lbl   { font-size:10px; color:#94a3b8; margin-top:1px; font-weight:500; }

    .ag-wrap  { background:#fff; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.05); }
    .ag-scroll { overflow-x:auto; overflow-y:auto; max-height:calc(100vh - 300px); }
    .ag-scroll::-webkit-scrollbar { width:5px; height:5px; }
    .ag-scroll::-webkit-scrollbar-track { background:#f1f5f9; }
    .ag-scroll::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:10px; }

    .ag-tbl { border-collapse:separate; border-spacing:0; width:100%; font-size:11.5px; }
    .ag-tbl thead th { position:sticky; top:0; z-index:3; }
    .ag-cn { position:sticky; left:0; z-index:4 !important; }
    .ag-tbl thead .ag-cn { z-index:5 !important; }
    .ag-tbl thead th {
        background:#f8fafc; color:#64748b; font-size:10px; font-weight:700;
        text-transform:uppercase; letter-spacing:.04em; padding:7px 5px;
        border-bottom:1.5px solid #e2e8f0; white-space:nowrap; text-align:center; user-select:none;
    }
    .ag-tbl thead th.ag-cn { text-align:left; padding-left:12px; min-width:215px; }

    .ag-tbl tbody tr:nth-child(even) td { background:#fafbfd; }
    .ag-tbl tbody tr:nth-child(odd)  td { background:#ffffff; }
    .ag-tbl tbody tr:hover td { background:#eef2ff !important; transition:background .1s; }
    .ag-tbl tbody tr:nth-child(even) td.ag-cn { background:#fafbfd; }
    .ag-tbl tbody tr:nth-child(odd)  td.ag-cn { background:#ffffff; }
    .ag-tbl tbody tr:hover           td.ag-cn { background:#eef2ff !important; }
    .ag-tbl tbody td { padding:5px 4px; border-bottom:1px solid #f1f5f9; text-align:center; vertical-align:middle; }
    .ag-tbl tbody td.ag-cn { text-align:left; padding-left:12px; white-space:nowrap; min-width:215px; }

    .ag-av { display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; border-radius:7px; background:#e0e7ff; color:#4f46e5; font-size:10px; font-weight:700; margin-right:7px; flex-shrink:0; vertical-align:middle; }
    .ag-gw { display:inline-flex; align-items:center; }
    .ag-gn { font-size:11.5px; font-weight:600; color:#334155; line-height:1.25; }
    .ag-gs { display:block; font-size:9.5px; color:#94a3b8; font-family:'JetBrains Mono',monospace; }

    .ag-tbl thead th.ag-wk { background:#fffbeb !important; color:#d97706 !important; }
    .ag-tbl thead th.ag-td { background:#eef2ff !important; color:#4f46e5 !important; font-weight:800 !important; }
    .ag-tbl tbody td.ag-td { background:#eef2ff !important; }
    .ag-tbl tbody tr:hover td.ag-td { background:#e0e7ff !important; }
    .ag-cr { position:sticky; right:0; background:inherit; border-left:1.5px solid #f1f5f9; z-index:2; }
    .ag-tbl thead th.ag-cr { background:#f8fafc; border-left:1.5px solid #e2e8f0; z-index:4; }

    .ag-b { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:5px; font-size:10px; font-weight:700; cursor:pointer; border:none; font-family:'Plus Jakarta Sans',sans-serif; transition:transform .1s,box-shadow .1s; }
    .ag-b:hover { transform:scale(1.18); box-shadow:0 2px 8px rgba(0,0,0,.15); }
    .ag-b-P{background:#dcfce7;color:#15803d;} .ag-b-A{background:#fee2e2;color:#b91c1c;}
    .ag-b-S{background:#fef9c3;color:#a16207;} .ag-b-I{background:#e0f2fe;color:#0369a1;}
    .ag-b-L{background:#fce7f3;color:#be185d;} .ag-b-W{background:#f3e8ff;color:#7e22ce;}
    .ag-be { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:5px; color:#e2e8f0; font-size:14px; cursor:pointer; transition:background .1s,color .1s,transform .1s; }
    .ag-be:hover { background:#eef2ff; color:#4f46e5; transform:scale(1.15); }
    .ag-rc { border-radius:4px; padding:1px 4px; font-size:9px; font-weight:700; display:inline-block; }

    .ag-legend { display:flex; gap:10px; flex-wrap:wrap; padding:8px 14px; border-top:1px solid #f1f5f9; background:#fafbfd; align-items:center; }
    .ag-li { display:flex; align-items:center; gap:4px; font-size:10px; color:#64748b; font-weight:500; }
    .ag-ld { width:16px; height:16px; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:700; }

    .ag-mov { display:none; position:fixed; inset:0; z-index:1000; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; }
    .ag-mov.show { display:flex; }
    .ag-mbox { background:#fff; border-radius:14px; padding:20px; width:275px; box-shadow:0 24px 60px rgba(0,0,0,.18); animation:agPop .2s cubic-bezier(.34,1.56,.64,1); }
    @keyframes agPop { from{transform:scale(.88);opacity:0} to{transform:scale(1);opacity:1} }
    .ag-mh { display:flex; align-items:center; gap:10px; margin-bottom:13px; }
    .ag-mav { width:34px; height:34px; border-radius:9px; background:#e0e7ff; color:#4f46e5; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; }
    .ag-mn  { font-size:13px; font-weight:700; color:#1e293b; line-height:1.2; }
    .ag-ms  { font-size:10px; color:#64748b; margin-top:2px; }
    .ag-sg  { display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:14px; }
    .ag-sb  { display:flex; flex-direction:column; align-items:center; gap:3px; padding:9px 4px; border-radius:9px; border:2px solid #f1f5f9; cursor:pointer; transition:all .15s; background:#f8fafc; font-family:inherit; }
    .ag-sb .sc { font-size:14px; font-weight:800; color:var(--sc); }
    .ag-sb .sl { font-size:9px; font-weight:600; color:#94a3b8; }
    .ag-sb:hover { border-color:var(--sc); transform:translateY(-1px); }
    .ag-sb.active { border-color:var(--sc); background:var(--sbg); }
    .ag-sb.active .sl { color:var(--sc); }
    .ag-sb[data-s="P"]{--sc:#15803d;--sbg:#dcfce7} .ag-sb[data-s="A"]{--sc:#b91c1c;--sbg:#fee2e2}
    .ag-sb[data-s="S"]{--sc:#a16207;--sbg:#fef9c3} .ag-sb[data-s="I"]{--sc:#0369a1;--sbg:#e0f2fe}
    .ag-sb[data-s="L"]{--sc:#be185d;--sbg:#fce7f3} .ag-sb[data-s="W"]{--sc:#7e22ce;--sbg:#f3e8ff}
    .ag-ma { display:flex; gap:6px; }
    .ag-mb { flex:1; padding:8px; border-radius:7px; border:none; font-size:11.5px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:4px; }
    .ag-mb-c{background:#f1f5f9;color:#64748b;} .ag-mb-c:hover{background:#e2e8f0;}
    .ag-mb-s{background:#4f46e5;color:#fff;}    .ag-mb-s:hover{background:#4338ca;}
    .ag-mb-d{background:#fee2e2;color:#b91c1c;flex:none;padding:8px 10px;} .ag-mb-d:hover{background:#fecaca;}

    .ag-toast { position:fixed; bottom:20px; right:20px; z-index:9999; background:#1e293b; color:#fff; border-radius:9px; padding:10px 16px; font-size:12px; font-weight:500; display:flex; align-items:center; gap:8px; box-shadow:0 8px 24px rgba(0,0,0,.15); transform:translateY(80px); opacity:0; transition:all .28s cubic-bezier(.34,1.56,.64,1); pointer-events:none; }
    .ag-toast.show { transform:translateY(0); opacity:1; }
    .ag-toast.success i{color:#4ade80;} .ag-toast.error i{color:#f87171;}
    .ag-empty { padding:48px; text-align:center; color:#94a3b8; }
    .ag-empty i { font-size:36px; display:block; margin-bottom:10px; color:#cbd5e1; }
    .ag-empty p { font-size:12px; margin:0; line-height:1.7; }
</style>
@endpush

@section('content')
@php
    $daftarGuru  = $daftarGuru  ?? collect();
    $absensiData = $absensiData ?? [];
    $bulan       = $bulan       ?? date('n');
    $tahun       = $tahun       ?? date('Y');
    $jumlahHari  = $jumlahHari  ?? cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
    $bulanList   = $bulanList   ?? [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

    // UPDATE: Tahun sekarang tanpa batasan (dari 2000 sampai tahun ini + 10 tahun ke depan)
    $tahunList   = range(2000, date('Y') + 10);
    $daftarKelas = $daftarKelas ?? collect();
    $kelasFilter = $kelasFilter ?? null;
    $ringkasan   = $ringkasan   ?? ['hadir'=>0,'alpha'=>0,'sakit'=>0,'izin'=>0,'telat'=>0,'total'=>0];

    $namaHari  = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    $todayDay  = (date('Y')==$tahun && date('n')==$bulan) ? (int)date('j') : 0;
    $wm = [];
    for ($d=1;$d<=$jumlahHari;$d++) {
        $dw=(int)date('w',mktime(0,0,0,$bulan,$d,$tahun));
        $wm[$d]=($dw===0||$dw===6);
    }
    $tanpaProfil = $daftarGuru->filter(fn($u)=>!$u->guru)->count();
@endphp

<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="ag-header">
        <h5 class="ag-title">
            <i class="bi bi-calendar-check-fill"></i>
            Absensi Guru
            <span class="sub">— {{ $bulanList[$bulan] }} {{ $tahun }}</span>
        </h5>

        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <a href="{{ route('admin.absensi-guru.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="ag-btn-kelola" style="background:#ef4444; border-color:#ef4444; color:white;">
                <i class="bi bi-file-pdf-fill"></i>
                PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 mb-2" style="font-size:12px;border-radius:8px;">
            <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
        </div>
    @endif

    @if($tanpaProfil > 0)
        <div style="background:#fefce8;border:1px solid #fde68a;border-radius:8px;padding:8px 12px;margin-bottom:10px;font-size:11.5px;color:#92400e;display:flex;align-items:center;gap:7px;">
            <i class="bi bi-exclamation-triangle-fill" style="color:#d97706;flex-shrink:0;"></i>
            <span>
                <strong>{{ $tanpaProfil }} guru</strong> belum punya profil — sel absensinya tidak bisa diisi.
                <a href="{{ route('admin.users.index', ['tab'=>'guru']) }}" style="color:#4f46e5;font-weight:600;">→ Lengkapi profil</a>
            </span>
        </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.absensi-guru.index') }}" class="ag-filter">
        <label><i class="bi bi-calendar3 me-1"></i>Periode:</label>
        <select name="bulan">
            @foreach($bulanList as $num=>$nama)
                <option value="{{ $num }}" {{ $bulan==$num?'selected':'' }}>{{ $nama }}</option>
            @endforeach
        </select>
        <select name="tahun">
            @foreach($tahunList as $t)
                <option value="{{ $t }}" {{ $tahun==$t?'selected':'' }}>{{ $t }}</option>
            @endforeach
        </select>
        @if(is_iterable($daftarKelas) && count($daftarKelas))
            <select name="kelas">
                <option value="">Semua Kelas</option>
                @foreach($daftarKelas as $k)
                    <option value="{{ $k }}" {{ ($kelasFilter??'')==$k?'selected':'' }}>{{ $k }}</option>
                @endforeach
            </select>
        @endif
        <input type="text" id="agSearch" placeholder="Cari nama guru…"
               style="min-width:150px;" oninput="agFil(this.value)" autocomplete="off">
        <button type="submit" class="ag-btn-filter">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>
    </form>

    {{-- STATS --}}
    <div class="ag-stats">
        <div class="ag-stat"><span class="ag-dot" style="background:#4f46e5;"></span><div><div class="ag-val">{{ $daftarGuru->count() }}</div><div class="ag-lbl">Total Guru</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#15803d;"></span><div><div class="ag-val">{{ $ringkasan['hadir'] }}</div><div class="ag-lbl">Hadir</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#b91c1c;"></span><div><div class="ag-val">{{ $ringkasan['alpha'] }}</div><div class="ag-lbl">Alpha</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#a16207;"></span><div><div class="ag-val">{{ $ringkasan['sakit'] }}</div><div class="ag-lbl">Sakit</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#0369a1;"></span><div><div class="ag-val">{{ $ringkasan['izin'] }}</div><div class="ag-lbl">Izin</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#be185d;"></span><div><div class="ag-val">{{ $ringkasan['telat'] }}</div><div class="ag-lbl">Terlambat</div></div></div>
    </div>

    {{-- TABLE --}}
    <div class="ag-wrap">
        <div class="ag-scroll" id="agScroll">
            <table class="ag-tbl">
                <thead>
                    <tr>
                        <th class="ag-cn">
                            Nama Guru
                            <span style="font-weight:400;color:#94a3b8;margin-left:4px;">({{ $daftarGuru->count() }})</span>
                        </th>
                        @for($d=1;$d<=$jumlahHari;$d++)
                            @php $dw=(int)date('w',mktime(0,0,0,$bulan,$d,$tahun)); @endphp
                            <th class="{{ $wm[$d]?'ag-wk':'' }} {{ $todayDay===$d?'ag-td':'' }}">
                                {{ $d }}
                                <div style="font-size:8px;font-weight:500;opacity:.65;">{{ $namaHari[$dw] }}</div>
                            </th>
                        @endfor
                        <th class="ag-cr" style="min-width:80px;text-align:center;">Rekap</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($daftarGuru as $user)
                @php
                    $guru = $user->guru;
                    $namaTampil = ($guru && $guru->nama) ? $guru->nama : $user->name;
                    $subInfo    = ($guru && $guru->nip)  ? $guru->nip  : $user->email;
                    $fotoUrl    = $user->photo ? Storage::url($user->photo) : null;
                    $inisial    = strtoupper(mb_substr($namaTampil, 0, 1));
                    $gid        = ($guru && $guru->id) ? (int)$guru->id : null;

                    $rP=0;$rA=0;$rS=0;$rI=0;$rL=0;$rW=0;
                    if ($gid && !empty($absensiData[$gid])) {
                        foreach ($absensiData[$gid] as $ai) {
                            match($ai->status){ 'P'=>$rP++,'A'=>$rA++,'S'=>$rS++,'I'=>$rI++,'L'=>$rL++,'W'=>$rW++,default=>null };
                        }
                    }
                @endphp

                <tr class="ag-row" data-nama="{{ strtolower($namaTampil) }}">

                    <td class="ag-cn">
                        <div class="ag-gw">
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt=""
                                     style="width:26px;height:26px;border-radius:7px;object-fit:cover;margin-right:7px;flex-shrink:0;border:1px solid #e2e8f0;">
                            @else
                                <span class="ag-av">{{ $inisial }}</span>
                            @endif
                            <div>
                                <div class="ag-gn">{{ $namaTampil }}</div>
                                <span class="ag-gs">{{ $subInfo }}</span>
                            </div>
                        </div>
                    </td>

                    @for($d=1;$d<=$jumlahHari;$d++)
                        @php $abs=($gid&&isset($absensiData[$gid][$d]))?$absensiData[$gid][$d]:null; @endphp
                        <td id="ag-c-{{ $gid??'u'.$user->id }}-{{ $d }}"
                            class="{{ $todayDay===$d?'ag-td':'' }}">
                            @if(!$gid)
                                <span style="color:#f1f5f9;font-size:10px;" title="Profil belum ada">·</span>
                            @elseif($abs)
                                <button class="ag-b ag-b-{{ $abs->status }}"
                                    onclick="agM({{ $gid }},{{ $d }},'{{ $abs->status }}',@js($namaTampil),{{ $abs->id }})"
                                    title="{{ $namaTampil }} · {{ $d }} {{ $bulanList[$bulan] }}">{{ $abs->status }}</button>
                            @else
                                <span class="ag-be"
                                    onclick="agM({{ $gid }},{{ $d }},'',@js($namaTampil),null)"
                                    title="Isi absensi {{ $d }} {{ $bulanList[$bulan] }}">+</span>
                            @endif
                        </td>
                    @endfor

                    <td class="ag-cr">
                        @if(!$gid)
                            <span style="font-size:9px;color:#fca5a5;">Profil kosong</span>
                        @else
                            <div style="display:flex;flex-wrap:wrap;gap:2px;justify-content:center;min-width:70px;">
                                @if($rP)<span class="ag-rc" style="background:#dcfce7;color:#15803d;">P:{{ $rP }}</span>@endif
                                @if($rA)<span class="ag-rc" style="background:#fee2e2;color:#b91c1c;">A:{{ $rA }}</span>@endif
                                @if($rS)<span class="ag-rc" style="background:#fef9c3;color:#a16207;">S:{{ $rS }}</span>@endif
                                @if($rI)<span class="ag-rc" style="background:#e0f2fe;color:#0369a1;">I:{{ $rI }}</span>@endif
                                @if($rL)<span class="ag-rc" style="background:#fce7f3;color:#be185d;">L:{{ $rL }}</span>@endif
                                @if($rW)<span class="ag-rc" style="background:#f3e8ff;color:#7e22ce;">W:{{ $rW }}</span>@endif
                                @if(!$rP&&!$rA&&!$rS&&!$rI&&!$rL&&!$rW)
                                    <span style="color:#cbd5e1;font-size:10px;">—</span>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>

                @empty
                <tr><td colspan="{{ $jumlahHari+2 }}">
                    <div class="ag-empty">
                        <i class="bi bi-people"></i>
                        <p>
                            Belum ada akun dengan role <strong>guru</strong>.<br>
                            Tambahkan via <a href="{{ route('admin.users.index', ['tab'=>'guru']) }}" style="color:#4f46e5;font-weight:600;">Kelola User → Tambah User</a>,
                            pilih role <strong>Guru</strong>.
                        </p>
                    </div>
                </td></tr>
                @endforelse

                </tbody>
            </table>
        </div>

        <div class="ag-legend">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;">Keterangan:</span>
            @foreach(['P'=>['#dcfce7','#15803d','Hadir'],'A'=>['#fee2e2','#b91c1c','Alpha'],'S'=>['#fef9c3','#a16207','Sakit'],'I'=>['#e0f2fe','#0369a1','Izin'],'L'=>['#fce7f3','#be185d','Terlambat'],'W'=>['#f3e8ff','#7e22ce','WFH']] as $k=>[$bg,$fc,$lb])
                <span class="ag-li">
                    <span class="ag-ld" style="background:{{ $bg }};color:{{ $fc }};">{{ $k }}</span>{{ $lb }}
                </span>
            @endforeach
            <span style="margin-left:auto;font-size:10px;color:#cbd5e1;"><i class="bi bi-info-circle me-1"></i>Klik sel untuk isi / ubah</span>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="ag-mov" id="agModal" onclick="if(event.target.id==='agModal')agMC()">
    <div class="ag-mbox">
        <div class="ag-mh">
            <div class="ag-mav" id="agMav">G</div>
            <div><div class="ag-mn" id="agMn">—</div><div class="ag-ms" id="agMs">—</div></div>
        </div>
        <div class="ag-sg">
            @foreach(['P'=>'Hadir','A'=>'Alpha','S'=>'Sakit','I'=>'Izin','L'=>'Terlambat','W'=>'WFH'] as $k=>$lb)
                <button type="button" class="ag-sb" data-s="{{ $k }}" onclick="agSS('{{ $k }}')">
                    <span class="sc">{{ $k }}</span><span class="sl">{{ $lb }}</span>
                </button>
            @endforeach
        </div>
        <div class="ag-ma">
            <button class="ag-mb ag-mb-c" onclick="agMC()"><i class="bi bi-x"></i> Batal</button>
            <button class="ag-mb ag-mb-d" id="agDelBtn" onclick="agDel()" style="display:none;"><i class="bi bi-trash3"></i></button>
            <button class="ag-mb ag-mb-s" id="agSaveBtn" onclick="agSave()"><i class="bi bi-check2"></i> Simpan</button>
        </div>
    </div>
</div>

<div class="ag-toast" id="agToast"><i id="agTi"></i><span id="agTm"></span></div>

@endsection

@push('scripts')
<script>
const AG_B = {{ (int)$bulan }}, AG_Y = {{ (int)$tahun }};
const AG_BN = @js($bulanList[$bulan]);
const AG_SU = "{{ route('admin.absensi-guru.store') }}";
const AG_DB = "{{ url('admin/absensi-guru') }}";
const agCS  = () => document.querySelector('meta[name="csrf-token"]').content;
let ag = {gid:null,hari:null,status:null,aid:null,nama:''};

function agFil(v){
    const q=v.toLowerCase().trim();
    document.querySelectorAll('.ag-row').forEach(r=>{r.style.display=(!q||r.dataset.nama.includes(q))?'':'none';});
}
function agM(gid,hari,status,nama,aid){
    ag={gid,hari,status:status||null,aid,nama};
    document.getElementById('agMav').textContent=nama.charAt(0).toUpperCase();
    document.getElementById('agMn').textContent=nama;
    document.getElementById('agMs').textContent=`${hari} ${AG_BN} ${AG_Y}`+(status?` · ${status}`:' · Belum diisi');
    document.querySelectorAll('.ag-sb').forEach(b=>b.classList.toggle('active',b.dataset.s===status));
    document.getElementById('agDelBtn').style.display=aid?'inline-flex':'none';
    document.getElementById('agModal').classList.add('show');
}
function agMC(){document.getElementById('agModal').classList.remove('show');}
function agSS(k){ag.status=k;document.querySelectorAll('.ag-sb').forEach(b=>b.classList.toggle('active',b.dataset.s===k));}

async function agSave(){
    if(!ag.status){agT('Pilih status','error');return;}
    agBL(true);
    const tgl=`${AG_Y}-${String(AG_B).padStart(2,'0')}-${String(ag.hari).padStart(2,'0')}`;
    try{
        const r=await fetch(AG_SU,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':agCS(),'Accept':'application/json'},body:JSON.stringify({guru_id:ag.gid,tanggal:tgl,status:ag.status,keterangan:''})});
        const d=await r.json();
        if(r.ok&&d.success){agMC();agT('Tersimpan ✓','success');agUC(ag.gid,ag.hari,ag.status,d.id??ag.aid);}
        else agT(d.message??(d.errors?Object.values(d.errors).flat().join(', '):'Gagal'),'error');
    }catch(e){agT('Gagal terhubung','error');}finally{agBL(false);}
}
async function agDel(){
    if(!ag.aid)return;agBL(true);
    try{
        const r=await fetch(`${AG_DB}/${ag.aid}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':agCS(),'Accept':'application/json'}});
        const d=await r.json();
        if(r.ok&&d.success){agMC();agT('Dihapus','success');agCC(ag.gid,ag.hari);}
        else agT(d.message??'Gagal','error');
    }catch(e){agT('Gagal terhubung','error');}finally{agBL(false);}
}
const AGS={P:['#dcfce7','#15803d'],A:['#fee2e2','#b91c1c'],S:['#fef9c3','#a16207'],I:['#e0f2fe','#0369a1'],L:['#fce7f3','#be185d'],W:['#f3e8ff','#7e22ce']};
function agUC(gid,hari,status,aid){
    const c=document.getElementById(`ag-c-${gid}-${hari}`);if(!c)return;
    const[bg,fc]=AGS[status]??['#f1f5f9','#64748b'];
    c.innerHTML=`<button class="ag-b" style="background:${bg};color:${fc};" onclick="agM(${gid},${hari},'${status}',${JSON.stringify(ag.nama)},${aid})">${status}</button>`;
}
function agCC(gid,hari){
    const c=document.getElementById(`ag-c-${gid}-${hari}`);if(!c)return;
    c.innerHTML=`<span class="ag-be" onclick="agM(${gid},${hari},'',${JSON.stringify(ag.nama)},null)">+</span>`;
}
function agBL(on){const b=document.getElementById('agSaveBtn');b.disabled=on;b.innerHTML=on?'<span style="opacity:.6">Menyimpan…</span>':'<i class="bi bi-check2"></i> Simpan';}
let _at;
function agT(msg,type='success'){
    clearTimeout(_at);const el=document.getElementById('agToast');
    document.getElementById('agTi').className=type==='success'?'bi bi-check-circle-fill':'bi bi-exclamation-circle-fill';
    document.getElementById('agTm').textContent=msg;
    el.className=`ag-toast ${type} show`;
    _at=setTimeout(()=>{el.className=`ag-toast ${type}`;},3000);
}
document.addEventListener('DOMContentLoaded',()=>{
    const th=document.querySelector('.ag-tbl thead th.ag-td');
    if(th){const sc=document.getElementById('agScroll');sc.scrollLeft=Math.max(0,th.offsetLeft-240);}
});
document.addEventListener('keydown',e=>{if(e.key==='Escape')agMC();});
</script>
@endpush
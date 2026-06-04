{{-- resources/views/guru/wali-kelas/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Data Kelas Wali')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════
   WALI KELAS — FULL PAGE STYLES
   Compact & professional, sesuai layout app.blade.php
══════════════════════════════════════════════════════ */

/* ── Header banner ── */
.wk-banner {
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 55%, #2563eb 100%);
    border-radius: 12px;
    padding: 14px 18px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
    position: relative;
    overflow: hidden;
}
.wk-banner::before {
    content: '';
    position: absolute;
    right: -30px; top: -30px;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.wk-banner::after {
    content: '';
    position: absolute;
    right: 40px; bottom: -40px;
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.wk-banner-title { font-size: .9rem; font-weight: 800; margin: 0 0 2px; line-height: 1.2; }
.wk-banner-sub   { font-size: .6rem; color: rgba(255,255,255,.75); margin: 0; }
.wk-banner-stats {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.wk-stat-pill {
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 8px;
    padding: 5px 10px;
    text-align: center;
    min-width: 52px;
}
.wk-stat-pill .val { font-size: .9rem; font-weight: 800; line-height: 1; display: block; }
.wk-stat-pill .lbl { font-size: .52rem; color: rgba(255,255,255,.7); display: block; margin-top: 1px; }

/* ── Alert strip ── */
.wk-alert-strip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 9px;
    font-size: .68rem;
    font-weight: 600;
    margin-bottom: 10px;
    border: 1px solid;
}
.wk-alert-risiko { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
.wk-alert-hadir  { background: #fefce8; color: #a16207; border-color: #fde68a; }

/* ── Toolbar: search + filter ── */
.wk-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.wk-search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 340px;
}
.wk-search-wrap svg {
    position: absolute;
    left: 9px; top: 50%;
    transform: translateY(-50%);
    width: 13px; height: 13px;
    color: #94a3b8;
    pointer-events: none;
}
.wk-search {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 10px 6px 30px;
    font-size: .7rem;
    font-family: inherit;
    color: #1e293b;
    background: #fff;
    outline: none;
    transition: border .15s, box-shadow .15s;
    height: 32px;
}
.wk-search:focus { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.12); }
.wk-search::placeholder { color: #cbd5e1; }

.wk-filter-select {
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 0 10px;
    font-size: .68rem;
    font-family: inherit;
    color: #475569;
    background: #fff;
    outline: none;
    height: 32px;
    cursor: pointer;
    transition: border .15s;
}
.wk-filter-select:focus { border-color: #7c3aed; }

.wk-found-info {
    font-size: .6rem;
    color: #94a3b8;
    font-weight: 600;
    white-space: nowrap;
}

/* ── Siswa berisiko strip ── */
.wk-risiko-section {
    background: #fff;
    border: 1px solid #fecaca;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 10px;
    box-shadow: 0 1px 3px rgba(220,38,38,.06);
}
.wk-risiko-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    background: #fef2f2;
    border-bottom: 1px solid #fecaca;
    cursor: pointer;
    user-select: none;
}
.wk-risiko-title {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .72rem;
    font-weight: 700;
    color: #b91c1c;
}
.wk-risiko-cards {
    display: flex;
    gap: 8px;
    padding: 10px 12px;
    overflow-x: auto;
    flex-wrap: nowrap;
}
.wk-risiko-card {
    background: #fafafa;
    border: 1px solid #f3f4f6;
    border-radius: 8px;
    padding: 8px 10px;
    flex-shrink: 0;
    min-width: 140px;
    max-width: 160px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.wk-risiko-card.danger  { border-color: #fecaca; background: #fff5f5; }
.wk-risiko-card.warning { border-color: #fde68a; background: #fffbeb; }
.wk-risiko-nama { font-size: .65rem; font-weight: 700; color: #1e293b; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.wk-risiko-detail { font-size: .56rem; color: #94a3b8; }
.wk-risiko-pct {
    font-size: .78rem;
    font-weight: 800;
    line-height: 1;
}
.wk-risiko-bar { height: 4px; background: #e2e8f0; border-radius: 99px; overflow: hidden; margin-top: 2px; }
.wk-risiko-bar-fill { height: 100%; border-radius: 99px; transition: width .4s; }

/* ── Absensi hari ini strip ── */
.wk-absensi-today {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.wk-abs-title { font-size: .68rem; font-weight: 700; color: #1e293b; flex-shrink: 0; }
.wk-abs-grid  { display: flex; gap: 6px; flex-wrap: wrap; flex: 1; }
.wk-abs-chip {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 7px;
    font-size: .62rem;
    font-weight: 700;
    border: 1px solid;
    cursor: pointer;
    transition: transform .1s;
    white-space: nowrap;
}
.wk-abs-chip:hover { transform: translateY(-1px); }
.wk-abs-chip.hadir    { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
.wk-abs-chip.sakit    { background: #fef9c3; color: #a16207; border-color: #fde68a; }
.wk-abs-chip.izin     { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
.wk-abs-chip.alpha    { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
.wk-abs-chip.terlambat { background: #fdf4ff; color: #7c3aed; border-color: #e9d5ff; }
.wk-abs-chip.kosong   { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }
.wk-abs-num { font-size: .85rem; font-weight: 800; min-width: 16px; text-align: center; }

/* ── Main card ── */
.wk-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.wk-card-head {
    padding: 8px 12px;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
}
.wk-card-title {
    font-size: .72rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 5px;
}
.wk-empty {
    padding: 40px 20px;
    text-align: center;
    color: #94a3b8;
}
.wk-empty .icon { font-size: 2rem; display: block; margin-bottom: 8px; }
.wk-empty p { font-size: .7rem; font-weight: 600; color: #64748b; margin: 0 0 3px; }
.wk-empty small { font-size: .6rem; color: #94a3b8; }

/* ── Table ── */
.wk-table-wrap { overflow-x: auto; }
.wk-table { width: 100%; border-collapse: collapse; min-width: 780px; }
.wk-table thead tr { background: #f8fafc; }
.wk-table thead th {
    padding: 7px 10px;
    font-size: .575rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .05em;
    border-bottom: 1.5px solid #e2e8f0;
    white-space: nowrap;
    text-align: left;
}
.wk-table thead th.center { text-align: center; }
.wk-table tbody td {
    padding: 8px 10px;
    border-bottom: 1px solid #f1f5f9;
    font-size: .69rem;
    color: #374151;
    vertical-align: middle;
}
.wk-table tbody td.center { text-align: center; }
.wk-table tbody tr:last-child td { border-bottom: none; }
.wk-table tbody tr:hover td { background: #f8fafc !important; }
.wk-table tbody tr:nth-child(even) td { background: #fafbfd; }
.wk-table tbody tr:nth-child(odd)  td { background: #fff; }
.wk-table tbody tr.highlight-alpha td { background: #fff5f5 !important; }
.wk-table tbody tr.highlight-terlambat td { background: #fdf4ff !important; }

/* ── Avatar ── */
.wk-av {
    width: 30px; height: 30px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .62rem;
    font-weight: 800;
    flex-shrink: 0;
    border: 1.5px solid #e0e7ff;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    color: #4f46e5;
}
.wk-av img { width: 100%; height: 100%; object-fit: cover; }

/* ── Status chip ── */
.wk-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 20px;
    border-radius: 5px;
    font-size: .6rem;
    font-weight: 700;
    padding: 0 6px;
}
.wk-chip-h  { background: #dcfce7; color: #15803d; }
.wk-chip-s  { background: #fef9c3; color: #a16207; }
.wk-chip-a  { background: #fee2e2; color: #b91c1c; }
.wk-chip-t  { background: #fdf4ff; color: #7c3aed; }
.wk-chip-0  { background: #f1f5f9; color: #94a3b8; }

/* ── Status today badge ── */
.wk-today-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 7px;
    border-radius: 99px;
    font-size: .56rem;
    font-weight: 700;
    white-space: nowrap;
    border: 1px solid;
}
.wk-today-hadir    { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
.wk-today-sakit    { background: #fef9c3; color: #a16207; border-color: #fde68a; }
.wk-today-izin     { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
.wk-today-alpha    { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
.wk-today-terlambat{ background: #fdf4ff; color: #7c3aed; border-color: #e9d5ff; }
.wk-today-none     { background: #f1f5f9; color: #94a3b8; border-color: #e2e8f0; }

/* ── Progress mini ── */
.wk-prog {
    display: flex;
    align-items: center;
    gap: 4px;
}
.wk-prog-bar {
    width: 48px; height: 4px;
    background: #e2e8f0;
    border-radius: 99px;
    overflow: hidden;
    flex-shrink: 0;
}
.wk-prog-fill { height: 100%; border-radius: 99px; }
.wk-prog-val  { font-size: .6rem; font-weight: 700; min-width: 28px; }

/* ── JK badge ── */
.wk-jk-l { background: #dbeafe; color: #1d4ed8; border-radius: 4px; padding: 1px 6px; font-size: .58rem; font-weight: 700; }
.wk-jk-p { background: #fce7f3; color: #be185d; border-radius: 4px; padding: 1px 6px; font-size: .58rem; font-weight: 700; }

/* ── Risiko badge ── */
.wk-risiko-badge {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: .52rem;
    font-weight: 700;
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
    animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.65} }

/* ── Footer ── */
.wk-footer {
    padding: 8px 12px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 6px;
    font-size: .6rem;
    color: #64748b;
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .wk-banner { flex-direction: column; align-items: flex-start; }
    .wk-risiko-cards { flex-wrap: wrap; }
    .wk-risiko-card  { min-width: 130px; }
}
</style>
@endpush

@section('content')
@php
    // ── Variabel dari controller ────────────────────────────────
    $kelas   = $kelas   ?? null;
    $siswa   = $siswa   ?? collect();
    $today   = \Carbon\Carbon::today()->format('Y-m-d');
    $bulanNm = \Carbon\Carbon::now()->isoFormat('MMMM Y');

    // ── Statistik dari kolom yang sudah di-set di controller ──
    $totalSiswa  = $siswa->count();
    $totalL      = $siswa->where('jk', 'L')->count();
    $totalP      = $siswa->where('jk', 'P')->count();

    // Absensi hari ini (sudah di-set di controller via absensi_today)
    $hadirHariIni    = $siswa->where('status_today', 'hadir')->count();
    $sakitHariIni    = $siswa->where('status_today', 'sakit')->count();
    $izinHariIni     = $siswa->where('status_today', 'izin')->count();
    $alphaHariIni    = $siswa->where('status_today', 'alpha')->count();
    $terlambatHariIni= $siswa->where('status_today', 'terlambat')->count();
    $belumAbsensi    = $siswa->whereNull('status_today')->count();

    // Siswa berisiko (kehadiran bulan ini < 75%)
    $siswaBerisiko = $siswa->filter(fn($s) =>
        ($s->kehadiran_pct ?? 100) < 75 || ($s->alpha_bulan ?? 0) > 3
    )->sortBy('kehadiran_pct')->values();

    // Warna kehadiran
    $pctColor = fn($pct) => $pct >= 80 ? '#059669' : ($pct >= 60 ? '#a16207' : '#b91c1c');
@endphp

{{-- ── Banner Header ─────────────────────────────────────────── --}}
<div class="wk-banner">
    <div>
        <p class="wk-banner-title">
            ⭐ Kelas Wali &nbsp;—&nbsp;
            {{ $kelas?->nama ?? 'Belum Ditugaskan' }}
        </p>
        <p class="wk-banner-sub">
            @if($kelas)
                {{ $kelas->tingkat ?? '' }}
                @if($kelas->tingkat ?? null) &nbsp;·&nbsp; @endif
                {{ $kelas->tahun_ajaran ?? '' }}
                &nbsp;·&nbsp; Bulan {{ $bulanNm }}
            @else
                Hubungi admin untuk ditugaskan sebagai wali kelas
            @endif
        </p>
    </div>

    @if($kelas)
    <div class="wk-banner-stats">
        <div class="wk-stat-pill">
            <span class="val">{{ $totalSiswa }}</span>
            <span class="lbl">Total</span>
        </div>
        <div class="wk-stat-pill">
            <span class="val">{{ $totalL }}</span>
            <span class="lbl">Laki-laki</span>
        </div>
        <div class="wk-stat-pill">
            <span class="val">{{ $totalP }}</span>
            <span class="lbl">Perempuan</span>
        </div>
        @if($siswaBerisiko->count() > 0)
        <div class="wk-stat-pill" style="background:rgba(239,68,68,.25);border-color:rgba(239,68,68,.4);">
            <span class="val">{{ $siswaBerisiko->count() }}</span>
            <span class="lbl">Berisiko</span>
        </div>
        @endif
    </div>
    @endif
</div>

@if(!$kelas || $siswa->isEmpty())
{{-- ── Empty / Not Assigned ─────────────────────────────────── --}}
<div class="wk-card">
    <div class="wk-empty">
        <span class="icon">👩‍🏫</span>
        <p>{{ !$kelas ? 'Belum Menjadi Wali Kelas' : 'Kelas Masih Kosong' }}</p>
        <small>{{ !$kelas ? 'Hubungi admin untuk ditugaskan sebagai wali kelas.' : 'Kelas ini belum memiliki siswa yang terdaftar.' }}</small>
    </div>
</div>

@else

{{-- ── Absensi Hari Ini Strip ──────────────────────────────── --}}
<div class="wk-absensi-today">
    <p class="wk-abs-title">📋 Absensi Hari Ini</p>
    <div class="wk-abs-grid">
        <div class="wk-abs-chip hadir" onclick="filterStatus('hadir')" title="Klik untuk filter">
            <span class="wk-abs-num">{{ $hadirHariIni }}</span> Hadir
        </div>
        <div class="wk-abs-chip sakit" onclick="filterStatus('sakit')" title="Klik untuk filter">
            <span class="wk-abs-num">{{ $sakitHariIni }}</span> Sakit
        </div>
        <div class="wk-abs-chip izin" onclick="filterStatus('izin')" title="Klik untuk filter">
            <span class="wk-abs-num">{{ $izinHariIni }}</span> Izin
        </div>
        <div class="wk-abs-chip alpha" onclick="filterStatus('alpha')" title="Klik untuk filter">
            <span class="wk-abs-num">{{ $alphaHariIni }}</span> Alpha
        </div>
        <div class="wk-abs-chip terlambat" onclick="filterStatus('terlambat')" title="Klik untuk filter">
            <span class="wk-abs-num">{{ $terlambatHariIni }}</span> Terlambat
        </div>
        @if($belumAbsensi > 0)
        <div class="wk-abs-chip kosong" onclick="filterStatus('kosong')" title="Klik untuk filter">
            <span class="wk-abs-num">{{ $belumAbsensi }}</span> Belum
        </div>
        @endif
    </div>
    @if(Route::has('guru.absensi-siswa.index'))
        <a href="{{ route('guru.absensi-siswa.index', ['kelas_id' => $kelas->id]) }}"
           style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;
                  background:#eef2ff;border:1px solid #c7d2fe;border-radius:6px;
                  padding:4px 10px;white-space:nowrap;flex-shrink:0;">
            Catat Absensi →
        </a>
    @endif
</div>

{{-- ── Siswa Berisiko Section ──────────────────────────────── --}}
@if($siswaBerisiko->isNotEmpty())
<div class="wk-risiko-section">
    <div class="wk-risiko-head" onclick="toggleRisiko()">
        <div class="wk-risiko-title">
            <span>⚠️</span>
            Siswa Perlu Perhatian
            <span style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;
                         border-radius:99px;font-size:.55rem;padding:1px 7px;font-weight:700;">
                {{ $siswaBerisiko->count() }} siswa
            </span>
        </div>
        <svg id="risikoChevron" style="width:14px;height:14px;color:#b91c1c;transition:transform .2s;"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
    <div id="risikoBody" class="wk-risiko-cards">
        @foreach($siswaBerisiko as $sr)
        @php
            $srPct   = $sr->kehadiran_pct ?? 0;
            $srAlpha = $sr->alpha_bulan   ?? 0;
            $srNama  = $sr->nama_tampil   ?? $sr->nama ?? '—';
            $srColor = $srPct >= 60 ? '#a16207' : '#b91c1c';
            $cardCls = $srPct < 60 ? 'danger' : 'warning';
        @endphp
        <div class="wk-risiko-card {{ $cardCls }}">
            <p class="wk-risiko-nama" title="{{ $srNama }}">{{ $srNama }}</p>
            <p class="wk-risiko-detail">
                Alpha: <strong>{{ $srAlpha }}x</strong>
                @if($sr->terlambat_count ?? 0 > 0)
                    · Terlambat: <strong>{{ $sr->terlambat_count }}x</strong>
                @endif
            </p>
            <div style="display:flex;align-items:baseline;justify-content:space-between;margin-top:2px;">
                <span class="wk-risiko-pct" style="color:{{ $srColor }};">{{ $srPct }}%</span>
                <span style="font-size:.52rem;color:#94a3b8;">hadir</span>
            </div>
            <div class="wk-risiko-bar">
                <div class="wk-risiko-bar-fill"
                     style="width:{{ $srPct }}%;background:{{ $srColor }};"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── Toolbar: Search + Filter ────────────────────────────── --}}
<div class="wk-toolbar">
    <div class="wk-search-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="wkSearch" class="wk-search"
               placeholder="Cari nama siswa, NIS…"
               oninput="filterSiswa()">
    </div>
    <select class="wk-filter-select" id="wkFilterStatus" onchange="filterSiswa()">
        <option value="">Semua Status Hari Ini</option>
        <option value="hadir">Hadir</option>
        <option value="sakit">Sakit</option>
        <option value="izin">Izin</option>
        <option value="alpha">Alpha</option>
        <option value="terlambat">Terlambat</option>
        <option value="kosong">Belum Absensi</option>
    </select>
    <select class="wk-filter-select" id="wkFilterJk" onchange="filterSiswa()">
        <option value="">Semua JK</option>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select>
    <select class="wk-filter-select" id="wkFilterRisiko" onchange="filterSiswa()">
        <option value="">Semua Siswa</option>
        <option value="risiko">Perlu Perhatian</option>
    </select>
    <span class="wk-found-info" id="wkFoundInfo">
        {{ $totalSiswa }} siswa
    </span>
    <button onclick="resetFilter()"
            style="height:32px;padding:0 10px;border:1.5px solid #e2e8f0;border-radius:8px;
                   font-size:.65rem;font-weight:600;color:#64748b;background:#f8fafc;
                   cursor:pointer;transition:all .15s;"
            onmouseover="this.style.background='#f1f5f9'"
            onmouseout="this.style.background='#f8fafc'">
        Reset
    </button>
</div>

{{-- ── Main Table Card ─────────────────────────────────────── --}}
<div class="wk-card">
    <div class="wk-card-head">
        <div class="wk-card-title">
            <span>👥</span>
            Daftar Siswa
            @if($kelas)
                <span style="background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe;
                             border-radius:5px;padding:1px 8px;font-size:.58rem;font-weight:700;">
                    {{ $kelas->nama }}
                </span>
            @endif
        </div>
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
            @if(Route::has('guru.absensi-siswa.rekap'))
                <a href="{{ route('guru.absensi-siswa.rekap', ['kelas_id' => $kelas->id]) }}"
                   style="font-size:.6rem;font-weight:700;color:#7c3aed;text-decoration:none;
                          background:#fdf4ff;border:1px solid #e9d5ff;border-radius:6px;
                          padding:4px 9px;white-space:nowrap;">
                    📊 Rekap Absensi
                </a>
            @endif
            @if(Route::has('guru.absensi-siswa.index'))
                <a href="{{ route('guru.absensi-siswa.index', ['kelas_id' => $kelas->id]) }}"
                   style="font-size:.6rem;font-weight:700;color:#fff;text-decoration:none;
                          background:#4f46e5;border:1px solid #4f46e5;border-radius:6px;
                          padding:4px 9px;white-space:nowrap;">
                    ✅ Catat Absensi
                </a>
            @endif
        </div>
    </div>

    <div class="wk-table-wrap">
        <table class="wk-table" id="wkTable">
            <thead>
                <tr>
                    <th style="width:36px;">#</th>
                    <th style="width:38px;">Foto</th>
                    <th style="min-width:160px;">Nama Lengkap</th>
                    <th style="width:90px;">NIS / NIK</th>
                    <th class="center" style="width:40px;">JK</th>
                    <th class="center" style="width:80px;">Tgl Lahir</th>
                    <th class="center" style="width:90px;">Status Hari Ini</th>
                    <th class="center" style="width:90px;">Kehadiran Bulan Ini</th>
                    <th class="center" style="width:65px;">Sakit Bln</th>
                    <th class="center" style="width:65px;">Alpha Bln</th>
                    <th class="center" style="width:70px;">Terlambat Bln</th>
                </tr>
            </thead>
            <tbody id="wkTbody">
                @forelse($siswa as $no => $s)
                @php
                    $nama      = $s->nama_tampil ?? $s->nama ?? '—';
                    $inisial   = $s->inisial ?? strtoupper(substr($nama, 0, 1));
                    $foto      = $s->foto ?? null;
                    $nis       = $s->nis ?? $s->nik ?? null;
                    $jk        = $s->jk ?? null;
                    $tglLahir  = $s->tanggal_lahir ?? null;
                    if (is_string($tglLahir) && $tglLahir) {
                        try { $tglLahir = \Carbon\Carbon::parse($tglLahir); } catch (\Exception $e) { $tglLahir = null; }
                    }

                    // Absensi hari ini
                    $statusToday = $s->status_today ?? null;

                    // Statistik bulan ini — sudah dihitung di controller
                    $hadirBln    = $s->hadir_bulan    ?? 0;
                    $sakitBln    = $s->sakit_bulan    ?? 0;
                    $alphaBln    = $s->alpha_bulan     ?? 0;
                    $terlambatBln= $s->terlambat_count ?? 0;
                    $totalBln    = $s->total_absensi_bulan ?? ($hadirBln + $sakitBln + ($s->izin_bulan ?? 0) + $alphaBln + $terlambatBln);
                    $pct         = $totalBln > 0 ? round($hadirBln / $totalBln * 100) : 0;
                    $pctColorVal = $pct >= 80 ? '#059669' : ($pct >= 60 ? '#a16207' : '#b91c1c');

                    $isRisiko    = $pct < 75 || $alphaBln > 3;
                    $trCls       = '';
                    if ($statusToday === 'alpha') $trCls = 'highlight-alpha';
                    elseif ($statusToday === 'terlambat') $trCls = 'highlight-terlambat';

                    // data-* untuk JS filter
                    $dataNama    = strtolower($nama);
                    $dataNis     = strtolower($nis ?? '');
                    $dataStatus  = $statusToday ?? 'kosong';
                    $dataJk      = strtolower($jk ?? '');
                    $dataRisiko  = $isRisiko ? 'risiko' : '';
                @endphp
                <tr class="{{ $trCls }}"
                    data-nama="{{ $dataNama }}"
                    data-nis="{{ $dataNis }}"
                    data-status="{{ $dataStatus }}"
                    data-jk="{{ $dataJk }}"
                    data-risiko="{{ $dataRisiko }}">

                    {{-- # ─── Nomor --}}
                    <td>
                        <span style="width:22px;height:22px;border-radius:5px;background:#f1f5f9;
                                     color:#94a3b8;font-size:.55rem;font-weight:700;
                                     display:inline-flex;align-items:center;justify-content:center;">
                            {{ $no + 1 }}
                        </span>
                    </td>

                    {{-- Foto / Avatar ── --}}
                    <td>
                        <div class="wk-av">
                            @if($foto)
                                <img src="{{ $foto }}" alt="{{ $nama }}">
                            @else
                                {{ $inisial }}
                            @endif
                        </div>
                    </td>

                    {{-- Nama ── --}}
                    <td>
                        <div style="font-weight:700;color:#1e293b;font-size:.7rem;line-height:1.3;">
                            {{ $nama }}
                            @if($isRisiko)
                                <span class="wk-risiko-badge">⚠ Perhatian</span>
                            @endif
                        </div>
                    </td>

                    {{-- NIS / NIK ── --}}
                    <td>
                        @if($nis)
                            <span style="font-family:monospace;font-size:.62rem;color:#64748b;">
                                {{ $nis }}
                            </span>
                        @else
                            <span style="color:#cbd5e1;font-size:.62rem;">—</span>
                        @endif
                    </td>

                    {{-- JK ── --}}
                    <td class="center">
                        @if($jk === 'L')
                            <span class="wk-jk-l">L</span>
                        @elseif($jk === 'P')
                            <span class="wk-jk-p">P</span>
                        @else
                            <span style="color:#cbd5e1;font-size:.62rem;">—</span>
                        @endif
                    </td>

                    {{-- Tgl Lahir ── --}}
                    <td class="center">
                        <span style="font-size:.62rem;color:#64748b;">
                            {{ $tglLahir ? (is_object($tglLahir) ? $tglLahir->format('d/m/Y') : date('d/m/Y', strtotime($tglLahir))) : '—' }}
                        </span>
                    </td>

                    {{-- Status Hari Ini ── --}}
                    <td class="center">
                        @if($statusToday)
                            @php
                                $todayCls = match($statusToday) {
                                    'hadir'     => 'wk-today-hadir',
                                    'sakit'     => 'wk-today-sakit',
                                    'izin'      => 'wk-today-izin',
                                    'alpha'     => 'wk-today-alpha',
                                    'terlambat' => 'wk-today-terlambat',
                                    default     => 'wk-today-none',
                                };
                                $todayIcon = match($statusToday) {
                                    'hadir'     => '✅',
                                    'sakit'     => '🤒',
                                    'izin'      => '📋',
                                    'alpha'     => '❌',
                                    'terlambat' => '⏰',
                                    default     => '—',
                                };
                            @endphp
                            <span class="wk-today-badge {{ $todayCls }}">
                                {{ $todayIcon }} {{ ucfirst($statusToday) }}
                            </span>
                        @else
                            <span class="wk-today-badge wk-today-none">— Belum</span>
                        @endif
                    </td>

                    {{-- Kehadiran Bulan Ini ── --}}
                    <td class="center">
                        @if($totalBln > 0)
                            <div class="wk-prog" style="justify-content:center;">
                                <div class="wk-prog-bar">
                                    <div class="wk-prog-fill"
                                         style="width:{{ $pct }}%;background:{{ $pctColorVal }};"></div>
                                </div>
                                <span class="wk-prog-val" style="color:{{ $pctColorVal }};">
                                    {{ $pct }}%
                                </span>
                            </div>
                        @else
                            <span style="font-size:.6rem;color:#cbd5e1;">—</span>
                        @endif
                    </td>

                    {{-- Sakit Bulan Ini ── --}}
                    <td class="center">
                        <span class="wk-chip {{ $sakitBln > 0 ? 'wk-chip-s' : 'wk-chip-0' }}">
                            {{ $sakitBln }}x
                        </span>
                    </td>

                    {{-- Alpha Bulan Ini ── --}}
                    <td class="center">
                        <span class="wk-chip {{ $alphaBln > 3 ? 'wk-chip-a' : ($alphaBln > 0 ? 'wk-chip-a' : 'wk-chip-0') }}"
                              {{ $alphaBln > 3 ? 'style=animation:pulse 1.5s infinite;' : '' }}>
                            {{ $alphaBln }}x
                        </span>
                    </td>

                    {{-- Terlambat Bulan Ini ── --}}
                    <td class="center">
                        <span class="wk-chip {{ $terlambatBln > 3 ? 'wk-chip-t' : ($terlambatBln > 0 ? 'wk-chip-t' : 'wk-chip-0') }}">
                            {{ $terlambatBln }}x
                        </span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="11">
                        <div class="wk-empty">
                            <span class="icon">👤</span>
                            <p>Tidak ada siswa ditemukan</p>
                            <small>Coba ubah kata kunci pencarian atau filter</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Empty state saat filter JS --}}
    <div id="wkEmptyFilter" style="display:none;">
        <div class="wk-empty">
            <span class="icon">🔍</span>
            <p>Tidak ditemukan</p>
            <small>Tidak ada siswa yang cocok dengan pencarian</small>
        </div>
    </div>

    <div class="wk-footer">
        <span id="wkFooterCount">{{ $totalSiswa }} siswa terdaftar</span>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <span>
                <span class="wk-jk-l" style="margin-right:3px;">L</span> {{ $totalL }} siswa
            </span>
            <span>
                <span class="wk-jk-p" style="margin-right:3px;">P</span> {{ $totalP }} siswa
            </span>
            @if($siswaBerisiko->count() > 0)
                <span style="color:#b91c1c;font-weight:700;">
                    ⚠️ {{ $siswaBerisiko->count() }} perlu perhatian
                </span>
            @endif
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
/* ══════════════════════════════════════════════════════
   WALI KELAS — CLIENT-SIDE SEARCH & FILTER
══════════════════════════════════════════════════════ */

// Toggle risiko section
function toggleRisiko() {
    var body    = document.getElementById('risikoBody');
    var chevron = document.getElementById('risikoChevron');
    if (!body) return;
    var hidden = body.style.display === 'none';
    body.style.display    = hidden ? 'flex' : 'none';
    chevron.style.transform = hidden ? 'rotate(0deg)' : 'rotate(-90deg)';
}

// Filter status dari chip absensi hari ini
function filterStatus(status) {
    var sel = document.getElementById('wkFilterStatus');
    if (!sel) return;
    // Toggle: jika sudah dipilih, reset
    if (sel.value === status) {
        sel.value = '';
    } else {
        sel.value = status;
    }
    filterSiswa();
}

// Reset semua filter
function resetFilter() {
    document.getElementById('wkSearch').value       = '';
    document.getElementById('wkFilterStatus').value = '';
    document.getElementById('wkFilterJk').value     = '';
    document.getElementById('wkFilterRisiko').value = '';
    filterSiswa();
}

// Filter + search utama
function filterSiswa() {
    var q       = (document.getElementById('wkSearch').value         || '').toLowerCase().trim();
    var status  = (document.getElementById('wkFilterStatus').value   || '').toLowerCase();
    var jk      = (document.getElementById('wkFilterJk').value       || '').toLowerCase();
    var risiko  = (document.getElementById('wkFilterRisiko').value   || '').toLowerCase();

    var rows    = document.querySelectorAll('#wkTbody tr[data-nama]');
    var visible = 0;

    rows.forEach(function (tr) {
        var nama   = tr.getAttribute('data-nama')   || '';
        var nis    = tr.getAttribute('data-nis')    || '';
        var st     = tr.getAttribute('data-status') || '';
        var jkRow  = tr.getAttribute('data-jk')    || '';
        var risRow = tr.getAttribute('data-risiko') || '';

        var matchQ  = !q      || nama.includes(q) || nis.includes(q);
        var matchSt = !status || st === status;
        var matchJk = !jk     || jkRow === jk;
        var matchRs = !risiko || risRow === risiko;

        var show = matchQ && matchSt && matchJk && matchRs;
        tr.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    // Update info count
    var info   = document.getElementById('wkFoundInfo');
    var footer = document.getElementById('wkFooterCount');
    var empty  = document.getElementById('wkEmptyFilter');

    if (info) info.textContent = visible + ' siswa';
    if (footer) footer.textContent = visible + ' siswa ditampilkan';
    if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
}

// Trigger filter saat halaman siap
document.addEventListener('DOMContentLoaded', function () {
    // Debounce search input
    var searchEl = document.getElementById('wkSearch');
    if (searchEl) {
        var timer;
        searchEl.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(filterSiswa, 200);
        });
    }
});
</script>
@endpush
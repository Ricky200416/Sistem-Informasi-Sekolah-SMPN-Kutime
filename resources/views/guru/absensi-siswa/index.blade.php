@extends('layouts.app')

@section('title', 'Absensi Siswa')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* ══════════════════════════════════════════
   ABSENSI SISWA — COMPACT SCALE
   Semua ukuran disesuaikan dengan layout
   app.blade.php (base 13px, konten ~11px)
══════════════════════════════════════════ */

/* ── Topbar row ── */
.as-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.692rem;
    flex-wrap: wrap;
    gap: 0.462rem;
}
.as-title {
    font-size: 0.923rem;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 0.462rem;
    margin: 0;
    letter-spacing: -.2px;
}
.as-title i { color: #6366f1; font-size: 0.923rem; }

.as-date-pill {
    background: #e0e7ff;
    color: #4338ca;
    border-radius: 99px;
    padding: 0.2rem 0.615rem;
    font-size: 0.615rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.as-topbar-right {
    display: flex;
    align-items: center;
    gap: 0.385rem;
    flex-wrap: wrap;
}

/* ── Filter card ── */
.as-filter-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.692rem;
    padding: 0.692rem 0.923rem;
    margin-bottom: 0.692rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    display: flex;
    align-items: flex-end;
    gap: 0.615rem;
    flex-wrap: wrap;
}
.as-filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}
.as-filter-group label {
    font-size: 0.577rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin: 0;
}
.as-filter-group select,
.as-filter-group input[type="date"] {
    border: 1px solid #e2e8f0;
    border-radius: 0.462rem;
    padding: 0.308rem 0.538rem;
    font-size: 0.692rem;
    font-family: inherit;
    color: #1e293b;
    background: #f8fafc;
    outline: none;
    transition: border .15s;
    min-width: 140px;
    height: 1.923rem;
}
.as-filter-group select:focus,
.as-filter-group input:focus {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 2px rgba(99,102,241,.12);
}

/* ── Stat strip ── */
.as-stats {
    display: flex;
    gap: 0.462rem;
    margin-bottom: 0.692rem;
    flex-wrap: wrap;
}
.as-stat {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.538rem;
    padding: 0.538rem 0.692rem;
    flex: 1;
    min-width: 72px;
    display: flex;
    align-items: center;
    gap: 0.538rem;
}
.as-stat-icon {
    width: 1.923rem;
    height: 1.923rem;
    border-radius: 0.462rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.769rem;
    flex-shrink: 0;
}
.as-stat-val {
    font-size: 1.077rem;
    font-weight: 800;
    line-height: 1;
}
.as-stat-lbl {
    font-size: 0.538rem;
    color: #94a3b8;
    font-weight: 600;
    margin-top: 0.1rem;
    white-space: nowrap;
}

/* ── Main card ── */
.as-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.692rem;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.as-card-head {
    padding: 0.538rem 0.769rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.462rem;
    background: #fafbff;
}
.as-card-title {
    font-size: 0.769rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.385rem;
}
.as-card-title i { color: #6366f1; }
.kelas-chip {
    background: #e0e7ff;
    color: #4338ca;
    border-radius: 0.385rem;
    padding: 0.1rem 0.462rem;
    font-size: 0.615rem;
    font-weight: 700;
}
.as-saved-badge {
    background: #dcfce7;
    color: #15803d;
    border-radius: 0.385rem;
    padding: 0.1rem 0.462rem;
    font-size: 0.577rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
}

/* ── Bulk actions ── */
.as-bulk-actions {
    display: flex;
    align-items: center;
    gap: 0.308rem;
    flex-wrap: wrap;
}
.as-bulk-actions span {
    font-size: 0.577rem;
    color: #94a3b8;
    font-weight: 600;
}
.as-bulk-btn {
    border: 1px solid;
    border-radius: 0.385rem;
    padding: 0.23rem 0.538rem;
    font-size: 0.615rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    gap: 0.231rem;
    transition: all .15s;
    background: transparent;
    line-height: 1;
}
.as-bulk-hadir { border-color: #16a34a; color: #16a34a; }
.as-bulk-hadir:hover { background: #16a34a; color: #fff; }
.as-bulk-alpha { border-color: #dc2626; color: #dc2626; }
.as-bulk-alpha:hover { background: #dc2626; color: #fff; }

/* ── Progress bar ── */
.as-progress-wrap {
    padding: 0.385rem 0.769rem;
    background: #fafbff;
    border-bottom: 1px solid #f1f5f9;
}
.as-progress-bar {
    height: 3px;
    border-radius: 99px;
    background: #e2e8f0;
    overflow: hidden;
}
.as-progress-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg,#6366f1,#818cf8);
    transition: width .3s ease;
}
.as-progress-label {
    font-size: 0.538rem;
    color: #94a3b8;
    margin-top: 0.25rem;
    font-weight: 600;
}

/* ── Siswa list ── */
.as-siswa-list { padding: 0; }

/* ── Siswa row — updated layout ── */
.as-siswa-row {
    display: flex;
    align-items: center;
    gap: 0.615rem;
    padding: 0.615rem 0.769rem;
    transition: background .1s;
    border-bottom: 1px solid #f1f5f9;
    min-height: 3.6rem;
}
.as-siswa-row:last-child { border-bottom: none; }
.as-siswa-row:hover { background: #fafbff; }

/* nomor urut */
.as-no {
    width: 1.462rem;
    height: 1.462rem;
    border-radius: 0.308rem;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 0.538rem;
    font-weight: 700;
    text-align: center;
    line-height: 1.462rem;
    flex-shrink: 0;
}

/* avatar — lebih besar agar profil terlihat jelas */
.as-av {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.615rem;
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    color: #6366f1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.923rem;
    font-weight: 800;
    flex-shrink: 0;
    overflow: hidden;
    border: 1.5px solid #e0e7ff;
    box-shadow: 0 1px 4px rgba(99,102,241,.10);
}
.as-av img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* nama — flex:1 agar nama panjang tidak terpotong */
.as-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}
.as-nama {
    font-size: 0.815rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
    /* Izinkan wrap agar nama panjang tampil penuh */
    white-space: normal;
    word-break: break-word;
}
.as-nis {
    font-size: 0.577rem;
    color: #94a3b8;
    font-weight: 500;
    line-height: 1.2;
}

/* ── Right side: status + keterangan dibungkus bersama ── */
.as-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.308rem;
    flex-shrink: 0;
}

/* status buttons */
.as-status-group {
    display: flex;
    gap: 0.231rem;
    flex-wrap: nowrap;
}
.as-st-btn {
    width: 3rem;
    height: 1.692rem;
    border-radius: 0.385rem;
    border: 1px solid;
    font-size: 0.577rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all .15s;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: .01em;
    line-height: 1;
}
.as-st-btn[data-s="hadir"] { border-color: #bbf7d0; color: #15803d; }
.as-st-btn[data-s="hadir"]:hover,
.as-st-btn[data-s="hadir"].active { background: #16a34a; border-color: #16a34a; color: #fff; }
.as-st-btn[data-s="sakit"] { border-color: #fde68a; color: #b45309; }
.as-st-btn[data-s="sakit"]:hover,
.as-st-btn[data-s="sakit"].active { background: #d97706; border-color: #d97706; color: #fff; }
.as-st-btn[data-s="izin"]  { border-color: #bae6fd; color: #0369a1; }
.as-st-btn[data-s="izin"]:hover,
.as-st-btn[data-s="izin"].active { background: #0284c7; border-color: #0284c7; color: #fff; }
.as-st-btn[data-s="alpha"] { border-color: #fecaca; color: #b91c1c; }
.as-st-btn[data-s="alpha"]:hover,
.as-st-btn[data-s="alpha"].active { background: #dc2626; border-color: #dc2626; color: #fff; }

/* keterangan input — di bawah tombol, lebar penuh sisi kanan */
.as-ket {
    width: 12.8rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.385rem;
    padding: 0.27rem 0.462rem;
    font-size: 0.615rem;
    font-family: inherit;
    color: #475569;
    background: #f8fafc;
    outline: none;
    transition: border .15s;
    height: 1.538rem;
}
.as-ket::placeholder { color: #cbd5e1; }
.as-ket:focus {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 2px rgba(99,102,241,.12);
}

/* ── Save bar ── */
.as-save-bar {
    position: sticky;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    border-top: 1px solid #e0e7ff;
    padding: 0.538rem 0.769rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.538rem;
    flex-wrap: wrap;
    box-shadow: 0 -3px 12px rgba(99,102,241,.07);
    z-index: 10;
}
.as-save-info {
    font-size: 0.615rem;
    color: #64748b;
}
.as-save-info strong { color: #1e293b; }

/* ── Buttons ── */
.as-btn {
    border-radius: 0.462rem;
    padding: 0.308rem 0.692rem;
    font-size: 0.692rem;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.308rem;
    transition: all .15s;
    border: 1px solid transparent;
    text-decoration: none;
    line-height: 1;
    white-space: nowrap;
    height: 1.923rem;
}
.as-btn-primary {
    background: #6366f1;
    color: #fff;
    border-color: #6366f1;
    box-shadow: 0 2px 8px rgba(99,102,241,.25);
}
.as-btn-primary:hover { background: #4f46e5; border-color: #4f46e5; }
.as-btn-primary:disabled {
    background: #a5b4fc;
    cursor: not-allowed;
    box-shadow: none;
    border-color: #a5b4fc;
}
.as-btn-outline {
    background: #f8fafc;
    color: #475569;
    border-color: #e2e8f0;
}
.as-btn-outline:hover { background: #f1f5f9; }
.as-btn-save-success {
    background: #16a34a !important;
    border-color: #16a34a !important;
}

/* ── Alert ── */
.as-alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.538rem;
    padding: 0.538rem 0.692rem;
    margin-bottom: 0.615rem;
    font-size: 0.692rem;
    color: #b91c1c;
    display: flex;
    align-items: flex-start;
    gap: 0.462rem;
}
.as-alert-error i { flex-shrink: 0; margin-top: 1px; }

.as-alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 0.538rem;
    padding: 0.462rem 0.692rem;
    margin-bottom: 0.615rem;
    font-size: 0.692rem;
    color: #15803d;
    display: flex;
    align-items: center;
    gap: 0.385rem;
}

/* ── Empty state ── */
.as-empty {
    padding: 2.5rem 1rem;
    text-align: center;
    color: #94a3b8;
}
.as-empty i {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.615rem;
    color: #cbd5e1;
}
.as-empty h3 {
    font-size: 0.769rem;
    font-weight: 700;
    color: #64748b;
    margin: 0 0 0.308rem;
}
.as-empty p {
    font-size: 0.692rem;
    margin: 0;
    line-height: 1.6;
}

/* ── Toast ── */
.as-toast {
    position: fixed;
    bottom: 4.615rem;
    right: 1rem;
    z-index: 9999;
    background: #0f172a;
    color: #fff;
    border-radius: 0.615rem;
    padding: 0.615rem 0.923rem;
    font-size: 0.692rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.462rem;
    box-shadow: 0 6px 24px rgba(0,0,0,.18);
    transform: translateY(3rem);
    opacity: 0;
    transition: all .3s cubic-bezier(.34,1.56,.64,1);
    pointer-events: none;
    max-width: 18rem;
}
.as-toast.show { transform: translateY(0); opacity: 1; }
.as-toast i { font-size: 0.846rem; flex-shrink: 0; }
.as-toast.success i { color: #4ade80; }
.as-toast.error   i { color: #f87171; }

/* ── Responsive ── */
@media (max-width: 640px) {
    .as-siswa-row { flex-wrap: wrap; gap: 0.462rem; }
    .as-right { width: 100%; align-items: stretch; flex-direction: row; flex-wrap: wrap; gap: 0.308rem; }
    .as-status-group { flex: 1; }
    .as-ket { flex: 1; width: auto; }
}
@media (max-width: 480px) {
    .as-st-btn { width: 2.6rem; font-size: 0.538rem; }
    .as-stats { gap: 0.308rem; }
    .as-stat { min-width: 60px; padding: 0.385rem 0.462rem; }
    .as-stat-val { font-size: 0.923rem; }
}
@media (max-width: 380px) {
    .as-stat-lbl { display: none; }
    .as-stat-icon { display: none; }
}
</style>
@endpush

@section('content')
@php
    $kelasList     = $kelasList     ?? collect();
    $kelasId       = $kelasId       ?? null;
    $tanggal       = $tanggal       ?? date('Y-m-d');
    $hariIni       = $hariIni       ?? '';
    $siswaList     = $siswaList     ?? collect();
    $absensiHari   = $absensiHari   ?? collect();
    $sudahDisimpan = $sudahDisimpan ?? false;
    $ringkasan     = $ringkasan     ?? [];

    $selectedKelas = $kelasList->firstWhere('id', $kelasId);
@endphp

{{-- ── Topbar ── --}}
<div class="as-topbar">
    <h5 class="as-title">
        <i class="bi bi-clipboard2-pulse-fill"></i>
        Absensi Siswa
    </h5>
    <div class="as-topbar-right">
        <div class="as-date-pill">
            <i class="bi bi-calendar3"></i>
            {{ $hariIni ?: \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}
        </div>
        <a href="{{ route('guru.absensi-siswa.rekap') }}" class="as-btn as-btn-outline">
            <i class="bi bi-bar-chart-line-fill"></i> Rekap
        </a>
    </div>
</div>

{{-- ── Flash messages ── --}}
@if(session('success'))
    <div class="as-alert-success">
        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
    </div>
@endif

<div id="errorContainer" style="display:none;" class="as-alert-error">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div id="errorMessage"></div>
</div>

{{-- ── Filter ── --}}
<form method="GET" action="{{ route('guru.absensi-siswa.index') }}" class="as-filter-card" id="filterForm">
    <div class="as-filter-group">
        <label><i class="bi bi-calendar3"></i> Tanggal</label>
        <input type="date" name="tanggal"
               value="{{ $tanggal }}"
               max="{{ date('Y-m-d') }}"
               onchange="this.form.submit()">
    </div>
    <div class="as-filter-group">
        <label><i class="bi bi-mortarboard"></i> Kelas</label>
        <select name="kelas_id" onchange="this.form.submit()">
            <option value="">— Pilih Kelas —</option>
            @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                    {{ $kelas->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="as-btn as-btn-primary">
        <i class="bi bi-arrow-clockwise"></i> Muat
    </button>
</form>

{{-- ── Stat strip ── --}}
@if($kelasId && $siswaList->count())
    <div class="as-stats">
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#ede9fe;">
                <i class="bi bi-people-fill" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#7c3aed;">{{ $siswaList->count() }}</div>
                <div class="as-stat-lbl">Total</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#dcfce7;">
                <i class="bi bi-check2-circle" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#16a34a;">{{ $ringkasan['hadir'] ?? 0 }}</div>
                <div class="as-stat-lbl">Hadir</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#fef9c3;">
                <i class="bi bi-thermometer-half" style="color:#ca8a04;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#ca8a04;">{{ $ringkasan['sakit'] ?? 0 }}</div>
                <div class="as-stat-lbl">Sakit</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#e0f2fe;">
                <i class="bi bi-envelope-check" style="color:#0284c7;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#0284c7;">{{ $ringkasan['izin'] ?? 0 }}</div>
                <div class="as-stat-lbl">Izin</div>
            </div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#fee2e2;">
                <i class="bi bi-x-circle" style="color:#dc2626;"></i>
            </div>
            <div>
                <div class="as-stat-val" style="color:#dc2626;">{{ $ringkasan['alpha'] ?? 0 }}</div>
                <div class="as-stat-lbl">Alpha</div>
            </div>
        </div>
    </div>
@endif

{{-- ── Main card ── --}}
<div class="as-card">

    {{-- Card header --}}
    <div class="as-card-head">
        <div class="as-card-title">
            <i class="bi bi-list-check"></i>
            Daftar Siswa
            @if($selectedKelas)
                <span class="kelas-chip">{{ $selectedKelas->nama }}</span>
            @endif
            @if($sudahDisimpan)
                <span class="as-saved-badge">
                    <i class="bi bi-check-circle-fill"></i> Tersimpan
                </span>
            @endif
        </div>

        @if($siswaList->count() > 0)
            <div class="as-bulk-actions">
                <span>Tandai:</span>
                <button type="button" class="as-bulk-btn as-bulk-hadir" onclick="tandaiSemua('hadir')">
                    <i class="bi bi-check2-all"></i> Hadir
                </button>
                <button type="button" class="as-bulk-btn as-bulk-alpha" onclick="tandaiSemua('alpha')">
                    <i class="bi bi-x-lg"></i> Alpha
                </button>
            </div>
        @endif
    </div>

    {{-- Progress --}}
    @if($siswaList->count() > 0)
        @php $pct = round($absensiHari->count() / $siswaList->count() * 100); @endphp
        <div class="as-progress-wrap">
            <div class="as-progress-bar">
                <div class="as-progress-fill" id="progressFill" style="width:{{ $pct }}%;"></div>
            </div>
            <div class="as-progress-label" id="progressLabel">
                {{ $absensiHari->count() }} / {{ $siswaList->count() }} siswa diisi ({{ $pct }}%)
            </div>
        </div>
    @endif

    {{-- Content --}}
    @if(!$kelasId)
        <div class="as-empty">
            <i class="bi bi-mortarboard"></i>
            <h3>Pilih Kelas Terlebih Dahulu</h3>
            <p>Pilih kelas dan tanggal di atas untuk mulai mengisi absensi.</p>
        </div>

    @elseif($siswaList->count() === 0)
        <div class="as-empty">
            <i class="bi bi-person-x"></i>
            <h3>Belum Ada Siswa</h3>
            <p>Kelas ini belum memiliki siswa yang terdaftar.</p>
        </div>

    @else
        <div class="as-siswa-list">
            @foreach($siswaList as $i => $siswa)
                @php
                    $existing    = $absensiHari->get($siswa->id);
                    $statusSaved = $existing ? $existing->status : null;
                    $ketSaved    = $existing ? ($existing->keterangan ?? '') : '';
                    $namaTampil  = $siswa->nama ?: ($siswa->user?->name ?? '—');
                    $nis         = $siswa->nis ?? null;
                    $fotoUrl     = ($siswa->user && $siswa->user->photo)
                                   ? Storage::url($siswa->user->photo) : null;
                    $inisial     = strtoupper(mb_substr($namaTampil, 0, 1));
                @endphp

                <div class="as-siswa-row" id="row-{{ $siswa->id }}">

                    {{-- Nomor --}}
                    <span class="as-no">{{ $i + 1 }}</span>

                    {{-- Avatar --}}
                    <div class="as-av">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="{{ $namaTampil }}">
                        @else
                            {{ $inisial }}
                        @endif
                    </div>

                    {{-- Nama & NIS — flex:1 agar mengambil sisa ruang --}}
                    <div class="as-info">
                        <div class="as-nama">{{ $namaTampil }}</div>
                        @if($nis)
                            <div class="as-nis"><i class="bi bi-person-badge" style="font-size:0.538rem;"></i> {{ $nis }}</div>
                        @endif
                    </div>

                    {{-- Kanan: tombol status + keterangan ditumpuk vertikal --}}
                    <div class="as-right">
                        {{-- Status buttons --}}
                        <div class="as-status-group" data-siswa-id="{{ $siswa->id }}">
                            @foreach(['hadir'=>'Hadir','sakit'=>'Sakit','izin'=>'Izin','alpha'=>'Alpha'] as $s => $label)
                                <button type="button"
                                        class="as-st-btn {{ $statusSaved === $s ? 'active' : '' }}"
                                        data-s="{{ $s }}"
                                        data-siswa="{{ $siswa->id }}"
                                        onclick="pilihStatus(this, {{ $siswa->id }}, '{{ $s }}')">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Keterangan --}}
                        <input type="text"
                               class="as-ket"
                               id="ket-{{ $siswa->id }}"
                               placeholder="Keterangan (opsional)…"
                               value="{{ $ketSaved }}"
                               maxlength="100">
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Save bar --}}
        <div class="as-save-bar">
            <div class="as-save-info">
                <strong id="filledCount">{{ $absensiHari->count() }}</strong>
                / <strong>{{ $siswaList->count() }}</strong> siswa diisi
                @if($sudahDisimpan && $absensiHari->first()?->updated_at)
                    &nbsp;·&nbsp;
                    <span style="color:#16a34a;">
                        <i class="bi bi-clock-history"></i>
                        {{ $absensiHari->first()->updated_at->format('H:i') }}
                    </span>
                @endif
            </div>
            <button type="button" class="as-btn as-btn-primary" id="saveBtn" onclick="simpanAbsensi()">
                <i class="bi bi-cloud-upload-fill"></i>
                {{ $sudahDisimpan ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    @endif

</div>

{{-- Toast --}}
<div class="as-toast" id="asToast">
    <i id="asTi"></i>
    <span id="asTm"></span>
</div>

@endsection

@push('scripts')
<script>
const AS_STATUS = {};
const KELAS_ID  = {{ $kelasId ? (int)$kelasId : 'null' }};
const TANGGAL   = "{{ $tanggal }}";
const TOTAL     = {{ $siswaList->count() }};
const STORE_URL = "{{ route('guru.absensi-siswa.store') }}";
const CSRF      = () => document.querySelector('meta[name="csrf-token"]').content;

// Pre-fill status dari data tersimpan
@foreach($absensiHari as $sid => $abs)
    AS_STATUS[{{ (int)$sid }}] = "{{ $abs->status }}";
@endforeach

/* ── Pilih status satu siswa ── */
function pilihStatus(btn, siswaId, status) {
    const group = btn.closest('.as-status-group');
    group.querySelectorAll('.as-st-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    AS_STATUS[siswaId] = status;
    updateProgress();

    // Flash row color
    const row = document.getElementById(`row-${siswaId}`);
    if (row) {
        const colors = { hadir:'#f0fdf4', sakit:'#fefce8', izin:'#f0f9ff', alpha:'#fef2f2' };
        row.style.background = colors[status] || '';
        setTimeout(() => { row.style.background = ''; }, 500);
    }
}

/* ── Tandai semua siswa ── */
function tandaiSemua(status) {
    document.querySelectorAll('.as-status-group').forEach(group => {
        const sid = parseInt(group.dataset.siswaId);
        group.querySelectorAll('.as-st-btn').forEach(b => {
            b.classList.toggle('active', b.dataset.s === status);
        });
        AS_STATUS[sid] = status;
    });
    updateProgress();
    asToast(`Semua siswa: ${status}`, 'success');
}

/* ── Update progress bar ── */
function updateProgress() {
    const filled = Object.keys(AS_STATUS).length;
    const pct    = TOTAL > 0 ? Math.round(filled / TOTAL * 100) : 0;
    const fill   = document.getElementById('progressFill');
    const lbl    = document.getElementById('progressLabel');
    const cnt    = document.getElementById('filledCount');
    if (fill) fill.style.width = pct + '%';
    if (lbl)  lbl.textContent  = `${filled} / ${TOTAL} siswa diisi (${pct}%)`;
    if (cnt)  cnt.textContent  = filled;
}

/* ── Simpan absensi ── */
async function simpanAbsensi() {
    const errBox = document.getElementById('errorContainer');
    if (errBox) errBox.style.display = 'none';

    const filled = Object.keys(AS_STATUS).length;
    if (filled === 0) { asToast('Belum ada status dipilih', 'error'); return; }
    if (filled < TOTAL) {
        if (!confirm(`Baru ${filled} dari ${TOTAL} siswa yang diisi. Lanjutkan?`)) return;
    }

    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan…';

    const absensiArr = [];
    document.querySelectorAll('.as-status-group').forEach(group => {
        const sid    = parseInt(group.dataset.siswaId);
        const status = AS_STATUS[sid];
        if (!status) return;
        const ket = document.getElementById(`ket-${sid}`)?.value ?? '';
        absensiArr.push({ siswa_id: sid, status, keterangan: ket });
    });

    const payload = { kelas_id: KELAS_ID, tanggal: TANGGAL, absensi: absensiArr };

    try {
        const res  = await fetch(STORE_URL, {
            method  : 'POST',
            headers : { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF(), 'Accept': 'application/json' },
            body    : JSON.stringify(payload),
        });
        let data;
        try { data = await res.json(); }
        catch { showError('Server error'); resetBtn(); return; }

        if (res.ok && data.success) {
            asToast(`✓ ${data.total} absensi disimpan`, 'success');
            btn.innerHTML = '<i class="bi bi-cloud-check-fill"></i> Tersimpan';
            btn.classList.add('as-btn-save-success');
            setTimeout(() => {
                btn.disabled = false;
                btn.classList.remove('as-btn-save-success');
                btn.innerHTML = '<i class="bi bi-cloud-upload-fill"></i> Perbarui';
            }, 3000);
        } else {
            const msg = data.errors
                ? Object.values(data.errors).flat().join('<br>')
                : (data.message || 'Gagal menyimpan');
            showError(msg);
            asToast('Gagal menyimpan', 'error');
            resetBtn();
        }
    } catch (e) {
        showError('Gagal terhubung: ' + e.message);
        asToast('Gagal terhubung ke server', 'error');
        resetBtn();
    }
}

function showError(msg) {
    const box = document.getElementById('errorContainer');
    const txt = document.getElementById('errorMessage');
    if (box && txt) { txt.innerHTML = msg; box.style.display = 'flex'; box.scrollIntoView({ behavior:'smooth', block:'center' }); }
}

function resetBtn() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = false;
    btn.classList.remove('as-btn-save-success');
    btn.innerHTML = '<i class="bi bi-cloud-upload-fill"></i> Simpan';
}

let _tt;
function asToast(msg, type = 'success') {
    clearTimeout(_tt);
    const el = document.getElementById('asToast');
    document.getElementById('asTi').className =
        type === 'success' ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-circle-fill';
    document.getElementById('asTm').textContent = msg;
    el.className = `as-toast ${type} show`;
    _tt = setTimeout(() => { el.className = `as-toast ${type}`; }, 3500);
}

document.addEventListener('DOMContentLoaded', updateProgress);
</script>
@endpush
@extends('layouts.app')
@section('title', 'Absensi Saya')

@push('styles')
<style>
/* ══════════════════════════════════════════════
   ABSENSI SAYA — CREATIVE UI LAYER
══════════════════════════════════════════════ */
@keyframes af-float {
    0%,100% { transform: translateY(0) rotate(0deg); }
    50%     { transform: translateY(-10px) rotate(3deg); }
}
@keyframes af-blob {
    0%,100% { border-radius: 42% 58% 65% 35% / 45% 45% 55% 55%; }
    50%     { border-radius: 60% 40% 30% 70% / 55% 65% 35% 45%; }
}
@keyframes af-pulse-ring {
    0%   { box-shadow: 0 0 0 0 rgba(99,102,241,.45); }
    70%  { box-shadow: 0 0 0 12px rgba(99,102,241,0); }
    100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
}
@keyframes af-pulse-dot {
    0%,100% { opacity: 1; transform: scale(1); }
    50%     { opacity: .5; transform: scale(1.25); }
}
@keyframes af-radar {
    0%   { transform: scale(.3); opacity: .9; }
    100% { transform: scale(1.6); opacity: 0; }
}
@keyframes af-check-pop {
    0%   { transform: scale(0) rotate(-20deg); opacity: 0; }
    60%  { transform: scale(1.15) rotate(4deg); opacity: 1; }
    100% { transform: scale(1) rotate(0deg); opacity: 1; }
}
@keyframes af-confetti-fall {
    0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
    100% { transform: translateY(220px) rotate(540deg); opacity: 0; }
}
@keyframes af-shimmer {
    0%   { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
@keyframes af-slide-in {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.af-hero {
    position: relative;
    overflow: hidden;
    border-radius: 1.25rem;
    padding: 1.25rem 1.25rem 1.4rem;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 45%, #8b5cf6 100%);
    color: #fff;
    box-shadow: 0 10px 30px -10px rgba(79,70,229,.55);
    animation: af-slide-in .35s ease;
}
.af-hero-blob {
    position: absolute;
    width: 140px; height: 140px;
    background: rgba(255,255,255,.14);
    right: -30px; top: -40px;
    animation: af-blob 7s ease-in-out infinite, af-float 6s ease-in-out infinite;
}
.af-hero-blob2 {
    position: absolute;
    width: 90px; height: 90px;
    background: rgba(255,255,255,.10);
    left: -20px; bottom: -30px;
    animation: af-blob 9s ease-in-out infinite reverse, af-float 8s ease-in-out infinite;
}
.af-hero-top {
    display: flex; align-items: flex-start; justify-content: space-between;
    position: relative; z-index: 1;
}
.af-hero-greet { font-size: .68rem; color: rgba(255,255,255,.8); font-weight: 600; letter-spacing: .02em; }
.af-hero-name  { font-size: 1rem; font-weight: 800; margin-top: .1rem; }
.af-hero-date  { font-size: .68rem; color: rgba(255,255,255,.85); margin-top: .3rem; }
.af-hero-clock {
    font-size: .68rem; font-weight: 700; color: #fff;
    background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.25);
    border-radius: 999px; padding: .3rem .7rem; white-space: nowrap;
    backdrop-filter: blur(4px);
}

.af-stats-row { display: flex; gap: .55rem; margin-top: .95rem; position: relative; z-index: 1; }
.af-stat-chip {
    flex: 1; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
    border-radius: .75rem; padding: .55rem .6rem; backdrop-filter: blur(3px);
}
.af-stat-chip .v { font-size: .95rem; font-weight: 800; line-height: 1; }
.af-stat-chip .l { font-size: .55rem; color: rgba(255,255,255,.78); margin-top: .2rem; font-weight: 600; }

.af-card {
    background: #fff;
    border-radius: 1.1rem;
    border: 1px solid #eef0f6;
    box-shadow: 0 2px 10px -4px rgba(15,23,42,.06);
    animation: af-slide-in .35s ease;
}
.dark .af-card { background: #1e293b; border-color: #334155; }

.af-status-card {
    position: relative;
    border-radius: 1.1rem;
    padding: 1.1rem;
    text-align: center;
    overflow: hidden;
    margin-top: .9rem;
}
.af-status-idle { background: linear-gradient(145deg,#f8fafc,#eef1f8); border: 1.5px dashed #cbd5e1; }
.af-status-progress { background: linear-gradient(145deg,#ecfdf5,#d1fae5); border: 1.5px solid #86efac; }
.af-status-done { background: linear-gradient(145deg,#eef2ff,#e0e7ff); border: 1.5px solid #c7d2fe; }

.af-status-icon {
    width: 3.1rem; height: 3.1rem; border-radius: 999px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .5rem; font-size: 1.35rem;
}
.af-status-idle .af-status-icon   { background: #e2e8f0; color: #64748b; animation: af-pulse-ring 2.4s infinite; }
.af-status-progress .af-status-icon { background: #16a34a; color: #fff; }
.af-status-done .af-status-icon   { background: #4f46e5; color: #fff; }

.af-cta {
    display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
    width: 100%; padding: .8rem 1rem; border-radius: .9rem;
    font-size: .78rem; font-weight: 800; letter-spacing: .01em;
    border: none; cursor: pointer; transition: transform .12s ease, box-shadow .12s ease;
    color: #fff;
}
.af-cta:active { transform: scale(.97); }
.af-cta-indigo { background: linear-gradient(135deg,#4f46e5,#6366f1); box-shadow: 0 8px 18px -6px rgba(79,70,229,.55); }
.af-cta-amber  { background: linear-gradient(135deg,#f59e0b,#f97316); box-shadow: 0 8px 18px -6px rgba(245,158,11,.55); }
.af-cta-slate  { background: linear-gradient(135deg,#334155,#1e293b); box-shadow: 0 8px 18px -6px rgba(30,41,59,.45); }
.af-cta-ghost  { background: #f1f5f9; color: #475569; box-shadow: none; }
.dark .af-cta-ghost { background: #334155; color: #cbd5e1; }

.af-mini-row { display: flex; gap: .4rem; justify-content: center; margin-top: .6rem; flex-wrap: wrap; }
.af-mini-pill {
    font-size: .62rem; font-weight: 700; padding: .22rem .55rem; border-radius: 999px;
    background: #fff; border: 1px solid #e2e8f0; color: #475569;
}
.dark .af-mini-pill { background: #0f172a; border-color: #334155; color: #cbd5e1; }

/* ── Jadwal timeline ── */
.af-timeline-item {
    display: flex; align-items: flex-start; gap: .65rem;
    padding: .6rem .1rem; position: relative;
}
.af-timeline-item::before {
    content: ''; position: absolute; left: 1.05rem; top: 2.1rem; bottom: -.3rem;
    width: 2px; background: #e2e8f0;
}
.af-timeline-item:last-child::before { display: none; }
.dark .af-timeline-item::before { background: #334155; }
.af-timeline-dot {
    width: 2.15rem; height: 2.15rem; border-radius: .65rem; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 800; color: #fff; z-index: 1;
}

/* ── Pilih mode cards ── */
.af-mode-card {
    display: block; border-radius: 1rem; padding: .9rem 1rem; cursor: pointer;
    border: 1.5px solid #e2e8f0; transition: all .15s ease; background: #fff;
}
.dark .af-mode-card { background: #0f172a; border-color: #334155; }
.af-mode-card:has(:checked) {
    border-color: #6366f1; background: linear-gradient(135deg,#eef2ff,#f5f3ff);
    box-shadow: 0 4px 14px -4px rgba(99,102,241,.35);
}

/* ── Radar lokasi ── */
.af-radar-wrap { width: 6rem; height: 6rem; margin: 0 auto 1rem; position: relative; display: flex; align-items: center; justify-content: center; }
.af-radar-ring {
    position: absolute; inset: 0; border-radius: 999px; border: 2px solid #818cf8;
    animation: af-radar 1.8s ease-out infinite;
}
.af-radar-ring.d2 { animation-delay: .6s; }
.af-radar-ring.d3 { animation-delay: 1.2s; }
.af-radar-core {
    width: 2.6rem; height: 2.6rem; border-radius: 999px;
    background: linear-gradient(135deg,#4f46e5,#818cf8);
    display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem;
    box-shadow: 0 6px 16px -4px rgba(79,70,229,.5);
}

/* ── Kamera / face frame ── */
.af-cam-frame { position: relative; }
.af-cam-corner { position: absolute; width: 22px; height: 22px; border-color: #4ade80; opacity: .9; }
.af-cc-tl { top: 8px; left: 8px; border-top: 3px solid; border-left: 3px solid; border-radius: 6px 0 0 0; }
.af-cc-tr { top: 8px; right: 8px; border-top: 3px solid; border-right: 3px solid; border-radius: 0 6px 0 0; }
.af-cc-bl { bottom: 8px; left: 8px; border-bottom: 3px solid; border-left: 3px solid; border-radius: 0 0 0 6px; }
.af-cc-br { bottom: 8px; right: 8px; border-bottom: 3px solid; border-right: 3px solid; border-radius: 0 0 6px 0; }

/* ── Polaroid preview ── */
.af-polaroid {
    background: #fff; padding: .6rem .6rem 1.4rem; border-radius: .6rem;
    box-shadow: 0 10px 26px -10px rgba(0,0,0,.35); transform: rotate(-1.2deg);
    display: inline-block;
}

/* ── Tiket ringkasan ── */
.af-ticket {
    position: relative; background: linear-gradient(145deg,#f8fafc,#eef2ff);
    border: 1.5px dashed #c7d2fe; border-radius: 1rem; padding: 1rem 1.1rem;
}
.dark .af-ticket { background: #0f172a; border-color: #4338ca; }
.af-ticket-row { display: flex; justify-content: space-between; font-size: .72rem; padding: .28rem 0; color: #475569; }
.dark .af-ticket-row { color: #cbd5e1; }
.af-ticket-row strong { color: #1e293b; }
.dark .af-ticket-row strong { color: #f1f5f9; }

/* ── Progress dots wizard ── */
#wizardProgress {
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    margin-bottom: .9rem;
}
.af-prog-dot {
    width: 2rem; height: .3rem; border-radius: 999px; background: #e2e8f0; transition: all .25s ease;
}
.dark .af-prog-dot { background: #334155; }
.af-prog-dot.active { background: linear-gradient(90deg,#4f46e5,#818cf8); width: 2.5rem; }
.af-prog-dot.done { background: #a5b4fc; }

/* ── Sukses / confetti ── */
.af-success-wrap { position: relative; overflow: hidden; }
.af-confetti-piece {
    position: absolute; top: 0; width: 8px; height: 8px; border-radius: 2px;
    animation: af-confetti-fall 1.6s ease-in forwards;
}
.af-check-anim { animation: af-check-pop .5s cubic-bezier(.34,1.56,.64,1); }

.af-shimmer-btn {
    background: linear-gradient(90deg,#4f46e5 25%,#818cf8 50%,#4f46e5 75%);
    background-size: 200% 100%;
    animation: af-shimmer 1.4s linear infinite;
}

.af-empty-jadwal {
    text-align: center; padding: 1.4rem .5rem; color: #94a3b8; font-size: .7rem;
}
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $jamNow  = now()->hour;
    $sapaan  = $jamNow < 11 ? 'Selamat Pagi' : ($jamNow < 15 ? 'Selamat Siang' : ($jamNow < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $emoji   = $jamNow < 11 ? '🌤️' : ($jamNow < 15 ? '☀️' : ($jamNow < 18 ? '🌇' : '🌙'));

    $totalRiwayat   = $riwayat->count();
    $totalHadir     = $riwayat->where('status', '!=', 'L')->count();
    $totalTerlambat = $riwayat->where('status', 'L')->count();
@endphp

<div id="absensiApp" class="space-y-4"
     data-sudah-absen-masuk="{{ $absensiHariIni ? 'true' : 'false' }}"
     data-sudah-absen-pulang="{{ ($absensiHariIni && $absensiHariIni->foto_pulang) ? 'true' : 'false' }}"
     data-tipe-absen="{{ $absensiHariIni->tipe_absensi ?? '' }}"
     data-sekolah-lat="{{ $lokasiSekolah['latitude'] }}"
     data-sekolah-lng="{{ $lokasiSekolah['longitude'] }}"
     data-sekolah-radius="{{ $lokasiSekolah['radius'] }}"
     data-url-masuk="{{ route('guru.absensi-foto.masuk') }}"
     data-url-pulang="{{ route('guru.absensi-foto.pulang') }}">

    {{-- ══════════════ HALAMAN 0: ABSENSI SAYA (default) ══════════════ --}}
    <div id="stepHome" class="wizard-step">

        {{-- ── Hero sapaan ── --}}
        <div class="af-hero">
            <div class="af-hero-blob"></div>
            <div class="af-hero-blob2"></div>
            <div class="af-hero-top">
                <div>
                    <p class="af-hero-greet">{{ $sapaan }} {{ $emoji }}</p>
                    <p class="af-hero-name">{{ Auth::user()->name }}</p>
                    <p class="af-hero-date">{{ $hariIniNama }}, {{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <span class="af-hero-clock" id="jamSekarang"></span>
            </div>

            <div class="af-stats-row">
                <div class="af-stat-chip">
                    <div class="v">{{ $totalHadir }}</div>
                    <div class="l">Hadir Terakhir</div>
                </div>
                <div class="af-stat-chip">
                    <div class="v">{{ $totalTerlambat }}</div>
                    <div class="l">Terlambat</div>
                </div>
                <div class="af-stat-chip">
                    <div class="v">{{ $jadwalHariIni->count() }}</div>
                    <div class="l">Jadwal Hari Ini</div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="flex items-center gap-2 px-3.5 py-2.5 mt-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
            <i class="bi bi-check-circle-fill text-emerald-600"></i>
            <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        {{-- ── Status card ── --}}
        <div class="af-card p-4">
            @if(!$absensiHariIni)
                <div class="af-status-card af-status-idle" style="margin-top:0;">
                    <div class="af-status-icon"><i class="bi bi-fingerprint"></i></div>
                    <p class="text-xs font-bold text-slate-500 mb-.5">BELUM ABSEN HARI INI</p>
                    <p class="text-[10.5px] text-slate-400 mb-3">Yuk mulai absensi, cukup 3 langkah mudah ✨</p>
                    <button type="button" id="btnMulaiAbsenMasuk" class="af-cta af-cta-indigo">
                        <i class="bi bi-geo-alt-fill"></i> ABSEN MASUK
                    </button>
                    <div class="af-mini-row">
                        <span class="af-mini-pill"><i class="bi bi-camera-fill"></i> Foto Wajah</span>
                        <span class="af-mini-pill"><i class="bi bi-geo-alt"></i> Verifikasi Lokasi</span>
                        <span class="af-mini-pill"><i class="bi bi-lightning-charge-fill"></i> 30 Detik</span>
                    </div>
                </div>
            @elseif($absensiHariIni->tipe_absensi === 'mengajar' && !$absensiHariIni->foto_pulang)
                <div class="af-status-card af-status-progress" style="margin-top:0;">
                    <div class="af-status-icon"><i class="bi bi-check2-circle"></i></div>
                    <p class="text-xs font-bold text-emerald-700 mb-2">SUDAH ABSEN MASUK</p>
                    <div class="af-mini-row" style="margin-top:0;margin-bottom:.7rem;">
                        <span class="af-mini-pill">⏰ {{ substr($absensiHariIni->jam_masuk,0,5) }} WIB</span>
                        <span class="af-mini-pill">{{ $absensiHariIni->status === 'L' ? '🐢 Terlambat' : '✅ Hadir' }}</span>
                        <span class="af-mini-pill">📍 {{ $absensiHariIni->jarak_masuk }} m</span>
                    </div>
                    <button type="button" id="btnMulaiAbsenPulang" class="af-cta af-cta-amber">
                        <i class="bi bi-box-arrow-right"></i> ABSEN PULANG
                    </button>
                </div>
            @else
                <div class="af-status-card af-status-done" style="margin-top:0;">
                    <div class="af-status-icon"><i class="bi bi-stars"></i></div>
                    <p class="text-xs font-bold text-indigo-700 mb-2">ABSENSI HARI INI SELESAI 🎉</p>
                    <div class="af-mini-row" style="margin-top:0;">
                        <span class="af-mini-pill">Masuk {{ substr($absensiHariIni->jam_masuk,0,5) }}</span>
                        @if($absensiHariIni->jam_pulang)
                        <span class="af-mini-pill">Pulang {{ substr($absensiHariIni->jam_pulang,0,5) }}</span>
                        @endif
                        <span class="af-mini-pill">{{ $absensiHariIni->status === 'L' ? 'Terlambat' : 'Hadir' }}</span>
                    </div>
                    <p class="text-[10px] text-indigo-400 mt-2">Sampai jumpa besok! Tetap semangat mengajar 💪</p>
                </div>
            @endif

            {{-- ── Jadwal hari ini — timeline ── --}}
            @if($jadwalHariIni->count())
            <div class="mt-4">
                <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                    <i class="bi bi-calendar2-week-fill text-indigo-500"></i> Jadwal Mengajar Hari Ini
                </p>
                <div>
                    @foreach($jadwalHariIni as $jadwal)
                    @php $warna = $jadwal->studySubject->color ?? '#6366f1'; @endphp
                    <div class="af-timeline-item">
                        <div class="af-timeline-dot" style="background: {{ $warna }};">
                            {{ substr($jadwal->start_time,0,2) }}
                        </div>
                        <div class="flex-1 min-w-0 pt-0.5">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">
                                    {{ $jadwal->studySubject->name ?? '—' }}
                                </p>
                                @if($absensiHariIni && $absensiHariIni->timetable_id === $jadwal->id)
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 shrink-0">
                                        <i class="bi bi-check-lg"></i> Sudah Absen
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400">
                                <i class="bi bi-clock"></i> {{ substr($jadwal->start_time,0,5) }}–{{ substr($jadwal->end_time,0,5) }}
                                &nbsp;·&nbsp; <i class="bi bi-mortarboard"></i> {{ $jadwal->studyGroup->name ?? '—' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="af-empty-jadwal mt-3">
                <i class="bi bi-cup-hot" style="font-size:1.4rem;display:block;margin-bottom:.3rem;"></i>
                Tidak ada jadwal mengajar terjadwal hari ini.
            </div>
            @endif

            <button type="button" id="btnLihatRiwayat"
                    class="mt-3 w-full flex items-center justify-center gap-1.5 text-[11px] text-indigo-600 dark:text-indigo-400 font-semibold py-2 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition">
                <i class="bi bi-clock-history"></i> Riwayat Absensi <i class="bi bi-chevron-down"></i>
            </button>
        </div>

        {{-- ── RIWAYAT ── --}}
        <div id="blokRiwayat" class="hidden mt-3 af-card overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                    <i class="bi bi-journal-text text-indigo-500"></i> Riwayat Absensi
                </h3>
                <span class="text-[10px] text-slate-400">{{ $totalRiwayat }} data terakhir</span>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @forelse($riwayat as $r)
                    <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-700/20 transition">
                        <img src="{{ Storage::url($r->foto_masuk) }}"
                             class="w-11 h-11 rounded-xl object-cover border-2 {{ $r->status === 'L' ? 'border-amber-300' : 'border-emerald-300' }}">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                                {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-[10px] text-slate-400">
                                {{ $r->tipe_absensi === 'kantor' ? '🏢 Kantor' : '📚 Mengajar' }}
                                · Masuk {{ substr($r->jam_masuk,0,5) }}
                                @if($r->jam_pulang) · Pulang {{ substr($r->jam_pulang,0,5) }} @endif
                                @if($r->jarak_masuk !== null) · {{ $r->jarak_masuk }}m @endif
                            </p>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $r->status === 'L' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $r->status === 'L' ? 'Terlambat' : 'Hadir' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="bi bi-inbox" style="font-size:1.8rem;color:#cbd5e1;"></i>
                        <p class="text-slate-400 text-xs mt-2">Belum ada riwayat absensi.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 0.5: PILIH MODE (mengajar/kantor) ══════════════ --}}
    <div id="stepPilihMode" class="wizard-step hidden">
        <div class="af-card p-4">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-1 flex items-center gap-1.5">
                <i class="bi bi-ui-checks-grid text-indigo-500"></i> Pilih Jenis Kehadiran
            </h3>
            <p class="text-[10.5px] text-slate-400 mb-3">Tentukan Anda mengajar di kelas mana, atau sedang di kantor.</p>

            @if($jadwalHariIni->count())
            <div class="mb-3">
                <p class="text-[11px] font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                    <i class="bi bi-easel2-fill text-indigo-500"></i> Saya Akan Mengajar
                </p>
                <div class="space-y-1.5 max-h-44 overflow-y-auto pr-1">
                    @foreach($jadwalHariIni as $jadwal)
                    <label class="af-mode-card flex items-center gap-2.5 cursor-pointer">
                        <input type="radio" name="pilihJadwal" value="{{ $jadwal->id }}" class="radioJadwal w-3.5 h-3.5 text-indigo-600">
                        <span class="w-2 h-8 rounded-full shrink-0" style="background: {{ $jadwal->studySubject->color ?? '#6366f1' }}"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200 truncate">
                                {{ $jadwal->studySubject->name ?? '—' }}
                                <span class="text-slate-400 font-normal">· {{ $jadwal->studyGroup->name ?? '—' }}</span>
                            </p>
                            <p class="text-[9.5px] text-slate-400"><i class="bi bi-clock"></i> {{ substr($jadwal->start_time,0,5) }}–{{ substr($jadwal->end_time,0,5) }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <button type="button" id="btnLanjutMengajar" class="af-cta af-cta-indigo mb-2 disabled:opacity-40" disabled>
                <i class="bi bi-arrow-right-circle"></i> Lanjut sebagai Mengajar
            </button>

            <div class="flex items-center gap-2 my-2">
                <span class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></span>
                <span class="text-[10px] text-slate-400 font-semibold">ATAU</span>
                <span class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></span>
            </div>

            <button type="button" id="btnLanjutKantor" class="af-cta af-cta-slate">
                <i class="bi bi-building-fill"></i> Saya di Kantor (Tidak Mengajar)
            </button>

            <button type="button" class="btnBatalWizard af-cta af-cta-ghost mt-3">
                <i class="bi bi-x-lg"></i> Batal
            </button>
        </div>
    </div>

    {{-- ══════════════ WIZARD PROGRESS (khusus step lokasi → berhasil) ══════════════ --}}
    <div id="wizardProgress" class="hidden">
        <div class="af-prog-dot" data-p="1"></div>
        <div class="af-prog-dot" data-p="2"></div>
        <div class="af-prog-dot" data-p="3"></div>
        <div class="af-prog-dot" data-p="4"></div>
    </div>

    {{-- ══════════════ HALAMAN 1: CEK LOKASI ══════════════ --}}
    <div id="stepLokasi" class="wizard-step hidden">
        <div class="af-card p-6 text-center">
            <div id="lokasiLoading">
                <div class="af-radar-wrap">
                    <span class="af-radar-ring"></span>
                    <span class="af-radar-ring d2"></span>
                    <span class="af-radar-ring d3"></span>
                    <span class="af-radar-core"><i class="bi bi-geo-alt-fill"></i></span>
                </div>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Mencari lokasi Anda...</p>
                <p class="text-[10px] text-slate-400 mt-1">Pastikan GPS aktif untuk hasil terbaik</p>
            </div>

            <div id="lokasiValid" class="hidden">
                <div class="af-status-icon af-check-anim" style="background:#16a34a;color:#fff;margin-bottom:.6rem;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <p class="text-xs font-bold text-emerald-600 mb-3">LOKASI TERDETEKSI</p>
                <div class="af-ticket text-left mb-3">
                    <div class="af-ticket-row"><span>Jarak dari sekolah</span><strong id="txtJarakValid">-</strong></div>
                    <div class="af-ticket-row"><span>Batas maksimal</span><strong id="txtRadiusValid">-</strong></div>
                </div>
                <p class="text-[11px] text-emerald-600 font-semibold mb-3"><i class="bi bi-check2"></i> Anda berada di area sekolah</p>
                <button type="button" id="btnLanjutFoto" class="af-cta af-cta-indigo">
                    <i class="bi bi-camera-fill"></i> LANJUTKAN
                </button>
            </div>

            <div id="lokasiInvalid" class="hidden">
                <div class="af-status-icon" style="background:#e11d48;color:#fff;margin-bottom:.6rem;">
                    <i class="bi bi-x-lg"></i>
                </div>
                <p class="text-xs font-bold text-rose-600 mb-3">LOKASI TIDAK VALID</p>
                <div class="af-ticket text-left mb-3">
                    <div class="af-ticket-row"><span>Jarak dari sekolah</span><strong id="txtJarakInvalid">-</strong></div>
                    <div class="af-ticket-row"><span>Batas maksimal</span><strong id="txtRadiusInvalid">-</strong></div>
                </div>
                <p class="text-[11px] text-rose-600 mb-3">Anda berada di luar area sekolah.</p>
                <button type="button" class="btnUlangiLokasi af-cta af-cta-ghost mb-2">
                    <i class="bi bi-arrow-repeat"></i> COBA LAGI
                </button>
                <button type="button" class="btnBatalWizard af-cta af-cta-ghost">Kembali</button>
            </div>

            <div id="lokasiError" class="hidden">
                <div class="af-status-icon" style="background:#f59e0b;color:#fff;margin-bottom:.6rem;">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <p class="text-xs font-bold text-amber-600 mb-2">LOKASI TIDAK TERSEDIA</p>
                <p class="text-[11px] text-slate-500 mb-2">Sistem tidak dapat mendapatkan lokasi Anda. Silakan:</p>
                <ul class="text-left text-[11px] text-slate-500 mb-3 space-y-1 bg-slate-50 dark:bg-slate-900/30 rounded-lg p-3">
                    <li>📡 Aktifkan GPS / lokasi</li>
                    <li>🔓 Izinkan browser mengakses lokasi</li>
                    <li>🌐 Pastikan koneksi internet aktif</li>
                </ul>
                <button type="button" class="btnUlangiLokasi af-cta af-cta-indigo mb-2">
                    <i class="bi bi-arrow-repeat"></i> COBA LAGI
                </button>
                <button type="button" class="btnBatalWizard af-cta af-cta-ghost">Batal</button>
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 2: FOTO ABSENSI ══════════════ --}}
    <div id="stepFoto" class="wizard-step hidden">
        <div class="af-card p-4 text-center">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-1 flex items-center justify-center gap-1.5">
                <i class="bi bi-camera-fill text-indigo-500"></i> Foto Absensi
            </h3>
            <p class="text-[10.5px] text-slate-400 mb-3">Posisikan wajah dalam bingkai lalu tekan tombol ambil foto</p>

            <div id="cameraWrap" class="af-cam-frame relative w-full max-w-xs mx-auto rounded-xl overflow-hidden bg-black mb-3" style="aspect-ratio: 3/4;">
                <video id="videoCamera" autoplay playsinline muted class="w-full h-full object-cover"></video>
                <span class="af-cam-corner af-cc-tl"></span>
                <span class="af-cam-corner af-cc-tr"></span>
                <span class="af-cam-corner af-cc-bl"></span>
                <span class="af-cam-corner af-cc-br"></span>
                <div id="cameraError" class="hidden absolute inset-0 flex items-center justify-center bg-slate-800 text-white text-[11px] p-4 text-center"></div>
            </div>
            <canvas id="canvasFoto" class="hidden"></canvas>

            <button type="button" id="btnAmbilFoto" class="af-cta af-cta-indigo">
                <i class="bi bi-camera-fill"></i> AMBIL FOTO
            </button>

            <div class="mt-3 bg-slate-50 dark:bg-slate-900/30 rounded-xl p-3">
                <label class="block text-[10px] text-slate-400 mb-1.5">
                    <i class="bi bi-upload"></i> Kamera tidak berfungsi? Unggah foto manual:
                </label>
                <input type="file" id="inputFotoManual" accept="image/*" capture="user"
                       class="w-full text-[10px] rounded-lg border-slate-200 dark:border-slate-700">
            </div>

            <button type="button" class="btnBatalWizard af-cta af-cta-ghost mt-3">
                <i class="bi bi-x-lg"></i> Batal
            </button>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 3: KONFIRMASI FOTO ══════════════ --}}
    <div id="stepKonfirmasiFoto" class="wizard-step hidden">
        <div class="af-card p-5 text-center">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3 flex items-center justify-center gap-1.5">
                <i class="bi bi-image-fill text-indigo-500"></i> Konfirmasi Foto
            </h3>
            <div class="af-polaroid">
                <img id="previewFoto" src="" class="rounded" style="width: 220px; aspect-ratio: 3/4; object-fit: cover;">
            </div>
            <p class="text-[11px] text-slate-500 mt-3 mb-3">Apakah foto sudah jelas dan wajah terlihat?</p>
            <div class="flex gap-2">
                <button type="button" id="btnUlangiFoto" class="af-cta af-cta-ghost">
                    <i class="bi bi-arrow-repeat"></i> ULANGI
                </button>
                <button type="button" id="btnGunakanFoto" class="af-cta af-cta-indigo">
                    <i class="bi bi-check-lg"></i> GUNAKAN
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 4: KONFIRMASI ABSENSI ══════════════ --}}
    <div id="stepKonfirmasiAbsensi" class="wizard-step hidden">
        <div class="af-card p-4">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-1.5">
                <i class="bi bi-clipboard2-check-fill text-indigo-500"></i> Konfirmasi Absensi
            </h3>
            <div class="af-ticket mb-3">
                <div class="af-ticket-row"><span>Nama</span><strong>{{ Auth::user()->name }}</strong></div>
                <div class="af-ticket-row"><span>Tanggal</span><strong>{{ now()->translatedFormat('d F Y') }}</strong></div>
                <div class="af-ticket-row"><span>Jam</span><strong id="ringkasJam">-</strong> WIB</div>
                <div class="af-ticket-row"><span>Jenis</span><strong id="ringkasJenis">-</strong></div>
                <div class="af-ticket-row"><span>Lokasi</span><strong class="text-emerald-600">✓ Valid (<span id="ringkasJarak">-</span> m)</strong></div>
                <div class="af-ticket-row"><span>Foto</span><strong class="text-emerald-600">✓ Tersedia</strong></div>
            </div>
            <div id="konfirmasiError" class="hidden text-[11px] text-rose-600 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-lg p-2.5 mb-3"></div>
            <div class="flex gap-2">
                <button type="button" class="btnBatalWizard af-cta af-cta-ghost">
                    <i class="bi bi-x-lg"></i> BATAL
                </button>
                <button type="button" id="btnKirimAbsensi" class="af-cta af-cta-indigo">
                    <i class="bi bi-send-fill"></i> <span id="btnKirimText">KIRIM ABSENSI</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════ HALAMAN 5: BERHASIL ══════════════ --}}
    <div id="stepBerhasil" class="wizard-step hidden">
        <div class="af-card af-success-wrap p-6 text-center" id="confettiHost">
            <div class="af-status-icon af-check-anim" style="background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;width:4rem;height:4rem;font-size:1.8rem;margin-bottom:.7rem;">
                <i class="bi bi-check-lg"></i>
            </div>
            <p class="text-sm font-bold text-emerald-600 mb-1">ABSENSI BERHASIL 🎉</p>
            <p class="text-[11px] text-slate-500 mb-4" id="txtBerhasilPesan">Absensi Anda telah tersimpan.</p>
            <button type="button" id="btnSelesai" class="af-cta af-cta-indigo">
                <i class="bi bi-house-fill"></i> SELESAI
            </button>
        </div>
    </div>

</div>

<script>
(function () {
    const app = document.getElementById('absensiApp');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const steps = {
        home: document.getElementById('stepHome'),
        pilihMode: document.getElementById('stepPilihMode'),
        lokasi: document.getElementById('stepLokasi'),
        foto: document.getElementById('stepFoto'),
        konfirmasiFoto: document.getElementById('stepKonfirmasiFoto'),
        konfirmasiAbsensi: document.getElementById('stepKonfirmasiAbsensi'),
        berhasil: document.getElementById('stepBerhasil'),
    };

    const sekolahLat    = parseFloat(app.dataset.sekolahLat) || null;
    const sekolahLng     = parseFloat(app.dataset.sekolahLng) || null;
    const sekolahRadius  = parseInt(app.dataset.sekolahRadius) || 100;
    const urlMasuk       = app.dataset.urlMasuk;
    const urlPulang      = app.dataset.urlPulang;

    // State absensi yang sedang berjalan
    let state = {
        aksi: null,        // 'masuk' | 'pulang'
        tipe: null,        // 'mengajar' | 'kantor'
        timetableId: null,
        latitude: null,
        longitude: null,
        jarak: null,
        fotoBlob: null,
        stream: null,
    };

    function showStep(name) {
        Object.values(steps).forEach(el => el.classList.add('hidden'));
        steps[name].classList.remove('hidden');
        syncProgress(name);
    }

    function resetState() {
        state = { aksi: null, tipe: null, timetableId: null, latitude: null, longitude: null, jarak: null, fotoBlob: null, stream: null };
        stopCamera();
    }

    // ── Jam berjalan ──
    function updateJam() {
        const el = document.getElementById('jamSekarang');
        if (el) el.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
    }
    updateJam();
    setInterval(updateJam, 30000);

    // ── Riwayat toggle ──
    const btnRiwayat = document.getElementById('btnLihatRiwayat');
    if (btnRiwayat) {
        btnRiwayat.addEventListener('click', () => {
            document.getElementById('blokRiwayat').classList.toggle('hidden');
        });
    }

    // ── Mulai Absen Masuk ──
    const btnMulaiMasuk = document.getElementById('btnMulaiAbsenMasuk');
    if (btnMulaiMasuk) {
        btnMulaiMasuk.addEventListener('click', () => {
            resetState();
            state.aksi = 'masuk';
            const punyaJadwal = document.querySelectorAll('.radioJadwal').length > 0;
            if (punyaJadwal) {
                showStep('pilihMode');
            } else {
                state.tipe = 'kantor';
                mulaiCekLokasi();
            }
        });
    }

    // ── Mulai Absen Pulang ──
    const btnMulaiPulang = document.getElementById('btnMulaiAbsenPulang');
    if (btnMulaiPulang) {
        btnMulaiPulang.addEventListener('click', () => {
            resetState();
            state.aksi = 'pulang';
            mulaiCekLokasi();
        });
    }

    // ── Pilih mode: mengajar / kantor ──
    document.querySelectorAll('.radioJadwal').forEach(r => {
        r.addEventListener('change', () => {
            document.getElementById('btnLanjutMengajar').disabled = false;
        });
    });

    const btnLanjutMengajar = document.getElementById('btnLanjutMengajar');
    if (btnLanjutMengajar) {
        btnLanjutMengajar.addEventListener('click', () => {
            const checked = document.querySelector('.radioJadwal:checked');
            if (!checked) return;
            state.tipe = 'mengajar';
            state.timetableId = checked.value;
            mulaiCekLokasi();
        });
    }

    const btnLanjutKantor = document.getElementById('btnLanjutKantor');
    if (btnLanjutKantor) {
        btnLanjutKantor.addEventListener('click', () => {
            state.tipe = 'kantor';
            state.timetableId = null;
            mulaiCekLokasi();
        });
    }

    // ── Batal wizard (kembali ke home) ──
    document.querySelectorAll('.btnBatalWizard').forEach(btn => {
        btn.addEventListener('click', () => {
            resetState();
            showStep('home');
        });
    });

    // ── STEP: Cek Lokasi ──
    function mulaiCekLokasi() {
        showStep('lokasi');
        document.getElementById('lokasiLoading').classList.remove('hidden');
        document.getElementById('lokasiValid').classList.add('hidden');
        document.getElementById('lokasiInvalid').classList.add('hidden');
        document.getElementById('lokasiError').classList.add('hidden');

        if (!('geolocation' in navigator)) {
            document.getElementById('lokasiLoading').classList.add('hidden');
            document.getElementById('lokasiError').classList.remove('hidden');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                state.latitude = lat;
                state.longitude = lng;

                let jarak = 0;
                let valid = true;
                if (sekolahLat !== null && sekolahLng !== null) {
                    jarak = hitungJarak(sekolahLat, sekolahLng, lat, lng);
                    valid = jarak <= sekolahRadius;
                }
                state.jarak = Math.round(jarak);

                document.getElementById('lokasiLoading').classList.add('hidden');
                if (valid) {
                    document.getElementById('txtJarakValid').textContent = state.jarak + ' m';
                    document.getElementById('txtRadiusValid').textContent = sekolahRadius + ' m';
                    document.getElementById('lokasiValid').classList.remove('hidden');
                } else {
                    document.getElementById('txtJarakInvalid').textContent = state.jarak + ' m';
                    document.getElementById('txtRadiusInvalid').textContent = sekolahRadius + ' m';
                    document.getElementById('lokasiInvalid').classList.remove('hidden');
                }
            },
            () => {
                document.getElementById('lokasiLoading').classList.add('hidden');
                document.getElementById('lokasiError').classList.remove('hidden');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    document.querySelectorAll('.btnUlangiLokasi').forEach(btn => {
        btn.addEventListener('click', mulaiCekLokasi);
    });

    document.getElementById('btnLanjutFoto').addEventListener('click', () => {
        showStep('foto');
        startCamera();
    });

    // Rumus Haversine (versi JS, sama dengan di server untuk estimasi cepat)
    function hitungJarak(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2 +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // ── STEP: Kamera ──
    const video = document.getElementById('videoCamera');
    const canvas = document.getElementById('canvasFoto');

    function startCamera() {
        document.getElementById('cameraError').classList.add('hidden');
        video.classList.remove('hidden');
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
            .then(stream => {
                state.stream = stream;
                video.srcObject = stream;
            })
            .catch(() => {
                video.classList.add('hidden');
                const err = document.getElementById('cameraError');
                err.textContent = 'Kamera tidak dapat diakses. Gunakan opsi unggah foto manual di bawah.';
                err.classList.remove('hidden');
            });
    }

    function stopCamera() {
        if (state.stream) {
            state.stream.getTracks().forEach(t => t.stop());
            state.stream = null;
        }
    }

    document.getElementById('btnAmbilFoto').addEventListener('click', () => {
        if (!state.stream) return;
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        canvas.toBlob(blob => {
            state.fotoBlob = blob;
            document.getElementById('previewFoto').src = URL.createObjectURL(blob);
            stopCamera();
            showStep('konfirmasiFoto');
        }, 'image/jpeg', 0.9);
    });

    document.getElementById('inputFotoManual').addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        state.fotoBlob = file;
        document.getElementById('previewFoto').src = URL.createObjectURL(file);
        stopCamera();
        showStep('konfirmasiFoto');
    });

    // ── STEP: Konfirmasi Foto ──
    document.getElementById('btnUlangiFoto').addEventListener('click', () => {
        state.fotoBlob = null;
        showStep('foto');
        startCamera();
    });

    document.getElementById('btnGunakanFoto').addEventListener('click', () => {
        document.getElementById('ringkasJam').textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        document.getElementById('ringkasJenis').textContent = state.aksi === 'pulang'
            ? 'Absen Pulang'
            : (state.tipe === 'mengajar' ? 'Absen Masuk — Mengajar' : 'Absen Masuk — Kantor');
        document.getElementById('ringkasJarak').textContent = state.jarak ?? 0;
        document.getElementById('konfirmasiError').classList.add('hidden');
        showStep('konfirmasiAbsensi');
    });

    // ── STEP: Kirim Absensi ──
    document.getElementById('btnKirimAbsensi').addEventListener('click', kirimAbsensi);

    function kirimAbsensi() {
        const btn = document.getElementById('btnKirimAbsensi');
        const errBox = document.getElementById('konfirmasiError');
        errBox.classList.add('hidden');
        btn.disabled = true;
        document.getElementById('btnKirimText').textContent = 'MENGIRIM...';

        const formData = new FormData();
        formData.append('foto', state.fotoBlob, 'absensi.jpg');
        formData.append('latitude', state.latitude);
        formData.append('longitude', state.longitude);

        let url = urlMasuk;
        if (state.aksi === 'masuk') {
            formData.append('tipe', state.tipe);
            if (state.tipe === 'mengajar') {
                formData.append('timetable_id', state.timetableId);
            }
        } else {
            url = urlPulang;
        }

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData,
        })
        .then(async (res) => {
            const data = await res.json();
            btn.disabled = false;
            document.getElementById('btnKirimText').textContent = 'KIRIM ABSENSI';

            if (!res.ok || !data.ok) {
                errBox.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                errBox.classList.remove('hidden');
                return;
            }

            document.getElementById('txtBerhasilPesan').textContent = data.message;
            showStep('berhasil');
            fireConfetti();
        })
        .catch(() => {
            btn.disabled = false;
            document.getElementById('btnKirimText').textContent = 'KIRIM ABSENSI';
            errBox.textContent = 'Gagal terhubung ke server. Periksa koneksi internet Anda.';
            errBox.classList.remove('hidden');
        });
    }

    // ── Selesai → reload halaman agar status ter-update ──
    document.getElementById('btnSelesai').addEventListener('click', () => {
        window.location.reload();
    });

    /* ══════════════════════════════════════════
       ── DEKORASI TAMBAHAN (tidak mengubah alur) ──
       Progress dots wizard + efek confetti saat sukses.
    ══════════════════════════════════════════ */
    const progressMap = { lokasi: 1, foto: 2, konfirmasiFoto: 3, konfirmasiAbsensi: 3, berhasil: 4 };
    const progressEl  = document.getElementById('wizardProgress');
    const progressDots = progressEl ? Array.from(progressEl.querySelectorAll('.af-prog-dot')) : [];

    function syncProgress(stepName) {
        if (!progressEl) return;
        if (!(stepName in progressMap)) {
            progressEl.classList.add('hidden');
            return;
        }
        progressEl.classList.remove('hidden');
        const active = progressMap[stepName];
        progressDots.forEach(dot => {
            const p = parseInt(dot.dataset.p, 10);
            dot.classList.remove('active', 'done');
            if (p < active) dot.classList.add('done');
            if (p === active) dot.classList.add('active');
        });
    }

    function fireConfetti() {
        const host = document.getElementById('confettiHost');
        if (!host) return;
        const colors = ['#4f46e5', '#818cf8', '#f59e0b', '#22c55e', '#ec4899'];
        for (let i = 0; i < 24; i++) {
            const piece = document.createElement('span');
            piece.className = 'af-confetti-piece';
            piece.style.left = Math.random() * 100 + '%';
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];
            piece.style.animationDelay = (Math.random() * .4) + 's';
            piece.style.borderRadius = Math.random() > .5 ? '999px' : '2px';
            host.appendChild(piece);
            setTimeout(() => piece.remove(), 2200);
        }
    }

})();
</script>
@endsection
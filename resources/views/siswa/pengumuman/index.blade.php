{{--
╔══════════════════════════════════════════════════════════════════════════════╗
║  resources/views/siswa/pengumuman/index.blade.php                           ║
║  Tampilan Pengumuman — Siswa                                                ║
║                                                                             ║
║  Desain: List vertikal rapi, full-width, tanpa filter overhead.            ║
║  Modal detail self-contained.                                               ║
╚══════════════════════════════════════════════════════════════════════════════╝
--}}

@extends('layouts.app')
@section('title', 'Pengumuman')

@section('content')

@php
    /*
     * Query sederhana: tampilkan semua pengumuman aktif untuk siswa/semua.
     * Tidak ada filter tanggal agar semua pengumuman yang diupload admin
     * langsung tertampil tanpa terblokir kondisi tanggal.
     */
    if (!isset($pengumuman)) {
        $pengumuman = \App\Models\Pengumuman::with('creator')
            ->where('is_active', 1)
            ->whereIn('target_audience', ['siswa', 'semua'])
            ->latest()
            ->paginate(15);
    }
@endphp

{{-- ══════════ MODAL DETAIL ══════════ --}}
<div id="pgModal"
     onclick="if(event.target===this)pgTutup()"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.55);backdrop-filter:blur(6px)">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700">
        <button onclick="pgTutup()"
                class="absolute top-4 right-4 z-10 w-9 h-9 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-500 rounded-2xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div id="pgModalKonten" class="p-6 sm:p-8"></div>
    </div>
</div>

{{-- ══════════ HALAMAN UTAMA ══════════ --}}
<div class="w-full space-y-0">

    {{-- ── HEADER HERO ── --}}
    <div class="relative overflow-hidden rounded-3xl mb-6"
         style="background: linear-gradient(135deg, #0284c7 0%, #2563eb 50%, #4338ca 100%);">

        {{-- Pola grid latar --}}
        <div class="absolute inset-0 opacity-[0.07]" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-siswa" width="32" height="32" patternUnits="userSpaceOnUse">
                        <path d="M 32 0 L 0 0 0 32" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-siswa)"/>
            </svg>
        </div>

        {{-- Lingkaran dekorasi --}}
        <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full"
             style="background:rgba(255,255,255,0.08)"></div>
        <div class="absolute -right-4 -bottom-8 w-32 h-32 rounded-full"
             style="background:rgba(255,255,255,0.06)"></div>

        <div class="relative px-7 py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-2xl"
                         style="background:rgba(255,255,255,0.2)">
                        📢
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-white tracking-tight">Pengumuman Sekolah</h1>
                        <p class="text-sky-200 text-sm mt-0.5">Informasi penting untuk seluruh siswa</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="rounded-2xl px-4 py-2.5 text-center border"
                     style="background:rgba(255,255,255,0.15);border-color:rgba(255,255,255,0.2)">
                    <p class="text-2xl font-black text-white leading-none">{{ $pengumuman->total() }}</p>
                    <p class="text-sky-200 text-xs font-medium mt-0.5">Pengumuman</p>
                </div>
                <div class="rounded-2xl px-4 py-2.5 text-center border"
                     style="background:rgba(255,255,255,0.15);border-color:rgba(255,255,255,0.2)">
                    <p class="text-sm font-bold text-white leading-snug">{{ now()->isoFormat('D MMM') }}</p>
                    <p class="text-sky-200 text-xs font-medium mt-0.5">{{ now()->isoFormat('Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── KOSONG ── --}}
    @if($pengumuman->isEmpty())
    <div class="w-full text-center py-24 bg-white dark:bg-slate-800
                rounded-3xl border border-slate-200 dark:border-slate-700">
        <div class="text-7xl mb-5">📭</div>
        <h3 class="text-xl font-bold text-slate-700 dark:text-slate-200 mb-2">Belum Ada Pengumuman</h3>
        <p class="text-slate-400 text-sm">Silakan cek kembali nanti.</p>
    </div>

    {{-- ── DAFTAR PENGUMUMAN ── --}}
    @else
    <div class="w-full bg-white dark:bg-slate-800
                rounded-3xl border border-slate-200 dark:border-slate-700
                shadow-sm overflow-hidden">

        {{-- Sub-header dalam card --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/70
                    flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                    {{ $pengumuman->firstItem() }}–{{ $pengumuman->lastItem() }}
                    <span class="font-normal text-slate-400">dari</span>
                    {{ $pengumuman->total() }} pengumuman
                </p>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 hidden sm:block">
                Klik untuk melihat detail lengkap
            </p>
        </div>

        {{-- ── LIST ITEM ── --}}
        <ul class="divide-y divide-slate-100 dark:divide-slate-700/60">
            @foreach($pengumuman as $item)
            @php
                $pgFileUrl = $item->file_path ? asset('storage/' . $item->file_path) : '';
                $pgData = [
                    'judul'         => (string)($item->judul ?? ''),
                    'isi'           => (string)($item->isi ?? ''),
                    'tipe'          => (string)($item->tipe_konten ?? 'teks'),
                    'tipeIcon'      => (string)($item->tipeIcon()),
                    'audience'      => (string)($item->audienceLabel()),
                    'audienceColor' => (string)($item->audienceBadgeColor()),
                    'fileUrl'       => $pgFileUrl,
                    'fileName'      => (string)($item->file_name ?? ''),
                    'fileExt'       => (string)($item->fileExtension() ?? ''),
                    'linkUrl'       => (string)($item->link_url ?? ''),
                    'linkLabel'     => (string)($item->link_label ?? 'Kunjungi Link'),
                    'tanggal'       => $item->created_at->isoFormat('D MMMM Y, HH:mm'),
                    'diffHumans'    => $item->created_at->diffForHumans(),
                    'creator'       => (string)(optional($item->creator)->name ?? 'Admin'),
                    'tglSelesai'    => $item->tanggal_selesai
                                        ? $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm')
                                        : '',
                ];
                $pgJson = json_encode($pgData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);

                // Warna aksen per tipe konten — tema biru untuk siswa
                $aksenBg = match($item->tipe_konten) {
                    'gambar'  => 'bg-rose-50 dark:bg-rose-900/20 border-rose-100 dark:border-rose-800/60',
                    'dokumen' => 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-100 dark:border-indigo-800/60',
                    'link'    => 'bg-sky-50 dark:bg-sky-900/20 border-sky-100 dark:border-sky-800/60',
                    default   => 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800/60',
                };
                $aksenTeks = match($item->tipe_konten) {
                    'gambar'  => 'text-rose-600 dark:text-rose-400',
                    'dokumen' => 'text-indigo-600 dark:text-indigo-400',
                    'link'    => 'text-sky-600 dark:text-sky-400',
                    default   => 'text-blue-600 dark:text-blue-400',
                };

                $isNew    = $item->created_at->gt(now()->subHours(24));
                $segBerakhir = $item->tanggal_selesai
                    && !$item->tanggal_selesai->isPast()
                    && $item->tanggal_selesai->diffInDays(now()) <= 3;
            @endphp

            <li>
                <button type="button"
                        onclick='pgBuka({{ $pgJson }})'
                        class="w-full text-left group px-6 py-5
                               hover:bg-slate-50/80 dark:hover:bg-slate-700/30
                               active:bg-sky-50/50 dark:active:bg-sky-900/10
                               transition-colors duration-150
                               focus:outline-none focus:bg-sky-50/40 dark:focus:bg-sky-900/10">

                    <div class="flex items-start gap-4">

                        {{-- ── IKON TIPE + GARIS VERTIKAL ── --}}
                        <div class="flex flex-col items-center shrink-0">
                            <div class="w-10 h-10 rounded-2xl {{ $aksenBg }} border
                                        flex items-center justify-center shrink-0 shadow-sm">
                                <span class="text-lg leading-none">{{ $item->tipeIcon() }}</span>
                            </div>
                            @if(!$loop->last)
                            <div class="w-px mt-2 flex-1 min-h-[16px]"
                                 style="background:linear-gradient(to bottom, #e2e8f0, transparent)"
                                 aria-hidden="true"></div>
                            @endif
                        </div>

                        {{-- ── KONTEN ── --}}
                        <div class="flex-1 min-w-0">

                            {{-- Baris atas: judul + badge BARU + waktu --}}
                            <div class="flex items-start justify-between gap-3 mb-1.5">
                                <div class="flex items-center gap-2 flex-1 min-w-0 flex-wrap">
                                    <h3 class="font-bold text-slate-800 dark:text-slate-100 text-[15px] leading-snug
                                               group-hover:text-sky-600 dark:group-hover:text-sky-400
                                               transition-colors">
                                        {{ $item->judul }}
                                    </h3>
                                    @if($isNew)
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full
                                                 text-[10px] font-bold tracking-wide
                                                 bg-emerald-100 text-emerald-700
                                                 dark:bg-emerald-900/40 dark:text-emerald-400
                                                 border border-emerald-200 dark:border-emerald-700">
                                        ✦ BARU
                                    </span>
                                    @endif
                                    @if($segBerakhir)
                                    <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                                 text-[10px] font-bold
                                                 bg-amber-100 text-amber-700
                                                 dark:bg-amber-900/40 dark:text-amber-400
                                                 border border-amber-200 dark:border-amber-700">
                                        ⏰ Segera berakhir
                                    </span>
                                    @endif
                                </div>
                                <span class="shrink-0 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap mt-0.5 hidden sm:block">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Baris meta: badge audience + tipe + tanggal --}}
                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold
                                             {{ $item->audienceBadgeColor() }}">
                                    {{ $item->audienceLabel() }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium
                                             bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 capitalize">
                                    {{ $item->tipe_konten }}
                                </span>
                                <span class="text-slate-300 dark:text-slate-600 text-[10px] select-none">•</span>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                    {{ $item->created_at->isoFormat('D MMM Y') }}
                                </span>
                                <span class="text-[11px] text-slate-400 sm:hidden">
                                    · {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- ── PREVIEW KONTEN BERDASARKAN TIPE ── --}}
                            @if($item->tipe_konten === 'teks')
                                @if($item->isi)
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                    {{ strip_tags($item->isi) }}
                                </p>
                                @endif

                            @elseif($item->tipe_konten === 'gambar')
                                <div class="flex items-center gap-3">
                                    @if($item->file_path)
                                    <div class="w-20 h-14 rounded-xl overflow-hidden shrink-0
                                                bg-slate-100 dark:bg-slate-700
                                                border border-slate-200 dark:border-slate-600
                                                flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $item->file_path) }}"
                                             alt="{{ $item->judul }}"
                                             loading="lazy"
                                             class="w-full h-full object-cover"
                                             onerror="pgImgFallback(this)">
                                    </div>
                                    @endif
                                    @if($item->isi)
                                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 flex-1 leading-relaxed">
                                        {{ strip_tags($item->isi) }}
                                    </p>
                                    @else
                                    <p class="text-sm text-slate-400 dark:text-slate-500 italic">Klik untuk melihat gambar</p>
                                    @endif
                                </div>

                            @elseif($item->tipe_konten === 'dokumen')
                                <div class="flex items-center gap-2.5 w-fit px-3 py-2 rounded-xl
                                            {{ $aksenBg }} border">
                                    <svg class="w-4 h-4 {{ $aksenTeks }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold {{ $aksenTeks }} uppercase leading-none">
                                            {{ $item->fileExtension() ?: 'DOKUMEN' }}
                                        </p>
                                        @if($item->file_name)
                                        <p class="text-xs text-slate-400 truncate max-w-[200px] mt-0.5">
                                            {{ $item->file_name }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @if($item->isi)
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-1 mt-2 leading-relaxed">
                                    {{ strip_tags($item->isi) }}
                                </p>
                                @endif

                            @elseif($item->tipe_konten === 'link')
                                <div class="flex items-center gap-2 w-fit px-3 py-2 rounded-xl
                                            {{ $aksenBg }} border">
                                    <svg class="w-4 h-4 {{ $aksenTeks }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    <span class="text-xs {{ $aksenTeks }} font-semibold truncate max-w-[240px]">
                                        {{ $item->link_label ?: $item->link_url }}
                                    </span>
                                </div>
                                @if($item->isi)
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-1 mt-2 leading-relaxed">
                                    {{ strip_tags($item->isi) }}
                                </p>
                                @endif
                            @endif

                            {{-- Badge berakhir --}}
                            @if($item->tanggal_selesai && !$segBerakhir)
                            <div class="mt-2.5 inline-flex items-center gap-1.5 text-[11px] font-medium
                                        text-amber-600 dark:text-amber-400
                                        bg-amber-50 dark:bg-amber-900/20
                                        px-2.5 py-1 rounded-full
                                        border border-amber-200 dark:border-amber-800/60">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Berakhir {{ $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm') }}
                            </div>
                            @endif
                        </div>

                        {{-- ── PANAH KANAN ── --}}
                        <div class="shrink-0 self-center">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center
                                        bg-slate-100 dark:bg-slate-700/60
                                        group-hover:bg-sky-100 dark:group-hover:bg-sky-900/40
                                        transition-colors duration-150">
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500
                                            group-hover:text-sky-500 dark:group-hover:text-sky-400
                                            group-hover:translate-x-0.5 transition-all duration-150"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>

                    </div>
                </button>
            </li>
            @endforeach
        </ul>

        {{-- ── PAGINATION KUSTOM ── --}}
        @if($pengumuman->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/70
                    flex flex-col sm:flex-row items-center justify-between gap-3">

            <p class="text-xs text-slate-400 dark:text-slate-500 order-last sm:order-first">
                Halaman <strong class="text-slate-600 dark:text-slate-300">{{ $pengumuman->currentPage() }}</strong>
                dari <strong class="text-slate-600 dark:text-slate-300">{{ $pengumuman->lastPage() }}</strong>
            </p>

            <div class="flex items-center gap-1.5">

                {{-- Prev --}}
                @if($pengumuman->onFirstPage())
                <span class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-semibold
                             bg-slate-100 dark:bg-slate-700 text-slate-400 cursor-not-allowed select-none">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Sebelumnya
                </span>
                @else
                <a href="{{ $pengumuman->previousPageUrl() }}"
                   class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-semibold
                          bg-slate-100 dark:bg-slate-700 hover:bg-sky-100 dark:hover:bg-sky-900/40
                          text-slate-600 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-400
                          transition-colors border border-transparent hover:border-sky-200 dark:hover:border-sky-800">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Sebelumnya
                </a>
                @endif

                {{-- Nomor halaman --}}
                @php
                    $start = max(1, $pengumuman->currentPage() - 2);
                    $end   = min($pengumuman->lastPage(), $pengumuman->currentPage() + 2);
                @endphp

                @if($start > 1)
                <a href="{{ $pengumuman->url(1) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-bold
                          text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                    1
                </a>
                @if($start > 2)
                <span class="text-slate-300 dark:text-slate-600 text-xs px-1">…</span>
                @endif
                @endif

                @for($p = $start; $p <= $end; $p++)
                <a href="{{ $pengumuman->url($p) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-bold transition-all
                          {{ $p == $pengumuman->currentPage()
                              ? 'bg-sky-600 text-white shadow-sm'
                              : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700' }}">
                    {{ $p }}
                </a>
                @endfor

                @if($end < $pengumuman->lastPage())
                @if($end < $pengumuman->lastPage() - 1)
                <span class="text-slate-300 dark:text-slate-600 text-xs px-1">…</span>
                @endif
                <a href="{{ $pengumuman->url($pengumuman->lastPage()) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-xl text-xs font-bold
                          text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors">
                    {{ $pengumuman->lastPage() }}
                </a>
                @endif

                {{-- Next --}}
                @if($pengumuman->hasMorePages())
                <a href="{{ $pengumuman->nextPageUrl() }}"
                   class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-semibold
                          bg-sky-600 hover:bg-sky-700 text-white
                          transition-colors shadow-sm">
                    Selanjutnya
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @else
                <span class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-semibold
                             bg-slate-100 dark:bg-slate-700 text-slate-400 cursor-not-allowed select-none">
                    Selanjutnya
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
                @endif
            </div>
        </div>
        @endif

    </div>
    @endif

</div>

{{-- ══════════ JAVASCRIPT MODAL ══════════ --}}
<script>
(function () {
    'use strict';

    // ── Fallback gambar di list (jika file rusak / tidak ada) ──
    window.pgImgFallback = function (imgEl) {
        var wrapper = imgEl.parentElement;
        if (!wrapper) return;
        wrapper.innerHTML = '<div class="w-full h-full flex items-center justify-center text-xl text-slate-400">🖼️</div>';
    };

    window.pgBuka = function (d) {
        var konten = document.getElementById('pgModalKonten');
        if (!konten) return;
        konten.innerHTML = '';
        konten.appendChild(pgBuatHtml(d));
        var overlay = document.getElementById('pgModal');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.pgTutup = function () {
        var overlay = document.getElementById('pgModal');
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') pgTutup();
    });

    // ── Fallback gambar di modal (jika file rusak / tidak ada) ──
    window.pgModalImgFallback = function (imgEl) {
        var wrapper = imgEl.closest('div');
        if (!wrapper) return;
        wrapper.innerHTML = '';

        var box = document.createElement('div');
        box.className = 'p-8 text-center';

        var icon = document.createElement('div');
        icon.className = 'text-5xl mb-3';
        icon.textContent = '🖼️';

        var p1 = document.createElement('p');
        p1.className = 'text-sm text-slate-400';
        p1.textContent = 'Gambar tidak dapat dimuat.';

        var p2 = document.createElement('p');
        p2.className = 'text-xs text-slate-400 mt-1';
        p2.textContent = 'Jalankan: php artisan storage:link';

        box.appendChild(icon);
        box.appendChild(p1);
        box.appendChild(p2);
        wrapper.appendChild(box);
    };

    function pgBuatHtml(d) {
        var container = document.createElement('div');

        // ── Header: ikon + judul + badge ──
        var header = document.createElement('div');
        header.className = 'flex items-start gap-4 mb-5 pr-10';

        var iconEl = document.createElement('div');
        iconEl.className = 'text-3xl shrink-0 mt-0.5 leading-none';
        iconEl.textContent = d.tipeIcon;
        header.appendChild(iconEl);

        var headerRight = document.createElement('div');
        headerRight.className = 'flex-1 min-w-0';

        var title = document.createElement('h2');
        title.className = 'text-xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words';
        title.textContent = d.judul;
        headerRight.appendChild(title);

        var badgeWrap = document.createElement('div');
        badgeWrap.className = 'flex gap-2 mt-2 flex-wrap';

        var badgeAudience = document.createElement('span');
        badgeAudience.className = 'px-2.5 py-1 rounded-full text-xs font-semibold ' + d.audienceColor;
        badgeAudience.textContent = d.audience;
        badgeWrap.appendChild(badgeAudience);

        var badgeTipe = document.createElement('span');
        badgeTipe.className = 'px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize';
        badgeTipe.textContent = d.tipe;
        badgeWrap.appendChild(badgeTipe);

        headerRight.appendChild(badgeWrap);
        header.appendChild(headerRight);
        container.appendChild(header);

        // ── Meta ──
        var meta = document.createElement('div');
        meta.className = 'flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400 mb-5 pb-5 border-b border-slate-200 dark:border-slate-700';

        var metaTanggal = document.createElement('span');
        metaTanggal.textContent = '📅 ' + d.tanggal;
        meta.appendChild(metaTanggal);

        var metaCreator = document.createElement('span');
        metaCreator.textContent = '👤 ' + d.creator;
        meta.appendChild(metaCreator);

        var metaDiff = document.createElement('span');
        metaDiff.textContent = '🕐 ' + d.diffHumans;
        meta.appendChild(metaDiff);

        container.appendChild(meta);

        // ── GAMBAR ──
        if (d.tipe === 'gambar') {
            if (d.fileUrl && d.fileUrl !== '') {
                var imgWrap = document.createElement('div');
                imgWrap.className = 'rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-5 bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-[120px]';

                var img = document.createElement('img');
                img.src = d.fileUrl;
                img.alt = d.judul;
                img.className = 'w-full max-h-[420px] object-contain block';
                img.onerror = function () { window.pgModalImgFallback(img); };

                imgWrap.appendChild(img);
                container.appendChild(imgWrap);
            } else {
                container.appendChild(buatKotakKosong('🖼️', 'Tidak ada file gambar.'));
            }
        }

        // ── ISI / TEKS ──
        if (d.isi && d.isi.trim() !== '') {
            var adaHtml = /<[a-z][\s\S]*>/i.test(d.isi);
            var isiWrap = document.createElement('div');

            if (adaHtml) {
                isiWrap.className = 'text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 prose prose-sm dark:prose-invert max-w-none';
                isiWrap.innerHTML = bersihHtml(d.isi);
            } else {
                isiWrap.className = 'text-sm text-slate-700 dark:text-slate-300 leading-relaxed mb-5 whitespace-pre-line';
                isiWrap.textContent = d.isi;
            }

            container.appendChild(isiWrap);
        }

        // ── DOKUMEN ──
        if (d.tipe === 'dokumen') {
            if (d.fileUrl && d.fileUrl !== '') {
                var docWrap = document.createElement('div');
                docWrap.className = 'flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-200 dark:border-indigo-700 mb-5';

                var docLeft = document.createElement('div');
                docLeft.className = 'flex items-center gap-3';

                var docIcon = document.createElement('div');
                docIcon.className = 'w-12 h-12 bg-indigo-100 dark:bg-indigo-800 rounded-xl flex items-center justify-center text-2xl';
                docIcon.textContent = '📄';
                docLeft.appendChild(docIcon);

                var docInfo = document.createElement('div');
                var docExt = document.createElement('p');
                docExt.className = 'text-sm font-bold text-indigo-700 dark:text-indigo-300';
                docExt.textContent = (d.fileExt || 'FILE') + ' Dokumen';
                docInfo.appendChild(docExt);

                var docName = document.createElement('p');
                docName.className = 'text-xs text-slate-400 max-w-[220px] truncate';
                docName.textContent = d.fileName;
                docInfo.appendChild(docName);

                docLeft.appendChild(docInfo);
                docWrap.appendChild(docLeft);

                var docBtn = document.createElement('a');
                docBtn.href = d.fileUrl;
                docBtn.target = '_blank';
                docBtn.setAttribute('download', '');
                docBtn.className = 'shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl no-underline';
                docBtn.addEventListener('click', function (e) { e.stopPropagation(); });

                docBtn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>';
                var docBtnLabel = document.createTextNode('Unduh Dokumen');
                docBtn.appendChild(docBtnLabel);

                docWrap.appendChild(docBtn);
                container.appendChild(docWrap);
            } else {
                container.appendChild(buatKotakKosong('📄', 'Tidak ada file dokumen.'));
            }
        }

        // ── LINK ──
        if (d.tipe === 'link') {
            if (d.linkUrl && d.linkUrl !== '') {
                var linkWrap = document.createElement('div');
                linkWrap.className = 'p-4 bg-sky-50 dark:bg-sky-900/30 rounded-2xl border border-sky-200 dark:border-sky-700 mb-5';

                var linkLabelTop = document.createElement('p');
                linkLabelTop.className = 'text-xs text-slate-500 dark:text-slate-400 mb-3 font-medium';
                linkLabelTop.textContent = '🔗 Tautan Resmi Pengumuman';
                linkWrap.appendChild(linkLabelTop);

                var linkBtn = document.createElement('a');
                linkBtn.href = d.linkUrl;
                linkBtn.target = '_blank';
                linkBtn.rel = 'noopener noreferrer';
                linkBtn.className = 'inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl no-underline';
                linkBtn.addEventListener('click', function (e) { e.stopPropagation(); });

                linkBtn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>';
                linkBtn.appendChild(document.createTextNode(d.linkLabel || 'Kunjungi Link'));
                linkWrap.appendChild(linkBtn);

                var linkUrlText = document.createElement('p');
                linkUrlText.className = 'text-xs text-slate-400 mt-2 break-all';
                linkUrlText.textContent = d.linkUrl;
                linkWrap.appendChild(linkUrlText);

                container.appendChild(linkWrap);
            } else {
                container.appendChild(buatKotakKosong('🔗', 'Tidak ada tautan.'));
            }
        }

        // ── Tanggal berakhir ──
        if (d.tglSelesai && d.tglSelesai !== '') {
            var endWrap = document.createElement('div');
            endWrap.className = 'flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-700 mb-4';

            var endIcon = document.createElement('span');
            endIcon.className = 'text-xl';
            endIcon.textContent = '⏰';
            endWrap.appendChild(endIcon);

            var endText = document.createElement('p');
            endText.className = 'text-xs text-amber-700 dark:text-amber-300 font-medium';

            var strong = document.createElement('strong');
            strong.textContent = d.tglSelesai;

            endText.appendChild(document.createTextNode('Berakhir: '));
            endText.appendChild(strong);
            endWrap.appendChild(endText);

            container.appendChild(endWrap);
        }

        // ── Tombol tutup ──
        var footer = document.createElement('div');
        footer.className = 'flex justify-end pt-2';

        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-2xl transition-colors';
        closeBtn.textContent = 'Tutup';
        closeBtn.addEventListener('click', function () { window.pgTutup(); });

        footer.appendChild(closeBtn);
        container.appendChild(footer);

        return container;
    }

    function buatKotakKosong(emoji, teks) {
        var box = document.createElement('div');
        box.className = 'p-8 mb-5 bg-slate-50 dark:bg-slate-900/40 rounded-2xl text-center';

        var iconEl = document.createElement('div');
        iconEl.className = 'text-4xl mb-2';
        iconEl.textContent = emoji;

        var textEl = document.createElement('p');
        textEl.className = 'text-sm text-slate-400';
        textEl.textContent = teks;

        box.appendChild(iconEl);
        box.appendChild(textEl);
        return box;
    }

    function bersihHtml(html) {
        if (!html) return '';
        return html
            .replace(/<script[\s\S]*?<\/script>/gi, '')
            .replace(/<iframe[\s\S]*?<\/iframe>/gi, '')
            .replace(/\bon\w+\s*=\s*["'][^"']*["']/gi, '')
            .replace(/javascript\s*:/gi, '#');
    }

})();
</script>

@endsection
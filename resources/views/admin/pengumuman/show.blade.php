{{--
| resources/views/admin/pengumuman/show.blade.php
| Halaman Detail Pengumuman — Admin Only
--}}

@extends('layouts.app')
@section('title', 'Detail Pengumuman')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── BREADCRUMB & BACK ── --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pengumuman') }}"
           class="inline-flex items-center gap-2 px-4 py-2
                  bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700
                  border border-slate-200 dark:border-slate-700
                  text-slate-600 dark:text-slate-400 text-sm font-medium
                  rounded-2xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <span class="text-slate-400 dark:text-slate-600">/</span>
        <span class="text-sm text-slate-500 dark:text-slate-400">Detail Pengumuman</span>
    </div>

    {{-- ── KARTU UTAMA ── --}}
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- Banner atas berdasarkan tipe --}}
        @if($pengumuman->tipe_konten === 'gambar' && $pengumuman->file_path)
            <div class="w-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center overflow-hidden"
                 style="max-height: 400px;">
                <img src="{{ asset('storage/' . $pengumuman->file_path) }}"
                     alt="{{ $pengumuman->judul }}"
                     class="w-full object-contain"
                     style="max-height: 400px;"
                     onerror="this.closest('div').innerHTML='<div class=\'p-12 text-center\'><div class=\'text-6xl mb-3\'>🖼️</div><p class=\'text-sm text-slate-400\'>Gambar tidak dapat dimuat.<br>Jalankan: <code>php artisan storage:link</code></p></div>'">
            </div>
        @else
            {{-- Gradient header berdasarkan tipe --}}
            @php
                $headerClass = match($pengumuman->tipe_konten) {
                    'dokumen' => 'from-indigo-600 to-indigo-700',
                    'link'    => 'from-sky-500 to-sky-700',
                    default   => 'from-slate-600 to-slate-700',
                };
            @endphp
            <div class="h-3 bg-gradient-to-r {{ $headerClass }}"></div>
        @endif

        <div class="p-6 sm:p-8">

            {{-- Header: judul + badges --}}
            <div class="flex items-start gap-4 mb-6">
                <div class="text-4xl shrink-0 leading-none mt-1">{{ $pengumuman->tipeIcon() }}</div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">
                        {{ $pengumuman->judul }}
                    </h1>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $pengumuman->audienceBadgeColor() }}">
                            {{ $pengumuman->audienceLabel() }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                     bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">
                            {{ $pengumuman->tipe_konten }}
                        </span>
                        @if($pengumuman->is_active)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                     bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                            ✓ Aktif
                        </span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                     bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                            Nonaktif
                        </span>
                        @endif
                        @if($pengumuman->show_di_dashboard)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                     bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300">
                            📌 Di Dashboard
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Meta info --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 pb-6
                        border-b border-slate-200 dark:border-slate-700">
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Dibuat oleh</p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ optional($pengumuman->creator)->name ?? 'Admin' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tanggal dibuat</p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ $pengumuman->created_at->format('d F Y, H:i') }}
                    </p>
                </div>
                @if($pengumuman->tanggal_mulai)
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Mulai</p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ $pengumuman->tanggal_mulai->format('d F Y, H:i') }}
                    </p>
                </div>
                @endif
                @if($pengumuman->tanggal_selesai)
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Berakhir</p>
                    <p class="text-sm font-medium
                              {{ $pengumuman->tanggal_selesai->isPast() ? 'text-red-500 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                        {{ $pengumuman->tanggal_selesai->format('d F Y, H:i') }}
                        @if($pengumuman->tanggal_selesai->isPast())
                            <span class="text-xs">(sudah berakhir)</span>
                        @endif
                    </p>
                </div>
                @endif
            </div>

            {{-- ISI / TEKS --}}
            @if($pengumuman->isi)
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">
                    Isi Pengumuman
                </h3>
                <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed
                            prose prose-sm dark:prose-invert max-w-none
                            prose-img:rounded-xl prose-a:text-indigo-600">
                    {!! $pengumuman->isi !!}
                </div>
            </div>
            @endif

            {{-- FILE / DOKUMEN --}}
            @if(in_array($pengumuman->tipe_konten, ['dokumen']) && $pengumuman->file_path)
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">
                    File Terlampir
                </h3>
                <div class="flex flex-col sm:flex-row items-start sm:items-center
                            justify-between gap-3 p-4
                            bg-indigo-50 dark:bg-indigo-900/30
                            rounded-2xl border border-indigo-200 dark:border-indigo-700">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-800 rounded-xl
                                    flex items-center justify-center text-2xl">📄</div>
                        <div>
                            <p class="text-sm font-bold text-indigo-700 dark:text-indigo-300">
                                {{ $pengumuman->fileExtension() ?: 'FILE' }} Dokumen
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $pengumuman->file_name }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $pengumuman->file_path) }}"
                       target="_blank"
                       download="{{ $pengumuman->file_name }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5
                              bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                              rounded-xl transition-colors shadow-sm no-underline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh Dokumen
                    </a>
                </div>
            </div>
            @endif

            {{-- LINK --}}
            @if($pengumuman->tipe_konten === 'link' && $pengumuman->link_url)
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">
                    Tautan
                </h3>
                <div class="p-4 bg-sky-50 dark:bg-sky-900/30 rounded-2xl border border-sky-200 dark:border-sky-700">
                    <a href="{{ $pengumuman->link_url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-5 py-2.5
                              bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold
                              rounded-xl transition-colors no-underline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4
                                     M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        {{ $pengumuman->link_label ?: 'Buka Link' }}
                    </a>
                    <p class="text-xs text-slate-400 mt-2 break-all">{{ $pengumuman->link_url }}</p>
                </div>
            </div>
            @endif

            {{-- INFO FILE GAMBAR jika tipe gambar tapi sudah ditampilkan di atas --}}
            @if($pengumuman->tipe_konten === 'gambar' && $pengumuman->file_path)
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">
                    Info File Gambar
                </h3>
                <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-900/50
                            rounded-xl border border-slate-200 dark:border-slate-700">
                    <span class="text-xl">🖼️</span>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                            {{ $pengumuman->file_name }}
                        </p>
                        <p class="text-xs text-slate-400">{{ $pengumuman->fileExtension() }}</p>
                    </div>
                    <a href="{{ asset('storage/' . $pengumuman->file_path) }}"
                       target="_blank"
                       class="ml-auto text-xs text-indigo-500 hover:text-indigo-700 font-medium no-underline">
                        Buka full →
                    </a>
                </div>
            </div>
            @endif

            {{-- AKSI ADMIN --}}
            <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                <a href="{{ route('admin.pengumuman.edit', $pengumuman) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5
                          bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                          rounded-2xl transition-all shadow-sm no-underline">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Pengumuman
                </a>
                <form method="POST" action="{{ route('admin.pengumuman.destroy', $pengumuman) }}"
                      onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5
                                   bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50
                                   text-red-600 dark:text-red-400 text-sm font-semibold
                                   rounded-2xl transition-colors border border-red-200 dark:border-red-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                     m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
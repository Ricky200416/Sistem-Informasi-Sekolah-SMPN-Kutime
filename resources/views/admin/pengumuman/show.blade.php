{{-- resources/views/admin/pengumuman/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Pengumuman')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Navigasi & Aksi --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pengumuman') }}"
               class="p-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                      text-slate-500 hover:text-indigo-600 rounded-2xl transition-all shadow-sm group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-slate-800 dark:text-white">Detail Pengumuman</h1>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pengumuman.edit', $pengumuman) }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2.5
                      bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold
                      rounded-2xl transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Ubah Konten
            </a>
            <form method="POST" action="{{ route('admin.pengumuman.destroy', $pengumuman) }}"
                  onsubmit="return confirm('Hapus pengumuman ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="p-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-2xl transition-all border border-red-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- KARTU UTAMA --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">

        {{-- Banner visual --}}
        @if($pengumuman->tipe_konten === 'gambar' && $pengumuman->file_path)
            <div class="w-full bg-slate-50 dark:bg-slate-900/50 p-4">
                <div class="relative w-full rounded-[2rem] overflow-hidden shadow-xl border-4 border-white dark:border-slate-800" style="max-height: 500px;">
                    <img src="{{ asset('storage/' . $pengumuman->file_path) }}"
                         alt="{{ $pengumuman->judul }}"
                         class="w-full h-full object-contain mx-auto bg-slate-100 dark:bg-slate-900"
                         onerror="this.parentElement.innerHTML='<div class=\'p-20 text-center\'><div class=\'text-7xl mb-4\'>🖼️</div><p class=\'text-slate-400 font-bold\'>Gagal memuat gambar utama.</p></div>'">
                </div>
            </div>
        @else
            <div class="h-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-sky-500"></div>
        @endif

        <div class="p-8 sm:p-12">

            {{-- Header info --}}
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-10 pb-10 border-b border-slate-100 dark:border-slate-700">
                <div class="flex-1 space-y-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $pengumuman->audienceBadgeColor() }}">
                            {{ $pengumuman->audienceLabel() }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                            {{ $pengumuman->tipe_konten }}
                        </span>
                        @if($pengumuman->is_active)
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-600">Terbit</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-400">Draf</span>
                        @endif
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-800 dark:text-white leading-tight">
                        {{ $pengumuman->judul }}
                    </h2>
                </div>
                <div class="shrink-0 flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 rounded-2xl flex items-center justify-center text-3xl shrink-0">
                        {{ $pengumuman->tipeIcon() }}
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dibuat Oleh</p>
                        <p class="text-sm font-black text-slate-700 dark:text-slate-200">{{ optional($pengumuman->creator)->name ?? 'Administrator' }}</p>
                    </div>
                </div>
            </div>

            {{-- Meta detil --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-12">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Terbit Pada</p>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $pengumuman->created_at->isoFormat('D MMMM Y, HH:mm') }}</p>
                </div>
                @if($pengumuman->tanggal_mulai)
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Jadwal Mulai</p>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $pengumuman->tanggal_mulai->isoFormat('D MMMM Y, HH:mm') }}</p>
                </div>
                @endif
                @if($pengumuman->tanggal_selesai)
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em]">Berakhir Pada</p>
                    <p class="text-sm font-bold {{ $pengumuman->tanggal_selesai->isPast() ? 'text-red-500' : 'text-slate-600 dark:text-slate-300' }}">
                        {{ $pengumuman->tanggal_selesai->isoFormat('D MMMM Y, HH:mm') }}
                    </p>
                </div>
                @endif
            </div>

            {{-- ISI UTAMA --}}
            @if($pengumuman->isi)
            <div class="mb-12">
                <div class="prose prose-slate dark:prose-invert max-w-none 
                            prose-headings:font-black prose-p:leading-relaxed prose-p:text-slate-600 dark:prose-p:text-slate-300
                            prose-img:rounded-[2rem] prose-a:text-indigo-600 font-medium">
                    {!! $pengumuman->isi !!}
                </div>
            </div>
            @endif

            {{-- FILE LAMPIRAN --}}
            @if(in_array($pengumuman->tipe_konten, ['dokumen']) && $pengumuman->file_path)
            <div class="mb-10 p-8 bg-indigo-600 rounded-[2rem] flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl shadow-indigo-200 dark:shadow-none">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-4xl shadow-inner">📄</div>
                    <div class="text-white">
                        <p class="text-xs font-black uppercase tracking-widest opacity-80 mb-1">Unduhan Berkas</p>
                        <p class="text-lg font-black truncate max-w-[250px]">{{ $pengumuman->file_name }}</p>
                        <p class="text-[10px] font-bold opacity-70">{{ strtoupper($pengumuman->fileExtension()) }} DOCUMENT</p>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $pengumuman->file_path) }}"
                   target="_blank" download="{{ $pengumuman->file_name }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4
                          bg-white hover:bg-slate-100 text-indigo-600 text-sm font-black
                          rounded-2xl transition-all shadow-lg active:scale-95">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Sekarang
                </a>
            </div>
            @endif

            {{-- LINK LAMPIRAN --}}
            @if($pengumuman->tipe_konten === 'link' && $pengumuman->link_url)
            <div class="mb-10 p-8 bg-slate-900 rounded-[2.5rem] border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-2xl">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-sky-500/20 text-sky-400 rounded-[1.5rem] flex items-center justify-center text-4xl">🔗</div>
                    <div class="text-white">
                        <p class="text-[10px] font-black uppercase tracking-widest text-sky-400 mb-1">Tautan Eksternal</p>
                        <p class="text-lg font-black break-all">{{ $pengumuman->link_label ?: 'Buka Tautan' }}</p>
                    </div>
                </div>
                <a href="{{ $pengumuman->link_url }}"
                   target="_blank" rel="noopener noreferrer"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4
                          bg-sky-500 hover:bg-sky-600 text-white text-sm font-black
                          rounded-2xl transition-all shadow-lg shadow-sky-500/20 active:scale-95">
                    {{ $pengumuman->link_label ?: 'Kunjungi Link' }} ↗
                </a>
            </div>
            @endif

            {{-- Footer detail --}}
            <div class="pt-10 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between opacity-50">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sekolah Digital App</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID #{{ $pengumuman->id }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
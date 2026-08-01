{{-- resources/views/website/galeri.blade.php --}}
@extends('layouts.public')
@section('title', 'Galeri Kegiatan')

@section('content')
<section class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 py-5 lg:py-9">

    {{-- Header --}}
    <header class="mb-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-1.5">
        <div>
            <h1 class="text-base lg:text-2xl font-semibold text-black">
                Galeri Kegiatan SMPN Kutime
            </h1>
            <p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400">
                Dokumentasi foto, video, dan kegiatan sekolah.
            </p>
        </div>
        <p class="text-[9px] text-slate-400 shrink-0">{{ $galeris->total() }} media</p>
    </header>

    {{-- Filter --}}
    <form method="GET" action="{{ route('website.galeri') }}" class="flex flex-wrap gap-1.5 mb-4">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari media..."
               class="rounded-lg border-slate-300 text-[10px] py-1 px-2.5 w-full sm:w-40 focus:border-indigo-500 focus:ring-indigo-500">

        <div class="flex gap-1.5 w-full sm:w-auto flex-wrap">
            <select name="kategori" class="flex-1 sm:flex-none rounded-lg border-slate-300 text-[10px] py-1 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoriOptions as $val => $label)
                    <option value="{{ $val }}" @selected(request('kategori') === $val)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="tipe" class="flex-1 sm:flex-none rounded-lg border-slate-300 text-[10px] py-1 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Tipe</option>
                <option value="photo"         @selected(request('tipe') === 'photo')>🖼️ Foto</option>
                <option value="video"         @selected(request('tipe') === 'video')>🎥 Video</option>
                <option value="link_youtube"  @selected(request('tipe') === 'link_youtube')>▶️ YouTube</option>
                <option value="link_facebook" @selected(request('tipe') === 'link_facebook')>📘 Facebook</option>
            </select>

            <button type="submit"
                    class="px-2.5 py-1 bg-indigo-600 text-white text-[10px] rounded-lg hover:bg-indigo-700 transition">
                Cari
            </button>

            @if(request()->hasAny(['cari','kategori','tipe']))
                <a href="{{ route('website.galeri') }}"
                   class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] rounded-lg hover:bg-slate-200 transition">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Grid Media --}}
    @if($galeris->isEmpty())
        <div class="text-center py-16 text-slate-400 text-xs">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Belum ada media tersedia.
        </div>
    @else
        <div class="grid grid-cols-4 sm:grid-cols-3 lg:grid-cols-4 gap-1.5 sm:gap-3">
            @foreach($galeris as $item)
            <a href="{{ route('website.galeri.show', $item) }}"
               class="group relative block bg-white dark:bg-slate-800 rounded-lg shadow-sm
                      border border-slate-200 dark:border-slate-700 overflow-hidden
                      transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">

                {{-- Thumbnail --}}
                <div class="aspect-square overflow-hidden bg-slate-100 dark:bg-slate-700 relative">
                    <img src="{{ $item->thumbnail_url }}"
                         alt="{{ $item->judul }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         loading="lazy">

                    {{-- Badge tipe --}}
                    <div class="absolute top-1 left-1">
                        <span class="px-1 py-0.5 text-[7px] sm:text-[9px] rounded bg-black/60 text-white backdrop-blur-sm leading-tight">
                            {{ $item->tipe_label }}
                        </span>
                    </div>

                    {{-- Play icon untuk video --}}
                    @if($item->is_video)
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-5 h-5 sm:w-7 sm:h-7 rounded-full bg-black/50 flex items-center justify-center
                                    group-hover:bg-black/70 transition backdrop-blur-sm">
                            <svg class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                    @endif

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/10 transition-colors duration-300"></div>
                </div>

                {{-- Keterangan --}}
                <div class="p-1.5 sm:p-2.5">
                    <p class="text-[9px] sm:text-xs font-semibold text-black dark:text-black line-clamp-1 group-hover:text-indigo-600 transition-colors leading-tight">
                        {{ $item->judul }}
                    </p>
                    <div class="flex items-center justify-between mt-0.5 gap-0.5">
                        <span class="text-[7px] sm:text-[10px] text-slate-400 capitalize truncate">{{ $item->kategori }}</span>
                        <span class="text-[7px] sm:text-[10px] text-slate-400 shrink-0">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    @if($item->deskripsi)
                        <p class="text-[7px] sm:text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1 leading-tight">
                            {{ $item->deskripsi }}
                        </p>
                    @endif
                </div>

            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($galeris->hasPages())
            <div class="mt-6">{{ $galeris->links() }}</div>
        @endif
    @endif

</section>
@endsection
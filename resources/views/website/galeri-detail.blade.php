{{-- resources/views/website/galeri-detail.blade.php --}}
@extends('layouts.public')
@section('title', $galeri->judul)

@section('content')
<section class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-6">
        <a href="{{ route('website.home') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('website.galeri') }}" class="hover:text-indigo-600">Galeri</a>
        <span>/</span>
        <span class="text-slate-700 truncate max-w-xs">{{ Str::limit($galeri->judul, 40) }}</span>
    </nav>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- ============================================================
             MEDIA UTAMA
        ============================================================ --}}
        <div class="bg-black w-full">

            {{-- Foto --}}
            @if($galeri->tipe === 'photo' && $galeri->file_path)
                <img
                    src="{{ $galeri->file_url }}"
                    alt="{{ $galeri->judul }}"
                    class="w-full max-h-[70vh] object-contain mx-auto block"
                >

            {{-- Video upload --}}
            @elseif($galeri->tipe === 'video' && $galeri->file_path)
                <video
                    src="{{ $galeri->file_url }}"
                    controls
                    autoplay
                    class="w-full max-h-[70vh]"
                ></video>

            {{-- YouTube embed --}}
            @elseif($galeri->tipe === 'link_youtube' && $galeri->embed_url)
                <div class="aspect-video w-full">
                    <iframe
                        src="{{ $galeri->embed_url }}"
                        class="w-full h-full"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>

            {{-- Facebook embed --}}
            @elseif($galeri->tipe === 'link_facebook' && $galeri->embed_url)
                <div class="aspect-video w-full">
                    <iframe
                        src="{{ $galeri->embed_url }}"
                        class="w-full h-full"
                        allow="autoplay; encrypted-media"
                        allowfullscreen
                        scrolling="no"
                    ></iframe>
                </div>

            {{-- Fallback --}}
            @else
                <div class="flex items-center justify-center h-48 text-slate-400 text-sm">
                    Media tidak tersedia
                </div>
            @endif

        </div>

        {{-- ============================================================
             INFO DETAIL
        ============================================================ --}}
        <div class="p-6 sm:p-8">

            {{-- Badge tipe + kategori --}}
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                    @if($galeri->tipe === 'photo') bg-blue-100 text-blue-700
                    @elseif($galeri->tipe === 'video') bg-purple-100 text-purple-700
                    @elseif($galeri->tipe === 'link_youtube') bg-red-100 text-red-700
                    @else bg-indigo-100 text-indigo-700 @endif">
                    {{ $galeri->tipe_label }}
                </span>

                <span class="px-2.5 py-1 text-xs rounded-full bg-slate-100 text-slate-600 capitalize">
                    {{ $galeri->kategori }}
                </span>

                <span class="text-xs text-slate-400">
                    {{ $galeri->created_at->format('d M Y') }}
                </span>
            </div>

            {{-- Judul --}}
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4 leading-snug">
                {{ $galeri->judul }}
            </h1>

            {{-- Deskripsi --}}
            @if($galeri->deskripsi)
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-5">
                    {{ $galeri->deskripsi }}
                </p>
            @endif

            {{-- Link sumber untuk YouTube / Facebook --}}
            @if(in_array($galeri->tipe, ['link_youtube', 'link_facebook']) && $galeri->link_url)
                <div class="mb-5 flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <a href="{{ $galeri->link_url }}" target="_blank"
                       class="text-sm text-indigo-600 hover:underline break-all">
                        Buka di {{ $galeri->tipe === 'link_youtube' ? 'YouTube' : 'Facebook' }} →
                    </a>
                </div>
            @endif

            {{-- Tombol kembali --}}
            <div class="pt-5 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <a href="{{ route('website.galeri') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Galeri
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MEDIA LAINNYA (terkait kategori sama)
    ============================================================ --}}
    @if($lainnya->isNotEmpty())
    <div class="mt-10">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">
            Media Lainnya
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($lainnya as $item)
            <a href="{{ route('website.galeri.show', $item->id) }}"
               class="group relative block bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-md hover:-translate-y-1">

                {{-- Thumbnail --}}
                <div class="aspect-[4/3] overflow-hidden bg-slate-100 dark:bg-slate-700">
                    <img
                        src="{{ $item->thumbnail_url }}"
                        alt="{{ $item->judul }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        loading="lazy"
                    >
                </div>

                {{-- Play icon untuk video --}}
                @if($item->is_video)
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 rounded-full bg-black/50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
                @endif

                {{-- Badge tipe --}}
                <div class="absolute top-1.5 left-1.5">
                    <span class="px-1.5 py-0.5 text-xs rounded bg-black/60 text-white backdrop-blur-sm">
                        {{ $item->tipe_label }}
                    </span>
                </div>

                {{-- Judul --}}
                <div class="p-2.5">
                    <p class="text-xs font-medium text-slate-800 dark:text-slate-100 line-clamp-1">
                        {{ $item->judul }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $item->created_at->format('d M Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</section>
@endsection
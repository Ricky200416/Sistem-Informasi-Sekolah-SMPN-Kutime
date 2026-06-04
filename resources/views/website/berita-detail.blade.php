{{-- resources/views/website/berita-detail.blade.php --}}
@extends('layouts.public')
@section('title', $berita->judul)

@section('content')
<section class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-6">
        <a href="{{ route('website.home') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('website.berita') }}" class="hover:text-indigo-600">Berita</a>
        <span>/</span>
        <span class="text-slate-700 truncate max-w-xs">{{ Str::limit($berita->judul, 40) }}</span>
    </nav>

    <article class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

        {{-- ============================================================
             MEDIA UTAMA
        ============================================================ --}}
        @if($berita->has_media)
        <div class="w-full bg-black">

            {{-- Foto upload --}}
            @if($berita->media_tipe === 'photo' && $berita->media_file)
                <img src="{{ $berita->media_file_url }}"
                     alt="{{ $berita->judul }}"
                     class="w-full max-h-[70vh] object-contain block mx-auto">

            {{-- Video upload --}}
            @elseif($berita->media_tipe === 'video' && $berita->media_file)
                <video src="{{ $berita->media_file_url }}"
                       controls
                       class="w-full max-h-[70vh]"
                       poster="{{ $berita->media_thumbnail ? asset('storage/'.$berita->media_thumbnail) : '' }}">
                    Browser Anda tidak mendukung video.
                </video>

            {{-- YouTube embed --}}
            @elseif($berita->media_tipe === 'link_youtube' && $berita->embed_url)
                <div class="aspect-video w-full">
                    <iframe src="{{ $berita->embed_url }}"
                            class="w-full h-full"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>

            {{-- Facebook embed --}}
            @elseif($berita->media_tipe === 'link_facebook' && $berita->embed_url)
                <div class="aspect-video w-full">
                    <iframe src="{{ $berita->embed_url }}"
                            class="w-full h-full"
                            allow="autoplay; encrypted-media"
                            allowfullscreen
                            scrolling="no">
                    </iframe>
                </div>

            @endif
        </div>
        @endif

        {{-- ============================================================
             KONTEN ARTIKEL
        ============================================================ --}}
        <div class="p-6 sm:p-8">

            {{-- Badge meta --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if($berita->kategori === 'pengumuman')
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">📢 Pengumuman</span>
                @else
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">📰 Berita</span>
                @endif

                @if($berita->is_penting)
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-600 text-white">🔴 Penting</span>
                @endif

                {{-- Badge tipe media --}}
                @if($berita->has_media)
                    @php
                        $badge = match($berita->media_tipe) {
                            'photo'          => ['🖼️ Foto',     'bg-blue-100 text-blue-700'],
                            'video'          => ['🎥 Video',    'bg-purple-100 text-purple-700'],
                            'link_youtube'   => ['▶️ YouTube',  'bg-red-100 text-red-700'],
                            'link_facebook'  => ['📘 Facebook', 'bg-indigo-100 text-indigo-700'],
                            default          => null,
                        };
                    @endphp
                    @if($badge)
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $badge[1] }}">{{ $badge[0] }}</span>
                    @endif
                @endif

                <span class="text-xs text-slate-400">
                    {{ $berita->tanggal_publish?->format('d F Y') ?? $berita->created_at->format('d F Y') }}
                </span>

                @if($berita->user)
                    <span class="text-xs text-slate-400">oleh {{ $berita->user->name }}</span>
                @endif
            </div>

            {{-- Judul --}}
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6 leading-snug">
                {{ $berita->judul }}
            </h1>

            {{-- Tombol buka sumber link YouTube / Facebook --}}
            @if(in_array($berita->media_tipe, ['link_youtube','link_facebook']) && $berita->media_link)
            <div class="mb-5 flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <a href="{{ $berita->media_link }}" target="_blank" rel="noopener noreferrer"
                   class="text-sm text-indigo-600 hover:underline break-all">
                    Buka di {{ $berita->media_tipe === 'link_youtube' ? 'YouTube' : 'Facebook' }} →
                </a>
            </div>
            @endif

            {{-- Isi berita --}}
            <div class="prose prose-sm sm:prose max-w-none dark:prose-invert
                        prose-headings:font-semibold prose-a:text-indigo-600
                        prose-img:rounded-lg prose-img:shadow-sm">
                {!! nl2br(e($berita->isi)) !!}
            </div>

            {{-- Tombol kembali --}}
            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                <a href="{{ route('website.berita') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Berita
                </a>
            </div>
        </div>
    </article>

    {{-- ============================================================
         BERITA TERKAIT
    ============================================================ --}}
    @if($terkait->isNotEmpty())
    <div class="mt-10">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">Berita Terkait</h2>

        <div class="grid sm:grid-cols-3 gap-5">
            @foreach($terkait as $item)
            <a href="{{ route('website.berita.show', $item->slug) }}"
               class="group flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200
                      dark:border-slate-700 shadow-sm overflow-hidden transition-all hover:shadow-md hover:-translate-y-1">

                {{-- Thumbnail terkait --}}
                <div class="relative h-32 bg-slate-100 dark:bg-slate-700 overflow-hidden">

                    @if($item->media_tipe === 'photo' && $item->media_file)
                        <img src="{{ $item->media_file_url }}" alt="{{ $item->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">

                    @elseif($item->media_tipe === 'video' && $item->media_file)
                        @if($item->media_thumbnail)
                            <img src="{{ $item->media_thumbnail_url }}" alt="{{ $item->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                        @else
                            <video src="{{ $item->media_file_url }}" class="w-full h-full object-cover" muted preload="none"></video>
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-black/50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                    @elseif($item->media_tipe === 'link_youtube')
                        <img src="{{ $item->media_thumbnail_url }}" alt="{{ $item->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                    @elseif($item->media_tipe === 'link_facebook')
                        @if($item->media_thumbnail)
                            <img src="{{ $item->media_thumbnail_url }}" alt="{{ $item->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                        @else
                            <div class="w-full h-full bg-blue-700 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white/40" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-3 flex-1 flex flex-col">
                    <span class="text-xs text-slate-400 mb-1">{{ $item->tanggal_publish?->format('d M Y') }}</span>
                    <h3 class="text-xs font-semibold text-slate-900 dark:text-slate-100 line-clamp-2 mb-2 group-hover:text-indigo-600 transition-colors">
                        {{ $item->judul }}
                    </h3>
                    <span class="text-xs text-indigo-600 hover:underline mt-auto">Baca →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</section>
@endsection
@extends('layouts.website')

@section('title', 'Beranda')

@section('content')

{{-- ═══════════════════ §1 HERO BANNER SLIDER ═══════════════════ --}}
@php
    $heroBanners = \App\Models\Banner::where('is_active', true)->orderBy('order', 'asc')->get();
@endphp

<section class="relative bg-slate-900 overflow-hidden" x-data="{ 
    activeSlide: 0, 
    totalSlides: {{ count($heroBanners) > 0 ? count($heroBanners) : 1 }},
    autoPlay: null,
    startAutoPlay() {
        this.autoPlay = setInterval(() => {
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        }, 5000);
    },
    stopAutoPlay() {
        clearInterval(this.autoPlay);
    }
}" x-init="startAutoPlay()" @mouseenter="stopAutoPlay()" @mouseleave="startAutoPlay()">

    <div class="relative w-full h-[400px] sm:h-[500px] md:h-[600px] overflow-hidden">
        @forelse($heroBanners as $index => $banner)
        <div x-show="activeSlide === {{ $index }}"
             x-transition:enter="transition ease-out duration-700 transform"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-500 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute inset-0 w-full h-full">
            
            {{-- Background Image & Gradient Overlay --}}
            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>

            {{-- Caption Banner --}}
            <div class="absolute inset-0 flex items-end sm:items-center justify-start max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 sm:pb-0">
                <div class="max-w-2xl text-white space-y-3">
                    @if($banner->subtitle)
                    <span class="inline-block px-3 py-1 rounded-full text-2xs sm:text-xs font-semibold bg-amber-500/90 text-slate-950 uppercase tracking-wider">
                        {{ $banner->subtitle }}
                    </span>
                    @endif
                    <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold font-lora leading-tight drop-shadow-md">
                        {{ $banner->title }}
                    </h1>
                    @if($banner->description)
                    <p class="text-xs sm:text-base text-slate-200 line-clamp-2 sm:line-clamp-3 leading-relaxed drop-shadow">
                        {{ $banner->description }}
                    </p>
                    @endif
                    @if($banner->button_link)
                    <div class="pt-2">
                        <a href="{{ $banner->button_link }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-xs sm:text-sm text-white shadow-lg transition-all transform hover:-translate-y-0.5" style="background:#0e2356">
                            <span>{{ $banner->button_text ?? 'Selengkapnya' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        {{-- Fallback Banner Default jika Belum Ada Data --}}
        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-navy to-slate-900 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white space-y-3">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-amber-500 text-slate-950 uppercase">Selamat Datang</span>
                <h1 class="text-2xl sm:text-4xl font-bold font-lora">Selamat Datang di Website Resmi {{ \App\Models\SchoolSetting::get('singkatan','SMPN Kutime') }}</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl">Mewujudkan generasi unggul, berkarakter, dan berdaya saing di era digital.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Controls & Pagination Slider --}}
    @if(count($heroBanners) > 1)
    <button @click="activeSlide = (activeSlide - 1 + totalSlides) % totalSlides" type="button" class="absolute left-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-slate-900/40 text-white hover:bg-slate-900/80 transition hidden sm:block">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click="activeSlide = (activeSlide + 1) % totalSlides" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-slate-900/40 text-white hover:bg-slate-900/80 transition hidden sm:block">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>

    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
        @foreach($heroBanners as $index => $b)
        <button @click="activeSlide = {{ $index }}" type="button" class="h-2 rounded-full transition-all duration-300" :class="activeSlide === {{ $index }} ? 'w-8 bg-amber-500' : 'w-2 bg-white/50 hover:bg-white'"></button>
        @endforeach
    </div>
    @endif
</section>

{{-- ═══════════════════ §2 RUNNING TEXT / PENGUMUMAN ═══════════════════ --}}
@php
    $runningText = \App\Models\SchoolSetting::get('running_text', 'Selamat Datang di Portal Resmi Sekolah. Dapatkan Informasi Terbaru dan Berita Kegiatan Terkini di Sini.');
@endphp
<div class="bg-amber-500 text-slate-950 py-2 px-4 shadow-inner overflow-hidden flex items-center gap-3 border-b border-amber-600">
    <div class="shrink-0 flex items-center gap-1.5 px-2 py-0.5 rounded bg-slate-900 text-amber-400 font-bold text-xs uppercase tracking-wider">
        <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c.41 0 .82-.13 1.166-.375l3.88-2.772c.502-.358 1.122-.32 1.58.096.458.415.542 1.096.195 1.609L13 7.5"/></svg>
        <span>Info</span>
    </div>
    <div class="overflow-hidden whitespace-nowrap w-full">
        <div class="inline-block animate-marquee text-xs sm:text-sm font-semibold">
            {{ $runningText }}
        </div>
    </div>
</div>

{{-- ═══════════════════ §3 SAMBUTAN KEPALA SEKOLAH ═══════════════════ --}}
@php
    $kepsek = \App\Models\SchoolSetting::getGroup('kepsek');
@endphp
<section class="py-12 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            {{-- Foto Kepala Sekolah --}}
            <div class="lg:col-span-4 flex justify-center">
                <div class="relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-amber-500 to-navy rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative w-64 h-80 sm:w-72 sm:h-96 rounded-2xl overflow-hidden border-4 border-white shadow-xl bg-slate-100">
                        <img src="{{ !empty($kepsek['foto']) ? asset('storage/' . $kepsek['foto']) : 'https://ui-avatars.com/api/?name=Kepala+Sekolah&background=0e2356&color=fff&size=256' }}" 
                             alt="Kepala Sekolah" 
                             class="w-full h-full object-cover object-top hover:scale-105 transition duration-500">
                    </div>
                </div>
            </div>

            {{-- Pesan Sambutan --}}
            <div class="lg:col-span-8 space-y-4">
                <div class="chip">Sambutan Kepala Sekolah</div>
                <h2 class="text-xl sm:text-3xl font-bold font-lora text-slate-900">
                    {{ $kepsek['judul_sambutan'] ?? 'Selamat Datang di Website Resmi Sekolah' }}
                </h2>
                <div class="gold-bar"><span></span><span></span></div>
                
                <div class="text-xs sm:text-sm text-slate-600 space-y-3 leading-relaxed text-justify">
                    {!! nl2br(e($kepsek['sambutan'] ?? 'Kami berkomitmen untuk terus meningkatkan kualitas pendidikan, membentuk karakter siswa yang mandiri, berilmu, dan bertakwa. Website ini hadir sebagai wadah informasi dan komunikasi terbuka antara sekolah, orang tua, alumni, dan masyarakat luas.')) !!}
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <h4 class="text-sm sm:text-base font-bold text-navy">
                        {{ $kepsek['nama'] ?? 'Nama Kepala Sekolah, M.Pd.' }}
                    </h4>
                    <p class="text-xs text-slate-500">Kepala Sekolah {{ \App\Models\SchoolSetting::get('nama_sekolah', 'SMP Negeri Kutime') }}</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════ §4 STATISTIK SEKOLAH ═══════════════════ --}}
@php
    $stats = \App\Models\SchoolSetting::getGroup('statistik');
@endphp
<section class="py-10 bg-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            
            <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50 backdrop-blur-sm">
                <div class="text-2xl sm:text-4xl font-extrabold text-amber-400 font-lora mb-1">
                    {{ $stats['jumlah_siswa'] ?? '500+' }}
                </div>
                <div class="text-xs sm:text-sm text-slate-300 font-medium">Siswa Active</div>
            </div>

            <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50 backdrop-blur-sm">
                <div class="text-2xl sm:text-4xl font-extrabold text-amber-400 font-lora mb-1">
                    {{ $stats['jumlah_guru'] ?? '35+' }}
                </div>
                <div class="text-xs sm:text-sm text-slate-300 font-medium">Guru & Tendik</div>
            </div>

            <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50 backdrop-blur-sm">
                <div class="text-2xl sm:text-4xl font-extrabold text-amber-400 font-lora mb-1">
                    {{ $stats['jumlah_rombel'] ?? '18' }}
                </div>
                <div class="text-xs sm:text-sm text-slate-300 font-medium">Rombongan Belajar</div>
            </div>

            <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50 backdrop-blur-sm">
                <div class="text-2xl sm:text-4xl font-extrabold text-amber-400 font-lora mb-1">
                    {{ $stats['akreditasi'] ?? 'A' }}
                </div>
                <div class="text-xs sm:text-sm text-slate-300 font-medium">Akreditasi Sekolah</div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════ §5 PROGRAM & EKSTRAKURIKULER ═══════════════════ --}}
@php
    $ekstras = \App\Models\Extracurricular::where('is_active', true)->get();
@endphp
@if($ekstras->count() > 0)
<section class="py-12 bg-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-8">
            <div class="chip">Ekstrakurikuler</div>
            <h2 class="text-xl sm:text-3xl font-bold font-lora mt-1 text-slate-900">Pengembangan Bakat & Minat</h2>
            <div class="gold-bar justify-center mt-1"><span></span><span></span></div>
            <p class="text-xs sm:text-sm text-slate-600 mt-2">
                Wadah kreativitas dan pembentukan karakter siswa melalui ragam kegiatan ekstrakurikuler.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($ekstras as $ekstra)
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition text-center group">
                <div class="w-12 h-12 mx-auto rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3 group-hover:bg-navy group-hover:text-white transition">
                    @if($ekstra->icon)
                        <i class="{{ $ekstra->icon }} text-xl"></i>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    @endif
                </div>
                <h3 class="text-xs sm:text-sm font-bold text-slate-800 line-clamp-1">{{ $ekstra->nama }}</h3>
                <p class="text-[10px] text-slate-500 mt-1 line-clamp-2">{{ $ekstra->pembina ?? 'Ekstrakurikuler' }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ═══════════════════ §6 BERITA & ARTIKEL TERKINI ═══════════════════ --}}
@php
    $latestNews = \App\Models\News::where('status', 'published')->latest()->take(3)->get();
@endphp
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div>
                <div class="chip">Kabar Sekolah</div>
                <h2 class="text-xl sm:text-3xl font-bold font-lora mt-1 text-slate-900">Berita & Informasi Terbaru</h2>
                <div class="gold-bar mt-1"><span></span><span></span></div>
            </div>
            <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-navy hover:text-amber-600 transition shrink-0">
                <span>Lihat Semua Berita</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($latestNews as $news)
            <article class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
                {{-- Cover Image --}}
                <a href="{{ route('news.show', $news->slug) }}" class="relative aspect-video overflow-hidden bg-slate-100">
                    <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold bg-navy/90 text-white backdrop-blur-sm">
                        {{ $news->category->name ?? 'Informasi' }}
                    </span>
                </a>
                
                {{-- Content Body --}}
                <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[11px] text-slate-400 mb-2 flex items-center gap-2">
                            <span>{{ $news->created_at->format('d M Y') }}</span>
                            <span>•</span>
                            <span>Oleh {{ $news->user->name ?? 'Admin' }}</span>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-navy transition line-clamp-2 mb-2">
                            <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mb-4">
                            {{ Str::limit(strip_tags($news->content), 120) }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <a href="{{ route('news.show', $news->slug) }}" class="text-xs font-bold text-amber-600 hover:text-navy transition inline-flex items-center gap-1">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-3 text-center py-8 text-xs text-slate-400">
                Belum ada berita terbaru yang diterbitkan.
            </div>
            @endforelse
        </div>

    </div>
</section>

{{-- ═══════════════════ §7 GALERI FOTO TERKINI ═══════════════════ --}}
@php
    $latestGalleries = \App\Models\Gallery::latest()->take(6)->get();
@endphp
@if($latestGalleries->count() > 0)
<section class="py-12 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div>
                <div class="chip">Dokumentasi</div>
                <h2 class="text-xl sm:text-3xl font-bold font-lora mt-1 text-slate-900">Galeri Kegiatan Sekolah</h2>
                <div class="gold-bar mt-1"><span></span><span></span></div>
            </div>
            <a href="{{ route('galleries.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-navy hover:text-amber-600 transition shrink-0">
                <span>Lihat Galeri Lengkap</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($latestGalleries as $gallery)
            <div class="relative group aspect-square rounded-xl overflow-hidden bg-slate-200 shadow-sm border border-slate-200">
                <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 p-3 flex items-end">
                    <p class="text-[11px] font-semibold text-white line-clamp-2 leading-tight">
                        {{ $gallery->title }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ═══════════════════ §8 SESI KOMENTAR & MASUKAN PUBLIK ═══════════════════ --}}
@php
    $publicComments = \App\Models\Comment::where('is_active', true)->latest()->get();
@endphp

<section class="bg-white py-8 lg:py-12 border-t border-slate-200" id="sesi-komentar" x-data="{ showForm: false }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header --}}
        <div class="sec-head text-center max-w-2xl mx-auto mb-6">
            <div class="chip">Apresiasi & Masukan</div>
            <h2 class="text-lg sm:text-2xl font-bold font-lora mt-1 text-slate-900">Masukan Anda Sangat Membantu Kami</h2>
            <div class="gold-bar justify-center mt-1"><span></span><span></span></div>
            <p class="text-xs sm:text-sm text-slate-600 mt-2">
                Masukan Anda akan sangat membantu kami dalam pengembangan website resmi {{ \App\Models\SchoolSetting::get('singkatan','SMPN Kutime') }} untuk kedepannya.
            </p>

            {{-- Tombol untuk Menampilkan Form Komentar --}}
            <button @click="showForm = !showForm" 
                    type="button"
                    class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white shadow-md hover:shadow-lg transition-all"
                    style="background:#0e2356">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span x-text="showForm ? 'Tutup Form Komentar' : 'Tulis Komentar / Masukan'"></span>
            </button>
        </div>

        {{-- Alert Sukses Pengiriman --}}
        @if(session('success_comment'))
        <div class="max-w-xl mx-auto mb-6 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success_comment') }}</span>
        </div>
        @endif

        {{-- Form Tulis Komentar (Hidden by default) --}}
        <div x-show="showForm" x-transition class="max-w-xl mx-auto bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-6 mb-8 shadow-sm">
            <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Komentar / Masukan <span class="text-red-500">*</span></label>
                    <textarea name="komentar" rows="3" required placeholder="Tulis masukan Anda untuk sekolah kami..."
                              class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama (Opsional)</label>
                        <input type="text" name="nama" placeholder="Biarkan kosong jika tanpa nama"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:border-navy">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Foto Profil (Opsional)</label>
                        <input type="file" name="foto" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
                    </div>
                </div>

                <div class="text-right pt-2">
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white shadow hover:bg-slate-800 transition" style="background:#0e2356">
                        Kirim Komentar
                    </button>
                </div>
            </form>
        </div>

        {{-- Slider Daftar Komentar User --}}
        @if($publicComments->count() > 0)
        <div x-data="{ 
            active: 0, 
            total: {{ $publicComments->count() }},
            next() { this.active = (this.active + 1) % this.total },
            prev() { this.active = (this.active - 1 + this.total) % this.total }
        }" class="relative max-w-2xl mx-auto px-4 py-6 bg-slate-50 rounded-2xl border border-slate-200/80">
            
            <div class="overflow-hidden relative min-h-[130px] flex items-center">
                @foreach($publicComments as $idx => $c)
                <div x-show="active === {{ $idx }}" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 -translate-x-4"
                     class="w-full flex flex-col items-center text-center">
                    
                    <img src="{{ $c->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-amber-400 mb-3 shadow-sm">
                    
                    <p class="text-xs sm:text-sm text-slate-700 italic max-w-lg mb-2">
                        "{{ $c->komentar }}"
                    </p>
                    
                    <h4 class="text-xs font-bold text-navy">
                        {{ $c->nama ?? 'Unknown' }}
                    </h4>
                    <span class="text-[10px] text-slate-400">{{ $c->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>

            {{-- Navigasi Titik-Titik (Pagination Dots) --}}
            @if($publicComments->count() > 1)
            <div class="flex items-center justify-center gap-1.5 mt-4">
                <button @click="prev()" type="button" class="p-1 text-slate-400 hover:text-navy transition" aria-label="Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                
                @foreach($publicComments as $idx => $c)
                <button @click="active = {{ $idx }}" 
                        type="button" 
                        aria-label="Slide {{ $idx + 1 }}"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="active === {{ $idx }} ? 'w-6 bg-amber-500' : 'w-2 bg-slate-300 hover:bg-slate-400'"></button>
                @endforeach

                <button @click="next()" type="button" class="p-1 text-slate-400 hover:text-navy transition" aria-label="Berikutnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            @endif

        </div>
        @else
        <div class="text-center py-6 text-xs text-slate-400 italic">
            Belum ada komentar. Jadilah yang pertama memberikan masukan!
        </div>
        @endif

    </div>
</section>

@endsection
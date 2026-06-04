{{--
    resources/views/admin/kelola-website/index.blade.php
    ─────────────────────────────────────────────────────
    File ini adalah SATU halaman admin kelola website yang menggabungkan
    semua tab: home, identitas, kontak.
    Alpine.js diperlukan untuk tab switching dan form interaktif.
--}}

@extends('layouts.app')
@section('title', 'Kelola Website')

@section('content')
<div x-data="{ tab: '{{ $tab ?? 'home' }}' }" class="min-h-screen" style="background:#f1f5f9">

    {{-- ══════════════════════════════════════════════════════════
         TOP BAR — Judul + Tab Navigator
    ══════════════════════════════════════════════════════════ --}}
    <div class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Baris atas: judul + tombol buka website --}}
            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                         style="background:linear-gradient(135deg,#4f46e5,#6366f1)">🌐</div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-800 leading-tight">Kelola Website</h1>
                        <p class="text-xs text-slate-400 leading-none mt-0.5">Atur tampilan & konten website resmi sekolah</p>
                    </div>
                </div>
                <a href="{{ route('website.home') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition hover:opacity-90"
                   style="background:#059669">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka Website
                </a>
            </div>

            {{-- Tab bar --}}
            <div class="flex items-center gap-1 py-1 overflow-x-auto scrollbar-none">
                @php
                $tabItems = [
                    ['key' => 'home',      'icon' => '🏠', 'label' => 'Beranda',   'color' => 'indigo'],
                    ['key' => 'identitas', 'icon' => '🏫', 'label' => 'Identitas', 'color' => 'amber'],
                    ['key' => 'kontak',    'icon' => '📞', 'label' => 'Kontak',    'color' => 'teal'],
                    ['key' => 'berita',    'icon' => '📰', 'label' => 'Berita',    'color' => 'blue'],
                    ['key' => 'galeri',    'icon' => '🖼️', 'label' => 'Galeri',    'color' => 'violet'],
                ];
                @endphp

                @foreach($tabItems as $t)
                <button
                    @click="tab = '{{ $t['key'] }}'; history.replaceState(null,'','?tab={{ $t['key'] }}')"
                    class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-all"
                    :class="tab === '{{ $t['key'] }}'
                        ? 'bg-{{ $t['color'] }}-50 text-{{ $t['color'] }}-700 shadow-sm'
                        : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'">
                    <span>{{ $t['icon'] }}</span>
                    {{ $t['label'] }}
                    @if($t['key'] === 'berita')
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-xs font-bold"
                          :class="tab === 'berita' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500'">
                        {{ $beritaStats['total'] ?? 0 }}
                    </span>
                    @elseif($t['key'] === 'galeri')
                    <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-xs font-bold"
                          :class="tab === 'galeri' ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-500'">
                        {{ $galeriStats['total'] ?? 0 }}
                    </span>
                    @endif
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         KONTEN SEMUA TAB
    ══════════════════════════════════════════════════════════ --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-5">

        {{-- Flash success/error global --}}
        @if(session('success'))
        <div class="flex items-center gap-2 px-4 py-2.5 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-2 px-4 py-2.5 mb-4 bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Validation errors global --}}
        @if($errors->any())
        <div class="px-4 py-3 mb-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-xs font-bold text-red-700 mb-1">Terdapat kesalahan:</p>
            <ul class="text-xs text-red-600 list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        {{-- ════════════════════════════════════════════════════
             TAB: HOME (BERANDA)
             Section 1–6: Hero Media, Teks Hero, Statistik,
             Tentang/Visi/Misi, Fasilitas, Sambutan & Info
        ════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'home'" x-cloak class="space-y-4">

            @php
                $hm         = $heroMedia;
                $hmTipe     = $hm?->hero_media_tipe ?? 'none';
                $hmFiles    = $hm?->heroFilesArray  ?? [];
                $hmFileUrls = $hm?->heroFilesUrls   ?? [];
                $hmLink     = $hm?->hero_media_link ?? '';
                $hmInterval = $hm?->hero_slide_interval ?? 4000;
                $valTentang = $contents['tentang']->value ?? '';
                $valVisi    = $contents['visi']->value    ?? '';
                $valMisi    = $contents['misi']->value    ?? '';
                $statsRow   = $stats;
                $sambFotoPath = $contents['sambutan_foto_path']->value ?? null;
            @endphp

            {{-- ── Quick nav section ──────────────────────────────── --}}
            <div class="flex flex-wrap gap-2 p-3 bg-white rounded-xl border border-slate-200 shadow-sm">
                <span class="text-xs font-semibold text-slate-500 self-center mr-1">Lompat ke:</span>
                @foreach([
                    ['id'=>'sec-hero-media', 'label'=>'Media Hero',  'color'=>'indigo'],
                    ['id'=>'sec-teks-hero',  'label'=>'Teks Hero',   'color'=>'slate'],
                    ['id'=>'sec-statistik',  'label'=>'Statistik',   'color'=>'cyan'],
                    ['id'=>'sec-tentang',    'label'=>'Tentang/Visi/Misi', 'color'=>'blue'],
                    ['id'=>'sec-fasilitas',  'label'=>'Fasilitas',   'color'=>'violet'],
                    ['id'=>'sec-sambutan',   'label'=>'Sambutan',    'color'=>'pink'],
                ] as $nav)
                <a href="#{{ $nav['id'] }}"
                   class="px-2.5 py-1 rounded-lg text-xs font-semibold transition hover:opacity-80"
                   style="background:rgba(79,70,229,.07);color:#4338ca">
                    {{ $nav['label'] }}
                </a>
                @endforeach
            </div>

            {{-- ══════════════════════════════════════════════
                 §1 MEDIA HERO
            ══════════════════════════════════════════════ --}}
            <div id="sec-hero-media"
                 class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
                 x-data="heroAdmin('{{ $hmTipe }}', {{ $hmInterval }})">

                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">1</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Latar Belakang Hero</p>
                        <p class="text-xs text-slate-400 leading-none mt-0.5">Gambar, video, YouTube, atau slideshow</p>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            @if($hmTipe==='none') bg-slate-100 text-slate-500
                            @elseif($hmTipe==='image') bg-blue-100 text-blue-700
                            @elseif($hmTipe==='video') bg-purple-100 text-purple-700
                            @elseif($hmTipe==='youtube') bg-red-100 text-red-700
                            @else bg-indigo-100 text-indigo-700 @endif">
                            {{ ['none'=>'Warna Saja','image'=>'Gambar','video'=>'Video','youtube'=>'YouTube','slideshow'=>'Slideshow'][$hmTipe] }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.kelola-website.update-hero-media') }}"
                      enctype="multipart/form-data" class="p-5 space-y-3">
                    @csrf
                    <input type="hidden" name="hero_media_tipe" :value="heroTipe">

                    {{-- Pilih tipe --}}
                    <div class="grid grid-cols-5 gap-1.5">
                        @foreach(['none'=>['🚫','Tanpa Media'],'image'=>['🖼️','Gambar'],'video'=>['🎥','Video'],'youtube'=>['▶️','YouTube'],'slideshow'=>['🎞️','Slideshow']] as $val=>[$ico,$lbl])
                        <label class="flex flex-col items-center gap-1 p-2.5 border-2 rounded-xl cursor-pointer transition-all"
                               :class="heroTipe==='{{ $val }}' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300 bg-white'">
                            <input type="radio" value="{{ $val }}" class="sr-only" x-model="heroTipe">
                            <span class="text-lg leading-none">{{ $ico }}</span>
                            <span class="text-xs leading-tight text-center"
                                  :class="heroTipe==='{{ $val }}' ? 'text-indigo-700 font-semibold' : 'text-slate-500'">{{ $lbl }}</span>
                        </label>
                        @endforeach
                    </div>

                    {{-- Gambar --}}
                    <template x-if="heroTipe === 'image'">
                        <div class="space-y-2">
                            @if($hmTipe==='image' && !empty($hmFileUrls))
                            <div class="relative rounded-xl overflow-hidden border border-slate-200" style="height:110px">
                                <img src="{{ $hmFileUrls[0] }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/25 flex items-center justify-center">
                                    <span class="text-white text-xs bg-black/50 px-2 py-1 rounded-lg">Gambar saat ini</span>
                                </div>
                            </div>
                            <input type="hidden" name="keep_files[]" value="{{ $hmFiles[0] ?? '' }}">
                            @endif
                            <label class="block text-xs font-semibold text-slate-600 mb-0.5">Upload Gambar Baru</label>
                            <input type="file" name="hero_files[]" accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"
                                   @change="prev=$event.target.files[0]?URL.createObjectURL($event.target.files[0]):null">
                            <template x-if="prev">
                                <div class="rounded-xl overflow-hidden border border-indigo-200" style="height:90px">
                                    <img :src="prev" class="w-full h-full object-cover">
                                </div>
                            </template>
                            <p class="text-xs text-slate-400">Format: JPG, PNG, WEBP. Maks 100 MB. Resolusi minimal 1280×720px.</p>
                        </div>
                    </template>

                    {{-- Video --}}
                    <template x-if="heroTipe === 'video'">
                        <div class="space-y-2">
                            @if($hmTipe==='video' && !empty($hmFileUrls))
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-200">
                                <p class="text-xs text-slate-500 mb-1.5">Video saat ini:</p>
                                <video src="{{ $hmFileUrls[0] }}" controls muted class="w-full rounded-lg" style="max-height:90px"></video>
                                <input type="hidden" name="keep_files[]" value="{{ $hmFiles[0] ?? '' }}">
                            </div>
                            @endif
                            <label class="block text-xs font-semibold text-slate-600 mb-0.5">Upload Video Baru</label>
                            <input type="file" name="hero_files[]" accept="video/mp4,video/webm,video/quicktime"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-600 hover:file:bg-purple-100">
                            <p class="text-xs text-slate-400">Format: MP4, WEBM, MOV. Maks 200 MB. Video akan diputar otomatis tanpa suara.</p>
                        </div>
                    </template>

                    {{-- YouTube --}}
                    <template x-if="heroTipe === 'youtube'">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-0.5">URL Video YouTube</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-red-400 text-sm">▶️</span>
                                <input type="url" name="hero_media_link"
                                       value="{{ $hmTipe==='youtube' ? $hmLink : '' }}"
                                       class="w-full rounded-xl border-slate-300 text-xs py-2 pl-9 focus:border-red-400 focus:ring-red-300"
                                       placeholder="https://www.youtube.com/watch?v=..."
                                       @input="ytThumb($event.target.value)"
                                       x-init="$nextTick(()=>{ if($el.value) ytThumb($el.value) })">
                            </div>
                            <template x-if="yt">
                                <div class="relative rounded-xl overflow-hidden border border-red-100" style="height:90px">
                                    <img :src="yt" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                        <div class="w-9 h-9 bg-red-600/90 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p class="text-xs text-slate-400">Video YouTube akan diputar otomatis, muted, dan loop di background hero.</p>
                        </div>
                    </template>

                    {{-- Slideshow --}}
                    <template x-if="heroTipe === 'slideshow'">
                        <div class="space-y-2.5">
                            @if($hmTipe==='slideshow' && !empty($hmFiles))
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                                <p class="text-xs font-semibold text-slate-600 mb-2">Foto aktif — centang untuk pertahankan:</p>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach($hmFiles as $i => $path)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/'.$path) }}"
                                             class="w-full aspect-video object-cover rounded-lg border border-slate-200">
                                        <label class="absolute inset-0 flex items-end justify-end p-1 cursor-pointer">
                                            <input type="checkbox" name="keep_files[]" value="{{ $path }}" checked
                                                   class="w-4 h-4 rounded text-indigo-600 shadow">
                                        </label>
                                        <span class="absolute top-1 left-1 text-white text-xs bg-black/60 rounded px-1 leading-tight font-bold">{{ $i+1 }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Tambah Foto Baru (maks 10 foto total)</label>
                                <input type="file" name="hero_files[]" accept="image/*" multiple
                                       class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"
                                       @change="slides=Array.from($event.target.files).map(f=>URL.createObjectURL(f))">
                            </div>
                            <template x-if="slides.length > 0">
                                <div>
                                    <p class="text-xs text-slate-500 mb-1.5">Pratinjau foto baru:</p>
                                    <div class="grid grid-cols-5 gap-1.5">
                                        <template x-for="s in slides" :key="s">
                                            <img :src="s" class="w-full aspect-video object-cover rounded-lg border-2 border-indigo-300">
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                <span class="text-xs text-slate-500 shrink-0 font-semibold">⏱ Interval:</span>
                                <input type="range" name="hero_slide_interval" min="1000" max="10000" step="500"
                                       :value="interval" x-model="interval" class="flex-1 accent-indigo-600">
                                <span class="text-xs font-bold text-indigo-600 w-14 text-right"
                                      x-text="(interval/1000).toFixed(1)+' detik'"></span>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-end pt-1">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Media Hero
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════════════
                 §2 TEKS HERO
            ══════════════════════════════════════════════ --}}
            <div id="sec-teks-hero" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">2</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Teks Hero</p>
                        <p class="text-xs text-slate-400 mt-0.5">Judul besar & tagline yang tampil di atas foto hero</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.kelola-website.update-home') }}" class="p-5 space-y-3">
                    @csrf @method('PATCH')
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Judul / Nama Sekolah di Hero
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="hero_title"
                                   value="{{ old('hero_title', $contents['hero_title']->value ?? 'SMP Negeri Kutime') }}"
                                   class="w-full rounded-xl border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="SMP Negeri Kutime" required>
                            <p class="text-xs text-slate-400 mt-1">Tampil sebagai heading utama di hero</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Tagline / Deskripsi Singkat</label>
                            <input type="text" name="hero_description"
                                   value="{{ old('hero_description', $contents['hero_description']->value ?? '') }}"
                                   class="w-full rounded-xl border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Tagline singkat dan menarik...">
                            <p class="text-xs text-slate-400 mt-1">Kalimat pendek di bawah judul hero</p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-700 text-white text-xs font-semibold rounded-xl hover:bg-slate-800 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Teks Hero
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════════════
                 §3 STATISTIK SEKOLAH
            ══════════════════════════════════════════════ --}}
            <div id="sec-statistik" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-bold">3</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Statistik Sekolah</p>
                        <p class="text-xs text-slate-400 mt-0.5">Angka yang tampil di bar bawah hero</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.kelola-website.update-stats') }}" class="p-5">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        @foreach([
                            ['key'=>'stat_siswa',    'icon'=>'👨‍🎓','label'=>'Siswa Aktif',  'ph'=>'480+'],
                            ['key'=>'stat_guru',     'icon'=>'👩‍🏫','label'=>'Tenaga Didik', 'ph'=>'32'],
                            ['key'=>'stat_prestasi', 'icon'=>'🏆', 'label'=>'Prestasi',      'ph'=>'50+'],
                            ['key'=>'stat_ekskul',   'icon'=>'⭐', 'label'=>'Ekskul',         'ph'=>'12'],
                        ] as $s)
                        <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                            <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 mb-1.5">
                                <span class="text-base">{{ $s['icon'] }}</span>{{ $s['label'] }}
                            </label>
                            <input type="text" name="{{ $s['key'] }}"
                                   value="{{ old($s['key'], $statsRow?->{$s['key']} ?? '') }}"
                                   class="w-full rounded-lg border-slate-300 text-sm py-1.5 focus:border-cyan-500 focus:ring-cyan-400 bg-white"
                                   placeholder="{{ $s['ph'] }}">
                        </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-400">Kosongkan field yang tidak ingin ditampilkan.</p>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-cyan-600 text-white text-xs font-semibold rounded-xl hover:bg-cyan-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Statistik
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════════════
                 §4 TENTANG + VISI + MISI (Quill editor)
            ══════════════════════════════════════════════ --}}
            <div id="sec-tentang" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">4</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Tentang, Visi & Misi</p>
                        <p class="text-xs text-slate-400 mt-0.5">Rich-text — gunakan toolbar untuk format teks</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.kelola-website.update-home') }}"
                      id="form-tentang-visi-misi" class="p-5 space-y-4">
                    @csrf @method('PATCH')
                    <input type="hidden" name="tentang" id="input-tentang">
                    <input type="hidden" name="visi"    id="input-visi">
                    <input type="hidden" name="misi"    id="input-misi">

                    {{-- Tentang --}}
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                            <label class="text-xs font-semibold text-slate-700">Tentang Sekolah</label>
                            <span class="text-xs text-slate-400">— paragraf atau beberapa kalimat</span>
                        </div>
                        <div id="toolbar-tentang"
                             class="flex flex-wrap items-center gap-1 px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-t-xl border-b-0">
                            <button type="button" class="ql-bold   p-1 rounded hover:bg-slate-200 text-xs font-bold text-slate-600" title="Bold"><b>B</b></button>
                            <button type="button" class="ql-italic p-1 rounded hover:bg-slate-200 text-xs italic text-slate-600" title="Italic"><i>I</i></button>
                            <button type="button" class="ql-underline p-1 rounded hover:bg-slate-200 text-xs underline text-slate-600" title="Underline">U</button>
                            <div class="w-px h-4 bg-slate-300 mx-0.5"></div>
                            <select class="ql-size text-xs border border-slate-200 rounded px-1 py-0.5 text-slate-600 bg-white">
                                <option value="small">Kecil</option><option selected>Normal</option>
                                <option value="large">Besar</option><option value="huge">Sangat Besar</option>
                            </select>
                            <div class="w-px h-4 bg-slate-300 mx-0.5"></div>
                            <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600" value="ordered" title="Nomor">1.</button>
                            <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600" value="bullet"  title="Bullet">•</button>
                            <div class="w-px h-4 bg-slate-300 mx-0.5"></div>
                            <select class="ql-color text-xs border border-slate-200 rounded px-1 py-0.5 text-slate-600 bg-white" title="Warna teks">
                                <option value="">Hitam</option><option value="#1d4ed8">Biru</option>
                                <option value="#15803d">Hijau</option><option value="#b91c1c">Merah</option>
                                <option value="#6d28d9">Ungu</option><option value="#92400e">Coklat</option>
                            </select>
                            <button type="button" class="ql-clean p-1 rounded hover:bg-slate-200 text-slate-500 text-xs ml-auto" title="Reset format">✕ Reset</button>
                        </div>
                        <div id="editor-tentang" class="border border-slate-200 rounded-b-xl text-sm text-slate-700" style="min-height:100px"></div>
                    </div>

                    {{-- Visi & Misi 2 kolom --}}
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                <label class="text-xs font-semibold text-slate-700">Visi Sekolah</label>
                            </div>
                            <div id="toolbar-visi"
                                 class="flex flex-wrap items-center gap-1 px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-t-xl border-b-0">
                                <button type="button" class="ql-bold   p-1 rounded hover:bg-slate-200 text-xs font-bold text-slate-600"><b>B</b></button>
                                <button type="button" class="ql-italic p-1 rounded hover:bg-slate-200 text-xs italic text-slate-600"><i>I</i></button>
                                <div class="w-px h-4 bg-slate-300 mx-0.5"></div>
                                <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600" value="ordered">1.</button>
                                <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600" value="bullet">•</button>
                                <div class="w-px h-4 bg-slate-300 mx-0.5"></div>
                                <select class="ql-color text-xs border border-slate-200 rounded px-1 py-0.5 text-slate-600 bg-white">
                                    <option value="">Hitam</option><option value="#1d4ed8">Biru</option>
                                    <option value="#15803d">Hijau</option><option value="#b91c1c">Merah</option>
                                </select>
                                <button type="button" class="ql-clean p-1 rounded hover:bg-slate-200 text-slate-500 text-xs ml-auto">✕</button>
                            </div>
                            <div id="editor-visi" class="border border-slate-200 rounded-b-xl text-sm text-slate-700" style="min-height:90px"></div>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                <label class="text-xs font-semibold text-slate-700">Misi Sekolah</label>
                            </div>
                            <div id="toolbar-misi"
                                 class="flex flex-wrap items-center gap-1 px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-t-xl border-b-0">
                                <button type="button" class="ql-bold   p-1 rounded hover:bg-slate-200 text-xs font-bold text-slate-600"><b>B</b></button>
                                <button type="button" class="ql-italic p-1 rounded hover:bg-slate-200 text-xs italic text-slate-600"><i>I</i></button>
                                <div class="w-px h-4 bg-slate-300 mx-0.5"></div>
                                <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600" value="ordered">1.</button>
                                <button type="button" class="ql-list p-1 rounded hover:bg-slate-200 text-slate-600" value="bullet">•</button>
                                <div class="w-px h-4 bg-slate-300 mx-0.5"></div>
                                <select class="ql-color text-xs border border-slate-200 rounded px-1 py-0.5 text-slate-600 bg-white">
                                    <option value="">Hitam</option><option value="#1d4ed8">Biru</option>
                                    <option value="#15803d">Hijau</option><option value="#b91c1c">Merah</option>
                                </select>
                                <button type="button" class="ql-clean p-1 rounded hover:bg-slate-200 text-slate-500 text-xs ml-auto">✕</button>
                            </div>
                            <div id="editor-misi" class="border border-slate-200 rounded-b-xl text-sm text-slate-700" style="min-height:90px"></div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-1">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Tentang, Visi & Misi
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════════════
                 §5 FASILITAS
            ══════════════════════════════════════════════ --}}
            <div id="sec-fasilitas" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-violet-100 text-violet-600 flex items-center justify-center text-xs font-bold">5</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Fasilitas Sekolah</p>
                        <p class="text-xs text-slate-400 mt-0.5">Keterangan singkat tiap fasilitas di website</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.kelola-website.update-home') }}" class="p-5">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        @foreach([
                            ['key'=>'fasilitas_ruang_kelas',  'icon'=>'🏫','label'=>'Ruang Kelas'],
                            ['key'=>'fasilitas_perpustakaan', 'icon'=>'📚','label'=>'Perpustakaan'],
                            ['key'=>'fasilitas_lapangan',     'icon'=>'⚽','label'=>'Lapangan'],
                            ['key'=>'fasilitas_laboratorium', 'icon'=>'🖥️','label'=>'Laboratorium'],
                        ] as $f)
                        <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                            <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 mb-1.5">
                                <span>{{ $f['icon'] }}</span>{{ $f['label'] }}
                            </label>
                            <input type="text" name="{{ $f['key'] }}"
                                   value="{{ old($f['key'], $contents[$f['key']]->value ?? '') }}"
                                   class="w-full rounded-lg border-slate-300 text-xs py-1.5 focus:border-violet-500 focus:ring-violet-400 bg-white"
                                   placeholder="Keterangan singkat...">
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-violet-600 text-white text-xs font-semibold rounded-xl hover:bg-violet-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Fasilitas
                        </button>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════════════
                 §6 SAMBUTAN + INFO PENTING
                 Foto hapus sambutan: form TERPISAH di atas form utama
                 agar tidak terjadi form bersarang.
            ══════════════════════════════════════════════ --}}
            <div id="sec-sambutan" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold">6</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Sambutan & Info Penting</p>
                        <p class="text-xs text-slate-400 mt-0.5">Kata sambutan kepala sekolah, PPDB, kalender akademik</p>
                    </div>
                </div>

                {{-- ── §6-A  FOTO SAMBUTAN AKTIF — form hapus TERPISAH (di luar form utama) ── --}}
                @if($sambFotoPath)
                <div class="mx-5 mt-4 flex items-center gap-3 p-3 bg-pink-50 rounded-xl border border-pink-100">
                    <img src="{{ asset('storage/'.$sambFotoPath) }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-pink-200 shrink-0"
                         alt="Foto sambutan">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-pink-800">Foto kepala sekolah aktif</p>
                        <p class="text-xs text-pink-400 truncate">{{ basename($sambFotoPath) }}</p>
                    </div>
                    {{-- Form hapus foto — MANDIRI, tidak di dalam form update-home --}}
                    <form method="POST"
                          action="{{ route('admin.kelola-website.delete-sambutan-foto') }}"
                          onsubmit="return confirm('Hapus foto kepala sekolah?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 text-xs font-semibold rounded-lg hover:bg-red-100 transition">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Foto
                        </button>
                    </form>
                </div>
                @endif

                {{-- ── §6-B  FORM UTAMA — tidak ada form bersarang di dalamnya ── --}}
                <form method="POST" action="{{ route('admin.kelola-website.update-home') }}"
                      enctype="multipart/form-data" class="p-5 {{ $sambFotoPath ? 'pt-3' : '' }}">
                    @csrf @method('PATCH')
                    <div class="grid md:grid-cols-2 gap-5 mb-4">

                        {{-- Sambutan --}}
                        <div class="space-y-2.5">
                            <p class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-pink-500 shrink-0"></span>
                                Sambutan Kepala Sekolah
                            </p>

                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Nama Kepala Sekolah</label>
                                <input type="text" name="sambutan_nama"
                                       value="{{ old('sambutan_nama', $contents['sambutan_nama']->value ?? '') }}"
                                       class="w-full rounded-xl border-slate-300 text-xs py-1.5 focus:border-pink-400 focus:ring-pink-300"
                                       placeholder="Nama Lengkap Kepala Sekolah">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Jabatan</label>
                                <input type="text" name="sambutan_jabatan"
                                       value="{{ old('sambutan_jabatan', $contents['sambutan_jabatan']->value ?? 'Kepala Sekolah') }}"
                                       class="w-full rounded-xl border-slate-300 text-xs py-1.5 focus:border-pink-400 focus:ring-pink-300"
                                       placeholder="Kepala Sekolah">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">Teks Sambutan</label>
                                <textarea name="sambutan_teks" rows="4"
                                          class="w-full rounded-xl border-slate-300 text-xs focus:border-pink-400 focus:ring-pink-300"
                                          placeholder="Isi sambutan singkat...">{{ old('sambutan_teks', $contents['sambutan_teks']->value ?? '') }}</textarea>
                            </div>

                            {{-- Upload foto baru — tanpa tombol hapus (ada di §6-A di atas) --}}
                            <div>
                                <label class="block text-xs text-slate-500 mb-1.5">
                                    {{ $sambFotoPath ? 'Ganti Foto Kepala Sekolah' : 'Upload Foto Kepala Sekolah' }}
                                </label>
                                @if(!$sambFotoPath)
                                <div class="flex items-center gap-2 p-2.5 bg-slate-50 rounded-xl border border-dashed border-slate-300 mb-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-lg shrink-0">👤</div>
                                    <p class="text-xs text-slate-400">Belum ada foto kepala sekolah.</p>
                                </div>
                                @endif
                                <input type="file" name="sambutan_foto" accept="image/*"
                                       class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100">
                                <p class="text-xs text-slate-400 mt-1">JPG/PNG/WEBP, maks 2 MB. Rasio 1:1 disarankan.</p>
                            </div>
                        </div>

                        {{-- Info Penting --}}
                        <div class="space-y-3">
                            <p class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                                Info Penting
                            </p>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">
                                    📢 PPDB — Penerimaan Siswa Baru
                                </label>
                                <textarea name="info_ppdb" rows="4"
                                          class="w-full rounded-xl border-slate-300 text-xs focus:border-red-400 focus:ring-red-300"
                                          placeholder="Informasi PPDB, syarat pendaftaran, jadwal, dll...">{{ old('info_ppdb', $contents['info_ppdb']->value ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-500 mb-1">
                                    📅 Kalender Akademik
                                </label>
                                <textarea name="info_kalender" rows="4"
                                          class="w-full rounded-xl border-slate-300 text-xs focus:border-blue-400 focus:ring-blue-300"
                                          placeholder="Jadwal semester, ujian, libur, dll...">{{ old('info_kalender', $contents['info_kalender']->value ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-slate-100">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-pink-600 text-white text-xs font-semibold rounded-xl hover:bg-pink-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Sambutan & Info
                        </button>
                    </div>
                </form>
            </div>

            {{-- Pratinjau --}}
            <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                <div>
                    <p class="text-xs font-semibold text-emerald-800">Lihat hasil di website</p>
                    <p class="text-xs text-emerald-600 mt-0.5">Semua perubahan akan langsung tampil di halaman beranda.</p>
                </div>
                <a href="{{ route('website.home') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-xl hover:bg-emerald-700 transition shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka Beranda Website
                </a>
            </div>

        </div>{{-- /tab home --}}


        {{-- ════════════════════════════════════════════════════
             TAB: IDENTITAS SEKOLAH
             Logo, Favicon, Nama Sekolah, Singkatan, Tagline
        ════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'identitas'" x-cloak class="space-y-4">

            @php
                $logoUrl       = \App\Models\SchoolSetting::logoUrl();
                $faviconUrl    = \App\Models\SchoolSetting::faviconUrl();
                $namaSekolah   = \App\Models\SchoolSetting::get('nama_sekolah',   'SMP Negeri Kutime');
                $singkatan     = \App\Models\SchoolSetting::get('singkatan',      'SMPN Kutime');
                $taglineFooter = \App\Models\SchoolSetting::get('tagline_footer', 'Menyiapkan generasi unggul, berakhlak, dan berprestasi.');
            @endphp

            {{-- ══════════════════════════════════════════════
                 §A  LOGO AKTIF — form hapus TERPISAH di luar form upload
                 HTML tidak memperbolehkan <form> di dalam <form>.
                 Jika form hapus diletakkan di dalam form upload,
                 browser akan mengabaikan form dalam dan men-submit
                 form luar dengan _method=DELETE → error 405.
                 Solusi: form hapus logo berdiri sendiri di sini,
                 sebelum form upload dimulai.
            ══════════════════════════════════════════════ --}}
            @if($logoUrl)
            <div class="bg-white rounded-xl border border-amber-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-amber-50 border-b border-amber-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <p class="text-xs font-semibold text-amber-900">Logo Sekolah Aktif</p>
                    <span class="ml-auto text-xs text-amber-500">Tampil di navbar & footer</span>
                </div>
                <div class="p-5 flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center p-2 shrink-0">
                        <img src="{{ $logoUrl }}" alt="Logo aktif" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">Logo terpasang</p>
                        <p class="text-xs text-slate-400 mt-0.5">Upload logo baru di form di bawah untuk mengganti.</p>
                    </div>
                    {{-- Form hapus logo — MANDIRI, tidak bersarang di form lain --}}
                    <form method="POST"
                          action="{{ route('admin.kelola-website.delete-logo') }}"
                          onsubmit="return confirm('Hapus logo sekolah? Navbar & footer akan kembali menampilkan ikon default.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 text-red-600 border border-red-200 text-xs font-semibold rounded-xl hover:bg-red-100 transition">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Logo
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- ══════════════════════════════════════════════
                 §B  FORM UPLOAD + IDENTITAS — form utama
                 Tidak ada <form> bersarang di dalam ini.
                 Tombol hapus logo ada di §A (di atas, form terpisah).
            ══════════════════════════════════════════════ --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-amber-100 text-amber-600 flex items-center justify-center text-sm">🏫</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">{{ $logoUrl ? 'Ganti Logo, Favicon & Identitas' : 'Upload Logo, Favicon & Identitas' }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Tampil di navbar, footer, dan tab browser di seluruh halaman</p>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('admin.kelola-website.update-school-settings') }}"
                      enctype="multipart/form-data"
                      class="p-5 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid sm:grid-cols-2 gap-5">

                        {{-- Upload Logo Baru --}}
                        <div x-data="{ prev: null }" class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-700">
                                {{ $logoUrl ? 'Ganti Logo Sekolah' : 'Upload Logo Sekolah' }}
                                <span class="text-slate-400 font-normal ml-1">(PNG/JPG/WEBP · maks 2 MB)</span>
                            </label>

                            {{-- Info logo saat ini — TANPA tombol hapus (tombol hapus ada di §A di atas) --}}
                            @if($logoUrl)
                            <div class="flex items-center gap-2.5 p-2.5 bg-slate-50 rounded-xl border border-slate-200">
                                <img src="{{ $logoUrl }}" class="h-8 w-8 object-contain rounded-lg bg-white p-0.5 border border-slate-200 shrink-0" alt="Logo saat ini">
                                <p class="text-xs text-slate-500">Logo aktif · upload baru untuk mengganti</p>
                            </div>
                            @else
                            <div class="flex items-center gap-2.5 p-2.5 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-lg shrink-0">🏫</div>
                                <p class="text-xs text-slate-400">Belum ada logo — upload di bawah.</p>
                            </div>
                            @endif

                            {{-- Live preview sebelum submit --}}
                            <template x-if="prev">
                                <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-xl border border-indigo-200">
                                    <div class="w-14 h-14 rounded-xl bg-white border border-indigo-200 flex items-center justify-center p-1.5 shrink-0">
                                        <img :src="prev" class="w-full h-full object-contain">
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-indigo-700">Pratinjau logo baru</p>
                                        <p class="text-xs text-indigo-400 mt-0.5">Klik "Simpan" untuk menerapkan</p>
                                    </div>
                                </div>
                            </template>

                            <input type="file" name="logo"
                                   accept="image/png,image/jpeg,image/webp"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer"
                                   @change="prev = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                            <p class="text-xs text-slate-400">💡 PNG transparan direkomendasikan. Ukuran ≥ 128×128px.</p>
                        </div>

                        {{-- Favicon --}}
                        <div x-data="{ prev: null }" class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-700">
                                Favicon (ikon tab browser)
                                <span class="text-slate-400 font-normal ml-1">(PNG/ICO · maks 512 KB)</span>
                            </label>

                            @if($faviconUrl)
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center p-1 shrink-0">
                                    <img src="{{ $faviconUrl }}" alt="Favicon" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700">Favicon aktif</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Tampil di tab browser</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-xl shrink-0">🌐</div>
                                <p class="text-xs text-slate-400">Belum ada favicon.</p>
                            </div>
                            @endif

                            <template x-if="prev">
                                <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-xl border border-indigo-200">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-indigo-200 flex items-center justify-center p-1 shrink-0">
                                        <img :src="prev" class="w-full h-full object-contain">
                                    </div>
                                    <p class="text-xs font-semibold text-indigo-700">Pratinjau favicon baru</p>
                                </div>
                            </template>

                            <input type="file" name="favicon"
                                   accept="image/png,image/jpeg,image/x-icon,image/vnd.microsoft.icon"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
                                   @change="prev = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                            <p class="text-xs text-slate-400">💡 Ukuran optimal 32×32 atau 64×64 piksel.</p>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Nama & Singkatan --}}
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nama Sekolah Lengkap
                            </label>
                            <input type="text" name="nama_sekolah"
                                   value="{{ old('nama_sekolah', $namaSekolah) }}"
                                   class="w-full rounded-xl border-slate-300 text-sm py-2 focus:border-amber-500 focus:ring-amber-400"
                                   placeholder="SMP Negeri Kutime">
                            <p class="text-xs text-slate-400 mt-1">Tampil di title browser & meta description</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Singkatan / Nama Pendek
                            </label>
                            <input type="text" name="singkatan"
                                   value="{{ old('singkatan', $singkatan) }}"
                                   class="w-full rounded-xl border-slate-300 text-sm py-2 focus:border-amber-500 focus:ring-amber-400"
                                   placeholder="SMPN Kutime">
                            <p class="text-xs text-slate-400 mt-1">Tampil di navbar dan footer website</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tagline Footer</label>
                        <textarea name="tagline_footer" rows="2"
                                  class="w-full rounded-xl border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-400"
                                  placeholder="Menyiapkan generasi unggul...">{{ old('tagline_footer', $taglineFooter) }}</textarea>
                        <p class="text-xs text-slate-400 mt-1">Kalimat di bawah logo di footer halaman website</p>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-400">Perubahan langsung berlaku setelah disimpan.</p>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-5 py-2 bg-amber-500 text-white text-xs font-semibold rounded-xl hover:bg-amber-600 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Identitas Sekolah
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info card --}}
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-xs font-bold text-amber-800 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Petunjuk Identitas Sekolah
                </p>
                <ul class="text-xs text-amber-700 space-y-1 list-disc list-inside leading-relaxed">
                    <li>Logo tampil di <strong>navbar atas</strong> dan <strong>footer bawah</strong> semua halaman website.</li>
                    <li>Gunakan logo format <strong>PNG transparan</strong> (background transparan) untuk hasil terbaik.</li>
                    <li>Favicon adalah ikon kecil di <strong>tab browser</strong> — gunakan ukuran <strong>32×32</strong> atau <strong>64×64</strong> piksel.</li>
                    <li>Setelah update logo, tekan <strong>Ctrl+Shift+R</strong> (Windows) atau <strong>Cmd+Shift+R</strong> (Mac) untuk melihat perubahan.</li>
                    <li>Nama sekolah dan singkatan digunakan di title browser (<em>Tab name</em>) dan beberapa bagian konten website.</li>
                </ul>
            </div>

        </div>{{-- /tab identitas --}}


        {{-- ════════════════════════════════════════════════════
             TAB: KONTAK & SOSMED
        ════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'kontak'" x-cloak class="space-y-4">

            @php $kr = $kontak; @endphp

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
                    <div class="w-6 h-6 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center text-sm">📞</div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800">Kontak Sekolah</p>
                        <p class="text-xs text-slate-400 mt-0.5">Alamat, telepon, email, peta Google Maps</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.kelola-website.update-kontak') }}" class="p-5 space-y-3">
                    @csrf @method('PATCH')

                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">📞 Telepon / WhatsApp</label>
                            <input type="text" name="kontak_telepon"
                                   value="{{ old('kontak_telepon', $kr?->kontak_telepon ?? '') }}"
                                   class="w-full rounded-xl border-slate-300 text-sm py-1.5 focus:border-teal-500 focus:ring-teal-400"
                                   placeholder="+62 812 3456 7890">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">✉️ Email</label>
                            <input type="email" name="kontak_email"
                                   value="{{ old('kontak_email', $kr?->kontak_email ?? '') }}"
                                   class="w-full rounded-xl border-slate-300 text-sm py-1.5 focus:border-teal-500 focus:ring-teal-400"
                                   placeholder="info@smpnkutime.sch.id">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">📍 Alamat Lengkap</label>
                        <textarea name="kontak_alamat" rows="2"
                                  class="w-full rounded-xl border-slate-300 text-sm focus:border-teal-500 focus:ring-teal-400"
                                  placeholder="Jl. Pendidikan No. 1, Kelurahan ..., Kecamatan ..., Kab/Kota ...">{{ old('kontak_alamat', $kr?->kontak_alamat ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">🗺️ Google Maps Embed URL</label>
                        <input type="text" name="kontak_maps_embed"
                               value="{{ old('kontak_maps_embed', $kr?->kontak_maps_embed ?? '') }}"
                               class="w-full rounded-xl border-slate-300 text-sm py-1.5 focus:border-teal-500 focus:ring-teal-400"
                               placeholder="https://maps.google.com/maps?q=...&output=embed">
                        <p class="text-xs text-slate-400 mt-1">
                            Cara mendapatkan: Google Maps → Bagikan → Sematkan peta → salin URL yang ada di <code class="bg-slate-100 px-1 rounded">src="..."</code>
                        </p>
                    </div>

                    <hr class="border-slate-100">

                    <p class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                        Media Sosial
                    </p>
                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach([
                            ['key'=>'sosmed_instagram','icon'=>'📸','label'=>'Instagram', 'ph'=>'https://instagram.com/smpn_kutime'],
                            ['key'=>'sosmed_facebook', 'icon'=>'📘','label'=>'Facebook',  'ph'=>'https://facebook.com/smpnkutime'],
                            ['key'=>'sosmed_youtube',  'icon'=>'▶️','label'=>'YouTube',   'ph'=>'https://youtube.com/@smpnkutime'],
                            ['key'=>'sosmed_twitter',  'icon'=>'🐦','label'=>'X/Twitter', 'ph'=>'https://twitter.com/smpnkutime'],
                        ] as $sm)
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">{{ $sm['icon'] }} {{ $sm['label'] }}</label>
                            <input type="url" name="{{ $sm['key'] }}"
                                   value="{{ old($sm['key'], $kr?->{$sm['key']} ?? '') }}"
                                   class="w-full rounded-xl border-slate-300 text-sm py-1.5 focus:border-teal-500 focus:ring-teal-400"
                                   placeholder="{{ $sm['ph'] }}">
                        </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-400">Kontak tampil di halaman beranda dan footer website.</p>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 text-white text-xs font-semibold rounded-xl hover:bg-teal-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Kontak & Sosmed
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                <div>
                    <p class="text-xs font-semibold text-emerald-800">Lihat tampilan kontak di website</p>
                    <p class="text-xs text-emerald-600 mt-0.5">Section kontak ada di bagian bawah halaman beranda.</p>
                </div>
                <a href="{{ route('website.home') }}#kontak" target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-xl hover:bg-emerald-700 transition shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka Section Kontak
                </a>
            </div>
        </div>{{-- /tab kontak --}}


        {{-- ════════════════════════════════════════════════════
             TAB: BERITA
             (Sesuaikan dengan struktur berita yang sudah ada)
        ════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'berita'" x-cloak>
            @include('admin.kelola-website.tabs.berita')
        </div>

        {{-- ════════════════════════════════════════════════════
             TAB: GALERI
             (Sesuaikan dengan struktur galeri yang sudah ada)
        ════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'galeri'" x-cloak>
            @include('admin.kelola-website.tabs.galeri')
        </div>

    </div>{{-- /content area --}}
</div>{{-- /x-data tab --}}

{{-- ══════════════════════════════════════════════════════
     QUILL.JS + SCRIPTS
══════════════════════════════════════════════════════ --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>

<script>
/* ── Alpine: hero media form ─────────────────────────────── */
function heroAdmin(t, i) {
    return {
        heroTipe: t,
        interval: i,
        prev: null,
        yt: null,
        slides: [],
        ytThumb(url) {
            if (!url) { this.yt = null; return; }
            const m = url.match(/(?:youtu\.be\/|[?&]v=|shorts\/)([a-zA-Z0-9_-]{11})/);
            this.yt = m ? `https://img.youtube.com/vi/${m[1]}/hqdefault.jpg` : null;
        }
    };
}

/* ── Quill editors ───────────────────────────────────────── */
(function initQuill() {
    function tryInit() {
        if (!document.getElementById('editor-tentang')) {
            setTimeout(tryInit, 120);
            return;
        }
        setupEditors();
    }

    function setupEditors() {
        const savedTentang = @json($valTentang ?? '');
        const savedVisi    = @json($valVisi    ?? '');
        const savedMisi    = @json($valMisi    ?? '');

        const qTentang = new Quill('#editor-tentang', {
            theme: 'snow',
            modules: { toolbar: '#toolbar-tentang' }
        });
        if (savedTentang) qTentang.root.innerHTML = savedTentang;

        const qVisi = new Quill('#editor-visi', {
            theme: 'snow',
            modules: { toolbar: '#toolbar-visi' }
        });
        if (savedVisi) qVisi.root.innerHTML = savedVisi;

        const qMisi = new Quill('#editor-misi', {
            theme: 'snow',
            modules: { toolbar: '#toolbar-misi' }
        });
        if (savedMisi) qMisi.root.innerHTML = savedMisi;

        const form = document.getElementById('form-tentang-visi-misi');
        if (form) {
            form.addEventListener('submit', function () {
                document.getElementById('input-tentang').value = qTentang.root.innerHTML;
                document.getElementById('input-visi').value    = qVisi.root.innerHTML;
                document.getElementById('input-misi').value    = qMisi.root.innerHTML;
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', tryInit);
    } else {
        tryInit();
    }
})();
</script>

<style>
/* ── Quill: sembunyikan toolbar default, tampilkan custom ── */
.ql-container.ql-snow { border: none !important; }
.ql-toolbar.ql-snow   { display: none !important; }
.ql-editor             { padding: 8px 12px !important; min-height: 80px; }
.ql-editor p           { margin-bottom: 4px; }
.ql-editor ol,
.ql-editor ul          { padding-left: 1.5em; }
/* ── Tab transition ── */
[x-cloak] { display: none !important; }
/* ── Scrollbar hide untuk tab bar ── */
.scrollbar-none::-webkit-scrollbar { display: none; }
.scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection
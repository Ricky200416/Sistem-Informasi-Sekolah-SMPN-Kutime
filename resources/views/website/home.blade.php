{{-- resources/views/website/home.blade.php --}}
@extends('layouts.public')
@section('title', 'Beranda')

@push('styles')
<style>
:root {
    --navy:   #0e2356;
    --navy2:  #183580;
    --navy3:  #0a1e47;
    --gold:   #c8a84b;
    --gold2:  #e8c96b;
    --cream:  #f8f5ef;
    --cream2: #f2ede4;
    --text:   #1e293b;
    --muted:  #64748b;
    --border: #e2e8f0;
    --sh:     0 2px 10px rgba(14,35,86,.06);
    --sh2:    0 4px 18px rgba(14,35,86,.10);
}
body { font-family:'Plus Jakarta Sans',system-ui,sans-serif; color:var(--text); background:var(--cream); }
.font-lora { font-family:'Lora',Georgia,serif; }

/* ── Chip badge ── */
.chip {
    display:inline-flex; align-items:center; gap:5px;
    padding:2px 10px; border-radius:99px;
    font-size:.6rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase;
    background:rgba(14,35,86,.06); color:var(--navy2);
}

/* ── Gold bar ornament ── */
.gold-bar { display:flex; align-items:center; gap:4px; }
.gold-bar span:first-child { display:block; width:20px; height:2px; border-radius:99px; background:var(--gold); }
.gold-bar span:last-child  { display:block; width:7px; height:2px; border-radius:99px; background:var(--gold); opacity:.4; }

/* ── Section heading ── */
.sec-head { text-align:center; margin-bottom:1.2rem; }
.sec-head h2 { font-family:'Lora',serif; font-weight:700; font-size:clamp(1rem,2.2vw,1.35rem); color:#000; margin:.25rem 0 .35rem; line-height:1.2; }
.sec-head .gold-bar { justify-content:center; }
.sec-head p { color:var(--muted); font-size:.75rem; margin-top:.35rem; }

/* ── HERO ── */
.hero-wrap { position:relative; overflow:hidden; color:#fff; display:flex; align-items:center; min-height:220px; max-height:340px; }
.hero-media { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.hero-veil  {
    position:absolute; inset:0;
    background:linear-gradient(115deg,rgba(9,18,48,.92) 0%,rgba(12,28,76,.82) 40%,rgba(9,18,48,.72) 100%);
}
.hero-glow-r { position:absolute; bottom:-70px; right:-70px; width:260px; height:260px; border-radius:50%; background:radial-gradient(circle,rgba(200,168,75,.12),transparent 68%); pointer-events:none; }
.hero-glow-l { position:absolute; top:-50px; left:-50px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle,rgba(255,255,255,.05),transparent 70%); pointer-events:none; }
.hero-text-wrap { text-shadow:0 1px 10px rgba(0,0,0,.3); }
.hero-h1 { text-shadow:0 2px 14px rgba(0,0,0,.45); }

/* ── Stat bar ── */
.stat-bar { background:var(--navy3); }
.stat-item { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.10); border-radius:8px; padding:7px 10px; text-align:center; transition:background .2s; }
.stat-item:hover { background:rgba(255,255,255,.10); }

/* ── Card ── */
.card { background:#fff; border-radius:10px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--sh); transition:transform .22s ease,box-shadow .22s ease; }
.card:hover { transform:translateY(-2px); box-shadow:var(--sh2); }

/* ── Berita card image ── */
.berita-img { overflow:hidden; aspect-ratio:16/10; }
.berita-img img { width:100%; height:100%; object-fit:cover; transition:transform .38s ease; }
.card:hover .berita-img img { transform:scale(1.04); }

/* ── Fasilitas card ── */
.fasil { border-radius:10px; padding:13px 12px; text-align:center; border:2px solid transparent; transition:transform .22s,box-shadow .22s,border-color .22s; }
.fasil:hover { transform:translateY(-2px); box-shadow:var(--sh2); border-color:var(--gold) !important; }

/* ── Galeri ── */
.g-item { position:relative; overflow:hidden; border-radius:8px; aspect-ratio:4/3; display:block; }
.g-item img { width:100%; height:100%; object-fit:cover; transition:transform .36s ease; }
.g-item:hover img { transform:scale(1.06); }
.g-veil { position:absolute; inset:0; border-radius:8px; background:linear-gradient(to top,rgba(0,0,0,.6),transparent 60%); opacity:0; transition:opacity .25s; }
.g-item:hover .g-veil { opacity:1; }
.g-cap { position:absolute; bottom:0; left:0; right:0; padding:6px; opacity:0; transition:opacity .25s; z-index:2; }
.g-item:hover .g-cap { opacity:1; }

/* ── Prose (quill/rich text output) ── */
.prose-content { line-height:1.65; color:#334155; font-size:.825rem; }
.prose-content p { margin-bottom:.5rem; }
.prose-content p:last-child { margin-bottom:0; }
.prose-content ul { list-style:disc; padding-left:1.2em; margin-bottom:.5rem; }
.prose-content ol { list-style:decimal; padding-left:1.2em; margin-bottom:.5rem; }
.prose-content li { margin-bottom:.15rem; }
.prose-content strong { color:#000; font-weight:700; }
.prose-content a { color:var(--navy2); text-decoration:underline; }
.prose-content h1,
.prose-content h2,
.prose-content h3 { font-family:'Lora',serif; font-weight:700; color:#000; margin:.6rem 0 .25rem; line-height:1.25; }

/* ── Teks sambutan ── */
.sambutan-text { font-size:.8rem; line-height:1.65; color:rgba(255,255,255,.90); font-style:italic; }

/* ── Info card text ── */
.info-text { font-size:.775rem; line-height:1.6; color:#334155; }

/* ── Berita card snippet ── */
.berita-snippet {
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    font-size:.735rem; line-height:1.5; color:#64748b;
}
.berita-judul {
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    font-weight:700; font-size:.8rem; line-height:1.3; color:#000;
}

/* ── Line clamp ── */
.lc2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

/* ── Ticker ── */
@keyframes ticker { from{transform:translateX(100vw)} to{transform:translateX(-100%)} }
.ticker-run { animation:ticker 28s linear infinite; white-space:nowrap; }

/* ── Pulse dot ── */
.pulse { animation:pulseAnim 2s ease-in-out infinite; }
@keyframes pulseAnim { 0%,100%{opacity:1} 50%{opacity:.25} }

/* ── Wave divider ── */
.wave { line-height:0; }
.wave svg { display:block; width:100%; }

/* ── Fade-up entrance ── */
@keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
.anim { opacity:0; animation:fadeUp .5s ease forwards; }
.d1{animation-delay:.04s} .d2{animation-delay:.10s} .d3{animation-delay:.16s} .d4{animation-delay:.22s}

/* ── Visi Misi expandable ── */
.vm-card { border-radius:10px; overflow:hidden; }

/* ── Responsive font scale ── */
@media (max-width: 640px) {
    .prose-content { font-size:.775rem; }
    .sambutan-text { font-size:.755rem; }
    .info-text { font-size:.755rem; }
}
</style>
@endpush

@section('content')
@php
    use App\Models\PageContent;
    use App\Models\Berita;

    $heroMedia   = PageContent::getHeroMedia();
    $hmTipe      = $heroMedia?->hero_media_tipe ?? 'none';
    $hmFileUrls  = $heroMedia?->heroFilesUrls ?? [];
    $hmEmbedUrl  = $heroMedia?->heroYoutubeEmbed;
    $hmInterval  = $heroMedia?->hero_slide_interval ?? 4000;

    /*
    =====================================================
    FIX SAFE SCHOOL SETTING (ANTI ERROR 500)
    =====================================================
    */
    $defaultSchoolName = 'SMP Negeri Kutime';

    try {
        $schoolName = \App\Models\SchoolSetting::get('nama_sekolah', $defaultSchoolName);
    } catch (\Throwable $e) {
        $schoolName = $defaultSchoolName;
    }

    $heroTitle = PageContent::getValue('hero_title', $schoolName);

    $heroDesc    = PageContent::getValue('hero_description', 'Sekolah berkualitas yang mencetak generasi unggul, berkarakter, dan berdaya saing.');
    $tentang     = PageContent::getValue('tentang');
    $visi        = PageContent::getValue('visi');
    $misi        = PageContent::getValue('misi');

    $sambutan    = PageContent::getValue('sambutan_teks');
    $sambNama    = PageContent::getValue('sambutan_nama');
    $sambJabatan = PageContent::getValue('sambutan_jabatan','Kepala Sekolah');
    $sambFoto    = PageContent::getValue('sambutan_foto_path');

    $infoPpdb    = PageContent::getValue('info_ppdb');
    $infoKal     = PageContent::getValue('info_kalender');

    $statsRow  = PageContent::getStats();
    $kontakRow = PageContent::getKontak();

    $beritaList    = Berita::where('status','aktif')->latest()->limit(3)->get();
    $beritaPenting = Berita::where('status','aktif')->where('is_penting',true)->latest()->first();

    $galeriList = collect();

    if (class_exists('App\Models\Galeri')) {
        $galeriList = \App\Models\Galeri::where('status','aktif')
            ->where('tipe','foto')
            ->orderBy('created_at','desc')
            ->limit(8)
            ->get();
    }

    /*
    =====================================================
    LOAD AMBIL DATA KOMENTAR AKTIF
    =====================================================
    */
    $publicComments = collect();
    if (class_exists('App\Models\Comment')) {
        $publicComments = \App\Models\Comment::where('is_active', true)->latest()->get();
    }
@endphp

{{-- ═══════════════════ §1 HERO ═══════════════════ --}}
<section class="hero-wrap" aria-label="Header utama">
    @if($hmTipe === 'image' && !empty($hmFileUrls))
        <img src="{{ $hmFileUrls[0] }}" alt="" class="hero-media" loading="eager">
        <div class="hero-veil"></div>

    @elseif($hmTipe === 'video' && !empty($hmFileUrls))
        <video autoplay muted loop playsinline class="hero-media">
            <source src="{{ $hmFileUrls[0] }}" type="video/mp4">
        </video>
        <div class="hero-veil"></div>

    @elseif($hmTipe === 'youtube' && $hmEmbedUrl)
        <div class="absolute inset-0 overflow-hidden" style="background:#060f26">
            <div class="absolute pointer-events-none"
                 style="top:50%;left:50%;transform:translate(-50%,-50%);width:177.78vh;height:56.25vw;min-width:100%;min-height:100%">
                <iframe src="{{ $hmEmbedUrl }}" class="w-full h-full" allow="autoplay;encrypted-media" frameborder="0"></iframe>
            </div>
        </div>
        <div class="hero-veil"></div>

    @elseif($hmTipe === 'slideshow' && !empty($hmFileUrls))
        <div class="absolute inset-0"
             x-data="heroSlide({{ $hmInterval }}, {{ count($hmFileUrls) }})"
             x-init="init()">
            @foreach($hmFileUrls as $idx => $url)
            <div class="absolute inset-0 transition-opacity duration-1000"
                 :style="{{ $idx }}===current?'opacity:1':'opacity:0'">
                <img src="{{ $url }}" alt="" class="hero-media">
            </div>
            @endforeach
            <div class="hero-veil"></div>
            @if(count($hmFileUrls) > 1)
            <div class="absolute z-20 flex gap-1.5 bottom-10 left-1/2 -translate-x-1/2">
                @foreach($hmFileUrls as $idx => $url)
                <button @click="go({{ $idx }})"
                        class="w-1.5 h-1.5 rounded-full border border-white/40 transition-all duration-300"
                        :class="{{ $idx }}===current?'bg-white scale-125':'bg-transparent'"></button>
                @endforeach
            </div>
            @endif
        </div>

    @else
        <div class="absolute inset-0"
             style="background:var(--navy);background-image:linear-gradient(30deg,rgba(200,168,75,.06) 12%,transparent 12.5%,transparent 87%,rgba(200,168,75,.06) 87.5%),linear-gradient(150deg,rgba(200,168,75,.06) 12%,transparent 12.5%,transparent 87%,rgba(200,168,75,.06) 87.5%);background-size:40px 70px">
        </div>
        <div class="hero-veil"></div>
    @endif

    <div class="hero-glow-r"></div>
    <div class="hero-glow-l"></div>

    <div class="relative z-10 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="max-w-xl hero-text-wrap">
            <div class="anim d1 inline-flex items-center gap-1.5 mb-2 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider"
                 style="background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(6px);color:rgba(255,255,255,.90)">
                <span class="w-1.5 h-1.5 rounded-full pulse shrink-0" style="background:#4ade80"></span>
                Website Resmi Sekolah
            </div>
            <h1 class="anim d2 hero-h1 font-lora font-bold text-white leading-tight mb-2"
                style="font-size:clamp(1.25rem,3.2vw,2rem)">
                {{ $heroTitle }}
            </h1>
            <div class="anim d2 gold-bar mb-2"><span></span><span></span></div>
            @if($heroDesc)
            <p class="anim d3 leading-relaxed mb-3"
               style="color:rgba(255,255,255,.85);max-width:380px;font-size:clamp(.75rem,1.4vw,.85rem)">
                {{ $heroDesc }}
            </p>
            @endif
            <div class="anim d4 flex flex-wrap gap-1.5">
                <a href="{{ route('website.berita') }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 font-bold text-[11px] shadow transition-all hover:-translate-y-0.5 hover:shadow-md"
                   style="color:var(--navy)">
                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6"/>
                    </svg>
                    Berita & Pengumuman
                </a>
                <a href="#tentang"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-white text-[11px] font-semibold hover:bg-white/10 transition-all hover:-translate-y-0.5"
                   style="background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(4px)">
                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tentang Sekolah
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ §2 STATISTIK ═══════════════════ --}}
@if($statsRow && ($statsRow->stat_siswa || $statsRow->stat_guru || $statsRow->stat_prestasi || $statsRow->stat_ekskul))
<div class="stat-bar">
    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 py-1.5 sm:py-2.5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 sm:gap-2">
            @foreach([
                ['val'=>$statsRow->stat_siswa,    'icon'=>'👨‍🎓','label'=>'Siswa'],
                ['val'=>$statsRow->stat_guru,     'icon'=>'👩‍🏫','label'=>'Guru'],
                ['val'=>$statsRow->stat_prestasi, 'icon'=>'🏆', 'label'=>'Prestasi'],
                ['val'=>$statsRow->stat_ekskul,   'icon'=>'⭐', 'label'=>'Ekskul'],
            ] as $st)
            @if($st['val'])
            <div class="stat-item py-1 sm:py-0">
                <div class="text-xs sm:text-sm mb-0.5">{{ $st['icon'] }}</div>
                <div class="font-lora font-bold text-white text-xs sm:text-sm leading-none">{{ $st['val'] }}</div>
                <div style="color:rgba(255,255,255,.55);font-size:.55rem" class="mt-0.5 sm:text-[.6rem]">{{ $st['label'] }}</div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="wave" style="background:var(--navy3)">
    <svg viewBox="0 0 1440 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 24C360 2 1080 2 1440 24L1440 24L0 24Z" fill="#f8f5ef"/>
    </svg>
</div>

{{-- ═══════════════════ §3 TICKER INFO PENTING ═══════════════════ --}}
@if($beritaPenting || $infoPpdb)
<div style="background:var(--cream);border-bottom:1px solid #e5ddd0">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 py-1 sm:py-1.5 flex items-center gap-1.5 sm:gap-2">
        <span class="shrink-0 px-1 sm:px-1.5 py-0.5 rounded text-white text-[7px] sm:text-[8px] font-bold uppercase tracking-wider"
              style="background:#dc2626">🔴 Info</span>
        <div class="overflow-hidden flex-1"
             style="mask-image:linear-gradient(to right,transparent,black 2%,black 98%,transparent)">
            <p class="ticker-run text-[10px] sm:text-[11px] font-semibold text-slate-700">
                @if($beritaPenting) 📢 {{ $beritaPenting->judul }} @endif
                @if($beritaPenting && $infoPpdb) &nbsp;&nbsp;&bull;&nbsp;&nbsp; @endif
                @if($infoPpdb) 📋 PPDB: {{ Str::limit(strip_tags($infoPpdb), 120) }} @endif
            </p>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════════ §4 TENTANG + VISI + MISI ═══════════════════ --}}
@if($tentang || $visi || $misi)
<section style="background:var(--cream)" class="py-4 sm:py-7 lg:py-8" id="tentang">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">

        @if($tentang)
        <div class="sec-head mb-3 sm:mb-5">
            <div class="chip text-[9px] sm:text-xs">Mengenal Kami</div>
            <h2 class="text-sm sm:text-xl mt-1">Tentang {{ \App\Models\SchoolSetting::get('singkatan','SMPN Kutime') }}</h2>
            <div class="gold-bar mt-1"><span></span><span></span></div>
        </div>
        <div class="card p-3 sm:p-5 mb-3 sm:mb-5 mx-auto" style="max-width:740px">
            <div class="prose-content text-justify text-[11px] sm:text-sm leading-relaxed">
                {!! $tentang !!}
            </div>
        </div>
        @endif

        @if($visi || $misi)
        <div class="grid md:grid-cols-2 gap-2 sm:gap-3 max-w-4xl mx-auto">

            @if($visi)
            <div class="card p-3 sm:p-4 text-white relative overflow-hidden vm-card"
                 style="background:linear-gradient(140deg,var(--navy) 0%,var(--navy2) 100%)">
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full pointer-events-none"
                     style="background:rgba(200,168,75,.08)"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center shrink-0"
                             style="background:rgba(200,168,75,.15)">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" style="color:var(--gold2)" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[7px] sm:text-[8px] font-bold uppercase tracking-widest" style="color:var(--gold2)">Visi Sekolah</p>
                            <p class="font-lora text-[10px] sm:text-[11px] font-bold text-white mt-0.5 leading-tight">Arah & Cita-cita</p>
                        </div>
                    </div>
                    <div class="prose-content text-justify text-white/90" style="font-size:.7rem">
                        {!! $visi !!}
                    </div>
                </div>
            </div>
            @endif

            @if($misi)
            <div class="card p-3 sm:p-4 vm-card flex flex-col h-full">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center shrink-0"
                         style="background:rgba(14,35,86,.05)">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" style="color:var(--navy)" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[7px] sm:text-[8px] font-bold uppercase tracking-widest" style="color:var(--navy2)">Misi Sekolah</p>
                        <p class="font-lora text-[10px] sm:text-[11px] font-bold mt-0.5 leading-tight" style="color:#000">Langkah Nyata</p>
                    </div>
                </div>
                <div class="prose-content text-justify" style="color:#334155;font-size:.7rem">
                    {!! $misi !!}
                </div>
            </div>
            @endif

        </div>
        @endif
    </div>
</section>
@endif

{{-- ═══════════════════ §5 FASILITAS ═══════════════════ --}}
<section class="bg-white py-4 sm:py-7 lg:py-8" id="fasilitas">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="sec-head mb-3 sm:mb-5">
            <div class="chip text-[9px] sm:text-xs">Sarana & Prasarana</div>
            <h2 class="text-sm sm:text-xl mt-1">Fasilitas Pendukung Belajar</h2>
            <div class="gold-bar mt-1"><span></span><span></span></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 sm:gap-2.5 max-w-3xl mx-auto">
            @foreach([
                ['emoji'=>'🏫','label'=>'Ruang Kelas',      'key'=>'fasilitas_ruang_kelas',  'bg'=>'#eff6ff','bdr'=>'#bfdbfe'],
                ['emoji'=>'📚','label'=>'Perpustakaan',      'key'=>'fasilitas_perpustakaan', 'bg'=>'#fffbeb','bdr'=>'#fde68a'],
                ['emoji'=>'⚽','label'=>'Lapangan Olahraga', 'key'=>'fasilitas_lapangan',     'bg'=>'#f0fdf4','bdr'=>'#bbf7d0'],
                ['emoji'=>'🖥️','label'=>'Laboratorium',      'key'=>'fasilitas_laboratorium', 'bg'=>'#f5f3ff','bdr'=>'#ddd6fe'],
            ] as $f)
            <div class="fasil p-2 sm:p-3" style="background:{{ $f['bg'] }};border-color:{{ $f['bdr'] }}">
                <div class="text-lg sm:text-2xl mb-1">{{ $f['emoji'] }}</div>
                <h4 class="font-bold text-black text-[9px] sm:text-[11px] mb-0.5 sm:mb-1 leading-tight">{{ $f['label'] }}</h4>
                <p class="text-[8px] sm:text-[10px] text-slate-600 leading-relaxed">
                    {{ PageContent::getValue($f['key'], 'Fasilitas tersedia') }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════ §6 SAMBUTAN + INFO PENTING ═══════════════════ --}}
@if($sambutan || $infoPpdb || $infoKal)
<section style="background:var(--cream)" class="py-4 sm:py-7 lg:py-8" id="info">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">

        <div class="sec-head mb-3 sm:mb-5">
            <div class="chip text-[9px] sm:text-xs">Pesan & Informasi</div>
            <h2 class="text-sm sm:text-xl mt-1">Sambutan & Info Penting</h2>
            <div class="gold-bar mt-1"><span></span><span></span></div>
        </div>

        <div class="grid md:grid-cols-2 gap-2 sm:gap-3 items-start max-w-4xl mx-auto">

            {{-- Sambutan Kepala Sekolah --}}
            @if($sambutan)
            <div class="card p-3 sm:p-5 text-white relative overflow-hidden flex flex-col h-full"
                 style="background:linear-gradient(145deg,var(--navy) 0%,var(--navy2) 100%)">
                <div class="absolute top-2 right-3 font-lora pointer-events-none select-none"
                     style="font-size:2.5rem;line-height:1;color:rgba(200,168,75,.08)">❝</div>

                <div class="flex items-center gap-2 mb-2 sm:mb-3 relative z-10">
                    @if($sambFoto)
                    <img src="{{ asset('storage/'.$sambFoto) }}"
                         class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover shrink-0 border-2"
                         style="border-color:rgba(200,168,75,.40)" alt="{{ $sambNama }}">
                    @else
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-sm shrink-0"
                         style="background:rgba(255,255,255,.08);border:2px solid rgba(200,168,75,.25)">👤</div>
                    @endif
                    <div>
                        @if($sambNama)
                        <p class="font-bold text-white text-[10px] sm:text-xs leading-tight">{{ $sambNama }}</p>
                        @endif
                        <p class="text-[8px] sm:text-[10px] font-semibold mt-0.5" style="color:var(--gold2)">{{ $sambJabatan }}</p>
                    </div>
                </div>

                <div class="relative z-10">
                    <p class="text-[7px] sm:text-[9px] font-bold uppercase tracking-widest mb-1" style="color:var(--gold2)">
                        Kata Sambutan
                    </p>
                    <div class="sambutan-text text-justify text-white/90" style="font-size:.68rem">
                        {!! nl2br(e($sambutan)) !!}
                    </div>
                </div>
            </div>
            @endif

            {{-- Info PPDB & Kalender --}}
            @if($infoPpdb || $infoKal)
            <div class="flex flex-col gap-2 h-full justify-start">

                @if($infoPpdb)
                <div class="card p-2.5 sm:p-3.5" style="border-color:#fecaca;background:#fff5f5">
                    <div class="flex items-center gap-1.5 mb-1 sm:mb-1.5">
                        <span class="w-1.5 h-1.5 rounded-full pulse shrink-0" style="background:#ef4444"></span>
                        <span class="text-[8px] sm:text-[10px] font-bold uppercase tracking-wider" style="color:#dc2626">
                            PPDB — Penerimaan Siswa Baru
                        </span>
                    </div>
                    <div class="info-text text-justify" style="color:#374151;font-size:.68rem">
                        {!! nl2br(e($infoPpdb)) !!}
                    </div>
                    <a href="{{ route('website.berita') }}"
                       class="inline-flex items-center gap-0.5 mt-1.5 sm:mt-2 text-[9px] sm:text-[10px] font-bold transition-all hover:gap-1.5"
                       style="color:#dc2626">
                        Selengkapnya di halaman berita
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @endif

                @if($infoKal)
                <div class="card p-2.5 sm:p-3.5" style="border-color:#bfdbfe;background:#eff6ff">
                    <div class="flex items-center gap-1.5 mb-1 sm:mb-1.5">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 shrink-0" style="color:#2563eb" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-[8px] sm:text-[10px] font-bold uppercase tracking-wider" style="color:#2563eb">
                            Kalender Akademik
                        </span>
                    </div>
                    <div class="info-text text-justify" style="color:#374151;font-size:.68rem">
                        {!! nl2br(e($infoKal)) !!}
                    </div>
                </div>
                @endif

            </div>
            @endif

        </div>
    </div>
</section>
@endif

{{-- ═══════════════════ §7 BERITA TERBARU ═══════════════════ --}}
@if($beritaList->count())
<section class="bg-white py-7 lg:py-8" id="berita">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-3 sm:mb-4">
            <div>
                <div class="chip mb-1">Informasi & Kegiatan</div>
                <h2 class="font-lora text-base sm:text-xl font-bold" style="color:#000">Berita Terbaru</h2>
                <div class="gold-bar mt-1"><span></span><span></span></div>
            </div>
            <a href="{{ route('website.berita') }}"
               class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold border transition-all hover:-translate-y-0.5"
               style="color:var(--navy2);border-color:rgba(14,35,86,.18)">
                Semua Berita
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 sm:gap-3">
            @foreach($beritaList as $b)
            <?php
                $youtubeId = null;
                if ($b->media_tipe === 'link_youtube' && $b->media_file_url) {
                    $url = $b->media_file_url;
                    if (preg_match('/(?:youtube\.com\/(?:embed\/|watch\?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        $youtubeId = $matches[1];
                    }
                }
                $fbPlaceholder = $b->media_tipe === 'link_facebook';
            ?>

            <a href="{{ route('website.berita.show', $b->slug ?? $b->id) }}"
               class="card flex flex-col group overflow-hidden" style="text-decoration:none">

                <div class="berita-img relative">
                    @if($b->gambar)
                        <img src="{{ asset('storage/'.$b->gambar) }}" alt="{{ $b->judul }}" loading="lazy" class="w-full h-full object-cover">
                    @elseif($b->media_tipe && $b->media_file_url)
                        @if(in_array($b->media_tipe, ['photo','image']))
                            <img src="{{ $b->media_file_url }}" alt="{{ $b->judul }}" loading="lazy" class="w-full h-full object-cover">
                        @elseif($b->media_tipe === 'video' && $b->media_thumbnail_url)
                            <img src="{{ $b->media_thumbnail_url }}" alt="{{ $b->judul }}" loading="lazy" class="w-full h-full object-cover">
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-black/65 flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        @elseif($youtubeId)
                            <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg"
                                 alt="{{ $b->judul }}" loading="lazy" class="w-full h-full object-cover"
                                 onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/default.jpg';">
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-red-600/85 flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        @elseif($fbPlaceholder)
                            <div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-4xl text-white/80">f</div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-3xl text-white/50">
                                {{ match($b->media_tipe) { 'video' => '🎥', 'link_youtube' => '▶️', 'link_facebook' => '📘', default => '🔗' } }}
                            </div>
                        @endif
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-4xl opacity-70">📰</div>
                    @endif
                </div>

                <div class="flex flex-col flex-1 p-1.5 sm:p-3">
                    <div class="flex items-center gap-0.5 sm:gap-1 mb-1 sm:mb-1.5 flex-wrap">
                        @if($b->is_penting)
                        <span class="px-1 py-0.5 rounded-full font-bold text-[7px] sm:text-[8px]"
                              style="background:#fef2f2;color:#dc2626">🔴 Penting</span>
                        @endif
                        @if($b->kategori)
                        <span class="px-1 py-0.5 rounded-full font-semibold text-[7px] sm:text-[8px]"
                              style="background:#eff6ff;color:#1d4ed8">{{ $b->kategori }}</span>
                        @endif
                        @if($b->media_tipe && $b->media_tipe !== 'none')
                            @php
                                $badge = match($b->media_tipe) {
                                    'photo','image'  => ['bg-blue-100 text-blue-700', '📷 Foto'],
                                    'video'          => ['bg-purple-100 text-purple-700', '🎥 Video'],
                                    'link_youtube'   => ['bg-red-100 text-red-700', '▶️ YouTube'],
                                    'link_facebook'  => ['bg-indigo-100 text-indigo-700', '📘 FB'],
                                    default          => ['bg-slate-100 text-slate-600', 'Media'],
                                };
                            @endphp
                            <span class="px-1 py-0.5 rounded-full font-medium text-[7px] sm:text-[8px] {{ $badge[0] }} hidden sm:inline-flex">
                                {{ $badge[1] }}
                            </span>
                        @endif
                        <span class="ml-auto text-[7px] sm:text-[9px]" style="color:#94a3b8">{{ $b->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="berita-judul mb-0.5 sm:mb-1 text-[9px] sm:text-xs line-clamp-2 leading-tight">{{ $b->judul }}</h3>

                    @if($b->ringkasan || $b->isi)
                    <p class="berita-snippet mb-1 sm:mb-2 hidden sm:block">
                        {{ Str::limit(strip_tags($b->ringkasan ?? $b->isi), 90) }}
                    </p>
                    @endif

                    <div class="hidden sm:flex items-center gap-1 text-[10px] font-bold mt-auto transition-all group-hover:gap-1.5"
                         style="color:var(--navy2)">
                        Baca selengkapnya
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="flex sm:hidden items-center gap-0.5 text-[8px] font-bold mt-auto"
                         style="color:var(--navy2)">
                        Baca →
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-4 sm:mt-5 text-center sm:hidden">
            <a href="{{ route('website.berita') }}"
               class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-white text-[11px] font-bold"
               style="background:var(--navy)">
                Lihat Semua Berita →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════ §8 GALERI ═══════════════════ --}}
@if($galeriList->count())
<section style="background:var(--cream)" class="py-7 lg:py-8" id="galeri">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-4">
            <div>
                <div class="chip mb-1">Dokumentasi</div>
                <h2 class="font-lora text-lg sm:text-xl font-bold" style="color:#000">Galeri Kegiatan</h2>
                <div class="gold-bar mt-1"><span></span><span></span></div>
            </div>
            <a href="{{ route('website.galeri') }}"
               class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold border transition-all hover:-translate-y-0.5"
               style="color:var(--navy2);border-color:rgba(14,35,86,.18)">
                Lihat Semua
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
            @foreach($galeriList as $g)
            <a href="{{ route('website.galeri') }}" class="g-item">
                <img src="{{ $g->fileUrl ?? asset('storage/'.$g->file_path) }}" alt="{{ $g->judul }}" loading="lazy">
                <div class="g-veil"></div>
                <div class="g-cap">
                    <p class="text-white text-[10px] font-semibold leading-snug lc2">{{ $g->judul }}</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-4 text-center sm:hidden">
            <a href="{{ route('website.galeri') }}"
               class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-white text-[11px] font-bold"
               style="background:var(--navy)">
                Lihat Semua Galeri →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════ §9 SESI KOMENTAR & MASUKAN (UPDATED FITUR) ═══════════════════ --}}
<section class="bg-white py-8 lg:py-12 border-t border-slate-200" id="sesi-komentar" x-data="{ showForm: false }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header --}}
        <div class="sec-head text-center max-w-2xl mx-auto mb-6">
            <div class="chip">Apresiasi & Masukan</div>
            <h2 class="text-lg sm:text-2xl font-bold font-lora mt-1 text-slate-900">Masukan Anda Sangat Membantu Kami</h2>
            <div class="gold-bar justify-center mt-1"><span></span><span></span></div>
            <p class="text-xs sm:text-sm text-slate-600 mt-2">
                Masukan Anda akan sangat membantu kami dalam pengembangan website resmi {{ $schoolName }} untuk kedepannya.
            </p>

            {{-- Tombol untuk Menampilkan / Sembunyikan Form Komentar --}}
            <button @click="showForm = !showForm" 
                    type="button"
                    class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white shadow-md hover:shadow-lg transition-all"
                    style="background:var(--navy)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span x-text="showForm ? 'Tutup Form Komentar' : 'Tulis Komentar / Masukan'"></span>
            </button>
        </div>

        {{-- Alert Sukses Pengiriman Komentar --}}
        @if(session('success_comment'))
        <div class="max-w-xl mx-auto mb-6 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success_comment') }}</span>
        </div>
        @endif

        {{-- Form Tulis Komentar (Hidden Default, Toggle via AlpineJS) --}}
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
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white shadow hover:opacity-90 transition" style="background:var(--navy)">
                        Kirim Komentar
                    </button>
                </div>
            </form>
        </div>

        {{-- Carousel Slider Titik-Titik Komentar User --}}
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
                    
                    <h4 class="text-xs font-bold" style="color:var(--navy)">
                        {{ $c->nama ?? 'Unknown' }}
                    </h4>
                    <span class="text-[10px] text-slate-400">{{ $c->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>

            {{-- Navigasi Titik-Titik & Panah --}}
            @if($publicComments->count() > 1)
            <div class="flex items-center justify-center gap-1.5 mt-4">
                <button @click="prev()" type="button" class="p-1 text-slate-400 hover:text-navy transition" aria-label="Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                
                @foreach($publicComments as $idx => $c)
                <button @click="active = {{ $idx }}" 
                        type="button" 
                        class="h-2 rounded-full transition-all duration-300"
                        :class="active === {{ $idx }} ? 'w-6 bg-amber-500' : 'w-2 bg-slate-300 hover:bg-slate-400'"
                        aria-label="Slide {{ $idx + 1 }}"></button>
                @endforeach

                <button @click="next()" type="button" class="p-1 text-slate-400 hover:text-navy transition" aria-label="Selanjutnya">
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

{{-- ═══════════════════ §10 KONTAK & PETA ═══════════════════ --}}
@if($kontakRow && ($kontakRow->kontak_alamat || $kontakRow->kontak_telepon || $kontakRow->kontak_email))
<section class="bg-white py-7 lg:py-8" id="kontak">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="sec-head">
            <div class="chip">Hubungi Kami</div>
            <h2>Kontak & Lokasi</h2>
            <div class="gold-bar"><span></span><span></span></div>
        </div>

        <div class="grid md:grid-cols-2 gap-3 items-start max-w-4xl mx-auto">
            <div class="space-y-2">
                @if($kontakRow->kontak_alamat)
                <div class="card flex gap-2.5 p-3.5">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-base" style="background:rgba(14,35,86,.05)">📍</div>
                    <div>
                        <p class="text-[8px] font-bold uppercase tracking-wider mb-0.5" style="color:#94a3b8">Alamat</p>
                        <p class="text-[11px] text-slate-700 leading-relaxed">{{ $kontakRow->kontak_alamat }}</p>
                    </div>
                </div>
                @endif

                @if($kontakRow->kontak_telepon)
                <div class="card flex gap-2.5 p-3.5">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-base" style="background:rgba(14,35,86,.05)">📞</div>
                    <div>
                        <p class="text-[8px] font-bold uppercase tracking-wider mb-0.5" style="color:#94a3b8">Telepon / WhatsApp</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$kontakRow->kontak_telepon) }}"
                           class="text-[11px] font-bold hover:underline" style="color:var(--navy2)">
                            {{ $kontakRow->kontak_telepon }}
                        </a>
                    </div>
                </div>
                @endif

                @if($kontakRow->kontak_email)
                <div class="card flex gap-2.5 p-3.5">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-base" style="background:rgba(14,35,86,.05)">✉️</div>
                    <div>
                        <p class="text-[8px] font-bold uppercase tracking-wider mb-0.5" style="color:#94a3b8">Email</p>
                        <a href="mailto:{{ $kontakRow->kontak_email }}"
                           class="text-[11px] font-bold hover:underline" style="color:var(--navy2)">
                            {{ $kontakRow->kontak_email }}
                        </a>
                    </div>
                </div>
                @endif

                @if($kontakRow->sosmed_instagram || $kontakRow->sosmed_facebook || $kontakRow->sosmed_youtube || $kontakRow->sosmed_twitter)
                <div class="card p-3.5">
                    <p class="text-[8px] font-bold uppercase tracking-wider mb-2" style="color:#94a3b8">Media Sosial</p>
                    <div class="flex flex-wrap gap-1">
                        @if($kontakRow->sosmed_instagram)
                        <a href="{{ $kontakRow->sosmed_instagram }}" target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-white text-[10px] font-bold transition hover:opacity-90"
                           style="background:linear-gradient(135deg,#e1306c,#833ab4)">📸 Instagram</a>
                        @endif
                        @if($kontakRow->sosmed_facebook)
                        <a href="{{ $kontakRow->sosmed_facebook }}" target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-white text-[10px] font-bold transition hover:opacity-90"
                           style="background:#1877f2">📘 Facebook</a>
                        @endif
                        @if($kontakRow->sosmed_youtube)
                        <a href="{{ $kontakRow->sosmed_youtube }}" target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-white text-[10px] font-bold transition hover:opacity-90"
                           style="background:#ff0000">▶️ YouTube</a>
                        @endif
                        @if($kontakRow->sosmed_twitter)
                        <a href="{{ $kontakRow->sosmed_twitter }}" target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-white text-[10px] font-bold transition hover:opacity-90"
                           style="background:#1da1f2">🐦 X</a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            @if($kontakRow->kontak_maps_embed)
            <div class="card overflow-hidden" style="height:260px">
                <iframe src="{{ $kontakRow->kontak_maps_embed }}" width="100%" height="100%" style="border:0"
                        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            @else
            <div class="card flex items-center justify-center text-center p-5" style="height:260px;background:var(--cream)">
                <div>
                    <div class="text-3xl mb-2">🗺️</div>
                    <p class="text-[11px] text-slate-500 font-semibold">Peta lokasi belum diatur</p>
                    <p class="text-[10px] text-slate-400 mt-1 max-w-xs mx-auto leading-normal">
                        Admin dapat menambahkan embed Google Maps dari panel kelola website.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
// Hero Slideshow
function heroSlide(interval, total) {
    return {
        current: 0,
        timer: null,
        init() {
            if (total > 1) {
                this.timer = setInterval(() => {
                    this.current = (this.current + 1) % total;
                }, interval);
            }
        },
        go(i) {
            this.current = i;
            clearInterval(this.timer);
            if (total > 1) {
                this.timer = setInterval(() => {
                    this.current = (this.current + 1) % total;
                }, interval);
            }
        },
        destroy() { clearInterval(this.timer); }
    };
}

// Galeri hover fallback
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.g-item').forEach(el => {
        const veil = el.querySelector('.g-veil');
        const cap  = el.querySelector('.g-cap');
        if (!veil || !cap) return;
        el.addEventListener('mouseenter', () => { veil.style.opacity='1'; cap.style.opacity='1'; });
        el.addEventListener('mouseleave', () => { veil.style.opacity='0'; cap.style.opacity='0'; });
    });
});
</script>
@endpush
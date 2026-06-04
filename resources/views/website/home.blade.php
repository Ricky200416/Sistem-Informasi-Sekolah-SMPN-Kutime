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
    --sh:     0 2px 12px rgba(14,35,86,.08);
    --sh2:    0 6px 24px rgba(14,35,86,.13);
}
body { font-family:'Plus Jakarta Sans',system-ui,sans-serif; color:var(--text); background:var(--cream); }
.font-lora { font-family:'Lora',Georgia,serif; }

/* ── Chip badge ── */
.chip {
    display:inline-flex; align-items:center; gap:6px;
    padding:4px 14px; border-radius:99px;
    font-size:.7rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase;
    background:rgba(14,35,86,.07); color:var(--navy2);
}

/* ── Gold bar ornament ── */
.gold-bar { display:flex; align-items:center; gap:5px; }
.gold-bar span:first-child { display:block; width:28px; height:2.5px; border-radius:99px; background:var(--gold); }
.gold-bar span:last-child  { display:block; width:10px; height:2.5px; border-radius:99px; background:var(--gold); opacity:.4; }

/* ── Section heading ── */
.sec-head { text-align:center; margin-bottom:2rem; }
.sec-head h2 { font-family:'Lora',serif; font-weight:700; font-size:clamp(1.3rem,2.8vw,1.75rem); color:var(--text); margin:.4rem 0 .6rem; line-height:1.25; }
.sec-head .gold-bar { justify-content:center; }
.sec-head p { color:var(--muted); font-size:.9rem; margin-top:.6rem; }

/* ── HERO ── */
.hero-wrap { position:relative; overflow:hidden; color:#fff; display:flex; align-items:center; min-height:340px; max-height:460px; }
.hero-media { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.hero-veil  {
    position:absolute; inset:0;
    background:linear-gradient(115deg,rgba(9,18,48,.93) 0%,rgba(12,28,76,.82) 40%,rgba(9,18,48,.72) 100%);
}
.hero-glow-r { position:absolute; bottom:-70px; right:-70px; width:340px; height:340px; border-radius:50%; background:radial-gradient(circle,rgba(200,168,75,.13),transparent 68%); pointer-events:none; }
.hero-glow-l { position:absolute; top:-50px; left:-50px; width:220px; height:220px; border-radius:50%; background:radial-gradient(circle,rgba(255,255,255,.05),transparent 70%); pointer-events:none; }
.hero-text-wrap { text-shadow:0 2px 12px rgba(0,0,0,.35); }
.hero-h1 { text-shadow:0 2px 18px rgba(0,0,0,.5); }

/* ── Stat bar ── */
.stat-bar { background:var(--navy3); }
.stat-item { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:12px; padding:12px 14px; text-align:center; transition:background .2s; }
.stat-item:hover { background:rgba(255,255,255,.13); }

/* ── Card ── */
.card { background:#fff; border-radius:16px; border:1px solid var(--border); overflow:hidden; box-shadow:var(--sh); transition:transform .22s ease,box-shadow .22s ease; }
.card:hover { transform:translateY(-4px); box-shadow:var(--sh2); }

/* ── Berita card image ── */
.berita-img { overflow:hidden; aspect-ratio:16/9; }
.berita-img img { width:100%; height:100%; object-fit:cover; transition:transform .38s ease; }
.card:hover .berita-img img { transform:scale(1.06); }

/* ── Fasilitas card ── */
.fasil { border-radius:16px; padding:22px 18px; text-align:center; border:2px solid transparent; transition:transform .22s,box-shadow .22s,border-color .22s; }
.fasil:hover { transform:translateY(-5px); box-shadow:var(--sh2); border-color:var(--gold) !important; }

/* ── Galeri ── */
.g-item { position:relative; overflow:hidden; border-radius:12px; aspect-ratio:1; display:block; }
.g-item img { width:100%; height:100%; object-fit:cover; transition:transform .36s ease; }
.g-item:hover img { transform:scale(1.08); }
.g-veil { position:absolute; inset:0; border-radius:12px; background:linear-gradient(to top,rgba(0,0,0,.52),transparent 60%); opacity:0; transition:opacity .25s; }
.g-item:hover .g-veil { opacity:1; }
.g-cap { position:absolute; bottom:0; left:0; right:0; padding:8px; opacity:0; transition:opacity .25s; z-index:2; }
.g-item:hover .g-cap { opacity:1; }

/* ── Prose (quill/rich text output) ── */
/* Semua konten rich text dari admin TIDAK dibatasi agar bisa terbaca penuh */
.prose-content { line-height:1.8; color:#374151; font-size:.925rem; }
.prose-content p { margin-bottom:.75rem; }
.prose-content p:last-child { margin-bottom:0; }
.prose-content ul { list-style:disc; padding-left:1.4em; margin-bottom:.75rem; }
.prose-content ol { list-style:decimal; padding-left:1.4em; margin-bottom:.75rem; }
.prose-content li { margin-bottom:.3rem; }
.prose-content strong { color:#1e293b; font-weight:700; }
.prose-content a { color:var(--navy2); text-decoration:underline; }
.prose-content h1,
.prose-content h2,
.prose-content h3 { font-family:'Lora',serif; font-weight:700; color:var(--text); margin:.9rem 0 .4rem; line-height:1.3; }

/* ── Teks sambutan (bisa panjang, tampil semua) ── */
.sambutan-text { font-size:.9rem; line-height:1.8; color:rgba(255,255,255,.85); font-style:italic; }

/* ── Info card text (PPDB, kalender) ── */
.info-text { font-size:.875rem; line-height:1.75; color:#374151; }

/* ── Berita card snippet (sedikit clamp untuk menjaga konsistensi card) ── */
.berita-snippet {
    display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;
    font-size:.8rem; line-height:1.65; color:#64748b;
}
.berita-judul {
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    font-weight:700; font-size:.9rem; line-height:1.4; color:#1e293b;
}

/* ── Line clamp khusus beberapa tempat terbatas ── */
.lc2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

/* ── Ticker ── */
@keyframes ticker { from{transform:translateX(100vw)} to{transform:translateX(-100%)} }
.ticker-run { animation:ticker 32s linear infinite; white-space:nowrap; }

/* ── Pulse dot ── */
.pulse { animation:pulseAnim 2s ease-in-out infinite; }
@keyframes pulseAnim { 0%,100%{opacity:1} 50%{opacity:.25} }

/* ── Wave divider ── */
.wave { line-height:0; }
.wave svg { display:block; width:100%; }

/* ── Fade-up entrance ── */
@keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
.anim { opacity:0; animation:fadeUp .55s ease forwards; }
.d1{animation-delay:.06s} .d2{animation-delay:.14s} .d3{animation-delay:.22s} .d4{animation-delay:.30s}

/* ── Visi Misi expandable ── */
.vm-card { border-radius:16px; overflow:hidden; }
.vm-inner { padding:1.5rem; }

/* ── Responsive font scale ── */
@media (max-width: 640px) {
    .prose-content { font-size:.875rem; }
    .sambutan-text { font-size:.85rem; }
    .info-text { font-size:.84rem; }
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
            <div class="absolute z-20 flex gap-2 bottom-16 left-1/2 -translate-x-1/2">
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

    <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="max-w-xl hero-text-wrap">
            <div class="anim d1 inline-flex items-center gap-2 mb-3 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest"
                 style="background:rgba(255,255,255,.11);border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(8px);color:rgba(255,255,255,.90)">
                <span class="w-1.5 h-1.5 rounded-full pulse shrink-0" style="background:#4ade80"></span>
                Website Resmi Sekolah
            </div>
            <h1 class="anim d2 hero-h1 font-lora font-bold text-white leading-tight mb-3"
                style="font-size:clamp(1.7rem,4.5vw,2.7rem)">
                {{ $heroTitle }}
            </h1>
            <div class="anim d2 gold-bar mb-3"><span></span><span></span></div>
            @if($heroDesc)
            <p class="anim d3 leading-relaxed mb-5"
               style="color:rgba(255,255,255,.80);max-width:440px;font-size:clamp(.875rem,1.8vw,1rem)">
                {{ $heroDesc }}
            </p>
            @endif
            <div class="anim d4 flex flex-wrap gap-2.5">
                <a href="{{ route('website.berita') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white font-bold text-sm shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg"
                   style="color:var(--navy)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6"/>
                    </svg>
                    Berita & Pengumuman
                </a>
                <a href="#tentang"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:-translate-y-0.5"
                   style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.20);backdrop-filter:blur(6px)">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['val'=>$statsRow->stat_siswa,    'icon'=>'👨‍🎓','label'=>'Siswa Aktif'],
                ['val'=>$statsRow->stat_guru,     'icon'=>'👩‍🏫','label'=>'Tenaga Didik'],
                ['val'=>$statsRow->stat_prestasi, 'icon'=>'🏆', 'label'=>'Prestasi'],
                ['val'=>$statsRow->stat_ekskul,   'icon'=>'⭐', 'label'=>'Kegiatan Ekskul'],
            ] as $st)
            @if($st['val'])
            <div class="stat-item">
                <div class="text-xl mb-1">{{ $st['icon'] }}</div>
                <div class="font-lora font-bold text-white" style="font-size:1.25rem;line-height:1.1">{{ $st['val'] }}</div>
                <div class="text-xs mt-0.5" style="color:rgba(255,255,255,.52);font-size:.72rem">{{ $st['label'] }}</div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="wave" style="background:var(--navy3)">
    <svg viewBox="0 0 1440 30" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 30C360 3 1080 3 1440 30L1440 30L0 30Z" fill="#f8f5ef"/>
    </svg>
</div>

{{-- ═══════════════════ §3 TICKER INFO PENTING ═══════════════════ --}}
@if($beritaPenting || $infoPpdb)
<div style="background:var(--cream);border-bottom:1px solid #e5ddd0">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex items-center gap-3">
        <span class="shrink-0 px-2.5 py-1 rounded-md text-white text-xs font-bold uppercase tracking-wide"
              style="background:#dc2626">🔴 Info</span>
        <div class="overflow-hidden flex-1"
             style="mask-image:linear-gradient(to right,transparent,black 2%,black 98%,transparent)">
            <p class="ticker-run text-sm font-medium" style="color:#374151">
                @if($beritaPenting) 📢 {{ $beritaPenting->judul }} @endif
                @if($beritaPenting && $infoPpdb) &nbsp;&nbsp;&bull;&nbsp;&nbsp; @endif
                @if($infoPpdb) 📋 PPDB: {{ Str::limit(strip_tags($infoPpdb), 150) }} @endif
            </p>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════════ §4 TENTANG + VISI + MISI ═══════════════════ --}}
{{-- PERBAIKAN UTAMA: Semua teks tentang, visi, misi tampil penuh tanpa clamp --}}
@if($tentang || $visi || $misi)
<section style="background:var(--cream)" class="py-12 lg:py-16" id="tentang">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($tentang)
        <div class="sec-head">
            <div class="chip">Mengenal Kami</div>
            <h2>Tentang {{ \App\Models\SchoolSetting::get('singkatan','SMPN Kutime') }}</h2>
            <div class="gold-bar"><span></span><span></span></div>
        </div>
        {{-- Tentang: tampil penuh, rata kiri-kanan, ukuran nyaman dibaca --}}
        <div class="card p-6 sm:p-8 mb-8 mx-auto" style="max-width:780px">
            <div class="prose-content text-justify">
                {!! $tentang !!}
            </div>
        </div>
        @endif

        @if($visi || $misi)
        <div class="grid md:grid-cols-2 gap-5">

            @if($visi)
            {{-- Visi: tampil penuh, tidak di-clamp --}}
            <div class="card p-6 sm:p-7 text-white relative overflow-hidden vm-card"
                 style="background:linear-gradient(140deg,var(--navy) 0%,var(--navy2) 100%)">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full pointer-events-none"
                     style="background:rgba(200,168,75,.10)"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                             style="background:rgba(200,168,75,.20)">
                            <svg class="w-5 h-5" style="color:var(--gold2)" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest" style="color:var(--gold2)">Visi Sekolah</p>
                            <p class="font-lora text-base font-bold text-white mt-0.5 leading-tight">Arah & Cita-cita</p>
                        </div>
                    </div>
                    {{-- Visi tampil PENUH, tidak dipotong --}}
                    <div class="prose-content" style="color:rgba(255,255,255,.85);font-size:.9rem">
                        {!! $visi !!}
                    </div>
                </div>
            </div>
            @endif

            @if($misi)
            {{-- Misi: tampil penuh, tidak di-clamp --}}
            <div class="card p-6 sm:p-7 vm-card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                         style="background:rgba(14,35,86,.07)">
                        <svg class="w-5 h-5" style="color:var(--navy)" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest" style="color:var(--navy2)">Misi Sekolah</p>
                        <p class="font-lora text-base font-bold mt-0.5 leading-tight" style="color:var(--text)">Langkah Nyata</p>
                    </div>
                </div>
                {{-- Misi tampil PENUH --}}
                <div class="prose-content" style="color:#374151">
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
<section class="bg-white py-12 lg:py-16" id="fasilitas">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="sec-head">
            <div class="chip">Sarana & Prasarana</div>
            <h2>Fasilitas Pendukung Belajar</h2>
            <div class="gold-bar"><span></span><span></span></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto">
            @foreach([
                ['emoji'=>'🏫','label'=>'Ruang Kelas',      'key'=>'fasilitas_ruang_kelas',  'bg'=>'#eff6ff','bdr'=>'#bfdbfe'],
                ['emoji'=>'📚','label'=>'Perpustakaan',      'key'=>'fasilitas_perpustakaan', 'bg'=>'#fffbeb','bdr'=>'#fde68a'],
                ['emoji'=>'⚽','label'=>'Lapangan Olahraga', 'key'=>'fasilitas_lapangan',     'bg'=>'#f0fdf4','bdr'=>'#bbf7d0'],
                ['emoji'=>'🖥️','label'=>'Laboratorium',      'key'=>'fasilitas_laboratorium', 'bg'=>'#f5f3ff','bdr'=>'#ddd6fe'],
            ] as $f)
            <div class="fasil" style="background:{{ $f['bg'] }};border-color:{{ $f['bdr'] }}">
                <div class="text-4xl mb-3">{{ $f['emoji'] }}</div>
                <h4 class="font-bold text-slate-800 text-sm mb-2">{{ $f['label'] }}</h4>
                {{-- Deskripsi fasilitas tampil penuh --}}
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ PageContent::getValue($f['key'], 'Fasilitas tersedia') }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════ §6 SAMBUTAN + INFO PENTING ═══════════════════ --}}
{{-- PERBAIKAN UTAMA: Sambutan dan info tampil penuh --}}
@if($sambutan || $infoPpdb || $infoKal)
<section style="background:var(--cream)" class="py-12 lg:py-16" id="info">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul section --}}
        <div class="sec-head">
            <div class="chip">Pesan & Informasi</div>
            <h2>Sambutan & Info Penting</h2>
            <div class="gold-bar"><span></span><span></span></div>
        </div>

        <div class="grid md:grid-cols-2 gap-5 items-start">

            {{-- Sambutan Kepala Sekolah: tampil PENUH tanpa clamp --}}
            @if($sambutan)
            <div class="card p-6 sm:p-7 text-white relative overflow-hidden"
                 style="background:linear-gradient(145deg,var(--navy) 0%,var(--navy2) 100%)">
                <div class="absolute top-4 right-5 font-lora pointer-events-none select-none"
                     style="font-size:6rem;line-height:1;color:rgba(200,168,75,.10)">❝</div>

                <div class="flex items-center gap-3 mb-5 relative z-10">
                    @if($sambFoto)
                    <img src="{{ asset('storage/'.$sambFoto) }}"
                         class="w-14 h-14 rounded-full object-cover shrink-0 border-2"
                         style="border-color:rgba(200,168,75,.50)" alt="{{ $sambNama }}">
                    @else
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl shrink-0"
                         style="background:rgba(255,255,255,.11);border:2px solid rgba(200,168,75,.3)">👤</div>
                    @endif
                    <div>
                        @if($sambNama)
                        <p class="font-bold text-white text-base leading-tight">{{ $sambNama }}</p>
                        @endif
                        <p class="text-sm font-semibold mt-0.5" style="color:var(--gold2)">{{ $sambJabatan }}</p>
                    </div>
                </div>

                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--gold2)">
                        Kata Sambutan
                    </p>
                    {{-- Sambutan tampil PENUH tanpa dibatasi --}}
                    <div class="sambutan-text">
                        {!! nl2br(e($sambutan)) !!}
                    </div>
                </div>
            </div>
            @endif

            {{-- Info PPDB & Kalender: tampil PENUH --}}
            @if($infoPpdb || $infoKal)
            <div class="flex flex-col gap-4">

                @if($infoPpdb)
                <div class="card p-5 sm:p-6" style="border-color:#fecaca;background:#fff5f5">
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-2 h-2 rounded-full pulse shrink-0" style="background:#ef4444"></span>
                        <span class="text-sm font-bold uppercase tracking-wider" style="color:#dc2626">
                            PPDB — Penerimaan Siswa Baru
                        </span>
                    </div>
                    {{-- Info PPDB tampil PENUH --}}
                    <div class="info-text" style="color:#374151">
                        {!! nl2br(e($infoPpdb)) !!}
                    </div>
                    <a href="{{ route('website.berita') }}"
                       class="inline-flex items-center gap-1 mt-4 text-sm font-bold transition-all hover:gap-2"
                       style="color:#dc2626">
                        Selengkapnya di halaman berita
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @endif

                @if($infoKal)
                <div class="card p-5 sm:p-6" style="border-color:#bfdbfe;background:#eff6ff">
                    <div class="flex items-center gap-2.5 mb-3">
                        <svg class="w-4 h-4 shrink-0" style="color:#2563eb" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-bold uppercase tracking-wider" style="color:#2563eb">
                            Kalender Akademik
                        </span>
                    </div>
                    {{-- Kalender tampil PENUH --}}
                    <div class="info-text" style="color:#374151">
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
{{-- Card berita: judul 2 baris, snippet 3 baris — sudah cukup untuk preview --}}
@if($beritaList->count())
<section class="bg-white py-12 lg:py-16" id="berita">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-7">
            <div>
                <div class="chip mb-2">Informasi & Kegiatan</div>
                <h2 class="font-lora text-2xl sm:text-3xl font-bold" style="color:var(--text)">Berita Terbaru</h2>
                <div class="gold-bar mt-2"><span></span><span></span></div>
            </div>
            <a href="{{ route('website.berita') }}"
               class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold border transition-all hover:-translate-y-0.5"
               style="color:var(--navy2);border-color:rgba(14,35,86,.2)">
                Semua Berita
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
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
                                <div class="w-14 h-14 rounded-full bg-black/65 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        @elseif($youtubeId)
                            <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg"
                                 alt="{{ $b->judul }}" loading="lazy" class="w-full h-full object-cover"
                                 onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/default.jpg';">
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-14 h-14 rounded-full bg-red-600/85 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        @elseif($fbPlaceholder)
                            <div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-7xl text-white/80">f</div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-5xl text-white/50">
                                {{ match($b->media_tipe) { 'video' => '🎥', 'link_youtube' => '▶️', 'link_facebook' => '📘', default => '🔗' } }}
                            </div>
                        @endif
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-6xl opacity-70">📰</div>
                    @endif
                </div>

                <div class="flex flex-col flex-1 p-5">
                    {{-- Badge baris atas --}}
                    <div class="flex items-center gap-1.5 mb-3 flex-wrap">
                        @if($b->is_penting)
                        <span class="px-2 py-0.5 rounded-full font-bold"
                              style="background:#fef2f2;color:#dc2626;font-size:.7rem">🔴 Penting</span>
                        @endif
                        @if($b->kategori)
                        <span class="px-2 py-0.5 rounded-full font-semibold"
                              style="background:#eff6ff;color:#1d4ed8;font-size:.7rem">{{ $b->kategori }}</span>
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
                            <span class="px-2 py-0.5 rounded-full font-medium {{ $badge[0] }}" style="font-size:.7rem">
                                {{ $badge[1] }}
                            </span>
                        @endif
                        <span class="ml-auto" style="color:#94a3b8;font-size:.72rem">{{ $b->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Judul: max 2 baris (wajar untuk preview card) --}}
                    <h3 class="berita-judul mb-2">{{ $b->judul }}</h3>

                    {{-- Snippet: max 3 baris, cukup untuk memberi gambaran --}}
                    @if($b->ringkasan || $b->isi)
                    <p class="berita-snippet mb-3">
                        {{ Str::limit(strip_tags($b->ringkasan ?? $b->isi), 120) }}
                    </p>
                    @endif

                    <div class="flex items-center gap-1.5 text-sm font-bold mt-auto transition-all group-hover:gap-2"
                         style="color:var(--navy2)">
                        Baca selengkapnya
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('website.berita') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold"
               style="background:var(--navy)">
                Lihat Semua Berita →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════ §8 GALERI ═══════════════════ --}}
@if($galeriList->count())
<section style="background:var(--cream)" class="py-12 lg:py-16" id="galeri">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-7">
            <div>
                <div class="chip mb-2">Dokumentasi</div>
                <h2 class="font-lora text-2xl sm:text-3xl font-bold" style="color:var(--text)">Galeri Kegiatan</h2>
                <div class="gold-bar mt-2"><span></span><span></span></div>
            </div>
            <a href="{{ route('website.galeri') }}"
               class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold border transition-all hover:-translate-y-0.5"
               style="color:var(--navy2);border-color:rgba(14,35,86,.2)">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($galeriList as $g)
            <a href="{{ route('website.galeri') }}" class="g-item">
                <img src="{{ $g->fileUrl ?? asset('storage/'.$g->file_path) }}" alt="{{ $g->judul }}" loading="lazy">
                <div class="g-veil"></div>
                <div class="g-cap">
                    <p class="text-white text-xs font-semibold leading-snug lc2">{{ $g->judul }}</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('website.galeri') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-bold"
               style="background:var(--navy)">
                Lihat Semua Galeri →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════ §9 KONTAK & PETA ═══════════════════ --}}
@if($kontakRow && ($kontakRow->kontak_alamat || $kontakRow->kontak_telepon || $kontakRow->kontak_email))
<section class="bg-white py-12 lg:py-16" id="kontak">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="sec-head">
            <div class="chip">Hubungi Kami</div>
            <h2>Kontak & Lokasi</h2>
            <div class="gold-bar"><span></span><span></span></div>
        </div>

        <div class="grid md:grid-cols-2 gap-5 items-start">
            <div class="space-y-3">
                @if($kontakRow->kontak_alamat)
                <div class="card flex gap-4 p-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-xl" style="background:rgba(14,35,86,.06)">📍</div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color:#94a3b8">Alamat</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $kontakRow->kontak_alamat }}</p>
                    </div>
                </div>
                @endif

                @if($kontakRow->kontak_telepon)
                <div class="card flex gap-4 p-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-xl" style="background:rgba(14,35,86,.06)">📞</div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color:#94a3b8">Telepon / WhatsApp</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$kontakRow->kontak_telepon) }}"
                           class="text-sm font-bold hover:underline" style="color:var(--navy2)">
                            {{ $kontakRow->kontak_telepon }}
                        </a>
                    </div>
                </div>
                @endif

                @if($kontakRow->kontak_email)
                <div class="card flex gap-4 p-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-xl" style="background:rgba(14,35,86,.06)">✉️</div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color:#94a3b8">Email</p>
                        <a href="mailto:{{ $kontakRow->kontak_email }}"
                           class="text-sm font-bold hover:underline" style="color:var(--navy2)">
                            {{ $kontakRow->kontak_email }}
                        </a>
                    </div>
                </div>
                @endif

                @if($kontakRow->sosmed_instagram || $kontakRow->sosmed_facebook || $kontakRow->sosmed_youtube || $kontakRow->sosmed_twitter)
                <div class="card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider mb-3" style="color:#94a3b8">Media Sosial</p>
                    <div class="flex flex-wrap gap-2">
                        @if($kontakRow->sosmed_instagram)
                        <a href="{{ $kontakRow->sosmed_instagram }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-white text-sm font-bold transition hover:opacity-90"
                           style="background:linear-gradient(135deg,#e1306c,#833ab4)">📸 Instagram</a>
                        @endif
                        @if($kontakRow->sosmed_facebook)
                        <a href="{{ $kontakRow->sosmed_facebook }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-white text-sm font-bold transition hover:opacity-90"
                           style="background:#1877f2">📘 Facebook</a>
                        @endif
                        @if($kontakRow->sosmed_youtube)
                        <a href="{{ $kontakRow->sosmed_youtube }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-white text-sm font-bold transition hover:opacity-90"
                           style="background:#ff0000">▶️ YouTube</a>
                        @endif
                        @if($kontakRow->sosmed_twitter)
                        <a href="{{ $kontakRow->sosmed_twitter }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-white text-sm font-bold transition hover:opacity-90"
                           style="background:#1da1f2">🐦 X / Twitter</a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            @if($kontakRow->kontak_maps_embed)
            <div class="card overflow-hidden" style="height:360px">
                <iframe src="{{ $kontakRow->kontak_maps_embed }}" width="100%" height="100%" style="border:0"
                        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            @else
            <div class="card flex items-center justify-center text-center p-10" style="height:360px;background:var(--cream)">
                <div>
                    <div class="text-5xl mb-3">🗺️</div>
                    <p class="text-sm text-slate-500 font-medium">Peta lokasi belum diatur</p>
                    <p class="text-xs text-slate-400 mt-1.5 max-w-xs mx-auto leading-relaxed">
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
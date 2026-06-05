
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Website Resmi <?php echo e(\App\Models\SchoolSetting::get('nama_sekolah','SMP Negeri Kutime')); ?> — Informasi sekolah, berita, pengumuman, dan galeri kegiatan.">
    <meta name="theme-color" content="#0e2356">

    <title><?php echo $__env->yieldContent('title', 'Beranda'); ?> — <?php echo e(\App\Models\SchoolSetting::get('singkatan','SMPN Kutime')); ?></title>

    
    <?php $faviconUrl = \App\Models\SchoolSetting::faviconUrl(); ?>
    <?php if($faviconUrl): ?>
    <link rel="icon" type="image/png" href="<?php echo e($faviconUrl); ?>">
    <?php else: ?>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏫</text></svg>">
    <?php endif; ?>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:  { DEFAULT:'#0e2356', light:'#183580', dark:'#081535' },
                        gold:  { DEFAULT:'#c8a84b', light:'#e8c96b', dark:'#a07c2a' },
                        cream: { DEFAULT:'#f8f5ef', dark:'#f0ebe0' },
                    },
                    fontFamily: {
                        lora:    ['"Lora"', 'Georgia', 'serif'],
                        jakarta: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; -webkit-font-smoothing: antialiased; }

        /* ── Navbar ── */
        .navbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(226,232,240,0.7);
            transition: box-shadow .25s ease, background .25s ease;
        }
        .navbar.scrolled {
            box-shadow: 0 4px 24px rgba(14,35,86,.10);
            background: rgba(255,255,255,0.97);
        }
        .nav-link {
            position: relative; padding: 6px 14px; border-radius: 10px;
            font-size: .8125rem; font-weight: 600; color: #475569;
            transition: color .18s, background .18s;
            text-decoration: none;
        }
        .nav-link:hover { color: #0e2356; background: rgba(14,35,86,.055); }
        .nav-link.active { color: #0e2356; background: rgba(14,35,86,.07); }
        .nav-link.active::after {
            content: ''; position: absolute; bottom: 3px; left: 50%; transform: translateX(-50%);
            width: 18px; height: 2.5px; border-radius: 99px; background: #c8a84b;
        }

        .mobile-nav { transition: max-height .32s cubic-bezier(.4,0,.2,1), opacity .28s ease; overflow: hidden; }
        .mobile-nav.closed { max-height: 0; opacity: 0; pointer-events: none; }
        .mobile-nav.open   { max-height: 480px; opacity: 1; }

        .footer-link { color: #94a3b8; font-size: .8125rem; text-decoration: none; transition: color .18s; display: inline-block; }
        .footer-link:hover { color: #c8a84b; }

        #scrollTop {
            position: fixed; bottom: 24px; right: 24px; z-index: 40;
            width: 42px; height: 42px; border-radius: 12px;
            background: #0e2356; color: #fff; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(14,35,86,.3);
            opacity: 0; transform: translateY(12px);
            transition: opacity .3s, transform .3s, background .18s;
        }
        #scrollTop.visible { opacity: 1; transform: translateY(0); }
        #scrollTop:hover { background: #183580; }

        @keyframes marquee { from{transform:translateX(100%)} to{transform:translateX(-100%)} }
        .marquee-run { animation: marquee 30s linear infinite; white-space: nowrap; }

        @keyframes pageFadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        main { animation: pageFadeIn .45s ease both; }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="bg-cream min-h-screen flex flex-col" style="background:#f8f5ef">

<?php
    $logoUrl     = \App\Models\SchoolSetting::logoUrl();
    $namaSekolah = \App\Models\SchoolSetting::get('nama_sekolah', 'SMP Negeri Kutime');
    $singkatan   = \App\Models\SchoolSetting::get('singkatan',   'SMPN Kutime');

    $navItems = [
        ['route' => 'website.home',   'label' => 'Beranda'],
        ['route' => 'website.berita', 'label' => 'Berita'],
        ['route' => 'website.galeri', 'label' => 'Galeri'],
    ];
?>

<header class="navbar" id="navbar" x-data="{ open: false }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18">

            
            <a href="<?php echo e(route('website.home')); ?>" class="flex items-center gap-3 group min-w-0">
                <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 flex items-center justify-center shadow-sm border"
                     style="background:#fff;border-color:rgba(14,35,86,.12)">
                    <?php if($logoUrl): ?>
                    <img src="<?php echo e($logoUrl); ?>" alt="Logo <?php echo e($singkatan); ?>" class="w-full h-full object-contain p-1.5">
                    <?php else: ?>
                    <span class="text-xl">🏫</span>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <div class="font-lora font-bold text-base leading-tight truncate" style="color:#0e2356"><?php echo e($singkatan); ?></div>
                    <div class="text-xs truncate" style="color:#94a3b8;font-size:.68rem"><?php echo e($namaSekolah); ?></div>
                </div>
            </a>

            
            <nav class="hidden md:flex items-center gap-1">
                <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($item['route'])); ?>"
                   class="nav-link <?php echo e(request()->routeIs($item['route']) ? 'active' : ''); ?>">
                    <?php echo e($item['label']); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(request()->routeIs('website.home')): ?>
                <a href="#tentang" class="nav-link">Tentang</a>
                <?php endif; ?>

                
                <?php if(auth()->guard()->check()): ?>
                    
                    <?php
                        $dashRoute = match(auth()->user()->role) {
                            'admin' => 'admin.dashboard',
                            'guru'  => 'guru.dashboard',
                            'siswa' => 'siswa.dashboard',
                            default => 'website.home',
                        };
                    ?>
                    <a href="<?php echo e(route($dashRoute)); ?>"
                       class="ml-3 px-4 py-2 rounded-xl text-white text-xs font-bold shadow-sm
                              hover:shadow-md transition-all inline-flex items-center gap-1.5"
                       style="background:#0e2356;font-size:.8rem">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5"/>
                        </svg>
                        Dashboard
                    </a>
                <?php else: ?>
                    
                    <a href="<?php echo e(route('login')); ?>"
                       class="ml-3 px-4 py-2 rounded-xl text-white text-xs font-bold shadow-sm
                              hover:shadow-md hover:opacity-90 transition-all inline-flex items-center gap-1.5"
                       style="background:#0e2356;font-size:.8rem">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk
                    </a>
                <?php endif; ?>
            </nav>

            
            <button class="md:hidden flex flex-col justify-center items-center w-9 h-9 rounded-lg
                           gap-1.5 hover:bg-slate-100 transition-colors"
                    @click="open = !open" :aria-expanded="open" aria-label="Toggle menu">
                <span class="block h-0.5 w-5 rounded-full transition-all duration-300"
                      :class="open ? 'rotate-45 translate-y-2' : ''" style="background:#0e2356"></span>
                <span class="block h-0.5 w-5 rounded-full transition-all duration-300"
                      :class="open ? 'opacity-0 scale-x-0' : ''" style="background:#0e2356"></span>
                <span class="block h-0.5 w-5 rounded-full transition-all duration-300"
                      :class="open ? '-rotate-45 -translate-y-2' : ''" style="background:#0e2356"></span>
            </button>
        </div>
    </div>

    
    <div class="md:hidden mobile-nav px-4 pb-3" :class="open ? 'open' : 'closed'">
        <div class="pt-2 space-y-0.5 border-t" style="border-color:rgba(226,232,240,.7)">
            <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($item['route'])); ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
                      <?php echo e(request()->routeIs($item['route']) ? 'text-navy bg-blue-50' : 'text-slate-700 hover:bg-slate-50'); ?>"
               style="<?php echo e(request()->routeIs($item['route']) ? 'color:#0e2356;background:rgba(14,35,86,.06)' : ''); ?>"
               @click="open = false">
                <?php echo e($item['label']); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(request()->routeIs('website.home')): ?>
            <a href="#tentang" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                                      text-slate-700 hover:bg-slate-50" @click="open=false">Tentang</a>
            <a href="#kontak"  class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                                      text-slate-700 hover:bg-slate-50" @click="open=false">Kontak</a>
            <?php endif; ?>

            <div class="pt-2 pb-1">
                <?php if(auth()->guard()->check()): ?>
                    <?php
                        $dashRoute = match(auth()->user()->role) {
                            'admin' => 'admin.dashboard',
                            'guru'  => 'guru.dashboard',
                            'siswa' => 'siswa.dashboard',
                            default => 'website.home',
                        };
                    ?>
                    <a href="<?php echo e(route($dashRoute)); ?>"
                       class="flex items-center justify-center w-full py-2.5 rounded-xl
                              text-white text-sm font-bold gap-2"
                       style="background:#0e2356">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5"/>
                        </svg>
                        Ke Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>"
                       class="flex items-center justify-center w-full py-2.5 rounded-xl
                              text-white text-sm font-bold gap-2"
                       style="background:#0e2356">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk ke Sistem
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main class="flex-1">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<?php
    $kontakFooter  = \App\Models\PageContent::getKontak();
    $taglineFooter = \App\Models\SchoolSetting::get('tagline_footer','Menyiapkan generasi unggul, berakhlak, dan berprestasi melalui pendidikan berkualitas.');
?>

<footer style="background:#0a1e47;color:#94a3b8">
    <div style="line-height:0;background:#f8f5ef">
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 40 C480 0 960 0 1440 40 L1440 0 L0 0 Z" fill="#0a1e47"/>
        </svg>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">

            
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl overflow-hidden flex items-center justify-center shrink-0 border"
                         style="background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.1)">
                        <?php if($logoUrl): ?>
                        <img src="<?php echo e($logoUrl); ?>" alt="Logo <?php echo e($singkatan); ?>" class="w-full h-full object-contain p-1.5">
                        <?php else: ?>
                        <span class="text-xl">🏫</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="font-lora font-bold text-white text-base leading-tight"><?php echo e($singkatan); ?></div>
                        <div class="text-xs" style="color:#94a3b8;font-size:.7rem"><?php echo e($namaSekolah); ?></div>
                    </div>
                </div>
                <p class="text-sm leading-relaxed" style="color:#6b7fa8;max-width:220px"><?php echo e($taglineFooter); ?></p>

                <?php if($kontakFooter): ?>
                <div class="flex gap-2.5 mt-5">
                    <?php if($kontakFooter->sosmed_instagram): ?>
                    <a href="<?php echo e($kontakFooter->sosmed_instagram); ?>" target="_blank" rel="noopener" title="Instagram"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10"
                       style="background:rgba(255,255,255,.07)">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" style="color:#e1306c">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($kontakFooter->sosmed_facebook): ?>
                    <a href="<?php echo e($kontakFooter->sosmed_facebook); ?>" target="_blank" rel="noopener" title="Facebook"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10"
                       style="background:rgba(255,255,255,.07)">
                        <svg class="w-4 h-4" fill="#1877f2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($kontakFooter->sosmed_youtube): ?>
                    <a href="<?php echo e($kontakFooter->sosmed_youtube); ?>" target="_blank" rel="noopener" title="YouTube"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10"
                       style="background:rgba(255,255,255,.07)">
                        <svg class="w-4 h-4" fill="#ff0000" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if($kontakFooter->sosmed_twitter): ?>
                    <a href="<?php echo e($kontakFooter->sosmed_twitter); ?>" target="_blank" rel="noopener" title="X / Twitter"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10"
                       style="background:rgba(255,255,255,.07)">
                        <svg class="w-4 h-4" fill="#1da1f2" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63 5.905-5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            
            <div>
                <h4 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full inline-block" style="background:#c8a84b"></span>
                    Tautan Cepat
                </h4>
                <ul class="space-y-2.5">
                    <?php $__currentLoopData = [
                        ['route'=>'website.home',   'label'=>'Beranda'],
                        ['route'=>'website.berita', 'label'=>'Berita & Pengumuman'],
                        ['route'=>'website.galeri', 'label'=>'Galeri Kegiatan'],
                        ['route'=>'login',          'label'=>'Login Sistem'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route($fl['route'])); ?>" class="footer-link flex items-center gap-2">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                            <?php echo e($fl['label']); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route('website.home')); ?>#tentang" class="footer-link flex items-center gap-2">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                            Tentang Sekolah
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('website.home')); ?>#kontak" class="footer-link flex items-center gap-2">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                            Hubungi Kami
                        </a>
                    </li>
                </ul>
            </div>

            
            <div>
                <h4 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full inline-block" style="background:#c8a84b"></span>
                    Hubungi Kami
                </h4>
                <ul class="space-y-3 text-sm" style="color:#6b7fa8">
                    <?php if($kontakFooter?->kontak_alamat): ?>
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color:#c8a84b" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="leading-relaxed"><?php echo e($kontakFooter->kontak_alamat); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if($kontakFooter?->kontak_telepon): ?>
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" style="color:#c8a84b" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/','',$kontakFooter->kontak_telepon)); ?>" class="footer-link"><?php echo e($kontakFooter->kontak_telepon); ?></a>
                    </li>
                    <?php else: ?>
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" style="color:#c8a84b" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>Belum diatur</span>
                    </li>
                    <?php endif; ?>
                    <?php if($kontakFooter?->kontak_email): ?>
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" style="color:#c8a84b" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:<?php echo e($kontakFooter->kontak_email); ?>" class="footer-link"><?php echo e($kontakFooter->kontak_email); ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            
            <div>
                <h4 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full inline-block" style="background:#c8a84b"></span>
                    Jam Operasional
                </h4>
                <ul class="space-y-2.5 text-sm" style="color:#6b7fa8">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color:#c8a84b" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-white text-xs font-semibold">Senin – Jumat</p>
                            <p>07.00 – 15.30 WITA</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color:#6b7fa8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-white text-xs font-semibold">Sabtu</p>
                            <p>07.00 – 12.00 WITA</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color:#6b7fa8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold" style="color:#6b7fa8">Minggu & Hari Libur</p>
                            <p>Tutup</p>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <div class="pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-3"
             style="border-color:rgba(255,255,255,.07)">
            <p class="text-xs" style="color:#4b5e82">
                &copy; <?php echo e(date('Y')); ?> <?php echo e($namaSekolah); ?>. Hak cipta dilindungi undang-undang.
            </p>
            <p class="text-xs" style="color:#4b5e82">
                Dikembangkan oleh Tim IT Sekolah
            </p>
        </div>
    </div>
</footer>

<button id="scrollTop" aria-label="Kembali ke atas"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
    </svg>
</button>

<script>
    var navbar    = document.getElementById('navbar');
    var scrollBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', function() {
        var scrolled = window.scrollY > 40;
        if (navbar)    navbar.classList.toggle('scrolled', scrolled);
        if (scrollBtn) scrollBtn.classList.toggle('visible', window.scrollY > 320);
    }, { passive: true });
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH S:\PA3\smpn-kutime\resources\views/layouts/public.blade.php ENDPATH**/ ?>
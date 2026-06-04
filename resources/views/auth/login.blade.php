<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — {{ \App\Models\SchoolSetting::get('singkatan', 'SMPN Kutime') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            margin: 0;
            min-height: 100vh;
        }

        /* ── Background ─────────────────────────────────── */
        .bg-hero { position: fixed; inset: 0; z-index: 0; }
        .bg-hero-media {
            position: absolute; inset: 0;
            width: 100%; height: 100%; object-fit: cover;
        }
        .bg-hero-veil {
            position: absolute; inset: 0;
            background: linear-gradient(
                135deg,
                rgba(9,18,48,.62)  0%,
                rgba(12,28,76,.50) 45%,
                rgba(9,18,48,.58)  100%
            );
        }
        .bg-hero-pattern {
            position: absolute; inset: 0;
            background: #0e2356;
            background-image:
                linear-gradient(30deg,  rgba(200,168,75,.08) 12%, transparent 12.5%, transparent 87%, rgba(200,168,75,.08) 87.5%),
                linear-gradient(150deg, rgba(200,168,75,.08) 12%, transparent 12.5%, transparent 87%, rgba(200,168,75,.08) 87.5%);
            background-size: 40px 70px;
        }
        .bg-glow-r {
            position: absolute; bottom: -80px; right: -80px;
            width: 420px; height: 420px; border-radius: 50%;
            background: radial-gradient(circle, rgba(200,168,75,.14), transparent 68%);
            pointer-events: none;
        }
        .bg-glow-l {
            position: absolute; top: -60px; left: -60px;
            width: 280px; height: 280px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.05), transparent 70%);
            pointer-events: none;
        }

        /* ── Card ────────────────────────────────────────── */
        .login-card {
            position: relative; z-index: 10;
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,.28), 0 0 0 1px rgba(255,255,255,.20);
            width: 100%;
            max-width: 360px;   /* ← diperkecil dari 420px */
            overflow: hidden;
            animation: cardIn .45s cubic-bezier(.22,1,.36,1) both;
        }

        /* ── Top accent bar ──────────────────────────────── */
        .card-accent {
            height: 3px;
            background: linear-gradient(90deg, #0e2356 0%, #c8a84b 50%, #0e2356 100%);
        }

        /* ── Logo ────────────────────────────────────────── */
        .logo-wrap {
            width: 80px; height: 80px;   /* ← lebih besar dari sebelumnya */
            border-radius: 18px;
            background: #fff;
            border: 1.5px solid rgba(14,35,86,.12);
            box-shadow: 0 6px 20px rgba(14,35,86,.16);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            margin: 0 auto 10px;
        }

        /* ── Gold divider ────────────────────────────────── */
        .gold-bar { display: flex; align-items: center; gap: 4px; justify-content: center; }
        .gold-bar span:first-child { display: block; width: 22px; height: 2px; border-radius: 99px; background: #c8a84b; }
        .gold-bar span:last-child  { display: block; width: 8px;  height: 2px; border-radius: 99px; background: #c8a84b; opacity: .4; }

        /* ── Input ───────────────────────────────────────── */
        .form-input {
            width: 100%;
            padding: 8px 10px 8px 34px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-size: .8rem;
            font-family: inherit;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color .18s, box-shadow .18s, background .18s;
            outline: none;
        }
        .form-input:focus {
            border-color: #0e2356;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14,35,86,.09);
        }
        .form-input.has-error { border-color: #f87171; }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; pointer-events: none;
            display: flex; align-items: center;
        }

        /* ── Password toggle ─────────────────────────────── */
        .pw-toggle {
            position: absolute; right: 9px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: #94a3b8;
            padding: 2px; transition: color .18s; display: flex; align-items: center;
        }
        .pw-toggle:hover { color: #0e2356; }

        /* ── Submit button ───────────────────────────────── */
        .btn-login {
            width: 100%;
            padding: 9px;
            border-radius: 10px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #0e2356 0%, #183580 100%);
            color: #fff; font-weight: 700; font-size: .82rem; font-family: inherit;
            letter-spacing: .01em;
            box-shadow: 0 4px 14px rgba(14,35,86,.28);
            transition: transform .15s, box-shadow .15s;
            position: relative; overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(14,35,86,.36);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(200,168,75,.16) 50%, transparent 70%);
            opacity: 0; transition: opacity .25s;
        }
        .btn-login:hover::after { opacity: 1; }

        /* ── Form label ──────────────────────────────────── */
        .form-label {
            display: block;
            font-size: .7rem; font-weight: 600; color: #475569;
            margin-bottom: 4px; letter-spacing: .01em; text-transform: uppercase;
        }

        /* ── Error alert ─────────────────────────────────── */
        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 9px; padding: 9px 11px;
            color: #dc2626; font-size: .75rem; line-height: 1.5;
        }

        /* ── Checkbox ────────────────────────────────────── */
        input[type="checkbox"] { accent-color: #0e2356; width: 13px; height: 13px; cursor: pointer; }

        /* ── Entrance animation ──────────────────────────── */
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px) scale(.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</head>

<body>

{{-- ── Background (sama dengan hero website) ─────────────── --}}
@php
    use App\Models\PageContent;
    use App\Models\SchoolSetting;

    $logoUrl     = SchoolSetting::logoUrl();
    $singkatan   = SchoolSetting::get('singkatan',   'SMPN Kutime');
    $namaSekolah = SchoolSetting::get('nama_sekolah', 'SMP Negeri Kutime');

    $heroMedia  = PageContent::getHeroMedia();
    $hmTipe     = $heroMedia?->hero_media_tipe ?? 'none';
    $hmFileUrls = $heroMedia?->heroFilesUrls   ?? [];
    $hmEmbedUrl = $heroMedia?->heroYoutubeEmbed;
@endphp

<div class="bg-hero">
    @if($hmTipe === 'image' && !empty($hmFileUrls))
        <img src="{{ $hmFileUrls[0] }}" alt="" class="bg-hero-media">
    @elseif($hmTipe === 'video' && !empty($hmFileUrls))
        <video autoplay muted loop playsinline class="bg-hero-media">
            <source src="{{ $hmFileUrls[0] }}" type="video/mp4">
        </video>
    @elseif($hmTipe === 'youtube' && $hmEmbedUrl)
        <div style="position:absolute;inset:0;overflow:hidden">
            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                        width:177.78vh;height:56.25vw;min-width:100%;min-height:100%;pointer-events:none">
                <iframe src="{{ $hmEmbedUrl }}" style="width:100%;height:100%;border:0"
                        allow="autoplay;encrypted-media" frameborder="0"></iframe>
            </div>
        </div>
    @elseif($hmTipe === 'slideshow' && !empty($hmFileUrls))
        <img src="{{ $hmFileUrls[0] }}" alt="" class="bg-hero-media">
    @else
        <div class="bg-hero-pattern"></div>
    @endif
    <div class="bg-hero-veil"></div>
    <div class="bg-glow-r"></div>
    <div class="bg-glow-l"></div>
</div>

{{-- ── Wrapper ──────────────────────────────────────────────── --}}
<div style="position:relative;z-index:10;min-height:100vh;
            display:flex;flex-direction:column;align-items:center;justify-content:center;
            padding:20px 16px">

    <div class="login-card">

        {{-- Accent bar --}}
        <div class="card-accent"></div>

        <div style="padding:22px 24px 26px">

            {{-- ── Logo + Nama Sekolah ─────────────────────── --}}
            <div style="text-align:center;margin-bottom:18px">

                {{-- Logo — diperbesar --}}
                <div class="logo-wrap">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo {{ $singkatan }}"
                             style="width:100%;height:100%;object-fit:contain;padding:9px">
                    @else
                        <span style="font-size:2.4rem">🏫</span>
                    @endif
                </div>

                {{-- Singkatan --}}
                <h1 style="font-family:'Lora',serif;font-weight:700;font-size:1.1rem;
                           color:#0e2356;margin:0 0 2px;line-height:1.2">
                    {{ $singkatan }}
                </h1>

                {{-- Nama sekolah --}}
                <p style="font-size:.68rem;color:#94a3b8;margin:0 0 10px;font-weight:500">
                    {{ $namaSekolah }}
                </p>

                {{-- Gold divider --}}
                <div class="gold-bar" style="margin-bottom:10px">
                    <span></span><span></span>
                </div>

                <p style="font-size:.73rem;color:#64748b;font-weight:500;margin:0">
                    Silakan masuk untuk melanjutkan
                </p>
            </div>

            {{-- ── Error ──────────────────────────────────── --}}
            @if($errors->any())
                <div class="alert-error" style="margin-bottom:14px">
                    <div style="display:flex;align-items:flex-start;gap:7px">
                        <svg style="width:13px;height:13px;flex-shrink:0;margin-top:1px"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                                     3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <p style="margin:0;line-height:1.5">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(session('status'))
                <div style="margin-bottom:14px;padding:9px 11px;background:#f0fdf4;
                            border:1px solid #bbf7d0;border-radius:9px;
                            color:#16a34a;font-size:.75rem">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ── Form ───────────────────────────────────── --}}
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email --}}
                <div style="margin-bottom:11px">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg style="width:13px;height:13px" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0
                                         002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               required autofocus
                               class="form-input {{ $errors->has('email') ? 'has-error' : '' }}">
                    </div>
                </div>

                {{-- Password --}}
                <div style="margin-bottom:11px" x-data="{ show: false }">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg style="width:13px;height:13px" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0
                                         00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input :type="show ? 'text' : 'password'"
                               name="password" id="password"
                               placeholder="••••••••"
                               required
                               class="form-input {{ $errors->has('password') ? 'has-error' : '' }}"
                               style="padding-right:32px">
                        <button type="button" class="pw-toggle" @click="show = !show">
                            <svg x-show="!show" style="width:13px;height:13px"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                         9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" style="width:13px;height:13px;display:none"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97
                                         9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember + back link --}}
                <div style="display:flex;align-items:center;justify-content:space-between;
                            margin-bottom:16px">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer">
                        <input type="checkbox" name="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <span style="font-size:.73rem;color:#64748b;font-weight:500">Ingat saya</span>
                    </label>
                    <a href="{{ route('website.home') }}"
                       style="font-size:.7rem;color:#0e2356;font-weight:600;
                              text-decoration:none;opacity:.7;transition:opacity .15s"
                       onmouseover="this.style.opacity=1"
                       onmouseout="this.style.opacity=.7">
                        ← Website
                    </a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    <span style="display:flex;align-items:center;justify-content:center;gap:6px">
                        <svg style="width:13px;height:13px" fill="none" stroke="currentColor"
                             stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0
                                     01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk ke Sistem
                    </span>
                </button>

            </form>
        </div>
    </div>

    {{-- Footer --}}
    <p style="margin-top:16px;font-size:.67rem;color:rgba(255,255,255,.40);text-align:center">
        © {{ date('Y') }} {{ $namaSekolah }}
    </p>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'SMPN Kutime') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd',
                            300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9',
                            600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        /* ═══════════════════════════════════════════
           UKURAN PERMANEN — GLOBAL CONTENT SCALE
           Semua konten dalam layout ini menggunakan
           skala kompak yang konsisten
        ═══════════════════════════════════════════ */

        /* Reset base */
        *, *::before, *::after { box-sizing: border-box; }

        /* ── Root font size: 13px kompak ── */
        html { font-size: 13px; }

        /* ── Scrollbar tipis ── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ══════════════════════════════════════════
           TYPOGRAPHY SCALE — PERMANEN
        ══════════════════════════════════════════ */
        body {
            font-size: 0.846rem;   /* ~11px efektif */
            line-height: 1.55;
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Heading permanen di dalam konten */
        #mainContent h1,
        #mainContent .heading-xl  { font-size: 1.077rem; font-weight: 600; line-height: 1.3; }  /* ~14px */

        #mainContent h2,
        #mainContent .heading-lg  { font-size: 0.923rem; font-weight: 600; line-height: 1.35; } /* ~12px */

        #mainContent h3,
        #mainContent .heading-md  { font-size: 0.846rem; font-weight: 600; line-height: 1.4; }  /* ~11px */

        #mainContent h4,
        #mainContent .heading-sm  { font-size: 0.769rem; font-weight: 500; line-height: 1.4; }  /* ~10px */

        /* Label / caption kecil */
        #mainContent .label-xs,
        #mainContent .text-2xs    { font-size: 0.692rem; line-height: 1.3; }  /* ~9px */

        /* ══════════════════════════════════════════
           SPACING PERMANEN — CARD & SECTION
        ══════════════════════════════════════════ */

        /* Card / panel standar */
        #mainContent .card {
            background: #ffffff;
            border-radius: 0.692rem;
            border: 1px solid #e2e8f0;
            padding: 0.923rem 1rem;
            font-size: 0.846rem;
        }
        .dark #mainContent .card {
            background: #1e293b;
            border-color: #334155;
        }

        /* Card header row */
        #mainContent .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.769rem;
            padding-bottom: 0.615rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark #mainContent .card-header { border-color: #334155; }

        /* Card title */
        #mainContent .card-title {
            font-size: 0.846rem;
            font-weight: 600;
            color: #1e293b;
        }
        .dark #mainContent .card-title { color: #e2e8f0; }

        /* ── KPI / Stat card ── */
        #mainContent .stat-card {
            background: #f8fafc;
            border-radius: 0.615rem;
            border: 1px solid #e2e8f0;
            padding: 0.769rem 0.923rem;
        }
        .dark #mainContent .stat-card {
            background: #0f172a;
            border-color: #334155;
        }
        #mainContent .stat-value {
            font-size: 1.385rem;  /* ~18px — angka besar tapi tidak berlebihan */
            font-weight: 700;
            line-height: 1.1;
            color: #1e293b;
        }
        .dark #mainContent .stat-value { color: #f1f5f9; }

        #mainContent .stat-label {
            font-size: 0.692rem;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 0.25rem;
        }

        #mainContent .stat-badge {
            font-size: 0.692rem;
            font-weight: 600;
            padding: 0.154rem 0.462rem;
            border-radius: 99px;
        }

        /* ══════════════════════════════════════════
           TABLE — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.769rem;
        }
        #mainContent table th {
            font-size: 0.692rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            padding: 0.462rem 0.615rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            background: #f8fafc;
        }
        .dark #mainContent table th {
            color: #94a3b8;
            border-color: #334155;
            background: #0f172a;
        }
        #mainContent table td {
            padding: 0.462rem 0.615rem;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
            vertical-align: middle;
        }
        .dark #mainContent table td {
            border-color: #1e293b;
            color: #cbd5e1;
        }
        #mainContent table tbody tr:hover {
            background: #f8fafc;
        }
        .dark #mainContent table tbody tr:hover { background: #1e293b; }
        #mainContent table tbody tr:last-child td { border-bottom: none; }

        /* ══════════════════════════════════════════
           FORM ELEMENTS — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent input,
        #mainContent select,
        #mainContent textarea {
            font-size: 0.769rem;
            line-height: 1.5;
            padding: 0.385rem 0.615rem;
            border-radius: 0.462rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #1e293b;
            width: 100%;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .dark #mainContent input,
        .dark #mainContent select,
        .dark #mainContent textarea {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }
        #mainContent input:focus,
        #mainContent select:focus,
        #mainContent textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99,102,241,.15);
        }
        #mainContent label {
            font-size: 0.692rem;
            font-weight: 500;
            color: #475569;
            display: block;
            margin-bottom: 0.3rem;
        }
        .dark #mainContent label { color: #94a3b8; }

        /* ── Form group spacing ── */
        #mainContent .form-group { margin-bottom: 0.769rem; }

        /* ══════════════════════════════════════════
           BUTTON — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.308rem;
            padding: 0.385rem 0.769rem;
            border-radius: 0.462rem;
            font-size: 0.769rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .15s;
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
        }
        #mainContent .btn svg { width: 0.923rem; height: 0.923rem; flex-shrink: 0; }

        #mainContent .btn-primary {
            background: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
        }
        #mainContent .btn-primary:hover { background: #4338ca; border-color: #4338ca; }

        #mainContent .btn-secondary {
            background: #f8fafc;
            color: #475569;
            border-color: #e2e8f0;
        }
        .dark #mainContent .btn-secondary {
            background: #1e293b;
            color: #94a3b8;
            border-color: #334155;
        }
        #mainContent .btn-secondary:hover { background: #f1f5f9; }

        #mainContent .btn-danger {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }
        #mainContent .btn-danger:hover { background: #fee2e2; }

        #mainContent .btn-sm {
            padding: 0.231rem 0.538rem;
            font-size: 0.692rem;
            border-radius: 0.385rem;
        }
        #mainContent .btn-sm svg { width: 0.769rem; height: 0.769rem; }

        /* ══════════════════════════════════════════
           BADGE / PILL — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.231rem;
            padding: 0.154rem 0.462rem;
            border-radius: 99px;
            font-size: 0.615rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        #mainContent .badge-success  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        #mainContent .badge-warning  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        #mainContent .badge-danger   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        #mainContent .badge-info     { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        #mainContent .badge-neutral  { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        .dark #mainContent .badge-success { background: rgba(5,150,105,.15); color: #34d399; border-color: rgba(5,150,105,.3); }
        .dark #mainContent .badge-warning { background: rgba(217,119,6,.15);  color: #fbbf24; border-color: rgba(217,119,6,.3); }
        .dark #mainContent .badge-danger  { background: rgba(220,38,38,.15);  color: #f87171; border-color: rgba(220,38,38,.3); }
        .dark #mainContent .badge-info    { background: rgba(37,99,235,.15);  color: #60a5fa; border-color: rgba(37,99,235,.3); }
        .dark #mainContent .badge-neutral { background: #1e293b; color: #64748b; border-color: #334155; }

        /* ══════════════════════════════════════════
           ALERT / FLASH — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.538rem;
            padding: 0.615rem 0.769rem;
            border-radius: 0.538rem;
            font-size: 0.769rem;
            font-weight: 500;
            margin-bottom: 0.769rem;
            border: 1px solid transparent;
        }
        #mainContent .alert svg { width: 0.923rem; height: 0.923rem; flex-shrink: 0; margin-top: 0.1rem; }
        #mainContent .alert-success { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        #mainContent .alert-error   { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        #mainContent .alert-warning { background: #fffbeb; color: #92400e; border-color: #fde68a; }
        #mainContent .alert-info    { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }

        /* ══════════════════════════════════════════
           GRID UTILITIES — PERMANEN
        ══════════════════════════════════════════ */
        #mainContent .grid-kpi-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.615rem; }
        #mainContent .grid-kpi-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.615rem; }
        #mainContent .grid-kpi-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.615rem; }

        @media (max-width: 640px) {
            #mainContent .grid-kpi-3,
            #mainContent .grid-kpi-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 400px) {
            #mainContent .grid-kpi-2,
            #mainContent .grid-kpi-3,
            #mainContent .grid-kpi-4 { grid-template-columns: 1fr; }
        }

        /* ══════════════════════════════════════════
           DIVIDER
        ══════════════════════════════════════════ */
        #mainContent .divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 0.615rem 0;
        }
        .dark #mainContent .divider { border-color: #334155; }

        /* ══════════════════════════════════════════
           AVATAR
        ══════════════════════════════════════════ */
        #mainContent .avatar {
            border-radius: 0.462rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }
        #mainContent .avatar-sm { width: 1.846rem; height: 1.846rem; font-size: 0.615rem; }
        #mainContent .avatar-md { width: 2.308rem; height: 2.308rem; font-size: 0.769rem; }
        #mainContent .avatar-lg { width: 3rem;     height: 3rem;     font-size: 1rem; }

        /* ══════════════════════════════════════════
           LIST ITEM
        ══════════════════════════════════════════ */
        #mainContent .list-item {
            display: flex;
            align-items: center;
            gap: 0.538rem;
            padding: 0.462rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.769rem;
        }
        .dark #mainContent .list-item { border-color: #1e293b; }
        #mainContent .list-item:last-child { border-bottom: none; padding-bottom: 0; }

        /* ══════════════════════════════════════════
           SECTION HEADER (judul section konten)
        ══════════════════════════════════════════ */
        #mainContent .section-title {
            font-size: 0.846rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.615rem;
        }
        .dark #mainContent .section-title { color: #e2e8f0; }

        /* ══════════════════════════════════════════
           EMPTY STATE
        ══════════════════════════════════════════ */
        #mainContent .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #94a3b8;
            font-size: 0.769rem;
        }
        #mainContent .empty-state svg {
            width: 2.5rem;
            height: 2.5rem;
            margin: 0 auto 0.615rem;
            opacity: .4;
        }

        /* ══════════════════════════════════════════
           PAGINATION
        ══════════════════════════════════════════ */
        #mainContent .pagination {
            display: flex;
            align-items: center;
            gap: 0.308rem;
            font-size: 0.692rem;
        }
        #mainContent .pagination a,
        #mainContent .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.692rem;
            height: 1.692rem;
            padding: 0 0.384rem;
            border-radius: 0.385rem;
            border: 1px solid #e2e8f0;
            color: #475569;
            text-decoration: none;
            transition: all .15s;
        }
        #mainContent .pagination a:hover { background: #f1f5f9; }
        #mainContent .pagination .active { background: #4f46e5; color: #fff; border-color: #4f46e5; font-weight: 600; }
        #mainContent .pagination .disabled { opacity: .4; pointer-events: none; }

        /* ══════════════════════════════════════════
           PROGRESS BAR
        ══════════════════════════════════════════ */
        #mainContent .progress-bar {
            height: 0.385rem;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
        }
        .dark #mainContent .progress-bar { background: #334155; }
        #mainContent .progress-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: #4f46e5;
            transition: width .4s ease;
        }

        /* ══════════════════════════════════════════
           ICON BUTTON
        ══════════════════════════════════════════ */
        #mainContent .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.692rem;
            height: 1.692rem;
            border-radius: 0.385rem;
            border: 1px solid #e2e8f0;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            transition: all .15s;
        }
        #mainContent .icon-btn svg { width: 0.846rem; height: 0.846rem; }
        #mainContent .icon-btn:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }
        .dark #mainContent .icon-btn { border-color: #334155; color: #64748b; }
        .dark #mainContent .icon-btn:hover { background: #1e293b; color: #e2e8f0; }

        /* ══════════════════════════════════════════
           MODAL — PERMANEN
        ══════════════════════════════════════════ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-box {
            background: #fff;
            border-radius: 0.923rem;
            width: 100%;
            max-width: 28rem;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .dark .modal-box { background: #1e293b; }
        .modal-header {
            padding: 0.923rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dark .modal-header { border-color: #334155; }
        .modal-title { font-size: 0.923rem; font-weight: 600; color: #1e293b; }
        .dark .modal-title { color: #e2e8f0; }
        .modal-body { padding: 0.923rem 1rem; font-size: 0.769rem; }
        .modal-footer {
            padding: 0.769rem 1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 0.462rem;
        }
        .dark .modal-footer { border-color: #334155; }

        /* ══════════════════════════════════════════
           SIDEBAR NAV
        ══════════════════════════════════════════ */
        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.538rem;
            padding: 0.385rem 0.692rem;
            border-radius: 0.538rem;
            font-size: 0.769rem;
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .nav-link-item:hover { background: #f1f5f9; color: #1e293b; }
        .dark .nav-link-item { color: #94a3b8; }
        .dark .nav-link-item:hover { background: rgba(255,255,255,.06); color: #e2e8f0; }
        .nav-link-item.active { background: #eef2ff; color: #4338ca; font-weight: 600; }
        .dark .nav-link-item.active { background: rgba(99,102,241,.18); color: #a5b4fc; }
        .nav-link-item svg { width: 0.923rem; height: 0.923rem; flex-shrink: 0; }

        /* ── Nav item wali kelas — badge khusus ── */
        .nav-link-item .nav-badge-wali {
            margin-left: auto;
            font-size: 0.538rem;
            font-weight: 700;
            padding: 0.1rem 0.35rem;
            border-radius: 99px;
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            flex-shrink: 0;
        }
        .dark .nav-link-item .nav-badge-wali {
            background: rgba(245,158,11,.15);
            color: #fbbf24;
            border-color: rgba(245,158,11,.3);
        }
        .nav-link-item.active .nav-badge-wali {
            background: #e0e7ff;
            color: #4338ca;
            border-color: #c7d2fe;
        }

        /* ── Logo area sidebar ── */
        .sidebar-logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem 1rem 0.923rem;
            border-bottom: 1px solid #e2e8f0;
            gap: 0.538rem;
            position: relative;
        }
        .dark .sidebar-logo-area { border-color: #334155; }

        .sidebar-logo-img {
            width: 2.615rem;
            height: 2.615rem;
            border-radius: 0.769rem;
            overflow: hidden;
            border: 1.5px solid rgba(99,102,241,.18);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(99,102,241,.1);
            flex-shrink: 0;
        }

        .sidebar-logo-text { text-align: center; }
        .sidebar-logo-text .singkatan {
            display: block;
            font-size: 0.769rem;
            font-weight: 700;
            letter-spacing: -.01em;
            color: #4f46e5;
            line-height: 1.2;
        }
        .dark .sidebar-logo-text .singkatan { color: #818cf8; }
        .sidebar-logo-text .nama {
            display: block;
            color: #94a3b8;
            line-height: 1.3;
            margin-top: 1px;
            font-size: .615rem;
        }

        /* ── User dropdown ── */
        .user-dropdown { transform-origin: top right; }
        [x-cloak] { display: none !important; }

        /* ── Topbar height ── */
        .topbar { height: 3rem; }

        /* ── Main content padding ── */
        #mainContent { padding: 0.923rem; }
        @media (min-width: 768px) { #mainContent { padding: 1rem 1.154rem; } }

        /* Section spacing dalam konten */
        #mainContent .content-section + .content-section { margin-top: 0.923rem; }
        #mainContent .space-content > * + * { margin-top: 0.769rem; }


        /* ══════════════════════════════════════════
           BOOTSTRAP COMPATIBILITY LAYER
           Menyesuaikan kelas Bootstrap dengan sistem
           desain Tailwind yang sudah ada di layout ini
        ══════════════════════════════════════════ */

        /* ── Container ── */
        #mainContent .container,
        #mainContent .container-fluid {
            width: 100%;
            padding-left: 0;
            padding-right: 0;
            margin: 0;
        }

        /* ── Spacing utils Bootstrap → layout scale ── */
        #mainContent .py-4  { padding-top: 0.769rem;  padding-bottom: 0.769rem; }
        #mainContent .py-3  { padding-top: 0.615rem;  padding-bottom: 0.615rem; }
        #mainContent .px-4  { padding-left: 0.769rem; padding-right: 0.769rem; }
        #mainContent .p-4   { padding: 0.769rem; }
        #mainContent .p-3   { padding: 0.615rem; }
        #mainContent .mb-4  { margin-bottom: 0.769rem; }
        #mainContent .mb-3  { margin-bottom: 0.615rem; }
        #mainContent .mb-2  { margin-bottom: 0.462rem; }
        #mainContent .mb-1  { margin-bottom: 0.231rem; }
        #mainContent .mt-4  { margin-top: 0.769rem; }
        #mainContent .mt-3  { margin-top: 0.615rem; }
        #mainContent .mt-2  { margin-top: 0.462rem; }
        #mainContent .mt-1  { margin-top: 0.231rem; }
        #mainContent .me-2  { margin-right: 0.385rem; }
        #mainContent .me-1  { margin-right: 0.231rem; }
        #mainContent .ms-2  { margin-left: 0.385rem; }
        #mainContent .gap-2 { gap: 0.385rem; }
        #mainContent .gap-3 { gap: 0.615rem; }
        #mainContent .gap-1 { gap: 0.231rem; }

        /* ── Bootstrap Card ── */
        #mainContent .card {
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        #mainContent .card-body {
            padding: 0.769rem 0.923rem;
            flex: 1;
        }
        #mainContent .card-header {
            padding: 0.538rem 0.923rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 0.692rem 0.692rem 0 0;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dark #mainContent .card-header {
            background: #0f172a;
            border-color: #334155;
        }
        #mainContent .card-footer {
            padding: 0.462rem 0.923rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 0.692rem 0.692rem;
        }
        .dark #mainContent .card-footer {
            background: #0f172a;
            border-color: #334155;
        }
        #mainContent .card.border-0 { border: 1px solid #e2e8f0; }
        #mainContent .card.shadow-sm { box-shadow: 0 1px 6px rgba(0,0,0,.06); }
        #mainContent .card.h-100 { height: 100%; }

        /* ── Bootstrap Grid ── */
        #mainContent .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -0.385rem;
            margin-right: -0.385rem;
        }
        #mainContent .g-3 { gap: 0.615rem; margin-left: 0; margin-right: 0; }
        #mainContent .g-3 > [class*="col"] { padding: 0; }
        #mainContent [class*="col-"] {
            padding-left: 0.385rem;
            padding-right: 0.385rem;
        }
        #mainContent .col-12 { width: 100%; }
        #mainContent .col-6  { width: 50%; }
        #mainContent .col-7  { width: 58.333%; }
        #mainContent .col-5  { width: 41.667%; }

        @media (min-width: 576px) {
            #mainContent .col-sm-12 { width: 100%; }
        }
        @media (min-width: 768px) {
            #mainContent .col-md-3  { width: 25%; }
            #mainContent .col-md-4  { width: 33.333%; }
            #mainContent .col-md-6  { width: 50%; }
            #mainContent .col-md-12 { width: 100%; }
        }
        @media (min-width: 992px) {
            #mainContent .col-lg-3  { width: 25%; }
            #mainContent .col-lg-4  { width: 33.333%; }
            #mainContent .col-xl-4  { width: 33.333%; }
        }
        @media (min-width: 1200px) {
            #mainContent .col-xl-4  { width: 33.333%; }
        }

        /* ── Flexbox Bootstrap utils ── */
        #mainContent .d-flex           { display: flex !important; }
        #mainContent .d-block          { display: block !important; }
        #mainContent .d-none           { display: none !important; }
        #mainContent .flex-wrap        { flex-wrap: wrap; }
        #mainContent .align-items-center { align-items: center; }
        #mainContent .align-items-start  { align-items: flex-start; }
        #mainContent .justify-content-between { justify-content: space-between; }
        #mainContent .justify-content-center  { justify-content: center; }
        #mainContent .justify-content-end     { justify-content: flex-end; }
        #mainContent .flex-column      { flex-direction: column; }
        #mainContent .flex-grow-1      { flex-grow: 1; }
        #mainContent .flex-shrink-0    { flex-shrink: 0; }
        #mainContent .min-width-0,
        #mainContent .min-w-0          { min-width: 0; }

        @media (min-width: 576px) {
            #mainContent .d-sm-block { display: block !important; }
        }

        /* ── Text utilities Bootstrap ── */
        #mainContent .text-muted   { color: #64748b !important; }
        #mainContent .text-dark    { color: #1e293b !important; }
        #mainContent .text-white   { color: #ffffff !important; }
        #mainContent .text-primary { color: #4f46e5 !important; }
        #mainContent .text-success { color: #059669 !important; }
        #mainContent .text-warning { color: #d97706 !important; }
        #mainContent .text-danger  { color: #dc2626 !important; }
        #mainContent .text-info    { color: #2563eb !important; }
        #mainContent .text-center  { text-align: center !important; }
        #mainContent .text-end     { text-align: right !important; }
        #mainContent .text-start   { text-align: left !important; }
        #mainContent .fw-bold      { font-weight: 700 !important; }
        #mainContent .fw-semibold  { font-weight: 600 !important; }
        #mainContent .fw-black     { font-weight: 900 !important; }
        #mainContent .small        { font-size: 0.692rem !important; }
        #mainContent .truncate,
        #mainContent .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #mainContent .text-decoration-none { text-decoration: none !important; }

        /* Typography scale Bootstrap → layout */
        #mainContent .h3,
        #mainContent .h5,
        #mainContent .h6 { font-weight: 600; color: #1e293b; line-height: 1.3; }
        #mainContent .h3 { font-size: 1.077rem; margin-bottom: 0.3rem; }
        #mainContent .h5 { font-size: 0.923rem; margin-bottom: 0.25rem; }
        #mainContent .h6 { font-size: 0.846rem; margin-bottom: 0.2rem; }
        .dark #mainContent .h3,
        .dark #mainContent .h5,
        .dark #mainContent .h6 { color: #e2e8f0; }

        /* ── Bootstrap font sizes ── */
        #mainContent .fs-1 { font-size: 1.2rem !important; }
        #mainContent .fs-2 { font-size: 1.077rem !important; }
        #mainContent .fs-3 { font-size: 0.923rem !important; }
        #mainContent .fs-4 { font-size: 0.846rem !important; }
        #mainContent .fs-5 { font-size: 0.769rem !important; }
        #mainContent .fs-6 { font-size: 0.692rem !important; }

        /* ── Bootstrap Buttons ── */
        #mainContent .btn-warning {
            background: #f59e0b;
            color: #1e293b;
            border-color: #f59e0b;
        }
        #mainContent .btn-warning:hover { background: #d97706; border-color: #d97706; }

        #mainContent .btn-success {
            background: #059669;
            color: #ffffff;
            border-color: #059669;
        }
        #mainContent .btn-success:hover { background: #047857; border-color: #047857; }

        #mainContent .btn-info {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        #mainContent .btn-info:hover { background: #1d4ed8; border-color: #1d4ed8; }

        #mainContent .btn-light {
            background: #f8fafc;
            color: #475569;
            border-color: #e2e8f0;
        }
        #mainContent .btn-light:hover { background: #f1f5f9; }

        /* Outline variants */
        #mainContent .btn-outline-primary {
            background: transparent;
            color: #4f46e5;
            border-color: #4f46e5;
        }
        #mainContent .btn-outline-primary:hover { background: #eef2ff; }

        #mainContent .btn-outline-warning {
            background: transparent;
            color: #d97706;
            border-color: #d97706;
        }
        #mainContent .btn-outline-warning:hover { background: #fffbeb; }

        #mainContent .btn-outline-danger {
            background: transparent;
            color: #dc2626;
            border-color: #fecaca;
        }
        #mainContent .btn-outline-danger:hover { background: #fef2f2; }

        #mainContent .btn-outline-info {
            background: transparent;
            color: #2563eb;
            border-color: #bfdbfe;
        }
        #mainContent .btn-outline-info:hover { background: #eff6ff; }

        #mainContent .btn-outline-secondary {
            background: transparent;
            color: #475569;
            border-color: #e2e8f0;
        }
        #mainContent .btn-outline-secondary:hover { background: #f8fafc; }

        #mainContent .btn-close {
            width: 1.2rem;
            height: 1.2rem;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #64748b;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.308rem;
            padding: 0;
            opacity: 0.6;
            transition: opacity .15s;
            flex-shrink: 0;
        }
        #mainContent .btn-close::before { content: '✕'; }
        #mainContent .btn-close:hover { opacity: 1; background: #f1f5f9; }

        /* Sizing modifiers */
        #mainContent .btn-lg {
            padding: 0.538rem 1rem;
            font-size: 0.846rem;
            border-radius: 0.538rem;
        }
        #mainContent .px-4.btn { padding-left: 0.923rem; padding-right: 0.923rem; }
        #mainContent .w-100 { width: 100%; }

        /* ── Bootstrap Badge ── */
        #mainContent .badge.bg-primary   { background: #4f46e5 !important; color: #fff; border: none; text-transform: none; letter-spacing: 0; }
        #mainContent .badge.bg-success   { background: #059669 !important; color: #fff; border: none; text-transform: none; }
        #mainContent .badge.bg-warning   { background: #f59e0b !important; color: #1e293b; border: none; text-transform: none; }
        #mainContent .badge.bg-danger    { background: #dc2626 !important; color: #fff; border: none; text-transform: none; }
        #mainContent .badge.bg-info      { background: #2563eb !important; color: #fff; border: none; text-transform: none; }
        #mainContent .badge.bg-secondary { background: #64748b !important; color: #fff; border: none; text-transform: none; }
        #mainContent .badge.bg-light     { background: #f1f5f9 !important; color: #475569; border: 1px solid #e2e8f0; text-transform: none; }
        #mainContent .badge.bg-primary.bg-opacity-10 { background: #eef2ff !important; color: #4f46e5; }
        #mainContent .badge.text-white   { color: #fff !important; }
        #mainContent .badge.text-muted   { color: #64748b !important; }
        #mainContent .badge.text-primary { color: #4f46e5 !important; }

        /* ── Bootstrap Alert ── */
        #mainContent .alert-dismissible { position: relative; padding-right: 2rem; }
        #mainContent .alert-dismissible .btn-close {
            position: absolute;
            top: 50%;
            right: 0.615rem;
            transform: translateY(-50%);
        }
        #mainContent .alert.fade       { opacity: 0; transition: opacity .15s; }
        #mainContent .alert.show       { opacity: 1; }

        /* ── Bootstrap Form ── */
        #mainContent .form-label {
            font-size: 0.692rem;
            font-weight: 600;
            color: #374151;
            display: block;
            margin-bottom: 0.3rem;
        }
        .dark #mainContent .form-label { color: #94a3b8; }

        #mainContent .form-control,
        #mainContent .form-select {
            font-size: 0.769rem;
            padding: 0.385rem 0.615rem;
            border-radius: 0.462rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #1e293b;
            width: 100%;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
            line-height: 1.5;
        }
        .dark #mainContent .form-control,
        .dark #mainContent .form-select { background: #0f172a; border-color: #334155; color: #e2e8f0; }
        #mainContent .form-control:focus,
        #mainContent .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99,102,241,.15);
        }
        #mainContent .form-control.is-invalid,
        #mainContent .form-select.is-invalid { border-color: #dc2626; }
        #mainContent .invalid-feedback {
            font-size: 0.615rem;
            color: #dc2626;
            margin-top: 0.2rem;
            display: block;
        }
        #mainContent .form-text {
            font-size: 0.615rem;
            color: #94a3b8;
            margin-top: 0.2rem;
            display: block;
        }
        #mainContent .form-check {
            display: flex;
            align-items: center;
            gap: 0.385rem;
            padding: 0;
        }
        #mainContent .form-check-input {
            width: 0.923rem;
            height: 0.923rem;
            padding: 0;
            flex-shrink: 0;
            border-radius: 0.231rem;
            accent-color: #4f46e5;
            cursor: pointer;
        }
        #mainContent .form-check-label {
            font-size: 0.769rem;
            font-weight: 500;
            color: #374151;
            margin: 0;
            cursor: pointer;
        }
        .dark #mainContent .form-check-label { color: #cbd5e1; }

        /* ── Bootstrap Table overrides ── */
        #mainContent .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        #mainContent .table-hover tbody tr:hover { background: #f8fafc; }
        #mainContent .table-borderless td,
        #mainContent .table-borderless th { border: none; padding: 0.3rem 0.462rem; }
        #mainContent .table-light th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.615rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        #mainContent .align-middle td,
        #mainContent .align-middle th { vertical-align: middle; }

        /* ── Bootstrap Breadcrumb ── */
        #mainContent .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.2rem;
            padding: 0;
            margin: 0 0 0.615rem;
            list-style: none;
            font-size: 0.615rem;
        }
        #mainContent .breadcrumb-item { display: flex; align-items: center; color: #94a3b8; }
        #mainContent .breadcrumb-item a { color: #4f46e5; text-decoration: none; }
        #mainContent .breadcrumb-item a:hover { color: #4338ca; text-decoration: underline; }
        #mainContent .breadcrumb-item + .breadcrumb-item::before {
            content: '/';
            margin-right: 0.2rem;
            color: #cbd5e1;
        }
        #mainContent .breadcrumb-item.active { color: #475569; font-weight: 500; }

        /* ── Bootstrap Bootstrap Modal inside layout ── */
        /* Bootstrap modals already sit above layout via fixed positioning */
        .modal {
            position: fixed;
            inset: 0;
            z-index: 200;
            display: none;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .modal.show { display: block; }
        .modal.fade { opacity: 0; transition: opacity .15s; }
        .modal.fade.show { opacity: 1; }
        .modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 199;
            background: rgba(0,0,0,.5);
        }
        .modal-backdrop.fade { opacity: 0; transition: opacity .15s; }
        .modal-backdrop.show { opacity: 1; }
        .modal-dialog {
            position: relative;
            width: auto;
            margin: 1.5rem auto;
            max-width: 28rem;
            pointer-events: none;
        }
        .modal-dialog.modal-lg { max-width: 42rem; }
        .modal-dialog.modal-sm { max-width: 20rem; }
        .modal-content {
            position: relative;
            background: #fff;
            border-radius: 0.769rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 16px 48px rgba(0,0,0,.18);
            pointer-events: auto;
            display: flex;
            flex-direction: column;
        }
        .dark .modal-content { background: #1e293b; border-color: #334155; }
        .modal .modal-header {
            padding: 0.769rem 0.923rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 0.769rem 0.769rem 0 0;
            background: #fff;
        }
        .dark .modal .modal-header { background: #1e293b; border-color: #334155; }
        .modal .modal-title {
            font-size: 0.846rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .dark .modal .modal-title { color: #e2e8f0; }
        .modal .modal-body {
            padding: 0.769rem 0.923rem;
            font-size: 0.769rem;
            color: #374151;
            flex: 1;
        }
        .dark .modal .modal-body { color: #cbd5e1; }
        .modal .modal-footer {
            padding: 0.615rem 0.923rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 0.385rem;
            border-radius: 0 0 0.769rem 0.769rem;
            background: #fff;
        }
        .dark .modal .modal-footer { background: #1e293b; border-color: #334155; }
        .modal .btn-close {
            position: static;
            transform: none;
        }

        /* ── Bootstrap Pagination align dengan sistem desain ── */
        #mainContent nav[aria-label="Page navigation"] { margin-top: 0.615rem; }
        #mainContent .pagination.page-item,
        #mainContent ul.pagination { margin: 0; padding: 0; list-style: none; }
        #mainContent .pagination li.page-item { display: inline-flex; }
        #mainContent .pagination li .page-link,
        #mainContent .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.692rem;
            height: 1.692rem;
            padding: 0 0.462rem;
            border-radius: 0.385rem !important;
            border: 1px solid #e2e8f0;
            color: #475569;
            text-decoration: none;
            font-size: 0.615rem;
            font-weight: 500;
            transition: all .15s;
            margin: 0 1px;
            background: #fff;
        }
        #mainContent .page-link:hover { background: #f1f5f9; border-color: #cbd5e1; }
        #mainContent .page-item.active .page-link {
            background: #4f46e5;
            color: #fff;
            border-color: #4f46e5;
            font-weight: 700;
        }
        #mainContent .page-item.disabled .page-link { opacity: .4; pointer-events: none; }
        #mainContent .pagination-info {
            font-size: 0.615rem;
            color: #64748b;
            padding: 0.3rem 0.615rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.385rem;
            font-weight: 500;
        }

        /* ── Bootstrap Icons ── */
        #mainContent [class^="bi-"],
        #mainContent [class*=" bi-"] { font-size: inherit; }

        /* ── Background color utilities ── */
        #mainContent .bg-primary          { background-color: #4f46e5 !important; }
        #mainContent .bg-success          { background-color: #059669 !important; }
        #mainContent .bg-warning          { background-color: #f59e0b !important; }
        #mainContent .bg-danger           { background-color: #dc2626 !important; }
        #mainContent .bg-info             { background-color: #2563eb !important; }
        #mainContent .bg-secondary        { background-color: #64748b !important; }
        #mainContent .bg-light            { background-color: #f8fafc !important; }
        #mainContent .bg-white            { background-color: #ffffff !important; }
        #mainContent .bg-transparent      { background-color: transparent !important; }
        #mainContent .bg-primary.bg-opacity-10 { background-color: #eef2ff !important; }
        #mainContent .bg-success.bg-opacity-10 { background-color: #ecfdf5 !important; }
        #mainContent .bg-warning.bg-opacity-10 { background-color: #fffbeb !important; }
        #mainContent .bg-info.bg-opacity-10    { background-color: #eff6ff !important; }

        /* ── Border utilities ── */
        #mainContent .border-0      { border: none !important; }
        #mainContent .border-bottom { border-bottom: 1px solid #e2e8f0 !important; }
        #mainContent .border-2      { border-width: 2px !important; }
        #mainContent .border-dashed { border-style: dashed !important; border-color: #cbd5e1 !important; }
        #mainContent .border-secondary { border-color: #cbd5e1 !important; }

        /* ── Rounded utilities ── */
        #mainContent .rounded-3    { border-radius: 0.538rem !important; }
        #mainContent .rounded-2    { border-radius: 0.385rem !important; }
        #mainContent .rounded-pill { border-radius: 99px !important; }

        /* ── Shadow ── */
        #mainContent .shadow-sm { box-shadow: 0 1px 6px rgba(0,0,0,.06) !important; }

        /* ── Width / Height ── */
        #mainContent .w-100 { width: 100% !important; }
        #mainContent .h-100 { height: 100% !important; }

        /* ── Opacity ── */
        #mainContent .opacity-50 { opacity: 0.5 !important; }

        /* ── Overflow ── */
        #mainContent .overflow-hidden { overflow: hidden !important; }

        /* ── Position ── */
        #mainContent .position-relative { position: relative !important; }
        #mainContent .position-absolute { position: absolute !important; }

        /* ── Misc layout ── */
        #mainContent .ps-3 { padding-left: 0.769rem; }
        #mainContent .pt-0 { padding-top: 0; }
        #mainContent .pb-0 { padding-bottom: 0; }
        #mainContent .p-0  { padding: 0; }

        /* ── Bootstrap-style input group / inline form fixes ── */
        #mainContent .col-6 input,
        #mainContent .col-5 input,
        #mainContent .col-7 input,
        #mainContent .col-6 select,
        #mainContent .col-5 select,
        #mainContent .col-7 select { width: 100%; }

        /* ── Card hover effect (untuk academic planner) ── */
        #mainContent .card-hover {
            transition: transform .15s, box-shadow .15s;
            text-decoration: none;
        }
        #mainContent .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,.1) !important;
        }

        /* ── Timetable item style ── */
        #mainContent .timetable-item {
            border-bottom: 1px solid #f1f5f9;
            padding: 0.615rem 0.769rem;
            transition: background .15s;
        }
        #mainContent .timetable-item:last-child { border-bottom: none; }
        #mainContent .timetable-item:hover { background: #f8fafc; }
        .dark #mainContent .timetable-item { border-color: #1e293b; }
        .dark #mainContent .timetable-item:hover { background: #0f172a; }

        #mainContent .timetable-color-strip {
            width: 4px;
            min-height: 2.5rem;
            border-radius: 2px;
            flex-shrink: 0;
        }
        #mainContent .timetable-subject {
            font-size: 0.769rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.15rem;
        }
        .dark #mainContent .timetable-subject { color: #e2e8f0; }
        #mainContent .timetable-time,
        #mainContent .timetable-teacher {
            font-size: 0.615rem;
            color: #64748b;
            line-height: 1.4;
        }
        #mainContent .timetable-actions {
            flex-shrink: 0;
            display: flex;
            gap: 0.3rem;
            align-items: center;
            flex-wrap: wrap;
        }
        #mainContent .timetable-actions .btn {
            padding: 0.2rem 0.462rem;
            font-size: 0.615rem;
            min-width: auto;
        }

        /* ── Nav / Breadcrumb inside content ── */
        #mainContent nav[aria-label="breadcrumb"] { margin-bottom: 0.615rem; }

        /* ── Responsive: Sembunyikan kolom pada mobile ── */
        @media (max-width: 640px) {
            #mainContent .col-md-3,
            #mainContent .col-md-4,
            #mainContent .col-md-6 { width: 100%; }
            #mainContent .timetable-actions { flex-direction: column; }
            #mainContent .timetable-actions .btn { width: 100%; justify-content: center; }
        }

        /* ══════════════════════════════════════════
           WALI KELAS — NAV SEPARATOR & SECTION LABEL
           Pemisah visual untuk grup menu wali kelas
        ══════════════════════════════════════════ */

        /* Separator tipis antar grup nav */
        .nav-section-divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 0.385rem 0.692rem;
        }
        .dark .nav-section-divider { border-color: #334155; }

        /* Label sub-grup di nav (sama style dgn "Guru Panel") */
        .nav-section-label {
            padding: 0 0.692rem;
            margin-bottom: 0.25rem;
            margin-top: 0.462rem;
            font-size: 0.538rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
        }

        /* Wali kelas nav item — slight amber left accent saat hover/active */
        .nav-link-item.nav-wali-kelas {
            position: relative;
        }
        .nav-link-item.nav-wali-kelas::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 2.5px;
            border-radius: 99px;
            background: transparent;
            transition: background .15s;
        }
        .nav-link-item.nav-wali-kelas:hover::before,
        .nav-link-item.nav-wali-kelas.active::before {
            background: #f59e0b;
        }
        .nav-link-item.nav-wali-kelas.active {
            background: #fffbeb;
            color: #92400e;
        }
        .dark .nav-link-item.nav-wali-kelas.active {
            background: rgba(245,158,11,.12);
            color: #fbbf24;
        }

        /* ── Bootstrap Icons CDN load ── */
        /* Pastikan Bootstrap Icons tersedia via CDN di bawah */

    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200">

<div class="flex h-screen overflow-hidden">

    {{-- ════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════ --}}
    @php
        $logoUrl     = \App\Models\SchoolSetting::logoUrl();
        $singkatan   = \App\Models\SchoolSetting::get('singkatan', 'SMPN Kutime');
        $namaSekolah = \App\Models\SchoolSetting::get('nama_sekolah', 'SMP Negeri Kutime');
    @endphp

    <aside
        x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
        class="fixed inset-y-0 left-0 z-50 w-56 bg-white dark:bg-slate-900 shadow-lg
               border-r border-slate-200 dark:border-slate-700 flex flex-col
               transition-transform duration-300 ease-in-out
               lg:relative lg:translate-x-0"
        :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

        {{-- Logo area --}}
        <div class="sidebar-logo-area">

            {{-- Tombol close (mobile) --}}
            <button @click="sidebarOpen = false"
                    class="lg:hidden absolute top-2.5 right-2.5 p-1 text-slate-400
                           hover:text-slate-600 dark:text-slate-500 rounded-lg
                           hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <a href="{{ auth()->user()->isAdmin()
                            ? route('admin.dashboard')
                            : (auth()->user()->isGuru()
                                ? route('guru.dashboard')
                                : route('siswa.dashboard')) }}"
               class="flex flex-col items-center gap-1.5 group w-full">

                <div class="sidebar-logo-img group-hover:shadow-md transition-shadow">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}"
                             alt="Logo {{ $singkatan }}"
                             class="w-full h-full object-contain p-1">
                    @else
                        <span style="font-size:1.25rem">🏫</span>
                    @endif
                </div>

                <div class="sidebar-logo-text">
                    <span class="singkatan">{{ $singkatan }}</span>
                    <span class="nama">{{ $namaSekolah }}</span>
                </div>

            </a>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 overflow-y-auto px-2 py-2.5 space-y-px">

            @if(auth()->user()->isAdmin())
                <p class="px-2.5 mb-1.5 mt-0.5"
                   style="font-size:.6rem;font-weight:600;text-transform:uppercase;
                          letter-spacing:.06em;color:#94a3b8">Admin Panel</p>

                @php
                    $adminNav = [
                        ['route'=>'admin.dashboard',            'label'=>'Dashboard',       'match'=>'admin.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'admin.profil',               'label'=>'Data Diri',        'match'=>'admin.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['route'=>'admin.users.index',          'label'=>'Kelola User',      'match'=>'admin.users*',
                         'icon'=>'M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2'],
                        ['route'=>'admin.kelas.index',          'label'=>'Kelola Kelas',     'match'=>'admin.kelas*',
                         'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        ['route'=>'admin.pengumuman',           'label'=>'Pengumuman',       'match'=>'admin.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                        ['route'=>'admin.absensi-guru.index',   'label'=>'Absensi Guru',     'match'=>'admin.absensi-guru*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'admin.academic-planner.index',  'label'=>'Data Akademik',    'match'=>'admin.data-akademik*',
                         'icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route'=>'admin.kelola-website',       'label'=>'Kelola Website',   'match'=>'admin.kelola-website*',
                         'icon'=>'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp

                @foreach($adminNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach

            @elseif(auth()->user()->isGuru())

                {{-- ════════════════════════════════════
                     GURU PANEL NAVIGATION
                     Wali Kelas menu hanya muncul jika
                     guru ini terdaftar sebagai wali kelas
                     (relasi waliKelas / isWaliKelas())
                ════════════════════════════════════ --}}

                @php
                    /*
                     * Cek apakah guru ini adalah wali kelas.
                     *
                     * Sesuaikan dengan relasi / method yang
                     * ada di model User atau Guru Anda.
                     *
                     * Opsi A — pakai method isWaliKelas() di model:
                     *   $isWaliKelas = auth()->user()->isWaliKelas();
                     *
                     * Opsi B — cek relasi waliKelas langsung:
                     *   $isWaliKelas = auth()->user()->guru?->waliKelas()->exists();
                     *
                     * Opsi C — cek kolom boolean di tabel guru/users:
                     *   $isWaliKelas = (bool) auth()->user()->guru?->is_wali_kelas;
                     *
                     * Gunakan salah satu yang sesuai dengan
                     * struktur database proyek Anda.
                     */
                    $isWaliKelas = auth()->user()->isWaliKelas()
                                   ?? auth()->user()->guru?->waliKelas()->exists()
                                   ?? false;

                    $guruNav = [
                        ['route'=>'guru.dashboard',               'label'=>'Dashboard',        'match'=>'guru.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'guru.profil',                  'label'=>'Data Diri',         'match'=>'guru.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['route'=>'guru.jadwal-mengajar.index',   'label'=>'Jadwal Mengajar',   'match'=>'guru.jadwal-mengajar*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'guru.absensi-siswa.index',     'label'=>'Absensi Siswa',     'match'=>'guru.absensi-siswa*',
                         'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                        ['route'=>'guru.pengumuman',               'label'=>'Pengumuman',        'match'=>'guru.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                    ];
                @endphp

                <p class="px-2.5 mb-1.5 mt-0.5"
                   style="font-size:.6rem;font-weight:600;text-transform:uppercase;
                          letter-spacing:.06em;color:#94a3b8">Guru Panel</p>

                @foreach($guruNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                {{-- ── Wali Kelas section (hanya tampil jika guru = wali kelas) ── --}}
                @if($isWaliKelas)
                    <hr class="nav-section-divider">
                    <p class="nav-section-label">Wali Kelas</p>

                    <a href="{{ route('guru.wali-kelas') }}"
                       class="nav-link-item nav-wali-kelas {{ request()->routeIs('guru.wali-kelas*') ? 'active' : '' }}">
                        {{-- Ikon: orang-orang / grup kelas --}}
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Data Kelas
                        <span class="nav-badge-wali">Wali</span>
                    </a>

                    {{--
                        Tambahkan sub-menu Wali Kelas lainnya di sini sesuai kebutuhan.
                        Contoh:
                        <a href="{{ route('guru.wali-kelas.absensi') }}"
                           class="nav-link-item nav-wali-kelas {{ request()->routeIs('guru.wali-kelas.absensi*') ? 'active' : '' }}">
                            <svg>...</svg>
                            Rekap Absensi
                        </a>
                    --}}
                @endif

            @else
                <p class="px-2.5 mb-1.5 mt-0.5"
                   style="font-size:.6rem;font-weight:600;text-transform:uppercase;
                          letter-spacing:.06em;color:#94a3b8">Siswa Panel</p>

                @php
                    $siswaNav = [
                        ['route'=>'siswa.dashboard',        'label'=>'Dashboard',        'match'=>'siswa.dashboard',
                         'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5m10-10l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-5'],
                        ['route'=>'siswa.jadwal-pelajaran', 'label'=>'Jadwal Pelajaran', 'match'=>'siswa.jadwal-pelajaran*',
                         'icon'=>'M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2'],
                        ['route'=>'siswa.pengumuman',       'label'=>'Pengumuman',        'match'=>'siswa.pengumuman*',
                         'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.165 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                        ['route'=>'siswa.profil',           'label'=>'Data Diri',         'match'=>'siswa.profil*',
                         'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                @endphp

                @foreach($siswaNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            @endif

        </nav>

        {{-- Footer sidebar --}}
        <div class="px-3 py-2.5 border-t border-slate-200 dark:border-slate-700 shrink-0">
            <p class="text-slate-400 dark:text-slate-600 text-center"
               style="font-size:.55rem">
                © {{ date('Y') }} {{ $singkatan }} • Semua hak dilindungi
            </p>
        </div>

    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen && window.innerWidth < 1024"
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    {{-- ════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- TOPBAR --}}
        <header class="topbar bg-white dark:bg-slate-900 border-b border-slate-200
                       dark:border-slate-700 shadow-sm flex items-center px-3 gap-2.5 shrink-0">

            {{-- Hamburger (mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100
                           dark:hover:bg-slate-800 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="flex-1 font-semibold text-slate-800 dark:text-slate-100 truncate"
                style="font-size:.846rem">
                @yield('title', 'Dashboard')
            </h1>

            {{-- Wali Kelas indicator di topbar (hanya untuk guru wali kelas) --}}
            @if(auth()->user()->isGuru() && isset($isWaliKelas) && $isWaliKelas)
                <div class="hidden sm:flex items-center gap-1 px-2 py-0.5 rounded-lg
                            bg-amber-50 border border-amber-200 dark:bg-amber-900/20
                            dark:border-amber-700/40 shrink-0">
                    <svg class="w-2.5 h-2.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span style="font-size:.577rem;font-weight:600;color:#92400e"
                          class="dark:!text-amber-400">Wali Kelas</span>
                </div>
            @endif

            {{-- USER DROPDOWN --}}
            <div class="relative shrink-0" x-data="{ open: false }">

                <button @click="open = !open"
                        class="flex items-center gap-1.5 px-2 py-1 rounded-xl
                               hover:bg-slate-100 dark:hover:bg-slate-800
                               transition focus:outline-none">

                    {{-- Avatar --}}
                    <div class="w-6 h-6 rounded-lg overflow-hidden ring-2 ring-indigo-200
                                dark:ring-indigo-700 flex items-center justify-center
                                bg-slate-100 dark:bg-slate-700 shrink-0">
                        @if(!empty(auth()->user()->photo))
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                 alt="" class="w-full h-full object-cover">
                        @else
                            <span style="font-size:.615rem;font-weight:700;color:#4f46e5">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    {{-- Nama & Role --}}
                    <div class="hidden sm:block text-left">
                        <p class="font-semibold text-slate-700 dark:text-slate-200
                                  leading-tight truncate max-w-[110px]"
                           style="font-size:.692rem">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="leading-tight text-slate-400 dark:text-slate-500"
                           style="font-size:.577rem">
                            @if(auth()->user()->isAdmin()) Admin
                            @elseif(auth()->user()->isGuru())
                                Guru{{ (isset($isWaliKelas) && $isWaliKelas) ? ' · Wali Kelas' : '' }}
                            @else Siswa
                            @endif
                        </p>
                    </div>

                    {{-- Chevron --}}
                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-200 shrink-0"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     x-cloak
                     class="absolute right-0 mt-1.5 w-48 bg-white dark:bg-slate-800
                            rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700
                            py-1 z-50 user-dropdown">

                    {{-- Info akun --}}
                    <div class="px-3.5 py-2 border-b border-slate-100 dark:border-slate-700 mb-0.5">
                        <p class="font-semibold text-slate-700 dark:text-slate-200 truncate"
                           style="font-size:.692rem">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-slate-400 dark:text-slate-500 truncate mt-0.5"
                           style="font-size:.577rem">
                            {{ auth()->user()->email }}
                        </p>
                        {{-- Badge wali kelas di dropdown --}}
                        @if(auth()->user()->isGuru() && isset($isWaliKelas) && $isWaliKelas)
                            <span class="inline-flex items-center gap-0.5 mt-1 px-1.5 py-0.5
                                         rounded-md bg-amber-50 border border-amber-200
                                         dark:bg-amber-900/20 dark:border-amber-700/40"
                                  style="font-size:.538rem;font-weight:600;color:#92400e">
                                <svg class="w-2 h-2 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Wali Kelas
                            </span>
                        @endif
                    </div>

                    {{-- Profil Saya --}}
                    <a href="{{ auth()->user()->isAdmin()
                                    ? route('admin.profil')
                                    : (auth()->user()->isGuru()
                                        ? route('guru.profil')
                                        : route('siswa.profil')) }}"
                       @click="open = false"
                       class="flex items-center gap-2 px-3.5 py-1.5
                              text-slate-700 dark:text-slate-300
                              hover:bg-slate-50 dark:hover:bg-slate-700/60 transition"
                       style="font-size:.692rem;font-weight:500">
                        <span class="w-5 h-5 rounded-md bg-indigo-50 dark:bg-indigo-900/30
                                     flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        Profil Saya
                    </a>

                    {{-- Shortcut Wali Kelas di dropdown (hanya jika guru wali kelas) --}}
                    @if(auth()->user()->isGuru() && isset($isWaliKelas) && $isWaliKelas)
                        <a href="{{ route('guru.wali-kelas') }}"
                           @click="open = false"
                           class="flex items-center gap-2 px-3.5 py-1.5
                                  text-amber-700 dark:text-amber-400
                                  hover:bg-amber-50 dark:hover:bg-amber-900/20 transition"
                           style="font-size:.692rem;font-weight:500">
                            <span class="w-5 h-5 rounded-md bg-amber-50 dark:bg-amber-900/30
                                         flex items-center justify-center shrink-0 border border-amber-200 dark:border-amber-700/40">
                                <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            Wali Kelas
                        </a>
                    @endif

                    <div class="border-t border-slate-100 dark:border-slate-700 my-0.5"></div>

                    {{-- Keluar --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2 px-3.5 py-1.5
                                       text-red-600 dark:text-red-400
                                       hover:bg-red-50 dark:hover:bg-red-900/20 transition"
                                style="font-size:.692rem;font-weight:500">
                            <span class="w-5 h-5 rounded-md bg-red-50 dark:bg-red-900/20
                                         flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3
                                             3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            Keluar
                        </button>
                    </form>

                </div>
            </div>

        </header>

        {{-- KONTEN UTAMA --}}
        <main id="mainContent"
              class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950">

            {{-- Flash success --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Flash error --}}
            @if(session('error'))
                <div class="alert alert-error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                                 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>
    </div>
</div>

{{-- Bootstrap Icons CDN — dibutuhkan oleh konten academic planner --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- Bootstrap JS Bundle — dibutuhkan untuk modal & komponen Bootstrap di konten --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')

</body>
</html>
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

{{-- ── Fallback: memastikan semua variabel tersedia dengan tipe yang aman ──────────── --}}
@php
    $widgetPengumuman ??= collect();
    $stats            ??= ['total_guru' => 0, 'total_siswa' => 0, 'total_kelas' => 0, 'guru_hadir' => 0];
    $jadwalHariIni    ??= collect();
    $activityLogs     ??= collect();
    $absensiMinggu    ??= ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'telat' => 0];
    $guruUltah        ??= collect();
    $kelasTanpaWali   ??= 0;
@endphp

<div class="space-y-2.5 max-w-7xl mx-auto container-fluid px-2">

    {{-- ── Greeting & Tanggal Terkompresi ────────────────────────────── --}}
    <div class="flex items-center justify-between flex-wrap gap-2 bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div>
            <h2 class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-1">
                <span>👋</span> Selamat datang, {{ auth()->user()->name ?? 'Admin' }}!
            </h2>
            <p class="text-[9px] text-slate-400 dark:text-slate-500 font-medium tracking-wide">
                {{ now()->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB
            </p>
        </div>
        
        {{-- Quick Actions Ringkas --}}
        <div class="flex items-center gap-1.5 flex-wrap">
            @if(Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                      bg-indigo-600 text-white text-[9px] font-bold
                      hover:bg-indigo-700 transition shadow-sm active:scale-95">
                ➕ User Baru
            </a>
            @endif
            @if(Route::has('admin.pengumuman.create'))
            <a href="{{ route('admin.pengumuman.create') }}"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                      bg-slate-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300
                      border border-slate-200 dark:border-slate-600
                      text-[9px] font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition shadow-sm">
                📢 Pengumuman
            </a>
            @endif
        </div>
    </div>

    {{-- ── Statistik Ringkasan ────────────────────────────── --}}
    <div class="w-full">
        @include('admin.dashboard.stats', [
            'stats'          => $stats,
            'kelasTanpaWali' => $kelasTanpaWali,
        ])
    </div>

    {{-- ── Absensi Minggu Ini ─────────────────────────────── --}}
    <div class="w-full">
        @include('admin.dashboard.absensi_minggu', [
            'absensiMinggu' => $absensiMinggu,
        ])
    </div>

    {{-- ── Grid Utama Atas: Jadwal Hari Ini & Pengumuman Internal ───────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 items-stretch">

        {{-- Jadwal Hari Ini --}}
        <div class="flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-full">
            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                <h3 class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    📅 Jadwal Hari Ini
                </h3>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-[220px] custom-scrollbar">
                @include('admin.dashboard.schedule', [
                    'jadwalHariIni' => $jadwalHariIni,
                ])
            </div>
        </div>

        {{-- Pengumuman Internal --}}
        <div class="flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-full">
            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                <h3 class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    📢 Pengumuman Internal
                </h3>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-[220px] custom-scrollbar">
                @include('admin.dashboard.announcement', [
                    'widgetPengumuman' => $widgetPengumuman,
                ])
            </div>
        </div>

    </div>

    {{-- ── Grid Bawah: Log Aktivitas Sistem & Widget Pendukung ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 items-stretch">

        {{-- Log Aktivitas Sistem (Mengambil porsi 2 Kolom) --}}
        <div class="lg:col-span-2 flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-full">
            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                <h3 class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    ⏱️ Log Aktivitas Sistem
                </h3>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-[260px] custom-scrollbar">
                @include('admin.dashboard.activity_log', [
                    'activityLogs' => $activityLogs,
                ])
            </div>
        </div>

        {{-- Ulang Tahun Guru & Akses Cepat (Kombinasi 1 Kolom) --}}
        <div class="lg:col-span-1 flex flex-col gap-3 h-full">
            
            {{-- Bagian Ulang Tahun Guru --}}
            <div class="flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex-1">
                <div class="px-3 py-1.5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                    <h3 class="text-[10px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                        🎂 Guru Ulang Tahun
                    </h3>
                </div>
                <div class="p-2 overflow-y-auto max-h-[110px] custom-scrollbar flex-1">
                    @include('admin.dashboard.ultah_guru', [
                        'guruUltah' => $guruUltah,
                    ])
                </div>
            </div>

            {{-- Bagian Menu Akses Cepat Ringkas --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-2.5">
                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                    ⚡ Akses Cepat Menuju Fitur
                </p>
                <div class="grid grid-cols-2 gap-1.5">
                    @php
                        $quickLinks = [
                            ['icon'=>'📋','label'=>'Absensi Guru', 'route'=>'admin.absensi-guru.index',  'color'=>'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'],
                            ['icon'=>'👥','label'=>'Data Guru',    'route'=>'admin.users.index',          'color'=>'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300'],
                            ['icon'=>'🏫','label'=>'Kelola Kelas', 'route'=>'admin.kelas.index',          'color'=>'bg-sky-50 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300'],
                            ['icon'=>'📢','label'=>'Pengumuman',   'route'=>'admin.pengumuman',           'color'=>'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300'],
                            ['icon'=>'🎓','label'=>'Data Siswa',   'route'=>'admin.users.index',          'color'=>'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300'],
                        ];
                    @endphp

                    @foreach($quickLinks as $ql)
                        @if(Route::has($ql['route']))
                        <a href="{{ route($ql['route']) }}"
                           class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg
                                  {{ $ql['color'] }} hover:opacity-85 active:scale-[0.97]
                                  transition-all text-[10px] font-semibold leading-tight overflow-hidden">
                            <span class="text-xs shrink-0">{{ $ql['icon'] }}</span>
                            <span class="truncate">{{ $ql['label'] }}</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>

{{-- ── Custom Scrollbar Minimalis & Kompresi Global Component Layout ── --}}
<style>
    /* Mengurangi margin bawaan section layouts default jika terlalu longgar */
    .container-fluid {
        padding-top: 0.25rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    /* Scrollbar mikro agar tidak memakan ruang konten internal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
        height: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.25);
        border-radius: 999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.45);
    }
</style>

@endsection
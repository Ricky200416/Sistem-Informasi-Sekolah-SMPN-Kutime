{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

@php
    $widgetPengumuman ??= collect();
    $stats            ??= ['total_guru' => 0, 'total_siswa' => 0, 'total_kelas' => 0, 'guru_hadir' => 0];
    $jadwalHariIni    ??= collect();
    $activityLogs     ??= collect();
    $absensiMinggu    ??= ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'telat' => 0];
    $guruUltah        ??= collect();
    $kelasTanpaWali   ??= 0;
@endphp

<div class="space-y-4 max-w-full mx-auto container-fluid px-2 pb-6">

    {{-- ── Greeting & Info Bar ── --}}
    <div class="relative overflow-hidden flex items-center justify-between flex-wrap gap-3 bg-white dark:bg-slate-800 px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-xl shadow-inner">
                👋
            </div>
            <div>
                <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 leading-tight">
                    Halo, {{ auth()->user()->name ?? 'Admin' }}!
                </h2>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold tracking-wide uppercase">
                    {{ now()->isoFormat('dddd, D MMMM Y') }} <span class="mx-1 opacity-30">|</span> <span id="realtimeClock">{{ now()->format('H:i:s') }}</span> WIB
                </p>
            </div>
        </div>
        
        {{-- Quick Actions --}}
        <div class="flex items-center gap-2 relative z-10">
            @if(Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-indigo-600 text-white text-[10px] font-black
                      hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200 dark:shadow-none active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
                USER BARU
            </a>
            @endif
            @if(Route::has('admin.pengumuman.create'))
            <a href="{{ route('admin.pengumuman.create') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200
                      border border-slate-200 dark:border-slate-600
                      text-[10px] font-black hover:bg-slate-50 dark:hover:bg-slate-600 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                PENGUMUMAN
            </a>
            @endif
        </div>
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16"></div>
    </div>

    {{-- ── Row 1: Statistik Utama ── --}}
    <div class="w-full">
        @include('admin.dashboard.stats', ['stats' => $stats, 'kelasTanpaWali' => $kelasTanpaWali])
    </div>

    {{-- ── Row 2: Absensi Mingguan (Lebar Penuh) ── --}}
    <div class="w-full">
        @include('admin.dashboard.absensi_minggu', ['absensiMinggu' => $absensiMinggu])
    </div>

    {{-- ── Row 3: Jadwal & Pengumuman ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @include('admin.dashboard.schedule', ['jadwalHariIni' => $jadwalHariIni])
        @include('admin.dashboard.announcement', ['widgetPengumuman' => $widgetPengumuman])
    </div>

    {{-- ── Row 4: Log Aktivitas & Side Widgets ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <div class="lg:col-span-8">
            @include('admin.dashboard.activity_log', ['activityLogs' => $activityLogs])
        </div>
        <div class="lg:col-span-4 space-y-4">
            @include('admin.dashboard.ultah_guru', ['guruUltah' => $guruUltah])
            
            {{-- Quick Access Menu --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <span class="w-1 h-3 bg-indigo-500 rounded-full"></span> Navigasi Cepat
                </p>
                <div class="grid grid-cols-2 gap-2">
                    @php
                        $quickLinks = [
                            ['icon'=>'📋','label'=>'Absensi Guru', 'route'=>'admin.absensi-guru.index',  'color'=>'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'],
                            ['icon'=>'👥','label'=>'Data Guru',    'route'=>'admin.users.index',          'color'=>'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300'],
                            ['icon'=>'🏫','label'=>'Kelola Kelas', 'route'=>'admin.kelas.index',          'color'=>'bg-sky-50 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300'],
                            ['icon'=>'📢','label'=>'Pengumuman',   'route'=>'admin.pengumuman',           'color'=>'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300'],
                            ['icon'=>'🎓','label'=>'Data Siswa',   'route'=>'admin.users.index',          'color'=>'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300'],
                            ['icon'=>'⚙️','label'=>'User Role',    'route'=>'admin.users.index',          'color'=>'bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300'],
                        ];
                    @endphp
                    @foreach($quickLinks as $ql)
                        @if(Route::has($ql['route']))
                        <a href="{{ route($ql['route']) }}"
                           class="flex items-center gap-2 px-2.5 py-2 rounded-xl {{ $ql['color'] }} 
                                  hover:opacity-80 transition-all text-[10px] font-bold group">
                            <span class="text-sm group-hover:scale-110 transition-transform">{{ $ql['icon'] }}</span>
                            <span class="truncate">{{ $ql['label'] }}</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('realtimeClock').textContent = timeStr;
    }
    setInterval(updateClock, 1000);
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(156, 163, 175, 0.5); }
</style>
@endsection
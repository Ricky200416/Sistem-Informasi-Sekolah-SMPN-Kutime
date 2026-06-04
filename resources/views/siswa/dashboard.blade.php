{{--
| resources/views/siswa/dashboard.blade.php
| Dashboard utama siswa — profesional, efektif, dan rapi.
|
| Partial yang di-include:
|   - siswa.dashboard.stats        → KPI cards
|   - siswa.dashboard.schedule     → Jadwal hari ini + mingguan
|   - siswa.dashboard.announcement → Widget pengumuman
--}}

@extends('layouts.app')
@section('title', 'Dashboard Siswa')

@section('content')

<div class="space-y-5">

    {{-- ══════════════════════════════════════════════════════════
         HERO HEADER
         ══════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-3xl"
         style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 40%, #0891b2 100%);">

        {{-- Grid pattern overlay --}}
        <div class="absolute inset-0 opacity-[0.08]" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="hero-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#hero-grid)"/>
            </svg>
        </div>

        {{-- Dekorasi lingkaran --}}
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full opacity-10"
             style="background:radial-gradient(circle, #ffffff 0%, transparent 70%)"></div>
        <div class="absolute right-8 -bottom-12 w-40 h-40 rounded-full opacity-10"
             style="background:radial-gradient(circle, #93c5fd 0%, transparent 70%)"></div>

        <div class="relative px-6 py-6 sm:py-7">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                {{-- Kiri: Salam --}}
                <div class="flex items-center gap-4">
                    {{-- Avatar inisial --}}
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 font-black text-lg
                                shadow-lg" style="background:rgba(255,255,255,0.2); color: white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-blue-200 text-xs font-medium">
                            {{ now()->isoFormat('dddd, D MMMM Y') }}
                        </p>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-white leading-tight mt-0.5">
                            Halo, {{ explode(' ', auth()->user()->name ?? 'Siswa')[0] }}! 👋
                        </h1>
                        @if($studyGroup)
                        <p class="text-blue-200 text-xs mt-1">
                            Kelas <strong class="text-white">{{ $studyGroup->name }}</strong>
                            @if($studyGroup->homeroomTeacher)
                            · Wali: <span class="text-blue-100">{{ $studyGroup->homeroomTeacher->name }}</span>
                            @endif
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Kanan: Jam realtime + info hari --}}
                <div class="flex items-center gap-3 sm:gap-4">
                    {{-- Jam --}}
                    <div class="rounded-2xl px-4 py-2.5 text-center border"
                         style="background:rgba(255,255,255,0.12); border-color:rgba(255,255,255,0.2)">
                        <p id="jamSekarang" class="text-2xl font-black text-white leading-none tracking-tight">
                            {{ now()->format('H:i') }}
                        </p>
                        <p class="text-blue-200 text-[10px] mt-0.5 font-medium">WIB</p>
                    </div>

                    {{-- Sesi aktif hari ini --}}
                    @if($studyGroup)
                    <div class="rounded-2xl px-4 py-2.5 text-center border"
                         style="background:rgba(255,255,255,0.12); border-color:rgba(255,255,255,0.2)">
                        <p class="text-2xl font-black text-white leading-none">
                            {{ $jadwalHariIni->count() }}
                        </p>
                        <p class="text-blue-200 text-[10px] mt-0.5 font-medium whitespace-nowrap">
                            Sesi Hari Ini
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Progress hari sekolah (visual sederhana) --}}
            @if($studyGroup && $jadwalHariIni->isNotEmpty())
            @php
                $jamNow  = \Carbon\Carbon::now();
                $sorted  = $jadwalHariIni->sortBy('start_time');
                $firstTt = $sorted->first();
                $lastTt  = $sorted->last();
                $mulai   = \Carbon\Carbon::createFromTimeString($firstTt->start_time);
                $selesai = \Carbon\Carbon::createFromTimeString($lastTt->end_time);
                $totalMenit = $mulai->diffInMinutes($selesai);
                $lewat  = max(0, min($totalMenit, $mulai->diffInMinutes($jamNow)));
                $persen = $totalMenit > 0 ? round($lewat / $totalMenit * 100) : 0;
            @endphp
            <div class="mt-5 pt-4" style="border-top:1px solid rgba(255,255,255,0.15)">
                <div class="flex items-center justify-between mb-1.5">
                    <p class="text-[11px] text-blue-200 font-medium">
                        Progres belajar hari ini
                        ({{ substr($firstTt->start_time,0,5) }} – {{ substr($lastTt->end_time,0,5) }})
                    </p>
                    <p class="text-[11px] text-white font-bold">{{ $persen }}%</p>
                </div>
                <div class="w-full h-1.5 rounded-full" style="background:rgba(255,255,255,0.2)">
                    <div class="h-1.5 rounded-full transition-all duration-500"
                         style="background:rgba(255,255,255,0.9); width:{{ $persen }}%"></div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         STATS / KPI ROW
         ══════════════════════════════════════════════════════════ --}}
    @include('siswa.dashboard.stats', [
        'totalJadwal'      => $totalJadwal,
        'totalMapel'       => $totalMapel,
        'totalGuru'        => $totalGuru,
        'hariAktif'        => $hariAktif,
        'totalJamPerMinggu'=> $totalJamPerMinggu,
        'studyGroup'       => $studyGroup,
    ])

    {{-- ══════════════════════════════════════════════════════════
         MAIN CONTENT GRID
         ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ── KOLOM KIRI (2/3): Jadwal + Guru Hari Ini ──────── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Jadwal Hari Ini + Rekap Mingguan --}}
            @include('siswa.dashboard.schedule', [
                'studyGroup'       => $studyGroup,
                'jadwalHariIni'    => $jadwalHariIni,
                'jadwalByDay'      => $jadwalByDay,
                'jadwalBerikutnya' => $jadwalBerikutnya,
                'hariBerikutnya'   => $hariBerikutnya,
                'hariIni'          => $hariIni,
                'hariIniDb'        => $hariIniDb,
            ])

            {{-- ── GURU HARI INI ─────────────────────────────── --}}
            @if($studyGroup && $jadwalHariIni->isNotEmpty())
            @php
                $guruHariIni = $jadwalHariIni
                    ->groupBy(fn($tt) => $tt->teacher_id ?? 0)
                    ->map(fn($items) => [
                        'teacher'  => $items->first()->teacher,
                        'subjects' => $items->map(fn($t) => [
                            'name'       => $t->studySubject->name,
                            'color'      => $t->studySubject->color ?? '#6366f1',
                            'start'      => substr($t->start_time, 0, 5),
                            'end'        => substr($t->end_time, 0, 5),
                            'session'    => $t->session_type,
                            'room'       => $t->room,
                        ])->values(),
                    ])->values();
            @endphp

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm overflow-hidden">

                <div class="flex items-center justify-between px-4 py-3.5 border-b
                            border-slate-100 dark:border-slate-700/60
                            bg-gradient-to-r from-amber-50 to-orange-50
                            dark:from-amber-900/10 dark:to-orange-900/10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500
                                    flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                                Guru Mengajar Hari Ini
                            </h3>
                            <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                                {{ $guruHariIni->count() }} guru · {{ $hariIni }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($guruHariIni as $entry)
                    @php $guru = $entry['teacher']; @endphp
                    <div class="flex flex-col gap-2 p-3.5 rounded-xl border border-slate-100
                                dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/20
                                hover:border-amber-200 dark:hover:border-amber-700/50
                                transition-colors">
                        {{-- Info guru --}}
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500
                                        flex items-center justify-center shrink-0 font-bold text-white text-sm">
                                {{ strtoupper(substr($guru->name ?? 'G', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">
                                    {{ $guru->name ?? '-' }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    {{ count($entry['subjects']) }} sesi
                                </p>
                            </div>
                        </div>
                        {{-- Mapel yang diajar --}}
                        <div class="space-y-1">
                            @foreach($entry['subjects'] as $subj)
                            <div class="flex items-center gap-1.5 pl-1">
                                <div class="w-1.5 h-1.5 rounded-full shrink-0"
                                     style="background:{{ $subj['color'] }}"></div>
                                <span class="text-[10px] font-medium text-slate-700 dark:text-slate-300 truncate flex-1">
                                    {{ $subj['name'] }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono shrink-0">
                                    {{ $subj['start'] }}–{{ $subj['end'] }}
                                </span>
                                @if($subj['room'])
                                <span class="text-[9px] text-slate-400 shrink-0">
                                    · {{ $subj['room'] }}
                                </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ── KOLOM KANAN (1/3): Pengumuman + Info Kelas ─────── --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Widget Pengumuman --}}
            @include('siswa.dashboard.announcement', [
                'widgetPengumuman' => $widgetPengumuman,
            ])

            {{-- Info Kelas --}}
            @if($studyGroup)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm overflow-hidden">

                <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700/60
                            bg-gradient-to-r from-violet-50 to-purple-50
                            dark:from-violet-900/10 dark:to-purple-900/10">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Info Kelas
                    </h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        {{ $studyGroup->name }} · {{ $studyGroup->academic_year }}
                    </p>
                </div>

                <div class="p-4 space-y-3">
                    @php
                        $infoItems = [
                            ['icon' => '🏫', 'label' => 'Kelas',       'val' => $studyGroup->name],
                            ['icon' => '📅', 'label' => 'Tahun Ajaran', 'val' => $studyGroup->academic_year ?? '-'],
                            ['icon' => '📖', 'label' => 'Semester',     'val' => 'Semester ' . ($studyGroup->semester ?? '-')],
                        ];
                        if ($studyGroup->homeroomTeacher) {
                            $infoItems[] = [
                                'icon'  => '👩‍🏫',
                                'label' => 'Wali Kelas',
                                'val'   => $studyGroup->homeroomTeacher->name,
                            ];
                        }
                        if (isset($studyGroup->students_count) || method_exists($studyGroup, 'students')) {
                            $jumlahSiswa = is_numeric($studyGroup->students_count ?? null)
                                ? $studyGroup->students_count
                                : optional($studyGroup->students)->count();
                            if ($jumlahSiswa !== null) {
                                $infoItems[] = ['icon' => '👥', 'label' => 'Jumlah Siswa', 'val' => $jumlahSiswa . ' siswa'];
                            }
                        }
                    @endphp

                    @foreach($infoItems as $info)
                    <div class="flex items-center gap-2.5">
                        <span class="text-base shrink-0 leading-none">{{ $info['icon'] }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] text-slate-400 leading-none">{{ $info['label'] }}</p>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mt-0.5 truncate">
                                {{ $info['val'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Links --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                        dark:border-slate-700 shadow-sm p-4">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3">Menu Cepat</h3>
                <div class="grid grid-cols-2 gap-2">
                    @php
                        $quickLinks = [
                            ['label' => 'Jadwal',       'icon' => '🗓️', 'color' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100',   'route' => 'siswa.jadwal'],
                            ['label' => 'Pengumuman',   'icon' => '📢', 'color' => 'bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 hover:bg-sky-100',               'route' => 'siswa.pengumuman'],
                            ['label' => 'Nilai',        'icon' => '📊', 'color' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100', 'route' => 'siswa.nilai'],
                            ['label' => 'Absensi',      'icon' => '✅', 'color' => 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 hover:bg-amber-100',     'route' => 'siswa.absensi'],
                        ];
                    @endphp
                    @foreach($quickLinks as $ql)
                    @if(Route::has($ql['route']))
                    <a href="{{ route($ql['route']) }}"
                       class="flex flex-col items-center justify-center gap-1.5 py-3 px-2
                              rounded-xl text-center transition-all {{ $ql['color'] }}
                              active:scale-95">
                        <span class="text-xl leading-none">{{ $ql['icon'] }}</span>
                        <span class="text-[10px] font-semibold leading-tight">{{ $ql['label'] }}</span>
                    </a>
                    @else
                    <div class="flex flex-col items-center justify-center gap-1.5 py-3 px-2
                                rounded-xl text-center bg-slate-50 dark:bg-slate-900/20
                                text-slate-400 cursor-not-allowed opacity-60">
                        <span class="text-xl leading-none">{{ $ql['icon'] }}</span>
                        <span class="text-[10px] font-semibold leading-tight">{{ $ql['label'] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
/* Update jam realtime */
(function(){
    function updateClock(){
        var el = document.getElementById('jamSekarang');
        if(!el) return;
        var now = new Date();
        var h = String(now.getHours()).padStart(2,'0');
        var m = String(now.getMinutes()).padStart(2,'0');
        el.textContent = h + ':' + m;
    }
    setInterval(updateClock, 10000);
})();
</script>
@endpush

@endsection
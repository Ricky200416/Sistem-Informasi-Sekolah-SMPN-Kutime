{{-- resources/views/siswa/dashboard/schedule.blade.php --}}
{{--
    Variabel yang WAJIB dipass dari DashboardController:
      $studyGroup       - object kelas siswa (atau null)
      $jadwalHariIni    - Collection Timetable hari ini
      $jadwalBerikutnya - Collection Timetable hari berikutnya
      $hariBerikutnya   - string nama hari berikutnya
      $hariIni          - string nama hari ini (Bahasa Indonesia)
      $jadwalByDay      - Collection dikelompokkan per hari (rekap mingguan)
--}}
@php
    use Carbon\Carbon;

    $jamSekarang = Carbon::now()->format('H:i');

    $isOngoing = function($tt) use ($jamSekarang) {
        if (!$tt?->start_time || !$tt?->end_time) return false;
        $start = substr($tt->start_time, 0, 5);
        $end   = substr($tt->end_time,   0, 5);
        return $jamSekarang >= $start && $jamSekarang < $end;
    };

    $isPast = function($tt) use ($jamSekarang) {
        if (!$tt?->end_time) return false;
        return $jamSekarang >= substr($tt->end_time, 0, 5);
    };

    // Pastikan variabel selalu tersedia walau controller tidak pass
    $studyGroup       = $studyGroup       ?? null;
    $jadwalHariIni    = $jadwalHariIni    ?? collect();
    $jadwalBerikutnya = $jadwalBerikutnya ?? collect();
    $hariBerikutnya   = $hariBerikutnya   ?? null;
    $hariIni          = $hariIni          ?? Carbon::now()->locale('id')->isoFormat('dddd');
    $jadwalByDay      = $jadwalByDay      ?? collect();
@endphp

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- ─── Header ─────────────────────────────────────────── --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                dark:border-slate-700/60 bg-gradient-to-r from-indigo-50 to-violet-50
                dark:from-indigo-900/10 dark:to-violet-900/10">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600
                        flex items-center justify-center shadow-inner text-white text-lg">
                📅
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                    Jadwal Pelajaran Hari Ini
                </h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ $hariIni }} · {{ Carbon::now()->isoFormat('D MMMM Y') }}
                    @if($studyGroup)
                        · Kelas {{ $studyGroup->name ?? $studyGroup->nama ?? '-' }}
                    @endif
                </p>
            </div>
        </div>

        @if(Route::has('siswa.jadwal'))
            <a href="{{ route('siswa.jadwal') }}"
               class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-600
                      hover:text-indigo-800 dark:text-indigo-400 transition-colors
                      bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 px-3 py-1.5
                      rounded-lg border border-indigo-200 dark:border-indigo-700">
                Semua Jadwal
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif
    </div>

    {{-- ─── Belum Punya Kelas ───────────────────────────────── --}}
    @if(!$studyGroup)
        <div class="py-12 px-6 text-center">
            <div class="mx-auto w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-3xl
                        flex items-center justify-center text-4xl mb-4">📪</div>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                Belum Terdaftar di Kelas
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">
                Hubungi admin untuk penempatan kelas.
            </p>
        </div>

    {{-- ─── Tidak Ada Jadwal Hari Ini ──────────────────────── --}}
    @elseif($jadwalHariIni->isEmpty())
        <div class="py-12 px-6 text-center">
            <div class="text-5xl mb-4">🎉</div>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                Tidak Ada Pelajaran Hari Ini
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Nikmati hari {{ $hariIni }}!
            </p>

            @if($hariBerikutnya && $jadwalBerikutnya->isNotEmpty())
                <div class="mt-6 px-2 text-left max-w-xs mx-auto">
                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-2 tracking-wider">
                        Jadwal Berikutnya ({{ $hariBerikutnya }})
                    </p>
                    <div class="space-y-2">
                        @foreach($jadwalBerikutnya->take(4) as $bt)
                            <div class="flex items-center justify-between gap-3 py-1.5 px-2
                                        rounded-lg bg-slate-50 dark:bg-slate-700/30">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-2 h-2 rounded-full shrink-0"
                                         style="background: {{ $bt->studySubject->color ?? '#6366f1' }}">
                                    </div>
                                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">
                                        {{ $bt->studySubject->name ?? '-' }}
                                    </span>
                                </div>
                                <span class="font-mono text-[10px] text-slate-400 shrink-0">
                                    {{ substr($bt->start_time, 0, 5) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    {{-- ─── Ada Jadwal ─────────────────────────────────────── --}}
    @else
        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @foreach($jadwalHariIni->sortBy('start_time') as $tt)
                @php
                    $ongoing = $isOngoing($tt);
                    $past    = $isPast($tt);
                    $color   = $tt->studySubject->color ?? '#6366f1';
                @endphp

                <div class="flex transition-all duration-200
                            {{ $ongoing
                                ? 'bg-indigo-50 dark:bg-indigo-900/20'
                                : ($past ? 'bg-white dark:bg-slate-800 opacity-70' : 'bg-white dark:bg-slate-800') }}">

                    {{-- Kolom Waktu --}}
                    <div class="w-20 py-4 px-3 flex flex-col items-center justify-center shrink-0
                                border-r border-slate-100 dark:border-slate-700
                                bg-slate-50/60 dark:bg-slate-900/40">
                        <span class="font-bold text-sm text-slate-800 dark:text-slate-100 tabular-nums">
                            {{ substr($tt->start_time ?? '00:00', 0, 5) }}
                        </span>
                        <div class="w-6 h-px bg-slate-300 dark:bg-slate-600 my-1.5"></div>
                        <span class="text-[10px] text-slate-400 tabular-nums">
                            {{ substr($tt->end_time ?? '00:00', 0, 5) }}
                        </span>
                    </div>

                    {{-- Strip Warna --}}
                    <div class="w-1 shrink-0" style="background: {{ $color }}"></div>

                    {{-- Konten Utama --}}
                    <div class="flex-1 px-4 py-3.5">
                        <div class="flex items-start justify-between gap-3">

                            {{-- Info Mapel --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100
                                          leading-snug truncate">
                                    {{ $tt->studySubject->name ?? 'Mata Pelajaran' }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $tt->teacher->name ?? 'Guru tidak tersedia' }}
                                </p>
                                @if($tt->room)
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        📍 {{ $tt->room }}
                                    </p>
                                @endif
                                {{-- Badge Sesi --}}
                                <span class="inline-flex mt-1.5 px-2 py-0.5 rounded-md text-[9px]
                                             font-semibold
                                             {{ ($tt->session_type ?? 'teori') === 'praktikum'
                                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    {{ ucfirst($tt->session_type ?? 'Teori') }}
                                </span>
                            </div>

                            {{-- Badge Status --}}
                            <div class="shrink-0">
                                @if($ongoing)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1
                                                 rounded-xl text-[10px] font-bold
                                                 bg-emerald-500 text-white shadow-sm">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full
                                                         rounded-full bg-white opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span>
                                        </span>
                                        Berlangsung
                                    </span>
                                @elseif($past)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl
                                                 text-[10px] font-medium text-slate-400
                                                 bg-slate-100 dark:bg-slate-700">
                                        ✓ Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl
                                                 text-[10px] font-medium text-indigo-600
                                                 bg-indigo-50 dark:bg-indigo-900/30
                                                 border border-indigo-200 dark:border-indigo-700">
                                        🕐 Akan Datang
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ─── Ringkasan Bawah ─────────────────────────────── --}}
        @php
            $jmlOngoing = $jadwalHariIni->filter(fn($t) => $isOngoing($t))->count();
            $jmlPast    = $jadwalHariIni->filter(fn($t) => $isPast($t))->count();
            $jmlAkan    = $jadwalHariIni->count() - $jmlOngoing - $jmlPast;
        @endphp
        <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/60 dark:bg-slate-900/30
                    flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-3 text-[10px]">
                @if($jmlOngoing > 0)
                    <span class="text-emerald-600 font-semibold">🟢 {{ $jmlOngoing }} berlangsung</span>
                @endif
                @if($jmlPast > 0)
                    <span class="text-slate-400">✓ {{ $jmlPast }} selesai</span>
                @endif
                @if($jmlAkan > 0)
                    <span class="text-slate-500">🕐 {{ $jmlAkan }} akan datang</span>
                @endif
            </div>
            <span class="text-[10px] text-slate-400">
                Total {{ $jadwalHariIni->count() }} sesi hari ini
            </span>
        </div>
    @endif

    {{-- ─── Pratinjau Jadwal Hari Berikutnya ───────────────── --}}
    @if($hariBerikutnya && $jadwalBerikutnya->isNotEmpty() && !$jadwalHariIni->isEmpty())
        <div class="px-4 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-gradient-to-r from-slate-50 to-indigo-50/30
                    dark:from-slate-900/40 dark:to-indigo-900/10">
            <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">
                Jadwal {{ $hariBerikutnya }}
            </p>
            <div class="space-y-2">
                @foreach($jadwalBerikutnya->take(5) as $bt)
                    <div class="flex items-center gap-2.5">
                        <div class="w-2 h-2 rounded-full shrink-0"
                             style="background: {{ $bt->studySubject->color ?? '#6366f1' }}">
                        </div>
                        <span class="flex-1 text-xs font-medium text-slate-700 dark:text-slate-300 truncate">
                            {{ $bt->studySubject->name ?? '-' }}
                        </span>
                        <span class="font-mono text-[10px] text-slate-400 shrink-0">
                            {{ substr($bt->start_time, 0, 5) }} – {{ substr($bt->end_time, 0, 5) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

{{-- ─── Rekap Mingguan ─────────────────────────────────────── --}}
@if($studyGroup && $jadwalByDay->isNotEmpty())
<div class="mt-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700
                bg-slate-50 dark:bg-slate-900/40 flex items-center justify-between">
        <div>
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">
                Rekap Jadwal Mingguan
            </h3>
            <p class="text-[10px] text-slate-400 mt-0.5">
                Kelas {{ $studyGroup->name ?? $studyGroup->nama ?? '-' }}
            </p>
        </div>
        @if(Route::has('siswa.jadwal'))
            <a href="{{ route('siswa.jadwal') }}"
               class="text-[10px] font-semibold text-indigo-500 hover:underline">
                Lihat Detail →
            </a>
        @endif
    </div>

    <div class="p-4 space-y-3">
        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
            @php
                $jHari   = $jadwalByDay[$hari] ?? collect();
                $isToday = ($hari === ($hariIniDb ?? ''));
            @endphp
            @if($jHari->isEmpty()) @continue @endif

            <div class="flex items-start gap-3">
                {{-- Label Hari --}}
                <div class="w-10 shrink-0 pt-0.5 text-right">
                    <span class="text-[10px] font-bold
                                 {{ $isToday
                                    ? 'text-indigo-600 dark:text-indigo-400'
                                    : 'text-slate-400 dark:text-slate-500' }}">
                        {{ substr($hari, 0, 3) }}
                    </span>
                    @if($isToday)
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 ml-auto mt-0.5"></div>
                    @endif
                </div>

                {{-- Chips Jadwal --}}
                <div class="flex-1 flex flex-wrap gap-1.5">
                    @foreach($jHari->sortBy('start_time') as $tt)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl
                                    border text-[10px] font-medium
                                    {{ $isToday
                                       ? 'border-indigo-200 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-700'
                                       : 'border-slate-200 bg-slate-50 dark:bg-slate-700/30 dark:border-slate-600' }}">
                            <div class="w-1.5 h-1.5 rounded-full shrink-0"
                                 style="background: {{ $tt->studySubject->color ?? '#6366f1' }}">
                            </div>
                            <span class="{{ $isToday ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400' }}">
                                {{ $tt->studySubject->name ?? '-' }}
                            </span>
                            <span class="font-mono text-slate-400 dark:text-slate-500">
                                {{ substr($tt->start_time, 0, 5) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
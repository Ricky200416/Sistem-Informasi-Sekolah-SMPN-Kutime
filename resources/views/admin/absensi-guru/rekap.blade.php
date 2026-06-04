{{-- resources/views/admin/absensi-guru/rekap.blade.php --}}
@extends('layouts.app')
@section('title', 'Rekap Absensi Guru')

@section('content')
<div class="space-y-4">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                Rekap Absensi Guru
            </h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Rekapitulasi kehadiran guru per bulan
            </p>
        </div>
        <a href="{{ route('admin.absensi-guru.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl
                  bg-indigo-600 text-white text-xs font-semibold
                  hover:bg-indigo-700 transition shadow-sm w-fit">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0
                         00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Input Absensi Harian
        </a>
    </div>

    {{-- ── Navigasi Bulan ───────────────────────────────────── --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.absensi-guru.rekap', ['bulan' => $prevBulan->month, 'tahun' => $prevBulan->year]) }}"
           class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-600
                  text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <form method="GET" action="{{ route('admin.absensi-guru.rekap') }}"
              class="flex items-center gap-1.5">
            <select name="bulan" onchange="this.form.submit()"
                    class="px-2 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600
                           bg-white dark:bg-slate-800 text-xs font-medium text-slate-700
                           dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                @foreach(range(1, 12) as $bln)
                <option value="{{ $bln }}" {{ $bulan == $bln ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::createFromDate($tahun, $bln, 1)->isoFormat('MMMM') }}
                </option>
                @endforeach
            </select>
            <select name="tahun" onchange="this.form.submit()"
                    class="px-2 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600
                           bg-white dark:bg-slate-800 text-xs font-medium text-slate-700
                           dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                @foreach(range(now()->year - 3, now()->year + 1) as $thn)
                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.absensi-guru.rekap', ['bulan' => $nextBulan->month, 'tahun' => $nextBulan->year]) }}"
           class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-600
                  text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 ml-1">
            {{ $namaBulan }}
        </span>
    </div>

    {{-- ── Tabel Rekap ──────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200
                               dark:border-slate-700 text-left">
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide w-7">#</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[180px]">
                            Nama Guru
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-emerald-600
                                   dark:text-emerald-400 uppercase tracking-wide text-center">
                            Hadir
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-blue-600
                                   dark:text-blue-400 uppercase tracking-wide text-center">
                            Sakit
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-amber-600
                                   dark:text-amber-400 uppercase tracking-wide text-center">
                            Izin
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-red-600
                                   dark:text-red-400 uppercase tracking-wide text-center">
                            Alpha
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center">
                            Total
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[120px]">
                            % Kehadiran
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">

                    @forelse($rekap as $i => $r)
                    @php
                        $pct = $r['total'] > 0
                            ? round($r['hadir'] / $r['total'] * 100)
                            : 0;
                        $barColor = $pct >= 80 ? 'bg-emerald-500' : ($pct >= 60 ? 'bg-amber-400' : 'bg-red-500');
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors">

                        <td class="px-3 py-2.5 text-[10px] text-slate-400">{{ $i + 1 }}</td>

                        {{-- Nama --}}
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                                            flex items-center justify-center text-indigo-600
                                            dark:text-indigo-400 text-[10px] font-bold shrink-0">
                                    {{ strtoupper(substr($r['guru']->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                               leading-tight">
                                        {{ $r['guru']->nama }}
                                    </p>
                                    @if($r['guru']->nip)
                                    <p class="text-[10px] text-slate-400 font-mono">
                                        {{ $r['guru']->nip }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Hadir --}}
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-5
                                         rounded-full text-[10px] font-semibold
                                         bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                {{ $r['hadir'] }}
                            </span>
                        </td>

                        {{-- Sakit --}}
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-5
                                         rounded-full text-[10px] font-semibold
                                         {{ $r['sakit'] > 0 ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'text-slate-300' }}">
                                {{ $r['sakit'] ?: '—' }}
                            </span>
                        </td>

                        {{-- Izin --}}
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-5
                                         rounded-full text-[10px] font-semibold
                                         {{ $r['izin'] > 0 ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' : 'text-slate-300' }}">
                                {{ $r['izin'] ?: '—' }}
                            </span>
                        </td>

                        {{-- Alpha --}}
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-5
                                         rounded-full text-[10px] font-semibold
                                         {{ $r['alpha'] > 0 ? 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300' : 'text-slate-300' }}">
                                {{ $r['alpha'] ?: '—' }}
                            </span>
                        </td>

                        {{-- Total hari --}}
                        <td class="px-3 py-2.5 text-center">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $r['total'] }}
                            </span>
                        </td>

                        {{-- Progress % kehadiran --}}
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="{{ $barColor }} h-full rounded-full transition-all"
                                         style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-400 w-8 text-right">
                                    {{ $pct }}%
                                </span>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-14 text-center">
                            <p class="text-xs text-slate-400">
                                Belum ada data absensi untuk bulan ini.
                            </p>
                            <a href="{{ route('admin.absensi-guru.index') }}"
                               class="text-xs text-indigo-600 dark:text-indigo-400
                                      hover:underline mt-1 inline-block">
                                Mulai input absensi →
                            </a>
                        </td>
                    </tr>
                    @endforelse

                </tbody>

                {{-- Total baris --}}
                @if($rekap->count() > 0)
                @php
                    $totHadir = $rekap->sum('hadir');
                    $totSakit = $rekap->sum('sakit');
                    $totIzin  = $rekap->sum('izin');
                    $totAlpha = $rekap->sum('alpha');
                    $totAll   = $rekap->sum('total');
                    $totPct   = $totAll > 0 ? round($totHadir / $totAll * 100) : 0;
                @endphp
                <tfoot>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-t-2 border-slate-200
                               dark:border-slate-600 font-semibold">
                        <td colspan="2" class="px-3 py-2.5 text-[10px] font-bold
                                               text-slate-600 dark:text-slate-400 uppercase tracking-wide">
                            Total
                        </td>
                        <td class="px-3 py-2.5 text-center text-xs font-bold text-emerald-700
                                   dark:text-emerald-300">{{ $totHadir }}</td>
                        <td class="px-3 py-2.5 text-center text-xs font-bold text-blue-700
                                   dark:text-blue-300">{{ $totSakit ?: '—' }}</td>
                        <td class="px-3 py-2.5 text-center text-xs font-bold text-amber-700
                                   dark:text-amber-300">{{ $totIzin ?: '—' }}</td>
                        <td class="px-3 py-2.5 text-center text-xs font-bold text-red-700
                                   dark:text-red-300">{{ $totAlpha ?: '—' }}</td>
                        <td class="px-3 py-2.5 text-center text-xs font-bold text-slate-700
                                   dark:text-slate-200">{{ $totAll }}</td>
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="{{ $totPct >= 80 ? 'bg-emerald-500' : ($totPct >= 60 ? 'bg-amber-400' : 'bg-red-500') }} h-full rounded-full"
                                         style="width:{{ $totPct }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200 w-8 text-right">
                                    {{ $totPct }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                @endif

            </table>
        </div>
    </div>

</div>
@endsection
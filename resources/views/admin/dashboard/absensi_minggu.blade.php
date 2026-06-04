{{-- resources/views/admin/dashboard/absensi_minggu.blade.php
     Variabel: $absensiMinggu (array: hadir, sakit, izin, alpha, telat)
--}}
@php
    $total = array_sum($absensiMinggu);
    $pct   = fn(int $val): int => $total > 0 ? (int) round($val / $total * 100) : 0;

    $items = [
        ['label' => 'Hadir',     'key' => 'hadir', 'color' => 'bg-emerald-500', 'text' => 'text-emerald-700 dark:text-emerald-300', 'ring' => 'ring-emerald-200 dark:ring-emerald-800'],
        ['label' => 'Sakit',     'key' => 'sakit', 'color' => 'bg-blue-400',    'text' => 'text-blue-700 dark:text-blue-300',       'ring' => 'ring-blue-200 dark:ring-blue-800'],
        ['label' => 'Izin',      'key' => 'izin',  'color' => 'bg-amber-400',   'text' => 'text-amber-700 dark:text-amber-300',     'ring' => 'ring-amber-200 dark:ring-amber-800'],
        ['label' => 'Alpha',     'key' => 'alpha', 'color' => 'bg-red-500',     'text' => 'text-red-700 dark:text-red-300',         'ring' => 'ring-red-200 dark:ring-red-800'],
        ['label' => 'Terlambat', 'key' => 'telat', 'color' => 'bg-pink-400',    'text' => 'text-pink-700 dark:text-pink-300',       'ring' => 'ring-pink-200 dark:ring-pink-800'],
    ];
@endphp

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3
                border-b border-slate-100 dark:border-slate-700/60">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500
                        flex items-center justify-center text-white text-sm shadow-sm shrink-0">
                📊
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                    Absensi Minggu Ini
                </p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    {{ \Carbon\Carbon::now()->startOfWeek()->isoFormat('D MMM') }}
                    –
                    {{ \Carbon\Carbon::now()->endOfWeek()->isoFormat('D MMM Y') }}
                </p>
            </div>
        </div>
        <a href="{{ route('admin.absensi-guru.rekap') }}"
           class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700
                  flex items-center gap-0.5 transition-colors">
            Rekap
            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Stacked bar --}}
    @if($total > 0)
    <div class="px-4 pt-3">
        <div class="flex h-3 rounded-full overflow-hidden gap-px">
            @foreach($items as $item)
            @php $p = $pct($absensiMinggu[$item['key']] ?? 0); @endphp
            @if($p > 0)
            <div class="{{ $item['color'] }} transition-all" style="width:{{ $p }}%"
                 title="{{ $item['label'] }}: {{ $absensiMinggu[$item['key']] }}"></div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Stats grid --}}
    <div class="grid grid-cols-5 gap-0 px-4 py-3">
        @foreach($items as $item)
        @php $val = $absensiMinggu[$item['key']] ?? 0; @endphp
        <div class="flex flex-col items-center gap-1">
            <span class="text-sm font-black {{ $item['text'] }}">{{ $val }}</span>
            <div class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full {{ $item['color'] }}"></span>
                <span class="text-[9px] text-slate-400 font-medium">{{ $item['label'] }}</span>
            </div>
            @if($total > 0)
            <span class="text-[9px] font-bold {{ $item['text'] }} opacity-60">
                {{ $pct($val) }}%
            </span>
            @endif
        </div>
        @endforeach
    </div>

    @if($total === 0)
    <div class="px-4 pb-4 text-center">
        <p class="text-[10px] text-slate-400">Belum ada data absensi minggu ini.</p>
    </div>
    @endif
</div>
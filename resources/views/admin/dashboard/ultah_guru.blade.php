{{-- resources/views/admin/dashboard/ultah_guru.blade.php
     Variabel: $guruUltah (Collection — guru ulang tahun bulan ini)
--}}
@if($guruUltah->isNotEmpty())
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3
                border-b border-slate-100 dark:border-slate-700/60">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-pink-400 to-rose-500
                        flex items-center justify-center text-white text-sm shadow-sm shrink-0">
                🎂
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                    Ulang Tahun Bulan Ini
                </p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    {{ $guruUltah->count() }} guru
                    · {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
        @foreach($guruUltah->take(5) as $g)
        @php
            $day      = (int) \Carbon\Carbon::parse($g->tanggal_lahir)->format('d');
            $dayName  = \Carbon\Carbon::parse($g->tanggal_lahir)->isoFormat('D MMMM');
            $inisial  = strtoupper(mb_substr($g->nama ?? '?', 0, 2));
        @endphp
        <div class="flex items-center gap-3 px-4 py-2.5
                    {{ $g->ultah_hari_ini ? 'bg-pink-50/60 dark:bg-pink-900/10' : '' }}
                    hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors">

            {{-- Avatar --}}
            <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center
                        text-[11px] font-bold text-white shadow-sm
                        bg-gradient-to-br
                        {{ $g->ultah_hari_ini
                            ? 'from-pink-400 to-rose-500'
                            : 'from-slate-400 to-slate-500' }}">
                {{ $inisial }}
            </div>

            {{-- Nama --}}
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                           leading-tight truncate">
                    {{ $g->nama }}
                    @if($g->ultah_hari_ini)
                        <span class="text-pink-500 ml-1">🎉</span>
                    @endif
                </p>
                @if($g->nip)
                <p class="text-[10px] font-mono text-slate-400 leading-tight">
                    {{ $g->nip }}
                </p>
                @endif
            </div>

            {{-- Tanggal --}}
            <div class="shrink-0 text-right">
                <span class="text-[10px] font-bold
                             {{ $g->ultah_hari_ini
                                ? 'text-pink-600 dark:text-pink-400'
                                : 'text-slate-500 dark:text-slate-400' }}">
                    {{ $dayName }}
                </span>
                @if($g->ultah_hari_ini)
                <p class="text-[9px] font-bold text-pink-500 text-right">Hari ini!</p>
                @endif
            </div>
        </div>
        @endforeach

        @if($guruUltah->count() > 5)
        <div class="px-4 py-2 text-center">
            <p class="text-[10px] text-slate-400">
                +{{ $guruUltah->count() - 5 }} guru lainnya bulan ini
            </p>
        </div>
        @endif
    </div>
</div>
@endif
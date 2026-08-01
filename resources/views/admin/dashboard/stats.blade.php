{{-- resources/views/admin/dashboard/stats.blade.php --}}
@php
    $cards = [
        [
            'label'   => 'Total Guru',
            'value'   => $stats['total_guru'] ?? 0,
            'icon'    => '👨‍🏫',
            'color'   => 'from-indigo-600 to-blue-600',
            'bg'      => 'bg-indigo-50 dark:bg-indigo-900/20',
            'sub'     => ($stats['guru_hadir'] ?? 0) . ' hadir hari ini',
            'subColor'=> 'text-emerald-600',
        ],
        [
            'label'   => 'Total Siswa',
            'value'   => $stats['total_siswa'] ?? 0,
            'icon'    => '🧑‍🎓',
            'color'   => 'from-violet-600 to-purple-600',
            'bg'      => 'bg-violet-50 dark:bg-violet-900/20',
            'sub'     => 'Siswa Aktif',
            'subColor'=> 'text-slate-400',
        ],
        [
            'label'   => 'Total Kelas',
            'value'   => $stats['total_kelas'] ?? 0,
            'icon'    => '🏫',
            'color'   => 'from-sky-600 to-cyan-600',
            'bg'      => 'bg-sky-50 dark:bg-sky-900/20',
            'sub'     => ($kelasTanpaWali ?? 0) > 0 ? ($kelasTanpaWali . ' tnp wali') : 'Wali terisi semua',
            'subColor'=> ($kelasTanpaWali ?? 0) > 0 ? 'text-amber-600' : 'text-emerald-600',
        ],
        [
            'label'   => 'Guru Hadir',
            'value'   => $stats['guru_hadir'] ?? 0,
            'icon'    => '✅',
            'color'   => 'from-emerald-600 to-teal-600',
            'bg'      => 'bg-emerald-50 dark:bg-emerald-900/20',
            'sub'     => 'Kehadiran Real-time',
            'subColor'=> 'text-slate-400',
        ],
    ];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($cards as $card)
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm p-4 flex items-center gap-4
                hover:border-indigo-300 dark:hover:border-indigo-700 transition-all group">

        {{-- Icon dengan Shadow Gradient --}}
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $card['color'] }}
                    flex items-center justify-center text-2xl shrink-0 shadow-lg shadow-indigo-100 dark:shadow-none 
                    group-hover:scale-110 transition-transform">
            {{ $card['icon'] }}
        </div>

        {{-- Data dengan Font Black --}}
        <div class="min-w-0 flex-1">
            <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.15em] mb-1">
                {{ $card['label'] }}
            </p>
            <p class="text-2xl font-black text-slate-800 dark:text-slate-100 leading-none">
                {{ number_format($card['value']) }}
            </p>
            <p class="text-[9px] font-bold mt-1.5 {{ $card['subColor'] }} flex items-center gap-1">
                <span class="w-1 h-1 rounded-full bg-current opacity-50"></span> {{ $card['sub'] }}
            </p>
        </div>
    </div>
    @endforeach
</div>
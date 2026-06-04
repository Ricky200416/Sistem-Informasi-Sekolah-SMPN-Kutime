{{-- resources/views/admin/dashboard/stats.blade.php
     Variabel: $stats (array: total_guru, total_siswa, total_kelas, guru_hadir)
               $kelasTanpaWali (int)
--}}
@php
    $cards = [
        [
            'label'   => 'Total Guru',
            'value'   => $stats['total_guru'] ?? 0,
            'icon'    => '👨‍🏫',
            'color'   => 'from-indigo-500 to-indigo-600',
            'bg'      => 'bg-indigo-50 dark:bg-indigo-900/20',
            'text'    => 'text-indigo-700 dark:text-indigo-300',
            'sub'     => ($stats['guru_hadir'] ?? 0) . ' hadir hari ini',
            'subColor'=> 'text-emerald-600 dark:text-emerald-400',
        ],
        [
            'label'   => 'Total Siswa',
            'value'   => $stats['total_siswa'] ?? 0,
            'icon'    => '🧑‍🎓',
            'color'   => 'from-violet-500 to-violet-600',
            'bg'      => 'bg-violet-50 dark:bg-violet-900/20',
            'text'    => 'text-violet-700 dark:text-violet-300',
            'sub'     => 'Terdaftar aktif',
            'subColor'=> 'text-slate-400',
        ],
        [
            'label'   => 'Total Kelas',
            'value'   => $stats['total_kelas'] ?? 0,
            'icon'    => '🏫',
            'color'   => 'from-sky-500 to-sky-600',
            'bg'      => 'bg-sky-50 dark:bg-sky-900/20',
            'text'    => 'text-sky-700 dark:text-sky-300',
            'sub'     => ($kelasTanpaWali ?? 0) > 0
                        ? ($kelasTanpaWali . ' kelas tanpa wali')
                        : 'Semua ada wali kelas',
            'subColor'=> ($kelasTanpaWali ?? 0) > 0
                        ? 'text-amber-600 dark:text-amber-400'
                        : 'text-emerald-600 dark:text-emerald-400',
        ],
        [
            'label'   => 'Guru Hadir',
            'value'   => $stats['guru_hadir'] ?? 0,
            'icon'    => '✅',
            'color'   => 'from-emerald-500 to-emerald-600',
            'bg'      => 'bg-emerald-50 dark:bg-emerald-900/20',
            'text'    => 'text-emerald-700 dark:text-emerald-300',
            'sub'     => 'Hari ini',
            'subColor'=> 'text-slate-400',
        ],
    ];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
    @foreach($cards as $card)
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm p-4 flex items-start gap-3
                hover:shadow-md transition-shadow">

        {{-- Icon --}}
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $card['color'] }}
                    flex items-center justify-center text-lg shrink-0 shadow-sm">
            {{ $card['icon'] }}
        </div>

        {{-- Data --}}
        <div class="min-w-0 flex-1">
            <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400
                       uppercase tracking-wider leading-none mb-1">
                {{ $card['label'] }}
            </p>
            <p class="text-2xl font-black text-slate-800 dark:text-slate-100 leading-none">
                {{ number_format($card['value']) }}
            </p>
            <p class="text-[10px] font-medium mt-1 {{ $card['subColor'] }} leading-none">
                {{ $card['sub'] }}
            </p>
        </div>
    </div>
    @endforeach
</div>
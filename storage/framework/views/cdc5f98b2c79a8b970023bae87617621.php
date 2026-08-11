

<?php $__env->startSection('title', 'Jadwal Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;
    $jamSekarang = Carbon::now()->format('H:i');
    $hariIniDb   = $hariIniDb ?? Carbon::now()->locale('id')->isoFormat('dddd');

    $isOngoing = function($tt) use ($jamSekarang) {
        if (!$tt?->start_time || !$tt?->end_time) return false;
        return $jamSekarang >= substr($tt->start_time, 0, 5)
            && $jamSekarang <  substr($tt->end_time,   0, 5);
    };
    $isPast = function($tt) use ($jamSekarang) {
        if (!$tt?->end_time) return false;
        return $jamSekarang >= substr($tt->end_time, 0, 5);
    };
?>

<div class="space-y-4">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Jadwal Pelajaran</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Jadwal pembelajaran kelas Anda minggu ini.
            </p>
        </div>

        <?php if($studyGroup): ?>
        <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-indigo-50
                    dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800">
            <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2
                         0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1
                         1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <div>
                <p class="text-[10px] font-bold text-indigo-700 dark:text-indigo-300">
                    Kelas <?php echo e($studyGroup->name ?? $studyGroup->nama ?? '-'); ?>

                </p>
                <p class="text-[9px] text-indigo-500 dark:text-indigo-400">
                    <?php if(!empty($studyGroup->academic_year)): ?>
                        <?php echo e($studyGroup->academic_year); ?>

                        <?php if(!empty($studyGroup->semester)): ?> · Sem <?php echo e($studyGroup->semester); ?> <?php endif; ?>
                    <?php endif; ?>
                    <?php if(!empty($studyGroup->homeroomTeacher)): ?>
                        · Wali: <?php echo e($studyGroup->homeroomTeacher->name); ?>

                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if(!$studyGroup): ?>
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/30
                        flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2
                             2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                Anda belum terdaftar di kelas apapun
            </p>
            <p class="text-[10px] text-slate-400 mt-1">
                Hubungi admin atau wali kelas untuk mendaftarkan Anda ke kelas.
            </p>
        </div>

    <?php else: ?>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <?php $__currentLoopData = [
            ['label' => 'Total Jadwal',    'value' => $totalJadwal, 'color' => 'indigo'],
            ['label' => 'Mata Pelajaran',  'value' => $totalMapel,  'color' => 'emerald'],
            ['label' => 'Guru Pengajar',   'value' => $totalGuru,   'color' => 'amber'],
            ['label' => 'Hari Belajar',    'value' => $hariAktif,   'color' => 'sky'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-<?php echo e($kpi['color']); ?>-600
                      dark:text-<?php echo e($kpi['color']); ?>-400 leading-none">
                <?php echo e($kpi['value']); ?>

            </p>
            <p class="text-[10px] text-slate-400 mt-0.5"><?php echo e($kpi['label']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; ?>

    <?php if($totalJadwal === 0): ?>
        <div class="flex flex-col items-center justify-center py-14 text-center">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700
                        flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-slate-300 dark:text-slate-600" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2"/>
                </svg>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                Jadwal pelajaran belum tersedia
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">
                Guru belum mengatur jadwal untuk kelas Anda.
            </p>
        </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $jadwalHari = ($jadwalByDay[$hari] ?? collect())->sortBy('start_time'); ?>
            <?php if($jadwalHari->isEmpty()): ?> <?php continue; ?> <?php endif; ?>

            <?php $isToday = ($hari === $hariIniDb); ?>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border overflow-hidden flex flex-col
                        shadow-sm
                        <?php echo e($isToday
                           ? 'border-indigo-300 dark:border-indigo-600 ring-1 ring-indigo-200 dark:ring-indigo-700'
                           : 'border-slate-200 dark:border-slate-700'); ?>">

                
                <div class="flex items-center justify-between px-3.5 py-3 border-b
                            <?php echo e($isToday
                               ? 'border-indigo-100 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-900/20'
                               : 'border-slate-100 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/30'); ?>">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-5 rounded-full
                                    <?php echo e($isToday ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-slate-600'); ?>">
                        </div>
                        <span class="text-xs font-bold
                                     <?php echo e($isToday ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-200'); ?>">
                            <?php echo e($hari); ?>

                            <?php if($isToday): ?>
                                <span class="ml-1 text-[9px] font-semibold text-indigo-500">(Hari Ini)</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                 <?php echo e($isToday
                                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                    : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'); ?>">
                        <?php echo e($jadwalHari->count()); ?> sesi
                    </span>
                </div>

                
                <div class="divide-y divide-slate-50 dark:divide-slate-700/30 flex-1">
                    <?php $__currentLoopData = $jadwalHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $ongoing = $isToday && $isOngoing($tt);
                            $past    = $isToday && $isPast($tt);
                            $color   = $tt->studySubject->color ?? '#6366f1';
                        ?>
                        <div class="flex items-start gap-2.5 px-3.5 py-3 transition
                                    <?php echo e($ongoing
                                       ? 'bg-emerald-50 dark:bg-emerald-900/10'
                                       : ($past ? 'opacity-60' : '')); ?>">

                            
                            <div class="flex flex-col items-center shrink-0 pt-0.5 min-w-[38px]">
                                <span class="text-[10px] font-bold text-slate-700 dark:text-slate-200 tabular-nums">
                                    <?php echo e(substr($tt->start_time, 0, 5)); ?>

                                </span>
                                <div class="w-px h-3 bg-slate-200 dark:bg-slate-600 my-0.5"></div>
                                <span class="text-[9px] text-slate-400 tabular-nums">
                                    <?php echo e(substr($tt->end_time, 0, 5)); ?>

                                </span>
                            </div>

                            
                            <div class="w-1 self-stretch rounded-full shrink-0"
                                 style="background: <?php echo e($color); ?>"></div>

                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-1">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100
                                              leading-tight truncate">
                                        <?php echo e($tt->studySubject->name ?? '-'); ?>

                                    </p>
                                    <?php if($ongoing): ?>
                                        <span class="shrink-0 flex items-center gap-0.5 text-[9px]
                                                     font-bold text-emerald-600 dark:text-emerald-400">
                                            <span class="relative flex h-1.5 w-1.5">
                                                <span class="animate-ping absolute inline-flex h-full w-full
                                                             rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5
                                                             bg-emerald-500"></span>
                                            </span>
                                            Live
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                                    <?php echo e($tt->teacher->name ?? '-'); ?>

                                    <?php if($tt->room): ?> · <?php echo e($tt->room); ?> <?php endif; ?>
                                </p>

                                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                    <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px] font-semibold
                                                 <?php echo e(($tt->session_type ?? 'teori') === 'praktikum'
                                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                    : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'); ?>">
                                        <?php echo e(ucfirst($tt->session_type ?? 'Teori')); ?>

                                    </span>
                                    <span class="text-[9px] text-slate-400">
                                        <?php echo e($tt->studySubject->code ?? ''); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <?php if($mataPelajaran->isNotEmpty()): ?>
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden">

        <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700
                    bg-slate-50 dark:bg-slate-900/30 flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">
                    Mata Pelajaran — Kelas <?php echo e($studyGroup->name ?? $studyGroup->nama ?? '-'); ?>

                </h3>
                <p class="text-[10px] text-slate-400 mt-0.5">
                    <?php echo e($mataPelajaran->count()); ?> mata pelajaran terdaftar
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/40 border-b border-slate-100
                               dark:border-slate-700">
                        <?php $__currentLoopData = ['#','Mata Pelajaran','Kode','Guru Pengajar','Sesi/Minggu','Tipe']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500
                                   uppercase tracking-wide text-left whitespace-nowrap">
                            <?php echo e($th); ?>

                        </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                    <?php $__currentLoopData = $mataPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition">
                        <td class="px-3 py-2.5 text-[10px] text-slate-400"><?php echo e($idx + 1); ?></td>

                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full shrink-0"
                                     style="background: <?php echo e($mp->color ?? '#6366f1'); ?>"></div>
                                <span class="text-xs font-semibold text-slate-800 dark:text-slate-100">
                                    <?php echo e($mp->name); ?>

                                </span>
                            </div>
                        </td>

                        <td class="px-3 py-2.5">
                            <span class="font-mono text-[10px] text-slate-500
                                         bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded-md">
                                <?php echo e($mp->code); ?>

                            </span>
                        </td>

                        <td class="px-3 py-2.5">
                            <?php
                                $guruMapel = $allTimetables
                                    ->where('study_subject_id', $mp->id)
                                    ->pluck('teacher.name')
                                    ->unique()
                                    ->filter();
                            ?>
                            <?php $__empty_1 = true; $__currentLoopData = $guruMapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaGuru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <p class="text-[10px] text-slate-600 dark:text-slate-400 leading-tight">
                                    <?php echo e($namaGuru); ?>

                                </p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-3 py-2.5">
                            <?php
                                $sesiCount = $allTimetables
                                    ->where('study_subject_id', $mp->id)
                                    ->count();
                            ?>
                            <span class="inline-flex items-center justify-center w-6 h-5
                                         rounded-full text-[10px] font-bold
                                         bg-indigo-100 text-indigo-700
                                         dark:bg-indigo-900/40 dark:text-indigo-300">
                                <?php echo e($sesiCount); ?>

                            </span>
                        </td>

                        <td class="px-3 py-2.5">
                            <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px] font-semibold
                                         <?php echo e(($mp->type ?? '') === 'core'
                                            ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400'
                                            : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400'); ?>">
                                <?php echo e(($mp->type ?? '') === 'core' ? 'Wajib' : 'Pilihan'); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?> 

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/siswa/jadwal-pelajaran/index.blade.php ENDPATH**/ ?>
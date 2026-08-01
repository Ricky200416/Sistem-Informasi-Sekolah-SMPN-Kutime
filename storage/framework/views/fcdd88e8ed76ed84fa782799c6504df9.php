
<?php
    $timeNow = now()->format('H:i');
    $statusSesi = function($start, $end) use ($timeNow) {
        $s = substr($start ?? '', 0, 5);
        $e = substr($end   ?? '', 0, 5);
        if (!$s || !$e) return 'akan';
        if ($timeNow >= $s && $timeNow <= $e) return 'sekarang';
        if ($timeNow > $e) return 'selesai';
        return 'akan';
    };
?>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-[520px]">
    
    
    <div class="p-5 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between sticky top-0 bg-white dark:bg-slate-800 z-10">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-lg shrink-0">📅</div>
            <div>
                <h3 class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider">Jadwal Belajar</h3>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-0.5"><?php echo e(\Carbon\Carbon::now()->isoFormat('dddd, D MMM Y')); ?></p>
            </div>
        </div>
        <span class="px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 text-[10px] font-black text-slate-500"><?php echo e($jadwalHariIni->count()); ?> SESI</span>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/30 dark:bg-slate-900/20 custom-scrollbar">
        <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $status = $statusSesi($j->start_time, $j->end_time); ?>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border transition-all
                        <?php echo e($status === 'sekarang' ? 'border-amber-400 shadow-md ring-1 ring-amber-400/20' : 'border-slate-100 dark:border-slate-700'); ?>

                        <?php echo e($status === 'selesai' ? 'opacity-40 grayscale' : ''); ?> flex items-start gap-4 group">
                
                <div class="flex flex-col items-center gap-1 shrink-0 pt-1">
                    <p class="text-[10px] font-black text-slate-800 dark:text-white leading-none"><?php echo e(substr($j->start_time, 0, 5)); ?></p>
                    <div class="w-0.5 h-6 bg-slate-200 dark:bg-slate-700"></div>
                    <p class="text-[9px] font-bold text-slate-400 leading-none"><?php echo e(substr($j->end_time, 0, 5)); ?></p>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-xs font-black text-slate-800 dark:text-white truncate uppercase"><?php echo e($j->mapel_nama); ?></h4>
                        <?php if($status === 'sekarang'): ?>
                            <span class="text-[8px] font-black bg-amber-500 text-white px-1.5 py-0.5 rounded uppercase animate-pulse">Berlangsung</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 pt-2 border-t border-slate-50 dark:border-slate-700">
                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-1">
                             <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Kelas <?php echo e($j->kelas_nama); ?>

                        </p>
                        <p class="text-[10px] font-medium text-slate-400 truncate flex items-center gap-1">
                             <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> <?php echo e($j->guru_nama); ?>

                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="h-full flex flex-col items-center justify-center opacity-20 text-center py-10">
                <div class="text-5xl mb-4">🏫</div>
                <p class="text-xs font-black uppercase tracking-[0.2em]">Tidak ada jadwal</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if(Route::has('admin.academic-planner.index')): ?>
    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-700 text-center">
        <a href="<?php echo e(route('admin.academic-planner.index')); ?>" class="text-[10px] font-black text-indigo-600 uppercase hover:underline">Kelola Seluruh Jadwal ➜</a>
    </div>
    <?php endif; ?>
</div><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/dashboard/schedule.blade.php ENDPATH**/ ?>
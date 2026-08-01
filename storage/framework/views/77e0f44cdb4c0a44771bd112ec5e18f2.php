
<?php if($guruUltah->isNotEmpty()): ?>
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">

    
    <div class="flex items-center justify-between px-5 py-4
                border-b border-slate-100 dark:border-slate-700/60 bg-pink-50/30 dark:bg-pink-900/10">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-pink-500 text-white
                        flex items-center justify-center text-lg shadow-md animate-bounce shrink-0">
                🎂
            </div>
            <div>
                <p class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                    Ulang Tahun
                </p>
                <p class="text-[9px] text-pink-600 font-black uppercase tracking-widest mt-0.5">
                    <?php echo e(\Carbon\Carbon::now()->isoFormat('MMMM Y')); ?>

                </p>
            </div>
        </div>
        <span class="text-[10px] font-black text-pink-500"><?php echo e($guruUltah->count()); ?> Guru</span>
    </div>

    <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
        <?php $__currentLoopData = $guruUltah->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all">
            <?php $isToday = \Carbon\Carbon::parse($g->tanggal_lahir)->format('d-m') === now()->format('d-m'); ?>
            
            <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center text-[10px] font-black text-white shadow-sm border-2 border-white dark:border-slate-800
                        bg-gradient-to-br <?php echo e($isToday ? 'from-pink-500 to-rose-600 scale-110' : 'from-slate-400 to-slate-500'); ?>">
                <?php echo e(strtoupper(substr($g->nama, 0, 2))); ?>

            </div>

            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-slate-800 dark:text-slate-100 truncate flex items-center gap-1">
                    <?php echo e($g->nama); ?>

                    <?php if($isToday): ?> <span class="text-sm">🎉</span> <?php endif; ?>
                </p>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                    <?php echo e(\Carbon\Carbon::parse($g->tanggal_lahir)->isoFormat('D MMMM')); ?>

                </p>
            </div>

            <?php if($isToday): ?>
            <div class="shrink-0">
                <span class="px-2 py-1 rounded-lg bg-pink-500 text-white text-[8px] font-black uppercase tracking-widest shadow-sm">Hari Ini!</span>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/dashboard/ultah_guru.blade.php ENDPATH**/ ?>
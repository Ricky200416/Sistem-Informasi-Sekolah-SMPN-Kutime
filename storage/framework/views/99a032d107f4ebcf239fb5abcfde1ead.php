
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">

    
    <div class="flex items-center justify-between px-5 py-3.5
                border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/30 dark:bg-slate-800/50">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-slate-800 text-white
                        flex items-center justify-center shadow-md shrink-0">
                ⚡
            </div>
            <div>
                <p class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                    Log Aktivitas Sistem
                </p>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                    Real-time Tracking <span class="mx-1">•</span> 12 jam terakhir
                </p>
            </div>
        </div>
        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 text-[9px] font-black uppercase tracking-widest">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Live
        </span>
    </div>

    
    <div class="flex items-center gap-1 px-4 py-2 border-b border-slate-50 dark:border-slate-700/40 bg-white dark:bg-slate-800 overflow-x-auto no-scrollbar">
        <?php $__currentLoopData = ['all' => 'Semua', 'guru' => '👨‍🏫 Guru', 'siswa' => '🧑‍🎓 Siswa', 'admin' => '🔧 Admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button onclick="alFilter('<?php echo e($role); ?>')" id="al-tab-<?php echo e($role); ?>"
                    class="al-tab px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap
                    <?php echo e($role === 'all' ? 'bg-slate-800 text-white dark:bg-slate-100 dark:text-slate-900 shadow-sm' : 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700'); ?>">
                <?php echo e($label); ?>

            </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar bg-white dark:bg-slate-800" id="alLogList">
        <?php $__empty_1 = true; $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="al-row flex items-start gap-4 px-5 py-3.5 border-b border-slate-50 dark:border-slate-700/30
                    hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors last:border-0"
             data-role="<?php echo e($log->role); ?>">

            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-lg shrink-0 mt-0.5 border border-slate-200 dark:border-slate-600">
                <?php echo e($log->actionIcon()); ?>

            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="text-[11px] font-black text-slate-800 dark:text-slate-100 truncate">
                        <?php echo e($log->user?->name ?? 'Unknown'); ?>

                    </span>
                    <span class="text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-widest <?php echo e($log->roleBadgeColor()); ?>">
                        <?php echo e($log->role); ?>

                    </span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug">
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter"><?php echo e($log->action); ?></span>
                    <?php if($log->module): ?> <span class="mx-1 opacity-20">•</span> <span class="font-medium text-slate-400"><?php echo e($log->module); ?></span> <?php endif; ?>
                </p>
                <?php if($log->description): ?>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 italic line-clamp-1 border-l-2 border-slate-100 dark:border-slate-700 pl-2">
                    <?php echo e($log->description); ?>

                </p>
                <?php endif; ?>
            </div>

            <div class="shrink-0 text-right">
                <p class="text-[10px] font-black text-slate-800 dark:text-slate-100"><?php echo e($log->created_at->format('H:i')); ?></p>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1"><?php echo e($log->created_at->diffForHumans(null, true)); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="py-16 text-center">
            <div class="text-4xl mb-3 opacity-20">📜</div>
            <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Belum ada aktivitas terekam</p>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700/60 text-center">
        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
             Retention Policy: Log dihapus otomatis > 12 jam
        </p>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('5959e925-8dc0-460a-baff-b8a0bd6e63c8')): $__env->markAsRenderedOnce('5959e925-8dc0-460a-baff-b8a0bd6e63c8'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function alFilter(role) {
    document.querySelectorAll('.al-tab').forEach(btn => {
        btn.classList.remove('bg-slate-800', 'text-white', 'dark:bg-slate-100', 'dark:text-slate-900', 'shadow-sm');
        btn.classList.add('text-slate-400');
    });
    const active = document.getElementById('al-tab-' + role);
    active.classList.add('bg-slate-800', 'text-white', 'dark:bg-slate-100', 'dark:text-slate-900', 'shadow-sm');
    active.classList.remove('text-slate-400');

    document.querySelectorAll('.al-row').forEach(row => {
        row.style.display = (role === 'all' || row.dataset.role === role) ? 'flex' : 'none';
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/dashboard/activity_log.blade.php ENDPATH**/ ?>


<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
            dark:border-slate-700 shadow-sm overflow-hidden">

    
    <div class="flex items-center justify-between px-4 py-3
                border-b border-slate-100 dark:border-slate-700/60">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800
                        flex items-center justify-center text-white text-sm shadow-sm shrink-0">
                ⚡
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                    Log Aktivitas
                </p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">
                    12 jam terakhir · auto-hapus
                </p>
            </div>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="inline-flex items-center gap-1 text-[10px] font-bold
                         text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700
                         px-2 py-1 rounded-lg">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                Live
            </span>
        </div>
    </div>

    
    <div class="flex items-center gap-1 px-4 py-2 border-b border-slate-50
                dark:border-slate-700/40 overflow-x-auto">
        <button onclick="alFilter('all')" id="al-tab-all"
                class="al-tab px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all
                       bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-800
                       whitespace-nowrap">
            Semua
            <span class="ml-1 opacity-70"><?php echo e($activityLogs->count()); ?></span>
        </button>
        <button onclick="alFilter('guru')" id="al-tab-guru"
                class="al-tab px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all
                       bg-slate-100 text-slate-600 hover:bg-slate-200
                       dark:bg-slate-700 dark:text-slate-300
                       whitespace-nowrap">
            👨‍🏫 Guru
            <span class="ml-1 opacity-70"><?php echo e($activityLogs->where('role','guru')->count()); ?></span>
        </button>
        <button onclick="alFilter('siswa')" id="al-tab-siswa"
                class="al-tab px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all
                       bg-slate-100 text-slate-600 hover:bg-slate-200
                       dark:bg-slate-700 dark:text-slate-300
                       whitespace-nowrap">
            🧑‍🎓 Siswa
            <span class="ml-1 opacity-70"><?php echo e($activityLogs->where('role','siswa')->count()); ?></span>
        </button>
        <button onclick="alFilter('admin')" id="al-tab-admin"
                class="al-tab px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all
                       bg-slate-100 text-slate-600 hover:bg-slate-200
                       dark:bg-slate-700 dark:text-slate-300
                       whitespace-nowrap">
            🔧 Admin
            <span class="ml-1 opacity-70"><?php echo e($activityLogs->where('role','admin')->count()); ?></span>
        </button>
    </div>

    
    <?php if($activityLogs->isEmpty()): ?>
        <div class="flex flex-col items-center gap-2 py-10 text-center px-4">
            <div class="text-3xl">🌙</div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                Belum ada aktivitas
            </p>
            <p class="text-[10px] text-slate-400">
                Log akan muncul saat guru/siswa mulai beraktivitas.
            </p>
        </div>
    <?php else: ?>
        <div class="overflow-y-auto max-h-[320px]
                    scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-700"
             id="alLogList">

            <?php $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $icon       = $log->actionIcon();
                $badgeColor = $log->roleBadgeColor();
                $timeStr    = $log->created_at->format('H:i');
                $diffStr    = $log->created_at->diffForHumans(null, true);
                $userName   = $log->user?->name ?? 'Unknown';
            ?>
            <div class="al-row flex items-start gap-3 px-4 py-2.5
                        border-b border-slate-50 dark:border-slate-700/30
                        hover:bg-slate-50 dark:hover:bg-slate-700/20
                        transition-colors last:border-0"
                 data-role="<?php echo e($log->role); ?>">

                
                <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-700
                            flex items-center justify-center text-sm shrink-0 mt-0.5">
                    <?php echo e($icon); ?>

                </div>

                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                     truncate max-w-[120px]">
                            <?php echo e($userName); ?>

                        </span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-semibold
                                     <?php echo e($badgeColor); ?> shrink-0">
                            <?php echo e(ucfirst($log->role)); ?>

                        </span>
                    </div>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">
                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            <?php echo e($log->action); ?>

                        </span>
                        <?php if($log->module): ?>
                            <span class="text-slate-400">· <?php echo e($log->module); ?></span>
                        <?php endif; ?>
                    </p>
                    <?php if($log->description): ?>
                    <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">
                        <?php echo e($log->description); ?>

                    </p>
                    <?php endif; ?>
                </div>

                
                <div class="shrink-0 text-right">
                    <p class="text-[10px] font-mono font-semibold text-slate-500
                               dark:text-slate-400"><?php echo e($timeStr); ?></p>
                    <p class="text-[9px] text-slate-300 dark:text-slate-600 mt-0.5">
                        <?php echo e($diffStr); ?>

                    </p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-900/30
                border-t border-slate-100 dark:border-slate-700/60">
        <p class="text-[10px] text-slate-400 text-center">
            ⏰ Log dihapus otomatis setelah <strong class="text-slate-500">12 jam</strong>
        </p>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('f286f737-59e3-4db3-a753-7fc243c76a9c')): $__env->markAsRenderedOnce('f286f737-59e3-4db3-a753-7fc243c76a9c'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function alFilter(role) {
    // Update tab style
    document.querySelectorAll('.al-tab').forEach(btn => {
        const isDark  = document.documentElement.classList.contains('dark');
        btn.className = btn.className
            .replace(/bg-slate-800|text-white|dark:bg-slate-200|dark:text-slate-800/g, '')
            .trim();
        btn.classList.remove('bg-slate-800','text-white','dark:bg-slate-200','dark:text-slate-800');
        btn.classList.add('bg-slate-100','text-slate-600','hover:bg-slate-200',
                          'dark:bg-slate-700','dark:text-slate-300');
    });
    const activeTab = document.getElementById('al-tab-' + role);
    if (activeTab) {
        activeTab.classList.remove('bg-slate-100','text-slate-600','hover:bg-slate-200',
                                   'dark:bg-slate-700','dark:text-slate-300');
        activeTab.classList.add('bg-slate-800','text-white',
                                'dark:bg-slate-200','dark:text-slate-800');
    }

    // Filter rows
    document.querySelectorAll('.al-row').forEach(row => {
        row.style.display = (role === 'all' || row.dataset.role === role) ? '' : 'none';
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/dashboard/activity_log.blade.php ENDPATH**/ ?>
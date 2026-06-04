<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>


<?php
    $widgetPengumuman ??= collect();
    $stats            ??= ['total_guru' => 0, 'total_siswa' => 0, 'total_kelas' => 0, 'guru_hadir' => 0];
    $jadwalHariIni    ??= collect();
    $activityLogs     ??= collect();
    $absensiMinggu    ??= ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'telat' => 0];
    $guruUltah        ??= collect();
    $kelasTanpaWali   ??= 0;
?>

<div class="space-y-2.5 max-w-7xl mx-auto container-fluid px-2">

    
    <div class="flex items-center justify-between flex-wrap gap-2 bg-white dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div>
            <h2 class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-1">
                <span>👋</span> Selamat datang, <?php echo e(auth()->user()->name ?? 'Admin'); ?>!
            </h2>
            <p class="text-[9px] text-slate-400 dark:text-slate-500 font-medium tracking-wide">
                <?php echo e(now()->isoFormat('dddd, D MMMM Y · HH:mm')); ?> WIB
            </p>
        </div>
        
        
        <div class="flex items-center gap-1.5 flex-wrap">
            <?php if(Route::has('admin.users.index')): ?>
            <a href="<?php echo e(route('admin.users.index')); ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                      bg-indigo-600 text-white text-[9px] font-bold
                      hover:bg-indigo-700 transition shadow-sm active:scale-95">
                ➕ User Baru
            </a>
            <?php endif; ?>
            <?php if(Route::has('admin.pengumuman.create')): ?>
            <a href="<?php echo e(route('admin.pengumuman.create')); ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                      bg-slate-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300
                      border border-slate-200 dark:border-slate-600
                      text-[9px] font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition shadow-sm">
                📢 Pengumuman
            </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="w-full">
        <?php echo $__env->make('admin.dashboard.stats', [
            'stats'          => $stats,
            'kelasTanpaWali' => $kelasTanpaWali,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="w-full">
        <?php echo $__env->make('admin.dashboard.absensi_minggu', [
            'absensiMinggu' => $absensiMinggu,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 items-stretch">

        
        <div class="flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-full">
            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                <h3 class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    📅 Jadwal Hari Ini
                </h3>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-[220px] custom-scrollbar">
                <?php echo $__env->make('admin.dashboard.schedule', [
                    'jadwalHariIni' => $jadwalHariIni,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        
        <div class="flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-full">
            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                <h3 class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    📢 Pengumuman Internal
                </h3>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-[220px] custom-scrollbar">
                <?php echo $__env->make('admin.dashboard.announcement', [
                    'widgetPengumuman' => $widgetPengumuman,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 items-stretch">

        
        <div class="lg:col-span-2 flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden h-full">
            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                <h3 class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    ⏱️ Log Aktivitas Sistem
                </h3>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-[260px] custom-scrollbar">
                <?php echo $__env->make('admin.dashboard.activity_log', [
                    'activityLogs' => $activityLogs,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        
        <div class="lg:col-span-1 flex flex-col gap-3 h-full">
            
            
            <div class="flex flex-col bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex-1">
                <div class="px-3 py-1.5 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/40">
                    <h3 class="text-[10px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                        🎂 Guru Ulang Tahun
                    </h3>
                </div>
                <div class="p-2 overflow-y-auto max-h-[110px] custom-scrollbar flex-1">
                    <?php echo $__env->make('admin.dashboard.ultah_guru', [
                        'guruUltah' => $guruUltah,
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-2.5">
                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                    ⚡ Akses Cepat Menuju Fitur
                </p>
                <div class="grid grid-cols-2 gap-1.5">
                    <?php
                        $quickLinks = [
                            ['icon'=>'📋','label'=>'Absensi Guru', 'route'=>'admin.absensi-guru.index',  'color'=>'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'],
                            ['icon'=>'👥','label'=>'Data Guru',    'route'=>'admin.users.index',          'color'=>'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300'],
                            ['icon'=>'🏫','label'=>'Kelola Kelas', 'route'=>'admin.kelas.index',          'color'=>'bg-sky-50 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300'],
                            ['icon'=>'📢','label'=>'Pengumuman',   'route'=>'admin.pengumuman',           'color'=>'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300'],
                            ['icon'=>'🎓','label'=>'Data Siswa',   'route'=>'admin.users.index',          'color'=>'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300'],
                        ];
                    ?>

                    <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ql): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(Route::has($ql['route'])): ?>
                        <a href="<?php echo e(route($ql['route'])); ?>"
                           class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg
                                  <?php echo e($ql['color']); ?> hover:opacity-85 active:scale-[0.97]
                                  transition-all text-[10px] font-semibold leading-tight overflow-hidden">
                            <span class="text-xs shrink-0"><?php echo e($ql['icon']); ?></span>
                            <span class="truncate"><?php echo e($ql['label']); ?></span>
                        </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>

    </div>

</div>


<style>
    /* Mengurangi margin bawaan section layouts default jika terlalu longgar */
    .container-fluid {
        padding-top: 0.25rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    /* Scrollbar mikro agar tidak memakan ruang konten internal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
        height: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.25);
        border-radius: 999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.45);
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>
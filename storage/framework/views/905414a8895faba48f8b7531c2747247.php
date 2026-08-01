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

<div class="space-y-4 max-w-full mx-auto container-fluid px-2 pb-6">

    
    <div class="relative overflow-hidden flex items-center justify-between flex-wrap gap-3 bg-white dark:bg-slate-800 px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-xl shadow-inner">
                👋
            </div>
            <div>
                <h2 class="text-sm font-black text-slate-800 dark:text-slate-100 leading-tight">
                    Halo, <?php echo e(auth()->user()->name ?? 'Admin'); ?>!
                </h2>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold tracking-wide uppercase">
                    <?php echo e(now()->isoFormat('dddd, D MMMM Y')); ?> <span class="mx-1 opacity-30">|</span> <span id="realtimeClock"><?php echo e(now()->format('H:i:s')); ?></span> WIB
                </p>
            </div>
        </div>
        
        
        <div class="flex items-center gap-2 relative z-10">
            <?php if(Route::has('admin.users.index')): ?>
            <a href="<?php echo e(route('admin.users.index')); ?>"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-indigo-600 text-white text-[10px] font-black
                      hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200 dark:shadow-none active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
                USER BARU
            </a>
            <?php endif; ?>
            <?php if(Route::has('admin.pengumuman.create')): ?>
            <a href="<?php echo e(route('admin.pengumuman.create')); ?>"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl
                      bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200
                      border border-slate-200 dark:border-slate-600
                      text-[10px] font-black hover:bg-slate-50 dark:hover:bg-slate-600 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                PENGUMUMAN
            </a>
            <?php endif; ?>
        </div>
        
        <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16"></div>
    </div>

    
    <div class="w-full">
        <?php echo $__env->make('admin.dashboard.stats', ['stats' => $stats, 'kelasTanpaWali' => $kelasTanpaWali], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="w-full">
        <?php echo $__env->make('admin.dashboard.absensi_minggu', ['absensiMinggu' => $absensiMinggu], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <?php echo $__env->make('admin.dashboard.schedule', ['jadwalHariIni' => $jadwalHariIni], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.dashboard.announcement', ['widgetPengumuman' => $widgetPengumuman], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <div class="lg:col-span-8">
            <?php echo $__env->make('admin.dashboard.activity_log', ['activityLogs' => $activityLogs], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="lg:col-span-4 space-y-4">
            <?php echo $__env->make('admin.dashboard.ultah_guru', ['guruUltah' => $guruUltah], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <span class="w-1 h-3 bg-indigo-500 rounded-full"></span> Navigasi Cepat
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <?php
                        $quickLinks = [
                            ['icon'=>'📋','label'=>'Absensi Guru', 'route'=>'admin.absensi-guru.index',  'color'=>'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'],
                            ['icon'=>'👥','label'=>'Data Guru',    'route'=>'admin.users.index',          'color'=>'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300'],
                            ['icon'=>'🏫','label'=>'Kelola Kelas', 'route'=>'admin.kelas.index',          'color'=>'bg-sky-50 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300'],
                            ['icon'=>'📢','label'=>'Pengumuman',   'route'=>'admin.pengumuman',           'color'=>'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300'],
                            ['icon'=>'🎓','label'=>'Data Siswa',   'route'=>'admin.users.index',          'color'=>'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-300'],
                            ['icon'=>'⚙️','label'=>'User Role',    'route'=>'admin.users.index',          'color'=>'bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300'],
                        ];
                    ?>
                    <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ql): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(Route::has($ql['route'])): ?>
                        <a href="<?php echo e(route($ql['route'])); ?>"
                           class="flex items-center gap-2 px-2.5 py-2 rounded-xl <?php echo e($ql['color']); ?> 
                                  hover:opacity-80 transition-all text-[10px] font-bold group">
                            <span class="text-sm group-hover:scale-110 transition-transform"><?php echo e($ql['icon']); ?></span>
                            <span class="truncate"><?php echo e($ql['label']); ?></span>
                        </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('realtimeClock').textContent = timeStr;
    }
    setInterval(updateClock, 1000);
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(156, 163, 175, 0.5); }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>

<?php
    $total = array_sum($absensiMinggu);
    $pct = fn($val) => $total > 0 ? (int) round($val / $total * 100) : 0;
    $items = [
        ['label' => 'Hadir', 'key' => 'hadir', 'color' => 'bg-emerald-500', 'text' => 'text-emerald-600'],
        ['label' => 'Sakit', 'key' => 'sakit', 'color' => 'bg-blue-400', 'text' => 'text-blue-500'],
        ['label' => 'Izin', 'key' => 'izin', 'color' => 'bg-amber-400', 'text' => 'text-amber-500'],
        ['label' => 'Alpha', 'key' => 'alpha', 'color' => 'bg-red-500', 'text' => 'text-red-600'],
        ['label' => 'Telat', 'key' => 'telat', 'color' => 'bg-pink-400', 'text' => 'text-pink-500'],
    ];
?>

<div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/30">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-xl">📊</div>
            <div>
                <p class="text-sm font-black text-slate-800 dark:text-slate-100">Absensi Guru & Staff</p>
                <p class="text-xs text-slate-400"><?php echo e(\Carbon\Carbon::now()->startOfWeek()->isoFormat('D MMM')); ?> – <?php echo e(\Carbon\Carbon::now()->endOfWeek()->isoFormat('D MMM Y')); ?></p>
            </div>
        </div>
        <a href="<?php echo e(route('admin.absensi-guru.rekap')); ?>" class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition">Rekap Lengkap →</a>
    </div>

    <div class="p-6">
        <?php if($total > 0): ?>
        <div class="h-6 bg-slate-100 dark:bg-slate-700 rounded-2xl overflow-hidden mb-6 shadow-inner flex">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $p = $pct($absensiMinggu[$item['key']] ?? 0); ?>
                <?php if($p > 0): ?>
                <div class="<?php echo e($item['color']); ?>" style="width: <?php echo e($p); ?>%" title="<?php echo e($item['label']); ?>: <?php echo e($absensiMinggu[$item['key']]); ?>"></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $val = $absensiMinggu[$item['key']] ?? 0; ?>
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-3 h-3 rounded-full <?php echo e($item['color']); ?>"></div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500"><?php echo e($item['label']); ?></span>
                </div>
                <div class="text-3xl font-black <?php echo e($item['text']); ?>"><?php echo e($val); ?></div>
                <?php if($total > 0): ?><span class="text-sm text-slate-400"><?php echo e($pct($val)); ?>%</span><?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/dashboard/absensi_minggu.blade.php ENDPATH**/ ?>
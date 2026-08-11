<?php $__env->startSection('title', 'Dashboard Guru'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #mainContent { font-family: 'Plus Jakarta Sans', sans-serif !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    /*
     * SAFETY CAST — jamin $isWaliKelas selalu boolean di view ini.
     * Meski controller gagal inject, ?? false mencegah undefined error.
     */
    $isWaliKelas = isset($isWaliKelas) ? (bool) $isWaliKelas : false;

    /*
     * SAFETY CAST — jamin $guruUltah selalu Collection.
     * Jika controller gagal inject (misal guru belum terdaftar),
     * widget tidak akan crash karena ada guard @if di dalam partial.
     */
    $guruUltah = isset($guruUltah) ? $guruUltah : collect();
?>

<div class="space-y-4">

    <?php echo $__env->make('guru.dashboard.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        
        <div class="lg:col-span-2 space-y-4">

            <?php echo $__env->make('guru.dashboard.performance-summary', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('guru.dashboard.jadwal-mengajar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('guru.dashboard.attendance-trend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php if($isWaliKelas): ?>
                <?php echo $__env->make('guru.dashboard.rekap-absensi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <?php echo $__env->make('guru.dashboard.at-risk-students', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

        
        <div class="lg:col-span-1 space-y-4">

            <?php echo $__env->make('guru.dashboard.announcements', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php echo $__env->make('guru.dashboard.ultah_guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php if($isWaliKelas): ?>
                <?php echo $__env->make('guru.dashboard.wali-kelas-summary', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('guru.dashboard.scripts-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('guru.dashboard.scripts-chart', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/guru/dashboard.blade.php ENDPATH**/ ?>
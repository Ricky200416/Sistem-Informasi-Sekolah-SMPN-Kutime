<?php $__env->startSection('title', 'Absensi Foto'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div>
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Absensi Kehadiran (Foto)</h2>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
            Unggah foto sebagai bukti kehadiran Anda. Data akan otomatis tersinkron ke Dashboard Admin.
        </p>
    </div>

    <?php if(session('success')): ?>
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
        <i class="bi bi-check-circle-fill text-emerald-600"></i>
        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800">
        <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
        <p class="text-[11px] text-rose-700 dark:text-rose-300 font-medium"><?php echo e(session('error')); ?></p>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="flex items-start gap-2 px-3.5 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800">
        <i class="bi bi-exclamation-triangle-fill text-rose-600 mt-0.5"></i>
        <ul class="text-[11px] text-rose-700 dark:text-rose-300 font-medium list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">
                Status Hari Ini — <?php echo e(now()->translatedFormat('l, d F Y')); ?>

            </h3>
            <?php if($absensiHariIni): ?>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Hadir
                </span>
            <?php else: ?>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-300">
                    Belum Absen
                </span>
            <?php endif; ?>
        </div>

        <?php if(!$absensiHariIni): ?>
            
            <div class="mb-2.5 flex items-start gap-1.5 px-3 py-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800">
                <i class="bi bi-info-circle-fill text-indigo-500 text-xs mt-0.5"></i>
                <p class="text-[10px] text-indigo-700 dark:text-indigo-300">
                    Pilih salah satu opsi di bawah. Absensi hanya dapat dilakukan <strong>satu kali</strong> per hari — pastikan Anda memilih yang sesuai sebelum mengunggah.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                
                <form action="<?php echo e(route('guru.absensi-foto.masuk')); ?>" method="POST" enctype="multipart/form-data"
                      class="border border-slate-200 dark:border-slate-700 rounded-xl p-3.5"
                      onsubmit="return lockSubmit(this)">
                    <?php echo csrf_field(); ?>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                        <i class="bi bi-easel2-fill text-indigo-500 me-1"></i> Saya Akan Mengajar
                    </p>
                    <p class="text-[10px] text-slate-400 mb-2.5">
                        Ambil foto sebelum mulai mengajar di kelas.
                    </p>

                    <?php if($kelasList->count()): ?>
                        <select name="kelas_id" required
                                class="w-full text-[10px] rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100 mb-2.5">
                            <option value="">— Pilih Kelas —</option>
                            <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kelas->id); ?>"><?php echo e($kelas->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    <?php else: ?>
                        <p class="text-[10px] text-amber-600 mb-2.5">
                            <i class="bi bi-exclamation-triangle"></i>
                            Belum ada kelas yang ditugaskan ke Anda. Hubungi admin bila perlu.
                        </p>
                    <?php endif; ?>

                    <input type="file" name="foto" accept="image/*" capture="environment" required
                           class="w-full text-[10px] rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100 mb-2.5">

                    <button type="submit"
                            class="w-full py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition disabled:opacity-60">
                        <i class="bi bi-camera me-1"></i> Upload Foto Masuk
                    </button>
                </form>

                
                <form action="<?php echo e(route('guru.absensi-foto.kantor')); ?>" method="POST" enctype="multipart/form-data"
                      class="border border-slate-200 dark:border-slate-700 rounded-xl p-3.5"
                      onsubmit="return lockSubmit(this)">
                    <?php echo csrf_field(); ?>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                        <i class="bi bi-building-fill text-slate-500 me-1"></i> Saya di Kantor (Tidak Mengajar)
                    </p>
                    <p class="text-[10px] text-slate-400 mb-2.5">
                        Ambil foto sebagai bukti Anda hadir di kantor sekolah.
                    </p>
                    <input type="file" name="foto" accept="image/*" capture="environment" required
                           class="w-full text-[10px] rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100 mb-2.5">
                    <button type="submit"
                            class="w-full py-2 bg-slate-700 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 transition disabled:opacity-60">
                        <i class="bi bi-camera me-1"></i> Upload Foto Kantor
                    </button>
                </form>

            </div>

        <?php elseif($absensiHariIni->foto_masuk && !$absensiHariIni->foto_pulang && $absensiHariIni->tipe_absensi === 'mengajar'): ?>
            
            <div class="mb-2.5 flex items-center gap-1.5 px-3 py-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800">
                <i class="bi bi-hourglass-split text-amber-500 text-xs"></i>
                <p class="text-[10px] text-amber-700 dark:text-amber-300">
                    Anda sedang mengajar<?php echo e($absensiHariIni->kelas ? ' di kelas ' . $absensiHariIni->kelas->nama : ''); ?>.
                    Jangan lupa unggah foto pulang setelah selesai.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
                <div>
                    <p class="text-[10px] text-slate-400 mb-1">
                        Foto Masuk (<?php echo e(substr($absensiHariIni->jam_masuk, 0, 5)); ?>)
                        <?php if($absensiHariIni->kelas): ?>
                            <span class="ml-1 px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 font-semibold">
                                <?php echo e($absensiHariIni->kelas->nama); ?>

                            </span>
                        <?php endif; ?>
                    </p>
                    <img src="<?php echo e(Storage::url($absensiHariIni->foto_masuk)); ?>"
                         class="w-full h-40 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                </div>

                <form action="<?php echo e(route('guru.absensi-foto.pulang')); ?>" method="POST" enctype="multipart/form-data"
                      class="border border-slate-200 dark:border-slate-700 rounded-xl p-3.5"
                      onsubmit="return lockSubmit(this)">
                    <?php echo csrf_field(); ?>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">Selesai Mengajar</p>
                    <p class="text-[10px] text-slate-400 mb-2.5">Ambil foto setelah selesai mengajar di kelas.</p>
                    <input type="file" name="foto" accept="image/*" capture="environment" required
                           class="w-full text-[10px] rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100 mb-2.5">
                    <button type="submit"
                            class="w-full py-2 bg-amber-500 text-white text-xs font-semibold rounded-lg hover:bg-amber-600 transition disabled:opacity-60">
                        <i class="bi bi-camera me-1"></i> Upload Foto Pulang
                    </button>
                </form>
            </div>

        <?php else: ?>
            
            <div class="mb-2.5 flex items-center gap-1.5 px-3 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
                <i class="bi bi-check-circle-fill text-emerald-600 text-xs"></i>
                <p class="text-[10px] text-emerald-700 dark:text-emerald-300">
                    Absensi hari ini sudah lengkap. Terima kasih, sampai jumpa besok!
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[10px] text-slate-400 mb-1">
                        Foto <?php echo e($absensiHariIni->tipe_absensi === 'kantor' ? 'Kehadiran' : 'Masuk'); ?>

                        <?php if($absensiHariIni->jam_masuk): ?> (<?php echo e(substr($absensiHariIni->jam_masuk, 0, 5)); ?>) <?php endif; ?>
                        <?php if($absensiHariIni->kelas): ?>
                            <span class="ml-1 px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 font-semibold">
                                <?php echo e($absensiHariIni->kelas->nama); ?>

                            </span>
                        <?php endif; ?>
                    </p>
                    <img src="<?php echo e(Storage::url($absensiHariIni->foto_masuk)); ?>"
                         class="w-full h-40 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                </div>

                <?php if($absensiHariIni->foto_pulang): ?>
                    <div>
                        <p class="text-[10px] text-slate-400 mb-1">
                            Foto Pulang (<?php echo e(substr($absensiHariIni->jam_pulang, 0, 5)); ?>)
                        </p>
                        <img src="<?php echo e(Storage::url($absensiHariIni->foto_pulang)); ?>"
                             class="w-full h-40 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center h-40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-[10px] text-slate-400">
                        Absensi kantor — hanya 1 foto diperlukan
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-3.5 py-3 border-b border-slate-100 dark:border-slate-700">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">Riwayat Absensi Foto</h3>
        </div>
        <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
            <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center gap-3 px-3.5 py-2.5">
                    <img src="<?php echo e(Storage::url($r->foto_masuk)); ?>"
                         class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                            <?php echo e(\Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y')); ?>

                        </p>
                        <p class="text-[10px] text-slate-400">
                            <?php echo e($r->tipe_absensi === 'kantor' ? 'Absensi Kantor' : 'Mengajar'); ?>

                            <?php if($r->kelas): ?> · <?php echo e($r->kelas->nama); ?> <?php endif; ?>
                            · Masuk <?php echo e(substr($r->jam_masuk,0,5)); ?>

                            <?php if($r->jam_pulang): ?> · Pulang <?php echo e(substr($r->jam_pulang,0,5)); ?> <?php endif; ?>
                        </p>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                        Hadir
                    </span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8">
                    <p class="text-slate-400 text-xs">Belum ada riwayat absensi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Cegah klik ganda / submit dobel yang bisa memicu percobaan absen dua kali
// dari sisi UI (guard utama tetap di server / database).
function lockSubmit(form) {
    const btn = form.querySelector('button[type="submit"]');
    if (btn.dataset.locked === '1') {
        return false;
    }
    btn.dataset.locked = '1';
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Mengunggah…';
    return true;
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/guru/absensi-foto/index.blade.php ENDPATH**/ ?>
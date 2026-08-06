<?php $__env->startSection('title', 'Perizinan'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div>
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengajuan Izin</h2>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
            Ajukan izin tidak masuk kepada Kepala Sekolah melalui formulir di bawah ini.
        </p>
    </div>

    <?php if(session('success')): ?>
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 space-y-1">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p class="text-[11px] text-rose-700 dark:text-rose-300 font-medium">• <?php echo e($error); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
        <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-3">Formulir Perizinan</h3>
        <form action="<?php echo e(route('guru.perizinan.store')); ?>" method="POST" class="space-y-3">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?php echo e(old('nama', auth()->user()->name)); ?>"
                           class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jabatan</label>
                    <select name="jabatan" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <option value="Guru" <?php echo e(old('jabatan') == 'Guru' ? 'selected' : ''); ?>>Guru</option>
                        <option value="Wali Kelas" <?php echo e(old('jabatan') == 'Wali Kelas' ? 'selected' : ''); ?>>Wali Kelas</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="<?php echo e(old('no_hp')); ?>" placeholder="08xxxxxxxxxx"
                           class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tanggal Izin</label>
                    <input type="date" name="tanggal_izin" value="<?php echo e(old('tanggal_izin')); ?>"
                           class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tujuan</label>
                <input type="text" name="tujuan" value="<?php echo e(old('tujuan')); ?>" placeholder="Ex: Menghadiri acara keluarga"
                       class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Lama Izin</label>
                <select name="lama_izin" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                    <option value="" disabled <?php echo e(old('lama_izin') ? '' : 'selected'); ?>>-- Pilih Lama Izin --</option>
                    <option value="Setengah Hari" <?php echo e(old('lama_izin') == 'Setengah Hari' ? 'selected' : ''); ?>>Setengah Hari</option>
                    <option value="1 Hari" <?php echo e(old('lama_izin') == '1 Hari' ? 'selected' : ''); ?>>1 Hari</option>
                    <option value="2 Hari" <?php echo e(old('lama_izin') == '2 Hari' ? 'selected' : ''); ?>>2 Hari</option>
                    <option value="3 Hari" <?php echo e(old('lama_izin') == '3 Hari' ? 'selected' : ''); ?>>3 Hari</option>
                    <option value="Lebih dari 3 Hari" <?php echo e(old('lama_izin') == 'Lebih dari 3 Hari' ? 'selected' : ''); ?>>Lebih dari 3 Hari</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Alasan</label>
                <textarea name="alasan" rows="3" placeholder="Jelaskan alasan izin Anda..."
                          class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required><?php echo e(old('alasan')); ?></textarea>
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 shadow transition">
                    <i class="bi bi-send me-1"></i> Kirim Pengajuan Izin
                </button>
            </div>
        </form>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-3.5 py-3 border-b border-slate-100 dark:border-slate-700">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">Riwayat Pengajuan Izin Saya</h3>
        </div>
        <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
            <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="px-3.5 py-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200"><?php echo e($r->tujuan); ?></p>
                        <p class="text-[10px] text-slate-400 mt-0.5">
                            Tanggal Izin: <?php echo e($r->tanggal_izin->translatedFormat('d M Y')); ?> · Lama: <?php echo e($r->lama_izin); ?>

                        </p>
                        <p class="text-[10px] text-slate-400">Diajukan: <?php echo e($r->created_at->translatedFormat('d M Y, H:i')); ?></p>
                    </div>
                    <?php if($r->status === 'pending'): ?>
                        <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Menunggu</span>
                    <?php elseif($r->status === 'disetujui'): ?>
                        <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Disetujui</span>
                    <?php else: ?>
                        <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">Ditolak</span>
                    <?php endif; ?>
                </div>
                <?php if($r->catatan_admin): ?>
                <p class="text-[10px] text-slate-500 bg-slate-50 dark:bg-slate-900 mt-2 px-2 py-1.5 rounded-lg italic">
                    Catatan Kepala Sekolah: <?php echo e($r->catatan_admin); ?>

                </p>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-8">
                <p class="text-slate-400 text-xs">Belum ada pengajuan izin.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/guru/perizinan/index.blade.php ENDPATH**/ ?>
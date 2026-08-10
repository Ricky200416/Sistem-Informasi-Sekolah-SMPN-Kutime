<?php $__env->startSection('title', 'Absensi Foto'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div>
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Absensi Kehadiran (Foto)</h2>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
            Unggah foto sebagai bukti kehadiran Anda. Absensi mengajar otomatis terhubung dengan Jadwal Mengajar Anda hari ini.
            Data akan otomatis tersinkron ke Dashboard Admin.
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
                Status Hari Ini — <?php echo e($hariIniNama); ?>, <?php echo e(now()->translatedFormat('d F Y')); ?>

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

                    <?php if($jadwalHariIni->count()): ?>
                        <p class="text-[10px] text-slate-400 mb-2.5">
                            Pilih jadwal kelas Anda hari ini, lalu ambil foto sebelum mulai mengajar.
                        </p>

                        <div class="space-y-1.5 mb-2.5 max-h-32 overflow-y-auto pr-1">
                            <?php $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2 px-2.5 py-2 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-400 dark:has-[:checked]:bg-indigo-900/30 transition">
                                <input type="radio" name="timetable_id" value="<?php echo e($jadwal->id); ?>" required
                                       class="w-3.5 h-3.5 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200 truncate">
                                        <?php echo e($jadwal->studySubject->name ?? '—'); ?>

                                        <span class="text-slate-400 font-normal">· <?php echo e($jadwal->studyGroup->name ?? '—'); ?></span>
                                    </p>
                                    <p class="text-[9.5px] text-slate-400">
                                        <i class="bi bi-clock"></i>
                                        <?php echo e(substr($jadwal->start_time, 0, 5)); ?>–<?php echo e(substr($jadwal->end_time, 0, 5)); ?>

                                        <?php if($jadwal->room): ?> · <?php echo e($jadwal->room); ?> <?php endif; ?>
                                    </p>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <input type="file" name="foto" accept="image/*" capture="environment" required
                               class="w-full text-[10px] rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100 mb-2.5">
                        <button type="submit"
                                class="w-full py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition disabled:opacity-60">
                            <i class="bi bi-camera me-1"></i> Upload Foto Masuk
                        </button>
                    <?php else: ?>
                        <p class="text-[10px] text-slate-400 mb-2.5">
                            Tidak ada jadwal mengajar untuk Anda hari ini (<?php echo e($hariIniNama); ?>).
                        </p>
                        <div class="flex items-start gap-1.5 px-2.5 py-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800">
                            <i class="bi bi-exclamation-triangle-fill text-amber-500 text-[10px] mt-0.5"></i>
                            <p class="text-[10px] text-amber-700 dark:text-amber-300">
                                Belum ada jadwal untuk hari ini di menu
                                <a href="<?php echo e(route('guru.jadwal-mengajar.index')); ?>" class="font-semibold underline">Jadwal Mengajar</a>.
                                Jika Anda tetap hadir di sekolah, gunakan opsi <strong>Saya di Kantor</strong> di sebelah.
                            </p>
                        </div>
                    <?php endif; ?>
                </form>

                
                <form action="<?php echo e(route('guru.absensi-foto.kantor')); ?>" method="POST" enctype="multipart/form-data"
                      class="border border-slate-200 dark:border-slate-700 rounded-xl p-3.5"
                      onsubmit="return lockSubmit(this)">
                    <?php echo csrf_field(); ?>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                        <i class="bi bi-building-fill text-slate-500 me-1"></i> Saya di Kantor (Tidak Mengajar)
                    </p>
                    <p class="text-[10px] text-slate-400 mb-2.5">
                        Gunakan opsi ini jika Anda hadir di sekolah tetapi tidak sedang mengajar
                        (piket, rapat, atau tidak ada jadwal hari ini). Cukup 1 foto sebagai bukti kehadiran di kantor.
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
                    Anda sedang mengajar
                    <?php if($absensiHariIni->timetable): ?>
                        <strong><?php echo e($absensiHariIni->timetable->studySubject->name ?? ''); ?></strong>
                        di kelas <strong><?php echo e($absensiHariIni->timetable->studyGroup->name ?? ''); ?></strong>.
                    <?php endif; ?>
                    Jangan lupa unggah foto pulang setelah selesai.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
                <div>
                    <p class="text-[10px] text-slate-400 mb-1">
                        Foto Masuk (<?php echo e(substr($absensiHariIni->jam_masuk, 0, 5)); ?>)
                        <?php if($absensiHariIni->timetable): ?>
                            <span class="ml-1 px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 font-semibold">
                                <?php echo e($absensiHariIni->timetable->studyGroup->name ?? '—'); ?>

                            </span>
                            <span class="ml-1 px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold">
                                <?php echo e($absensiHariIni->timetable->studySubject->name ?? '—'); ?>

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
                        <?php if($absensiHariIni->timetable): ?>
                            <span class="ml-1 px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 font-semibold">
                                <?php echo e($absensiHariIni->timetable->studyGroup->name ?? '—'); ?>

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

    
    <?php if($jadwalHariIni->count()): ?>
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-3.5 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200">Jadwal Mengajar Hari Ini</h3>
            <a href="<?php echo e(route('guru.jadwal-mengajar.index')); ?>" class="text-[10px] text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">
                Kelola Jadwal <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="divide-y divide-slate-50 dark:divide-slate-700/30">
            <?php $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-2.5 px-3.5 py-2.5">
                <div class="w-1 h-9 rounded-full shrink-0" style="background: <?php echo e($jadwal->studySubject->color ?? '#6366f1'); ?>"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">
                        <?php echo e($jadwal->studySubject->name ?? '—'); ?>

                    </p>
                    <p class="text-[10px] text-slate-400">
                        <i class="bi bi-clock"></i> <?php echo e(substr($jadwal->start_time, 0, 5)); ?>–<?php echo e(substr($jadwal->end_time, 0, 5)); ?>

                        · Kelas <?php echo e($jadwal->studyGroup->name ?? '—'); ?>

                        <?php if($jadwal->room): ?> · <?php echo e($jadwal->room); ?> <?php endif; ?>
                    </p>
                </div>
                <?php if($absensiHariIni && $absensiHariIni->timetable_id === $jadwal->id): ?>
                    <span class="text-[9.5px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                        <i class="bi bi-check2"></i> Sudah Absen
                    </span>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
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
                            <?php if($r->tipe_absensi === 'kantor'): ?>
                                Absensi Kantor
                            <?php else: ?>
                                Mengajar
                                <?php if($r->timetable): ?>
                                    · <?php echo e($r->timetable->studySubject->name ?? '—'); ?>

                                    · <?php echo e($r->timetable->studyGroup->name ?? '—'); ?>

                                <?php endif; ?>
                            <?php endif; ?>
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
    if (!btn) return true;
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
<?php $__env->startSection('title', 'Perizinan Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div>
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Perizinan Guru</h2>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
            Kelola pengajuan izin dari guru — setujui atau tolak permohonan.
        </p>
    </div>

    <?php if(session('success')): ?>
    <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
        <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="<?php echo e(route('admin.perizinan.index')); ?>" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-slate-700 dark:text-slate-200 leading-none"><?php echo e($ringkasan['total']); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Total Pengajuan</p>
        </a>
        <a href="<?php echo e(route('admin.perizinan.index', ['status' => 'pending'])); ?>" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none"><?php echo e($ringkasan['pending']); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Menunggu</p>
        </a>
        <a href="<?php echo e(route('admin.perizinan.index', ['status' => 'disetujui'])); ?>" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 leading-none"><?php echo e($ringkasan['disetujui']); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Disetujui</p>
        </a>
        <a href="<?php echo e(route('admin.perizinan.index', ['status' => 'ditolak'])); ?>" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-rose-600 dark:text-rose-400 leading-none"><?php echo e($ringkasan['ditolak']); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Ditolak</p>
        </a>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Tujuan</th>
                        <th>Tanggal Izin</th>
                        <th>Lama</th>
                        <th>Diajukan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $perizinans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <p class="font-semibold text-slate-700 dark:text-slate-200"><?php echo e($p->nama); ?></p>
                            <p class="text-[10px] text-slate-400"><?php echo e($p->jabatan); ?> · <?php echo e($p->no_hp); ?></p>
                        </td>
                        <td><?php echo e($p->tujuan); ?></td>
                        <td><?php echo e($p->tanggal_izin->translatedFormat('d M Y')); ?></td>
                        <td><?php echo e($p->lama_izin); ?></td>
                        <td><?php echo e($p->created_at->translatedFormat('d M Y, H:i')); ?></td>
                        <td>
                            <?php if($p->status === 'pending'): ?>
                                <span class="badge bg-warning">Menunggu</span>
                            <?php elseif($p->status === 'disetujui'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo e($p->id); ?>">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400">Belum ada pengajuan izin.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php $__currentLoopData = $perizinans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalDetail<?php echo e($p->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengajuan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body space-y-2">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <p class="text-[10px] text-slate-400">Nama</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200"><?php echo e($p->nama); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Jabatan</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200"><?php echo e($p->jabatan); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">No. HP</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200"><?php echo e($p->no_hp); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Tanggal Izin</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200"><?php echo e($p->tanggal_izin->translatedFormat('d M Y')); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Lama Izin</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200"><?php echo e($p->lama_izin); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Status</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200"><?php echo e(ucfirst($p->status)); ?></p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400">Tujuan</p>
                    <p class="text-xs text-slate-700 dark:text-slate-200"><?php echo e($p->tujuan); ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400">Alasan</p>
                    <p class="text-xs text-slate-700 dark:text-slate-200"><?php echo e($p->alasan); ?></p>
                </div>

                <?php if($p->status === 'pending'): ?>
                <form action="<?php echo e(route('admin.perizinan.tolak', $p->id)); ?>" method="POST" class="pt-2 border-t border-slate-100 dark:border-slate-700 mt-2">
                    <?php echo csrf_field(); ?>
                    <label class="text-[10px] text-slate-400">Catatan (wajib diisi jika ditolak)</label>
                    <textarea name="catatan_admin" rows="2" class="form-control mt-1" placeholder="Alasan penolakan / catatan persetujuan..."></textarea>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="submit" formaction="<?php echo e(route('admin.perizinan.tolak', $p->id)); ?>" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-lg"></i> Tidak Setujui
                        </button>
                        <button type="submit" formaction="<?php echo e(route('admin.perizinan.setujui', $p->id)); ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg"></i> Setujui
                        </button>
                    </div>
                </form>
                <?php elseif($p->catatan_admin): ?>
                <div class="pt-2 border-t border-slate-100 dark:border-slate-700 mt-2">
                    <p class="text-[10px] text-slate-400">Catatan Kepala Sekolah</p>
                    <p class="text-xs text-slate-700 dark:text-slate-200 italic"><?php echo e($p->catatan_admin); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/perizinan/index.blade.php ENDPATH**/ ?>
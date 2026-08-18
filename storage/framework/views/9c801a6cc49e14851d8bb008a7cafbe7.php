 

<?php $__env->startSection('title', 'Kelola Komentar'); ?>

<?php $__env->startSection('content'); ?>
<div id="mainContent" class="space-y-4">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="heading-xl">Daftar Komentar User</h1>
            <p class="text-2xs text-slate-500">Kelola semua masukan dan komentar dari pengunjung website resmi.</p>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span><?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>

    <div class="card overflow-hidden">
        <div class="table-responsive overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 70px;">Foto</th>
                        <th>Nama Pengirim</th>
                        <th>Komentar / Masukan</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th style="width: 90px;">Status</th>
                        <th style="width: 130px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($comments->firstItem() + $index); ?></td>
                        <td>
                            <img src="<?php echo e($c->avatar_url); ?>" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                        </td>
                        <td>
                            <div class="font-semibold text-slate-800">
                                <?php echo e($c->nama ?? 'Unknown'); ?>

                            </div>
                            <?php if(!$c->nama): ?>
                            <span class="badge badge-neutral text-[9px]">Tanpa Nama</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <p class="text-xs text-slate-700 leading-relaxed max-w-md">
                                <?php echo e($c->komentar); ?>

                            </p>
                        </td>
                        <td class="text-2xs text-slate-500">
                            <?php echo e($c->created_at->format('d M Y, H:i')); ?>

                        </td>
                        <td>
                            <?php if($c->is_active): ?>
                            <span class="badge badge-success">Tampil</span>
                            <?php else: ?>
                            <span class="badge badge-danger">Disembunyikan</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <div class="inline-flex items-center gap-1">
                                
                                <form action="<?php echo e(route('admin.comments.toggle', $c->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Ubah Status">
                                        <?php if($c->is_active): ?>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.962 8.962 0 012.122-.163c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-3.111-3.111a3 3 0 00-4.243-4.243"/></svg>
                                        <?php else: ?>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <?php endif; ?>
                                    </button>
                                </form>

                                
                                <form action="<?php echo e(route('admin.comments.destroy', $c->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-slate-400">
                            Belum ada komentar dari pengunjung.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <?php echo e($comments->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/comments/index.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Manajemen Mata Pelajaran'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Custom table styling */
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    /* Button styling */
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        border: none;
        margin: 0 2px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .pagination .page-link:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: scale(1.1);
    }
    
    /* Bootstrap-style Pagination */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        border-radius: 4px;
    }
    
    .pagination .page-item {
        margin: 0 1px;
    }
    
    .pagination .page-link {
        position: relative;
        display: block;
        padding: 8px 16px;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        min-width: 44px;
        text-align: center;
    }
    
    .pagination .page-link:hover {
        z-index: 2;
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #adb5bd;
    }
    
    .pagination .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    .pagination .page-item:first-child .page-link {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    
    .pagination .page-item:last-child .page-link {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    
    .pagination-info {
        color: #6c757d;
        font-size: 0.875rem;
        margin: 0;
        padding: 8px 16px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        font-weight: 500;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-book me-2 text-primary"></i>Manajemen Mata Pelajaran
            </h1>
            <p class="text-muted mb-0">Kelola semua mata pelajaran sekolah</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMapelModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah Mapel
        </button>
    </div>

    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('admin.academic-planner.index')); ?>">Academic Planner</a>
            </li>
            <li class="breadcrumb-item active">Manajemen Mapel</li>
        </ol>
    </nav>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if($subjects->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>
                                <th>Nama Mata Pelajaran</th>
                                <th width="8%">SKS</th>
                                <th width="12%">Tipe</th>
                                <th width="10%">Status</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($subjects->firstItem() + $index); ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-semibold"><?php echo e($subject->code); ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($subject->name); ?></div>
                                        <?php if($subject->description): ?>
                                            <small class="text-muted"><?php echo e(Str::limit($subject->description, 50)); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($subject->credit_hours); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($subject->type == 'core' ? 'primary' : 'secondary'); ?> text-white">
                                            <?php echo e($subject->type_label ?? $subject->type); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($subject->is_active): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#showMapelModal<?php echo e($subject->id); ?>"
                                                    title="Lihat Detail">
                                                <i class="bi bi-eye"></i> Show
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#editMapelModal<?php echo e($subject->id); ?>"
                                                    title="Edit Mata Pelajaran">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            
                                            
                                            <form method="POST" action="<?php echo e(route('admin.academic-planner.study-subjects.destroy', $subject->id)); ?>" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran &quot;<?php echo e($subject->name); ?>&quot;?')" 
                                                  style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        title="Hapus Mata Pelajaran">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                
                <nav aria-label="Page navigation" class="mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="pagination-info">
                            Menampilkan <?php echo e($subjects->firstItem() ?? 0); ?> - <?php echo e($subjects->lastItem() ?? 0); ?> dari <?php echo e($subjects->total()); ?> data
                        </div>
                        <?php echo e($subjects->links('pagination::bootstrap-4')); ?>

                    </div>
                </nav>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-book-x d-block mb-3 opacity-50" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">Belum ada mata pelajaran</h5>
                    <p class="text-muted">Silakan tambah mata pelajaran terlebih dahulu.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMapelModal">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Mata Pelajaran
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="modal fade" id="addMapelModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mata Pelajaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.academic-planner.study-subjects.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="Contoh: Matematika"
                                       class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="100" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="<?php echo e(old('code')); ?>" placeholder="Contoh: MTK"
                                       class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="10" required
                                       style="text-transform:uppercase">
                                <div class="form-text">Kode unik, maksimal 10 karakter.</div>
                                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jumlah Jam Per Minggu <span class="text-danger">*</span></label>
                                <input type="number" name="credit_hours" value="<?php echo e(old('credit_hours', 2)); ?>"
                                       min="1" max="6" class="form-control <?php $__errorArgs = ['credit_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <?php $__errorArgs = ['credit_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                <select name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="core"     <?php echo e(old('type','core') == 'core'     ? 'selected' : ''); ?>>Wajib (Core)</option>
                                    <option value="elective" <?php echo e(old('type') == 'elective'        ? 'selected' : ''); ?>>Pilihan (Elective)</option>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="description" rows="3" maxlength="500"
                                          class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Deskripsi singkat mata pelajaran (opsional)"><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="showMapelModal<?php echo e($subject->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mata Pelajaran - <?php echo e($subject->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Kode:</strong></td>
                                <td><span class="badge bg-light text-dark fw-semibold"><?php echo e($subject->code); ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Mata Pelajaran:</strong></td>
                                <td><?php echo e($subject->name); ?></td>
                            </tr>
                            <tr>
                                <td><strong>SKS:</strong></td>
                                <td><?php echo e($subject->credit_hours); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tipe:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($subject->type == 'core' ? 'primary' : 'secondary'); ?> text-white">
                                        <?php echo e($subject->type_label ?? $subject->type); ?>

                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Status:</strong></td>
                                <td>
                                    <?php if($subject->is_active): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Non-aktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td><?php echo e($subject->created_at ? $subject->created_at->format('d M Y, H:i') : '-'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Diupdate:</strong></td>
                                <td><?php echo e($subject->updated_at ? $subject->updated_at->format('d M Y, H:i') : '-'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php if($subject->description): ?>
                    <div class="mt-3">
                        <strong>Deskripsi:</strong>
                        <p class="text-muted"><?php echo e($subject->description); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editMapelModal<?php echo e($subject->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Mata Pelajaran - <?php echo e($subject->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.academic-planner.study-subjects.update', $subject->id)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name', $subject->name)); ?>" placeholder="Contoh: Matematika"
                                       class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="100" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode Mapel <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="<?php echo e(old('code', $subject->code)); ?>" placeholder="Contoh: MTK"
                                       class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="10" required
                                       style="text-transform:uppercase">
                                <div class="form-text">Kode unik, maksimal 10 karakter.</div>
                                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jumlah Jam Per Minggu <span class="text-danger">*</span></label>
                                <input type="number" name="credit_hours" value="<?php echo e(old('credit_hours', $subject->credit_hours)); ?>"
                                       min="1" max="6" class="form-control <?php $__errorArgs = ['credit_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <?php $__errorArgs = ['credit_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                <select name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="core"     <?php echo e(old('type', $subject->type) == 'core'     ? 'selected' : ''); ?>>Wajib (Core)</option>
                                    <option value="elective" <?php echo e(old('type', $subject->type) == 'elective' ? 'selected' : ''); ?>>Pilihan (Elective)</option>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="description" rows="3" maxlength="500"
                                          class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Deskripsi singkat mata pelajaran (opsional)"><?php echo e(old('description', $subject->description)); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" id="is_active_<?php echo e($subject->id); ?>" name="is_active" value="1"
                                           <?php echo e($subject->is_active ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active_<?php echo e($subject->id); ?>">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i>Update Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Mengunci letak modal overlay agar presisi di tengah layar */
    .modal.show {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 1050;
    }
    
    .modal-backdrop.show {
        background-color: rgba(0, 0, 0, 0.7) !important;
        z-index: 1040;
    }
    
    .modal-content {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: none;
        z-index: 1060;
    }
    
    .modal-dialog {
        margin: auto !important;
        animation: modalSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 1055;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.92);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal tambah mapel
        const addForm = document.querySelector('#addMapelModal form');
        if (addForm) {
            addForm.addEventListener('submit', function() {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
        }

        // Modal edit mapel (dinamis)
        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        const editForm<?php echo e($subject->id); ?> = document.querySelector('#editMapelModal<?php echo e($subject->id); ?> form');
        if (editForm<?php echo e($subject->id); ?>) {
            editForm<?php echo e($subject->id); ?>.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const tempForm = document.createElement('form');
                tempForm.method = 'POST';
                tempForm.action = this.action;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    tempForm.appendChild(csrfInput);
                }
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                tempForm.appendChild(methodInput);
                
                const formData = new FormData(this);
                for (let [key, value] of formData.entries()) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    tempForm.appendChild(input);
                }
                
                document.body.appendChild(tempForm);
                tempForm.submit();
            });
        }
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/academic-planner/study-subjects.blade.php ENDPATH**/ ?>
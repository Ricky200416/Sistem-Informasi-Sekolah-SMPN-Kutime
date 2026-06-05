

<?php $__env->startSection('title', 'Jadwal Kelas ' . $studyGroup->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">

    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.academic-planner.index')); ?>">Academic Planner</a></li>
            <li class="breadcrumb-item active">Kelas <?php echo e($studyGroup->name); ?></li>
        </ol>
    </nav>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width:60px;height:60px;font-size:1.8rem;font-weight:900;">
                    <?php echo e($studyGroup->name); ?>

                </div>
                <div>
                    <h5 class="card-title mb-1"><?php echo e($studyGroup->name); ?></h5>
                    <div class="text-muted small">
                        <?php echo e($studyGroup->academic_year); ?> &bull; Semester <?php echo e($studyGroup->semester); ?>

                        <?php if($studyGroup->room): ?> &bull; Ruang <?php echo e($studyGroup->room); ?> <?php endif; ?>
                    </div>
                    <div class="text-muted small">
                        Wali Kelas: <strong><?php echo e($studyGroup->homeroomTeacher?->name ?? 'Belum ditetapkan'); ?></strong>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Jadwal
                </button>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    
    <div class="row g-3">
        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-calendar-day me-2 text-primary"></i><?php echo e($day); ?></h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary"><?php echo e($timetableByDay[$day]->count()); ?> sesi</span>
                </div>
                <div class="card-body p-0">
                    <?php $__empty_1 = true; $__currentLoopData = $timetableByDay[$day]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border-bottom group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-start gap-3">
                                <div style="width: 4px; height: 50px; background:<?php echo e($tt->studySubject->color ?? '#3B82F6'); ?>; rounded: 2px;"></div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo e($tt->studySubject->name); ?></div>
                                    <div class="text-muted small"><i class="bi bi-clock me-1"></i><?php echo e(substr($tt->start_time,0,5)); ?> – <?php echo e(substr($tt->end_time,0,5)); ?></div>
                                    <div class="text-primary small fw-semibold"><i class="bi bi-person me-1"></i>Guru: <?php echo e($tt->teacher?->name ?? 'Tidak Ada'); ?></div>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="openEditAdminJadwal(<?php echo e(json_encode($tt)); ?>)"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTimetable(<?php echo e($tt->id); ?>)"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4"><small>Tidak ada jadwal</small></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="modal fade" id="addJadwalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal Kelas - <?php echo e($studyGroup->name); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/academic-planner/<?php echo e($studyGroup->id); ?>/store-jadwal" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body space-y-3">
                    <div class="mb-2">
                        <label class="form-label font-semibold">Mata Pelajaran</label>
                        <select name="study_subject_id" class="form-select" required>
                            <?php $__currentLoopData = $studySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($subj->id); ?>"><?php echo e($subj->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label font-semibold text-primary">Pilih Guru Pengampu (Otomatis Sync ke Dashboard Guru)</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">-- Tentukan Guru Pengampu --</option>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Hari</label>
                            <select name="day_of_week" class="form-select" required>
                                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($d); ?>"><?php echo e($d); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="academic_year" value="<?php echo e($studyGroup->academic_year); ?>" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="1" <?php echo e($studyGroup->semester == 1 ? 'selected' : ''); ?>>1 (Ganjil)</option>
                                <option value="2" <?php echo e($studyGroup->semester == 2 ? 'selected' : ''); ?>>2 (Genap)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Ruangan</label>
                            <input type="text" name="room" value="<?php echo e($studyGroup->room); ?>" class="form-control">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Tipe Sesi</label>
                            <select name="session_type" class="form-select" required>
                                <option value="teori">Teori</option>
                                <option value="praktikum">Praktikum</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editJadwalModalAdmin" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal Pelajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditJadwalAdmin" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="study_subject_id" id="ae_subject" class="form-select" required></select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-primary">Guru Pengampu</label>
                        <select name="teacher_id" id="ae_teacher" class="form-select" required></select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2"><label class="form-label">Hari</label><select name="day_of_week" id="ae_day" class="form-select"></select></div>
                        <div class="col-md-4 mb-2"><label class="form-label">Mulai</label><input type="time" name="start_time" id="ae_start" class="form-control"></div>
                        <div class="col-md-4 mb-2"><label class="form-label">Selesai</label><input type="time" name="end_time" id="ae_end" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2"><label class="form-label">Tahun Ajaran</label><input type="text" name="academic_year" id="ae_year" class="form-control"></div>
                        <div class="col-md-6 mb-2"><label class="form-label">Semester</label><select name="semester" id="ae_semester" class="form-select"></select></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2"><label class="form-label">Ruangan</label><input type="text" name="room" id="ae_room" class="form-control"></div>
                        <div class="col-md-6 mb-2"><label class="form-label">Sesi</label><select name="session_type" id="ae_session" class="form-select"></select></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditAdminJadwal(tt) {
        document.getElementById('formEditJadwalAdmin').action = `/admin/academic-planner/jadwal/${tt.id}/update`;
        
        // Populate Dropdowns
        const subjectSelect = document.getElementById('ae_subject');
        subjectSelect.innerHTML = `<?php $__currentLoopData = $studySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>`;
        subjectSelect.value = tt.study_subject_id;

        const teacherSelect = document.getElementById('ae_teacher');
        teacherSelect.innerHTML = `<?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>`;
        teacherSelect.value = tt.teacher_id;

        const daySelect = document.getElementById('ae_day');
        daySelect.innerHTML = `<?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($d); ?>"><?php echo e($d); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>`;
        daySelect.value = tt.day_of_week;

        document.getElementById('ae_start').value = tt.start_time.substring(0,5);
        document.getElementById('ae_end').value = tt.end_time.substring(0,5);
        document.getElementById('ae_year').value = tt.academic_year;
        
        const semSelect = document.getElementById('ae_semester');
        semSelect.innerHTML = `<option value="1">1</option><option value="2">2</option>`;
        semSelect.value = tt.semester;

        document.getElementById('ae_room').value = tt.room || '';
        
        const sesSelect = document.getElementById('ae_session');
        sesSelect.innerHTML = `<option value="teori">Teori</option><option value="praktikum">Praktikum</option>`;
        sesSelect.value = tt.session_type;

        var myModal = new bootstrap.Modal(document.getElementById('editJadwalModalAdmin'));
        myModal.show();
    }

    function deleteTimetable(id) {
        if (confirm('Hapus jadwal mengajar ini? Data di dashboard guru terkait akan ikut terhapus.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/academic-planner/jadwal/${id}/delete`;
            form.innerHTML = `<?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/academic-planner/show-study-group.blade.php ENDPATH**/ ?>
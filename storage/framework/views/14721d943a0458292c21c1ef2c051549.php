

<?php $__env->startSection('title', 'Jadwal Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Jadwal Mengajar</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Kelola jadwal kelas yang Anda ampu. Semua jadwal tersinkronisasi otomatis ke Dashboard Admin.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            
            <button onclick="openModal('modalKelolaMapel')"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800
                           border border-slate-200 dark:border-slate-700
                           text-slate-700 dark:text-slate-200 text-xs font-semibold
                           hover:bg-slate-50 dark:hover:bg-slate-700
                           active:scale-95 transition shadow-sm w-fit">
                <i class="bi bi-book text-slate-400"></i>
                Kelola Mapel
            </button>

            
            <button onclick="openModal('modalTambahJadwal')"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600
                           text-white text-xs font-semibold hover:bg-indigo-700
                           active:scale-95 transition shadow-sm w-fit">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jadwal
            </button>
        </div>
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

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-indigo-600 dark:text-indigo-400 leading-none"><?php echo e($totalJadwal); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Total Jadwal</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 leading-none"><?php echo e($totalKelas); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Kelas Diampu</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-amber-600 dark:text-amber-400 leading-none"><?php echo e($totalMapel); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Mata Pelajaran</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm px-4 py-3">
            <p class="text-lg font-black text-sky-600 dark:text-sky-400 leading-none"><?php echo e($totalJamPerMinggu); ?></p>
            <p class="text-[10px] text-slate-400 mt-0.5">Jam / Minggu</p>
        </div>
    </div>

    
    <?php $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $jadwalHari = $jadwalByDay[$hari] ?? collect(); ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col overflow-hidden">
            <div class="flex items-center justify-between px-3.5 py-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/30">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200"><?php echo e($hari); ?></span>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300"><?php echo e($jadwalHari->count()); ?> Sesi</span>
            </div>

            <div class="flex-1 divide-y divide-slate-50 dark:divide-slate-700/30">
                <?php $__empty_1 = true; $__currentLoopData = $jadwalHari->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start gap-2.5 px-3.5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/20 transition group">
                    <div class="w-1 h-12 rounded-full shrink-0" style="background: <?php echo e($tt->studySubject->color ?? '#6366f1'); ?>"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate"><?php echo e($tt->studySubject->name); ?></p>
                        <p class="text-[10px] text-slate-500 mt-0.5">
                            <i class="bi bi-clock me-1"></i><?php echo e(substr($tt->start_time, 0, 5)); ?> – <?php echo e(substr($tt->end_time, 0, 5)); ?>

                        </p>
                        <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-medium mt-0.5 mb-1">
                            Kelas: <?php echo e($tt->studyGroup->name ?? 'N/A'); ?> <?php if($tt->room): ?> | Ruang: <?php echo e($tt->room); ?> <?php endif; ?>
                        </p>
                        <?php if($tt->notes): ?>
                            <p class="text-[9px] text-slate-400 bg-slate-100 dark:bg-slate-900 px-1.5 py-0.5 rounded italic truncate"><?php echo e($tt->notes); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="openEditJadwal(<?php echo e($tt->id); ?>)" class="p-1 text-slate-400 hover:text-amber-600 transition" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button onclick="openDeleteJadwal(<?php echo e($tt->id); ?>, '<?php echo e($tt->studySubject->name); ?>')" class="p-1 text-slate-400 hover:text-red-600 transition" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8">
                    <p class="text-slate-400 text-xs">Belum ada jadwal mengajar.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div id="modalTambahJadwal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-md w-full shadow-2xl border border-slate-100 dark:border-slate-700 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Tambah Jadwal Baru</h3>
            <button onclick="closeModal('modalTambahJadwal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form action="<?php echo e(route('guru.jadwal-mengajar.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Mata Pelajaran</label>
                <select name="study_subject_id" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                    <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                    <?php $__currentLoopData = $studySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subj->id); ?>"><?php echo e($subj->name); ?> (<?php echo e($subj->code); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Kelas Tujuan</label>
                <select name="study_group_id" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                    <option value="" disabled selected>-- Pilih Kelas --</option>
                    <?php $__currentLoopData = $studyGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Hari</label>
                    <select name="day_of_week" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($h); ?>"><?php echo e($h); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Selesai</label>
                    <input type="time" name="end_time" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tahun Ajaran</label>
                    <select name="academic_year" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <option value="" disabled selected>-- Pilih Tahun Ajaran --</option>
                        <?php
                            $currentYear = date('Y');
                            // Menghasilkan rentang pilihan tahun otomatis dari 5 tahun lalu sampai 5 tahun depan
                        ?>
                        <?php for($i = $currentYear - 5; $i <= $currentYear + 5; $i++): ?>
                            <?php 
                                $yearFormat = $i . '/' . ($i + 1); 
                                // Deteksi otomatis untuk mencentang tahun ajaran aktif saat ini
                                $isCurrent = ($i == $currentYear && date('n') >= 7) || ($i == $currentYear - 1 && date('n') < 7);
                            ?>
                            <option value="<?php echo e($yearFormat); ?>" <?php echo e($isCurrent ? 'selected' : ''); ?>><?php echo e($yearFormat); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Semester</label>
                    <select name="semester" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <option value="1" <?php echo e(date('n') >= 7 || date('n') <= 1 ? 'selected' : ''); ?>>1 (Ganjil)</option>
                        <option value="2" <?php echo e(date('n') >= 2 && date('n') <= 6 ? 'selected' : ''); ?>>2 (Genap)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Ruangan (Opsional)</label>
                    <input type="text" name="room" placeholder="Ex: Ruang LAB 1" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tipe Sesi</label>
                    <select name="session_type" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <option value="teori">Teori</option>
                        <option value="praktikum">Praktikum</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Tulis catatan jika ada..." class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="closeModal('modalTambahJadwal')" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 shadow transition">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>


<div id="modalEditJadwal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-md w-full shadow-2xl border border-slate-100 dark:border-slate-700 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Jadwal Mengajar</h3>
            <button onclick="closeModal('modalEditJadwal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="formEditJadwal" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Mata Pelajaran</label>
                <select name="study_subject_id" id="editSubject" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                    <?php $__currentLoopData = $studySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subj->id); ?>"><?php echo e($subj->name); ?> (<?php echo e($subj->code); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Kelas Tujuan</label>
                <select name="study_group_id" id="editGroup" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                    <?php $__currentLoopData = $studyGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Hari</label>
                    <select name="day_of_week" id="editDay" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($h); ?>"><?php echo e($h); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" id="editStart" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Selesai</label>
                    <input type="time" name="end_time" id="editEnd" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tahun Ajaran</label>
                    <select name="academic_year" id="editYear" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <?php for($i = date('Y') - 5; $i <= date('Y') + 5; $i++): ?>
                            <?php $yearFormat = $i . '/' . ($i + 1); ?>
                            <option value="<?php echo e($yearFormat); ?>"><?php echo e($yearFormat); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Semester</label>
                    <select name="semester" id="editSemester" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Ruangan</label>
                    <input type="text" name="room" id="editRoom" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Tipe Sesi</label>
                    <select name="session_type" id="editSessionType" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100" required>
                        <option value="teori">Teori</option>
                        <option value="praktikum">Praktikum</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" id="editNotes" rows="2" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-slate-100"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="closeModal('modalEditJadwal')" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-white text-xs font-semibold rounded-lg hover:bg-amber-600 shadow transition">Update Jadwal</button>
            </div>
        </form>
    </div>
</div>


<div id="modalKelolaMapel" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-2xl w-full shadow-2xl border border-slate-100 dark:border-slate-700 flex flex-col max-h-[85vh]">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Master Data Mata Pelajaran</h3>
            <button onclick="closeModal('modalKelolaMapel')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto pr-1 space-y-4">
            <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-xl border border-slate-200/60 dark:border-slate-700">
                <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Tambah Mapel Baru</p>
                <form action="<?php echo e(url('guru/study-subject')); ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-end">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-[10px] font-medium text-slate-500 dark:text-slate-400 mb-0.5">Nama Mata Pelajaran</label>
                        <input type="text" name="name" placeholder="Ex: Matematika" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-medium text-slate-500 dark:text-slate-400 mb-0.5">Kode Mapel</label>
                        <input type="text" name="code" placeholder="Ex: MTK-07" class="w-full text-xs rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" required>
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-800 dark:bg-indigo-600 hover:bg-slate-900 dark:hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                        + Tambahkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto border border-slate-100 dark:border-slate-700 rounded-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900 text-[10px] font-semibold text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                            <th class="p-2.5">Nama Mapel</th>
                            <th class="p-2.5">Kode</th>
                            <th class="p-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-xs text-slate-700 dark:text-slate-300">
                        <?php $__empty_1 = true; $__currentLoopData = $studySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition">
                            <td class="p-2.5 font-medium"><?php echo e($s->name); ?></td>
                            <td class="p-2.5"><span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-900 rounded font-mono text-[10px]"><?php echo e($s->code); ?></span></td>
                            <td class="p-2.5 text-center">
                                <button onclick="openDeleteMapel(<?php echo e($s->id); ?>, '<?php echo e($s->name); ?>')" class="text-slate-400 hover:text-red-600 text-xs px-2 py-1 rounded transition">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center p-4 text-slate-400 text-xs">Belum ada data mata pelajaran master.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="modalHapusMapel" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 max-w-sm w-full shadow-2xl border border-slate-100 dark:border-slate-700 text-center">
        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="bi bi-exclamation-triangle text-lg"></i>
        </div>
        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 mb-1">Hapus Mata Pelajaran?</h4>
        <p class="text-[11px] text-slate-500 mb-4">Mata pelajaran <span id="hapusMapelName" class="font-bold text-slate-700 dark:text-slate-300"></span> akan dihapus permanen dari sistem master.</p>
        <form id="formHapusMapel" method="POST" class="flex justify-center gap-2">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="button" onclick="closeModal('modalHapusMapel')" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs rounded-lg">Batal</button>
            <button type="submit" class="px-3 py-1.5 bg-rose-600 text-white text-xs font-semibold rounded-lg hover:bg-rose-700 shadow">Ya, Hapus</button>
        </form>
    </div>
</div>


<script>
    // Memuat data koleksi jadwal ke dalam Javascript Object Map untuk pemetaan Edit Data secara instan
    const dataJadwal = <?php echo json_encode($allTimetables->keyBy('id')); ?>;

    // Fungsi Utama Buka & Tutup Elemen Modal Kontainer
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Mengisi data lama ke form modal edit jadwal secara dinamis
    function openEditJadwal(id) {
        const item = dataJadwal[id];
        if (!item) return;

        // Atur action form update secara dinamis
        document.getElementById('formEditJadwal').action = `/guru/jadwal-mengajar/${id}`;
        
        // Pemetaan nilai ke dalam field input
        document.getElementById('editSubject').value = item.study_subject_id;
        document.getElementById('editGroup').value = item.study_group_id;
        document.getElementById('editDay').value = item.day_of_week;
        document.getElementById('editStart').value = item.start_time.substring(0, 5);
        document.getElementById('editEnd').value = item.end_time.substring(0, 5);
        document.getElementById('editYear').value = item.academic_year;
        document.getElementById('editSemester').value = item.semester;
        document.getElementById('editRoom').value = item.room || '';
        document.getElementById('editSessionType').value = item.session_type;
        document.getElementById('editNotes').value = item.notes || '';

        openModal('modalEditJadwal');
    }

    // Melakukan submit hapus data jadwal pelajaran dengan form runtime helper
    function openDeleteJadwal(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus jadwal untuk mata pelajaran "${nama}"? Data di dashboard admin terkait juga akan ikut terhapus.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/guru/jadwal-mengajar/${id}`;
            form.innerHTML = `
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Pemicu Modal Konfirmasi Hapus Master Mapel
    function openDeleteMapel(id, nama) {
        document.getElementById('hapusMapelName').textContent = nama;
        document.getElementById('formHapusMapel').action = '<?php echo e(url("guru/study-subject")); ?>/' + id;
        openModal('modalHapusMapel');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/guru/jadwal-mengajar/index.blade.php ENDPATH**/ ?>
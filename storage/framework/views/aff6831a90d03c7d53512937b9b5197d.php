
<div id="modalTambahKelas"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalTambahKelas')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg
                max-h-[92vh] flex flex-col animate-modal">

        
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100
                    dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Tambah / Edit Kelas
                    </h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                        Kelola data rombongan belajar dan tentukan wali kelasnya.
                    </p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahKelas')"
                    class="p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700
                           text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        
        <div class="flex-1 overflow-y-auto p-5 space-y-4">
            <form id="formTambahKelas" action="<?php echo e(route('admin.kelas.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="_method" id="formMethod" value="POST">

                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Nama Rombel / Kelas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="input_name" required value="<?php echo e(old('name')); ?>"
                               placeholder="Contoh: VII-A atau 7-A"
                               class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                      bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                      focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tingkat / Grade <span class="text-rose-500">*</span>
                        </label>
                        <select name="grade" id="input_grade" required
                                class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                       bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                       focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            <option value="" disabled <?php echo e(old('grade') ? '' : 'selected'); ?>>Pilih Tingkat</option>
                            <option value="7" <?php echo e(old('grade') == '7' ? 'selected' : ''); ?>>Tingkat 7 (VII)</option>
                            <option value="8" <?php echo e(old('grade') == '8' ? 'selected' : ''); ?>>Tingkat 8 (VIII)</option>
                            <option value="9" <?php echo e(old('grade') == '9' ? 'selected' : ''); ?>>Tingkat 9 (IX)</option>
                        </select>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Sub Bagian / Suffix <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="section" id="input_section" required value="<?php echo e(old('section', 'A')); ?>"
                               placeholder="Contoh: A, B, atau C"
                               class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                      bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                      focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Wali Kelas <span class="text-slate-400 text-[10px]">(Opsional)</span>
                        </label>
                        <select name="homeroom_teacher_id" id="input_homeroom_teacher_id"
                                class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                       bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                       focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            <option value="" selected>-- Tanpa Wali Kelas --</option>
                            <?php $__currentLoopData = $gurus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($guru->id); ?>" <?php echo e(old('homeroom_teacher_id') == $guru->id ? 'selected' : ''); ?>>
                                    <?php echo e($guru->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tahun Ajaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="academic_year" id="input_academic_year" required
                                class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                       bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                       focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            <option value="" disabled>-- Pilih Tahun Ajaran --</option>
                            <?php
                                $startYear = 2000;
                                $endYear = now()->year + 1;
                                $currentMonth = now()->month;

                                $defaultYearStr = ($currentMonth >= 7) 
                                    ? now()->year . '/' . (now()->year + 1) 
                                    : (now()->year - 1) . '/' . now()->year;
                            ?>

                            <?php for($year = $startYear; $year <= $endYear; $year++): ?>
                                <?php 
                                    $optionValue = $year . '/' . ($year + 1); 
                                    $isSelected = old('academic_year', $defaultYearStr) == $optionValue;
                                ?>
                                <option value="<?php echo e($optionValue); ?>" <?php echo e($isSelected ? 'selected' : ''); ?>>
                                    <?php echo e($optionValue); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Semester <span class="text-rose-500">*</span>
                        </label>
                        <select name="semester" id="input_semester" required
                                class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                       bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                       focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                            <option value="1" <?php echo e(old('semester', ($currentMonth >= 7 && $currentMonth <= 12 ? '1' : '2')) == '1' ? 'selected' : ''); ?>>Semester 1 (Ganjil)</option>
                            <option value="2" <?php echo e(old('semester', ($currentMonth >= 7 && $currentMonth <= 12 ? '1' : '2')) == '2' ? 'selected' : ''); ?>>Semester 2 (Genap)</option>
                        </select>
                    </div>
                </div>

                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Kapasitas Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="capacity" id="input_capacity" required value="<?php echo e(old('capacity', 32)); ?>" min="1"
                           placeholder="Masukkan jumlah kapasitas siswa (Bebas tanpa batas maksimal)"
                           class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                  bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                  focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                </div>

                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Deskripsi / Lokasi Ruangan <span class="text-slate-400 text-[10px]">(Opsional)</span>
                    </label>
                    <textarea name="room" id="input_room" rows="2" placeholder="Contoh: Gedung B Lantai 2, Samping Lab IPA"
                              class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700
                                     bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100
                                     focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm resize-none"><?php echo e(old('room')); ?></textarea>
                </div>

                
                <div class="p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-700/60">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" name="is_active" id="input_is_active" value="1" class="sr-only peer" checked>
                                <div class="w-9 h-4 bg-slate-300 dark:bg-slate-700 rounded-full peer
                                            peer-checked:bg-indigo-600 transition-colors duration-200"></div>
                                <div class="absolute top-0.5 left-0.5 w-3 h-3 rounded-full
                                            bg-white shadow transition-transform
                                            peer-checked:translate-x-4"></div>
                            </div>
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                                Kelas Aktif
                            </span>
                        </label>
                    </div>
                </div>

            </form>
        </div>

        
        <div class="flex gap-2 px-5 py-3.5 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalTambahKelas')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-slate-600 dark:text-slate-400 text-xs font-medium
                           hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formTambahKelas"
                    class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition">
                Simpan Kelas
            </button>
        </div>

    </div>
</div><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/kelas/_modal_tambah.blade.php ENDPATH**/ ?>
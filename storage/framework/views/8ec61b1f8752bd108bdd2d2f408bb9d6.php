
<div id="modalLuluskan"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-2xl overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 13c0
                                 5.523-4.477 10-9 10S3 18.523 3 13c0-.538.04-1.066.118-1.578L12 14z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Luluskan Siswa &rarr; Alumni</h3>
            </div>
            <button type="button" onclick="closeModal('modalLuluskan')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formLuluskan" action="<?php echo e(route('admin.alumni.graduate')); ?>" method="POST" class="p-5 space-y-4">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Tahun Lulus <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="tahun_lulus" required min="2000" max="2100"
                           value="<?php echo e(old('tahun_lulus', date('Y'))); ?>"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Tanggal Lulus <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="tanggal_lulus" required
                           value="<?php echo e(old('tanggal_lulus', date('Y-m-d'))); ?>"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Prefix No. Ijazah
                    </label>
                    <input type="text" name="no_ijazah_prefix" placeholder="cth: DN-09-Ma"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                    Catatan (opsional)
                </label>
                <input type="text" name="catatan" placeholder="cth: Lulus Ujian Sekolah Tahun Ajaran 2025/2026"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                              bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                              placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500 outline-none transition">
            </div>

            <div class="border-t border-slate-100 dark:border-slate-700 pt-3">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex-1">
                        Pilih Siswa Aktif
                    </p>
                    <select id="luluskan_kelasFilter"
                            class="rounded-lg border border-slate-200 dark:border-slate-600 text-xs px-2 py-1.5
                                   bg-white dark:bg-slate-900 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Semua Kelas</option>
                    </select>
                    <input type="text" id="luluskan_searchSiswa" placeholder="Cari nama/NISN..."
                           class="rounded-lg border border-slate-200 dark:border-slate-600 text-xs px-2 py-1.5
                                  bg-white dark:bg-slate-900 dark:text-slate-300 placeholder:text-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                <label class="flex items-center gap-2 px-2.5 py-1.5 mb-1 border-b border-slate-100 dark:border-slate-700">
                    <input type="checkbox" id="luluskan_pilihSemua" class="rounded border-slate-300">
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Pilih Semua</span>
                </label>

                <div id="luluskan_listSiswa"
                     class="max-h-56 overflow-y-auto rounded-xl border border-slate-100 dark:border-slate-700 divide-y divide-slate-50 dark:divide-slate-700/60">
                    <p class="text-xs text-slate-400 text-center py-4">Memuat data siswa...</p>
                </div>

                <p class="text-[10px] text-amber-500 mt-2">
                    ⚠ Setelah diluluskan, data siswa akan disalin ke Data Alumni dan akun login siswa dinonaktifkan otomatis.
                </p>
            </div>

            <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:scale-95
                               text-white py-2 rounded-xl text-xs font-bold transition shadow-sm">
                    Proses Kelulusan
                </button>
                <button type="button" onclick="closeModal('modalLuluskan')"
                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                               text-xs font-semibold text-slate-600 dark:text-slate-400
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/alumni/_modal_luluskan.blade.php ENDPATH**/ ?>
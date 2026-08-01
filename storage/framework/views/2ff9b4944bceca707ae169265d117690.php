<?php $__env->startSection('title', 'Tambah Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<div class="fixed inset-0 z-[1100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white dark:bg-slate-800 w-full max-w-3xl rounded-[2.5rem] shadow-2xl border border-white/20 dark:border-slate-700 max-h-[94vh] flex flex-col overflow-hidden animate-in zoom-in-95 duration-300">
        
        <!-- HEADER -->
        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-white/50 dark:bg-slate-800/50 backdrop-blur-md sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-indigo-200 dark:shadow-none shrink-0">📢</div>
                <div>
                    <h2 class="font-bold text-xl text-slate-800 dark:text-white">Pengumuman Baru</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Buat pesan yang menarik untuk audiens Anda</p>
                </div>
            </div>
            <a href="<?php echo e(route('admin.pengumuman')); ?>" 
               class="w-10 h-10 flex items-center justify-center rounded-full text-slate-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <form method="POST" action="<?php echo e(route('admin.pengumuman.store')); ?>" 
              enctype="multipart/form-data" id="formPengumuman" 
              onsubmit="return prepareSubmit()" class="flex-1 flex flex-col overflow-hidden">
            <?php echo csrf_field(); ?>

            <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">

                <!-- 1. Informasi Dasar -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400 px-2 py-1 rounded-md uppercase tracking-widest">Langkah 1</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Konfigurasi Pengumuman</h3>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 flex-1"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Judul Pengumuman <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="<?php echo e(old('judul')); ?>" required
                                   placeholder="Contoh: Jadwal Ujian Semester Ganjil 2024"
                                   class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 text-sm font-medium transition-all">
                            <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-[10px] mt-1.5 ml-2 font-bold"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Penerima</label>
                                <select name="target_audience" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium cursor-pointer">
                                    <option value="semua" <?php echo e(old('target_audience') == 'semua' ? 'selected' : ''); ?>>🌐 Semua (Umum)</option>
                                    <option value="guru"  <?php echo e(old('target_audience') == 'guru' ? 'selected' : ''); ?>>👨‍🏫 Guru Saja</option>
                                    <option value="siswa" <?php echo e(old('target_audience') == 'siswa' ? 'selected' : ''); ?>>🎓 Siswa Saja</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Jenis Konten</label>
                                <select name="tipe_konten" id="tipeKonten" onchange="switchTipe(this.value)" 
                                        class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium cursor-pointer">
                                    <option value="teks"    <?php echo e(old('tipe_konten') == 'teks' ? 'selected' : ''); ?>>📝 Pesan Teks</option>
                                    <option value="gambar"  <?php echo e(old('tipe_konten') == 'gambar' ? 'selected' : ''); ?>>🖼️ Gambar / Poster</option>
                                    <option value="dokumen" <?php echo e(old('tipe_konten') == 'dokumen' ? 'selected' : ''); ?>>📄 Berkas Dokumen</option>
                                    <option value="link"    <?php echo e(old('tipe_konten') == 'link' ? 'selected' : ''); ?>>🔗 Tautan Luar</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Waktu Mulai</label>
                                <input type="datetime-local" name="tanggal_mulai" value="<?php echo e(old('tanggal_mulai')); ?>" 
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Waktu Berakhir (Opsional)</label>
                                <input type="datetime-local" name="tanggal_selesai" value="<?php echo e(old('tanggal_selesai')); ?>" 
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Konten -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400 px-2 py-1 rounded-md uppercase tracking-widest">Langkah 2</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Isi Pengumuman</h3>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 flex-1"></div>
                    </div>

                    <!-- TEKS -->
                    <div id="sectionTeks" class="tipe-section animate-in fade-in slide-in-from-top-2 duration-300">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-3 uppercase tracking-wider">Pesan Utama</label>
                        <textarea name="isi" id="isiTeks" rows="6" 
                                  placeholder="Tuliskan detail pengumuman di sini..."
                                  class="w-full px-5 py-4 rounded-[1.5rem] border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-4 focus:ring-indigo-500/10 text-sm leading-relaxed transition-all resize-none min-h-[200px]"><?php echo e(old('isi')); ?></textarea>
                    </div>

                    <!-- GAMBAR -->
                    <div id="sectionGambar" class="tipe-section hidden animate-in fade-in slide-in-from-top-2 duration-300">
                        <div onclick="document.getElementById('fileGambarInput').click()" 
                             class="group border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-10 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-all">
                            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-4 group-hover:scale-110 transition-transform">🖼️</div>
                            <p class="font-bold text-slate-700 dark:text-slate-200">Pilih File Gambar</p>
                            <p class="text-[11px] text-slate-400 mt-2 font-medium">PNG, JPG, WebP (Maks 20MB)</p>
                            <input type="file" id="fileGambarInput" name="file" accept="image/*" class="hidden" onchange="previewFile(this)">
                        </div>
                        <div id="previewGambarBox" class="mt-6 hidden text-center p-4 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <img id="previewGambarImg" class="max-h-60 mx-auto rounded-2xl shadow-lg border-4 border-white dark:border-slate-800">
                            <p id="previewGambarName" class="text-[10px] font-bold text-slate-400 mt-3 truncate"></p>
                        </div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mt-6 mb-3 uppercase tracking-wider">Keterangan Gambar (Opsional)</label>
                        <textarea id="isiGambar" rows="3" placeholder="Tambahkan deskripsi singkat jika diperlukan..." class="w-full px-5 py-4 rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm transition-all resize-none"></textarea>
                    </div>

                    <!-- DOKUMEN -->
                    <div id="sectionDokumen" class="tipe-section hidden animate-in fade-in slide-in-from-top-2 duration-300">
                        <div onclick="document.getElementById('fileDokumenInput').click()" 
                             class="group border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-10 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-all">
                            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-4 group-hover:scale-110 transition-transform">📄</div>
                            <p class="font-bold text-slate-700 dark:text-slate-200">Unggah Berkas Dokumen</p>
                            <p class="text-[11px] text-slate-400 mt-2 font-medium">PDF, DOCX, XLSX, PPTX (Maks 20MB)</p>
                            <input type="file" id="fileDokumenInput" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" class="hidden" onchange="showFileName(this)">
                        </div>
                        <div id="fileNameBox" class="mt-6 hidden">
                            <div class="bg-indigo-600 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-lg shadow-indigo-200 dark:shadow-none">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">📁</div>
                                <div class="min-w-0">
                                    <p id="fileNameText" class="font-bold text-white text-sm truncate"></p>
                                    <p id="fileSizeText" class="text-[10px] text-indigo-100 font-medium"></p>
                                </div>
                            </div>
                        </div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mt-6 mb-3 uppercase tracking-wider">Deskripsi Berkas (Opsional)</label>
                        <textarea id="isiDokumen" rows="3" placeholder="Beri informasi mengenai isi berkas ini..." class="w-full px-5 py-4 rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm transition-all resize-none"></textarea>
                    </div>

                    <!-- LINK -->
                    <div id="sectionLink" class="tipe-section hidden space-y-6 animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">URL Tautan <span class="text-red-500">*</span></label>
                                <input type="url" name="link_url" value="<?php echo e(old('link_url')); ?>" placeholder="https://contoh.com/halaman"
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Label Tombol</label>
                                <input type="text" name="link_label" value="<?php echo e(old('link_label', 'Buka Tautan')); ?>" 
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Keterangan Tambahan</label>
                            <textarea id="isiLink" rows="3" placeholder="Penjelasan mengenai link yang dibagikan..." class="w-full px-5 py-4 rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm transition-all resize-none"></textarea>
                        </div>
                    </div>

                    <textarea name="isi" id="isiHidden" class="hidden"></textarea>
                </div>

                <!-- 3. Pengaturan -->
                <div class="space-y-6 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400 px-2 py-1 rounded-md uppercase tracking-widest">Langkah 3</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Publikasi</h3>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 flex-1"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php $__currentLoopData = [
                            ['id' => 'toggleAktif', 'name' => 'is_active', 'title' => 'Terbitkan Sekarang', 'desc' => 'Langsung tampil di aplikasi', 'icon' => '🚀'],
                            ['id' => 'toggleDashboard', 'name' => 'show_di_dashboard', 'title' => 'Sematkan di Dashboard', 'desc' => 'Muncul di halaman utama', 'icon' => '📌']
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toggle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center justify-between p-5 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700 cursor-pointer hover:border-indigo-200 dark:hover:border-indigo-800 transition-all">
                            <div class="flex items-center gap-4">
                                <span class="text-2xl"><?php echo e($toggle['icon']); ?></span>
                                <div>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200"><?php echo e($toggle['title']); ?></p>
                                    <p class="text-[10px] text-slate-400 font-medium"><?php echo e($toggle['desc']); ?></p>
                                </div>
                            </div>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="<?php echo e($toggle['name']); ?>" value="1" id="<?php echo e($toggle['id']); ?>" checked class="sr-only peer">
                                <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="px-8 py-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 backdrop-blur-md flex items-center justify-between">
                <a href="<?php echo e(route('admin.pengumuman')); ?>" class="text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Batal
                </a>
                <button type="submit" 
                        class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl flex items-center gap-3 transition-all shadow-xl shadow-indigo-200 dark:shadow-none hover:scale-[1.02] active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan & Terbitkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var currentTipe = '<?php echo e(old("tipe_konten", "teks")); ?>';

function switchTipe(val) {
    currentTipe = val;
    document.querySelectorAll('.tipe-section').forEach(el => el.classList.add('hidden'));
    const section = document.getElementById('section' + val.charAt(0).toUpperCase() + val.slice(1));
    if (section) section.classList.remove('hidden');
}

function prepareSubmit() {
    const isiMap = { teks: 'isiTeks', gambar: 'isiGambar', dokumen: 'isiDokumen', link: 'isiLink' };
    const hidden = document.getElementById('isiHidden');
    const activeEl = document.getElementById(isiMap[currentTipe]);
    if (activeEl && hidden) {
        hidden.value = activeEl.value || '';
        activeEl.removeAttribute('name');
    }
    return true;
}

function previewFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewGambarImg').src = e.target.result;
            document.getElementById('previewGambarName').textContent = input.files[0].name;
            document.getElementById('previewGambarBox').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function showFileName(input) {
    if (input.files && input.files[0]) {
        const f = input.files[0];
        document.getElementById('fileNameText').textContent = f.name;
        document.getElementById('fileSizeText').textContent = (f.size / 1024 / 1024).toFixed(2) + ' MB';
        document.getElementById('fileNameBox').classList.remove('hidden');
    }
}

switchTipe(currentTipe);
</script>
<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/pengumuman/create.blade.php ENDPATH**/ ?>
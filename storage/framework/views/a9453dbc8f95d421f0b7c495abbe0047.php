<?php $__env->startSection('title', 'Edit Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<div class="fixed inset-0 z-[1100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white dark:bg-slate-800 w-full max-w-3xl rounded-[2.5rem] shadow-2xl border border-white/20 dark:border-slate-700 max-h-[94vh] flex flex-col overflow-hidden animate-in zoom-in-95 duration-300">
        
        <!-- HEADER -->
        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-white/50 dark:bg-slate-800/50 backdrop-blur-md sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-amber-200 dark:shadow-none shrink-0">✏️</div>
                <div>
                    <h2 class="font-bold text-xl text-slate-800 dark:text-white">Edit Pengumuman</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium truncate max-w-[400px]">ID: #<?php echo e($pengumuman->id); ?> — <?php echo e($pengumuman->judul); ?></p>
                </div>
            </div>
            <a href="<?php echo e(route('admin.pengumuman')); ?>" 
               class="w-10 h-10 flex items-center justify-center rounded-full text-slate-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <form method="POST" action="<?php echo e(route('admin.pengumuman.update', $pengumuman)); ?>" 
              enctype="multipart/form-data" id="formEdit" 
              onsubmit="return prepareSubmit()" class="flex-1 flex flex-col overflow-hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">

                <!-- 1. Informasi Dasar -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400 px-2 py-1 rounded-md uppercase tracking-widest">Informasi</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Konfigurasi Pengumuman</h3>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 flex-1"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Judul Pengumuman <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="<?php echo e(old('judul', $pengumuman->judul)); ?>" required
                                   class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 text-sm font-bold transition-all">
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
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Target Penerima</label>
                                <select name="target_audience" class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm font-bold transition-all cursor-pointer focus:ring-4 focus:ring-indigo-500/10">
                                    <option value="semua" <?php echo e(old('target_audience', $pengumuman->target_audience) == 'semua' ? 'selected' : ''); ?>>🌐 Semua</option>
                                    <option value="guru"  <?php echo e(old('target_audience', $pengumuman->target_audience) == 'guru' ? 'selected' : ''); ?>>👨‍🏫 Khusus Guru</option>
                                    <option value="siswa" <?php echo e(old('target_audience', $pengumuman->target_audience) == 'siswa' ? 'selected' : ''); ?>>🎓 Khusus Siswa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Tipe Konten</label>
                                <select name="tipe_konten" id="tipeKonten" onchange="switchTipe(this.value)" 
                                        class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm font-bold transition-all cursor-pointer focus:ring-4 focus:ring-indigo-500/10">
                                    <option value="teks"    <?php echo e(old('tipe_konten', $pengumuman->tipe_konten) == 'teks' ? 'selected' : ''); ?>>📝 Teks</option>
                                    <option value="gambar"  <?php echo e(old('tipe_konten', $pengumuman->tipe_konten) == 'gambar' ? 'selected' : ''); ?>>🖼️ Gambar</option>
                                    <option value="dokumen" <?php echo e(old('tipe_konten', $pengumuman->tipe_konten) == 'dokumen' ? 'selected' : ''); ?>>📄 Dokumen</option>
                                    <option value="link"    <?php echo e(old('tipe_konten', $pengumuman->tipe_konten) == 'link' ? 'selected' : ''); ?>>🔗 Link URL</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Tanggal Mulai</label>
                                <input type="datetime-local" name="tanggal_mulai" 
                                       value="<?php echo e(old('tanggal_mulai', optional($pengumuman->tanggal_mulai)->format('Y-m-d\TH:i'))); ?>" 
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Tanggal Selesai</label>
                                <input type="datetime-local" name="tanggal_selesai" 
                                       value="<?php echo e(old('tanggal_selesai', optional($pengumuman->tanggal_selesai)->format('Y-m-d\TH:i'))); ?>" 
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Konten -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400 px-2 py-1 rounded-md uppercase tracking-widest">Konten</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Isi Pengumuman</h3>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 flex-1"></div>
                    </div>

                    <!-- TEKS -->
                    <div id="sectionTeks" class="tipe-section animate-in fade-in slide-in-from-top-2 duration-300">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-3 uppercase tracking-wider">Isi Teks</label>
                        <textarea id="isiTeks" name="isi" rows="6" 
                                  class="w-full px-5 py-4 rounded-[1.5rem] border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm leading-relaxed transition-all resize-none min-h-[200px]"><?php echo e(old('isi', $pengumuman->isi)); ?></textarea>
                    </div>

                    <!-- GAMBAR -->
                    <div id="sectionGambar" class="tipe-section hidden animate-in fade-in slide-in-from-top-2 duration-300">
                        <?php if($pengumuman->tipe_konten === 'gambar' && $pengumuman->file_path): ?>
                        <div class="mb-6 p-6 bg-slate-50 dark:bg-slate-900 rounded-[1.5rem] border border-slate-100 dark:border-slate-700">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-4 tracking-widest">Gambar Saat Ini</p>
                            <div class="relative group w-fit mx-auto">
                                <img src="<?php echo e(asset('storage/' . $pengumuman->file_path)); ?>" 
                                     class="max-h-48 rounded-2xl shadow-md border-4 border-white dark:border-slate-800" alt="Preview">
                                <label class="flex items-center gap-2 mt-4 px-4 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-bold cursor-pointer hover:bg-red-100 transition-colors mx-auto w-fit">
                                    <input type="checkbox" name="hapus_file" value="1" class="rounded text-red-600 focus:ring-0"> HAPUS GAMBAR INI
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div onclick="document.getElementById('fileGambarInput').click()" 
                             class="group border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-10 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-all">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 group-hover:scale-110 transition-transform">🖼️</div>
                            <p class="font-bold text-slate-700 dark:text-slate-200">Ganti Gambar (Opsional)</p>
                            <input type="file" id="fileGambarInput" name="file" accept="image/*" class="hidden" onchange="previewFile(this)">
                        </div>
                        <div id="previewGambarBox" class="mt-6 hidden text-center p-4 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <img id="previewGambarImg" class="max-h-52 mx-auto rounded-2xl shadow-sm">
                            <p id="previewGambarName" class="text-[10px] font-bold text-slate-400 mt-3"></p>
                        </div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mt-6 mb-3 uppercase tracking-wider">Keterangan Gambar</label>
                        <textarea id="isiGambar" rows="3" class="w-full px-5 py-4 rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm transition-all resize-none"><?php echo e(old('isi', $pengumuman->isi)); ?></textarea>
                    </div>

                    <!-- DOKUMEN -->
                    <div id="sectionDokumen" class="tipe-section hidden animate-in fade-in slide-in-from-top-2 duration-300">
                        <?php if($pengumuman->tipe_konten === 'dokumen' && $pengumuman->file_path): ?>
                        <div class="mb-6 p-5 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center text-xl">📄</div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-indigo-700 dark:text-indigo-300 truncate max-w-[200px]"><?php echo e($pengumuman->file_name); ?></p>
                                        <a href="<?php echo e(asset('storage/' . $pengumuman->file_path)); ?>" target="_blank" class="text-[10px] text-indigo-500 font-bold hover:underline">LIHAT BERKAS ↗</a>
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 px-3 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black cursor-pointer hover:bg-red-100 transition-colors shadow-sm">
                                    <input type="checkbox" name="hapus_file" value="1" class="rounded text-red-600"> HAPUS
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div onclick="document.getElementById('fileDokumenInput').click()" 
                             class="group border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-10 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-all">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 group-hover:scale-110 transition-transform">📄</div>
                            <p class="font-bold text-slate-700 dark:text-slate-200">Ganti Dokumen (Opsional)</p>
                            <input type="file" id="fileDokumenInput" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" class="hidden" onchange="showFileName(this)">
                        </div>
                        <div id="fileNameBox" class="mt-4 hidden">
                            <div class="bg-indigo-600 p-4 rounded-2xl flex items-center gap-4 text-white">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">📁</div>
                                <div><p id="fileNameText" class="text-xs font-bold truncate max-w-[200px]"></p><p id="fileSizeText" class="text-[10px] opacity-80"></p></div>
                            </div>
                        </div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mt-6 mb-3 uppercase tracking-wider">Deskripsi Berkas</label>
                        <textarea id="isiDokumen" rows="3" class="w-full px-5 py-4 rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm transition-all resize-none"><?php echo e(old('isi', $pengumuman->isi)); ?></textarea>
                    </div>

                    <!-- LINK -->
                    <div id="sectionLink" class="tipe-section hidden space-y-6 animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">URL Link <span class="text-red-500">*</span></label>
                                <input type="url" name="link_url" value="<?php echo e(old('link_url', $pengumuman->link_url)); ?>" 
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Label Tombol</label>
                                <input type="text" name="link_label" value="<?php echo e(old('link_label', $pengumuman->link_label ?? 'Kunjungi Link')); ?>" 
                                       class="w-full px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Keterangan</label>
                            <textarea id="isiLink" rows="3" class="w-full px-5 py-4 rounded-2xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm transition-all resize-none"><?php echo e(old('isi', $pengumuman->isi)); ?></textarea>
                        </div>
                    </div>

                    <textarea name="isi" id="isiHidden" class="hidden"></textarea>
                </div>

                <!-- 3. Pengaturan -->
                <div class="space-y-6 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400 px-2 py-1 rounded-md uppercase tracking-widest">Opsi</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Pengaturan Tampilan</h3>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 flex-1"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php $__currentLoopData = [
                            ['id' => 'toggleAktif', 'name' => 'is_active', 'title' => 'Status Aktif', 'desc' => 'Dapat dilihat oleh audiens', 'icon' => '🚀'],
                            ['id' => 'toggleDashboard', 'name' => 'show_di_dashboard', 'title' => 'Dashboard Utama', 'desc' => 'Muncul di widget utama', 'icon' => '📌']
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
                                <input type="checkbox" name="<?php echo e($toggle['name']); ?>" value="1" 
                                       id="<?php echo e($toggle['id']); ?>"
                                       <?php echo e(old($toggle['name'], $pengumuman->{$toggle['name']}) ? 'checked' : ''); ?>

                                       class="sr-only peer">
                                <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="px-8 py-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 backdrop-blur-md flex items-center justify-between">
                <a href="<?php echo e(route('admin.pengumuman')); ?>" class="text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">Batal</a>
                <button type="submit" 
                        class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-indigo-200 dark:shadow-none hover:scale-[1.02] active:scale-95">
                    Perbarui Pengumuman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var currentTipe = '<?php echo e(old("tipe_konten", $pengumuman->tipe_konten)); ?>';

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
        if (activeEl.hasAttribute('name')) activeEl.removeAttribute('name');
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/pengumuman/edit.blade.php ENDPATH**/ ?>
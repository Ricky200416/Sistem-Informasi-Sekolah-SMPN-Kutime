<?php $__env->startSection('title', 'Edit Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl space-y-4">

    
    <div class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400">
        <a href="<?php echo e(route('admin.pengumuman')); ?>" class="hover:text-indigo-600 font-semibold">Pengumuman</a>
        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-700 dark:text-slate-200 font-bold">Edit</span>
    </div>

    
    <div class="bg-gradient-to-r from-indigo-500 to-violet-600 rounded-2xl px-4 py-3 text-white">
        <div class="flex items-center gap-2.5">
            <span style="font-size:1.4rem"><?php echo e($pengumuman->tipeIcon()); ?></span>
            <div>
                <p class="text-[10px] font-semibold opacity-75 uppercase tracking-wider">Sedang mengedit</p>
                <p class="font-bold text-sm leading-tight"><?php echo e($pengumuman->judul); ?></p>
            </div>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('admin.pengumuman.update', $pengumuman)); ?>"
          enctype="multipart/form-data" id="formEdit" onsubmit="return prepareSubmit()">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm p-4 space-y-3.5">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <span class="w-5 h-5 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center
                             justify-center text-indigo-600 text-[10px] font-bold shrink-0">1</span>
                Informasi Dasar
            </h3>

            <div>
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                               uppercase tracking-wide mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="<?php echo e(old('judul', $pengumuman->judul)); ?>"
                       class="w-full px-3 py-2 rounded-xl border text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-slate-200 dark:border-slate-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                              bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] text-red-500 mt-0.5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                   uppercase tracking-wide mb-1">Target Penerima</label>
                    <select name="target_audience"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                   bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                        <option value="semua" <?php echo e(old('target_audience',$pengumuman->target_audience)=='semua'?'selected':''); ?>>🌐 Semua</option>
                        <option value="guru"  <?php echo e(old('target_audience',$pengumuman->target_audience)=='guru' ?'selected':''); ?>>👨‍🏫 Khusus Guru</option>
                        <option value="siswa" <?php echo e(old('target_audience',$pengumuman->target_audience)=='siswa'?'selected':''); ?>>🎓 Khusus Siswa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                   uppercase tracking-wide mb-1">Tipe Konten</label>
                    <select name="tipe_konten" id="tipeKonten"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                   bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                   focus:outline-none focus:ring-2 focus:ring-indigo-300 transition"
                            onchange="switchTipe(this.value)">
                        <option value="teks"    <?php echo e(old('tipe_konten',$pengumuman->tipe_konten)=='teks'   ?'selected':''); ?>>📝 Teks</option>
                        <option value="gambar"  <?php echo e(old('tipe_konten',$pengumuman->tipe_konten)=='gambar' ?'selected':''); ?>>🖼️ Gambar</option>
                        <option value="dokumen" <?php echo e(old('tipe_konten',$pengumuman->tipe_konten)=='dokumen'?'selected':''); ?>>📄 Dokumen</option>
                        <option value="link"    <?php echo e(old('tipe_konten',$pengumuman->tipe_konten)=='link'   ?'selected':''); ?>>🔗 Link URL</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                   uppercase tracking-wide mb-1">Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai"
                           value="<?php echo e(old('tanggal_mulai', optional($pengumuman->tanggal_mulai)->format('Y-m-d\TH:i'))); ?>"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                   uppercase tracking-wide mb-1">Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai"
                           value="<?php echo e(old('tanggal_selesai', optional($pengumuman->tanggal_selesai)->format('Y-m-d\TH:i'))); ?>"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                </div>
            </div>
        </div>

        
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm p-4 space-y-3.5 mt-3">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <span class="w-5 h-5 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center
                             justify-center text-indigo-600 text-[10px] font-bold shrink-0">2</span>
                Konten Pengumuman
            </h3>

            
            <div id="sectionTeks" class="tipe-section">
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                               uppercase tracking-wide mb-1">Isi Pengumuman</label>
                <textarea id="isiTeks" rows="5" placeholder="Tulis isi pengumuman..."
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition resize-none"><?php echo e(old('tipe_konten',$pengumuman->tipe_konten)==='teks' ? old('isi',$pengumuman->isi) : ''); ?></textarea>
            </div>

            
            <div id="sectionGambar" class="tipe-section hidden">
                <?php if($pengumuman->tipe_konten === 'gambar' && $pengumuman->file_path): ?>
                <div class="mb-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600">
                    <p class="text-[10px] font-semibold text-slate-400 mb-1.5">Gambar saat ini:</p>
                    <img src="<?php echo e($pengumuman->fileUrl()); ?>" alt="Gambar"
                         class="max-h-28 rounded-xl object-contain"
                         onerror="this.parentElement.innerHTML='<p class=\'text-[10px] text-red-400\'>Gambar tidak dapat dimuat.</p>'">
                    <p class="text-[10px] text-slate-400 mt-1"><?php echo e($pengumuman->file_name); ?></p>
                    <label class="flex items-center gap-1.5 mt-2 text-[10px] text-red-600 dark:text-red-400 cursor-pointer">
                        <input type="checkbox" name="hapus_file" value="1" class="w-3.5 h-3.5 rounded"> Hapus gambar ini
                    </label>
                </div>
                <?php endif; ?>
                <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-5
                            text-center hover:border-indigo-400 transition-colors cursor-pointer"
                     onclick="document.getElementById('fileGambarInput').click()">
                    <div class="text-2xl mb-1.5">🖼️</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        <?php echo e(($pengumuman->tipe_konten==='gambar'&&$pengumuman->file_path) ? 'Upload gambar baru (opsional)' : 'Klik untuk upload'); ?>

                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, GIF, WebP — maks. 20 MB</p>
                    <input type="file" id="fileGambarInput" name="file" accept="image/*"
                           class="hidden" onchange="previewFile(this)">
                </div>
                <div id="previewGambarBox" class="mt-3 hidden text-center">
                    <img id="previewGambarImg" class="max-h-28 rounded-xl mx-auto object-contain border border-slate-200 dark:border-slate-600">
                    <p id="previewGambarName" class="text-[10px] text-slate-400 mt-1"></p>
                </div>
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                               uppercase tracking-wide mb-1 mt-3">Keterangan (opsional)</label>
                <textarea id="isiGambar" rows="3" placeholder="Tambahkan keterangan..."
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition resize-none"><?php echo e(old('tipe_konten',$pengumuman->tipe_konten)==='gambar' ? old('isi',$pengumuman->isi) : ''); ?></textarea>
            </div>

            
            <div id="sectionDokumen" class="tipe-section hidden">
                <?php if($pengumuman->tipe_konten === 'dokumen' && $pengumuman->file_path): ?>
                <div class="mb-3 flex items-center gap-2.5 p-3 bg-indigo-50 dark:bg-indigo-900/30
                            rounded-xl border border-indigo-200 dark:border-indigo-700">
                    <span>📄</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-indigo-700 dark:text-indigo-300 truncate"><?php echo e($pengumuman->file_name); ?></p>
                        <p class="text-[10px] text-indigo-400">File saat ini</p>
                    </div>
                    <a href="<?php echo e($pengumuman->fileUrl()); ?>" target="_blank"
                       class="text-[10px] px-2.5 py-1 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-800
                              dark:hover:bg-indigo-700 text-indigo-700 dark:text-indigo-300 rounded-lg font-semibold transition">
                        Lihat
                    </a>
                </div>
                <label class="flex items-center gap-1.5 mb-3 text-[10px] text-red-600 dark:text-red-400 cursor-pointer">
                    <input type="checkbox" name="hapus_file" value="1" class="w-3.5 h-3.5 rounded"> Hapus file ini
                </label>
                <?php endif; ?>
                <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-5
                            text-center hover:border-indigo-400 transition-colors cursor-pointer"
                     onclick="document.getElementById('fileDokumenInput').click()">
                    <div class="text-2xl mb-1.5">📄</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        <?php echo e(($pengumuman->tipe_konten==='dokumen'&&$pengumuman->file_path) ? 'Upload dokumen baru (opsional)' : 'Klik untuk upload'); ?>

                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX — maks. 20 MB</p>
                    <input type="file" id="fileDokumenInput" name="file"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx"
                           class="hidden" onchange="showFileName(this)">
                </div>
                <div id="fileNameBox" class="mt-3 hidden">
                    <div class="flex items-center gap-2 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30
                                rounded-xl border border-indigo-200 dark:border-indigo-700">
                        <span>📄</span>
                        <div>
                            <p id="fileNameText" class="text-xs font-semibold text-indigo-700 dark:text-indigo-300"></p>
                            <p id="fileSizeText" class="text-[10px] text-indigo-400"></p>
                        </div>
                    </div>
                </div>
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                               uppercase tracking-wide mb-1 mt-3">Deskripsi (opsional)</label>
                <textarea id="isiDokumen" rows="3" placeholder="Tambahkan deskripsi..."
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition resize-none"><?php echo e(old('tipe_konten',$pengumuman->tipe_konten)==='dokumen' ? old('isi',$pengumuman->isi) : ''); ?></textarea>
            </div>

            
            <div id="sectionLink" class="tipe-section hidden space-y-3">
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                   uppercase tracking-wide mb-1">URL Link <span class="text-red-500">*</span></label>
                    <input type="url" name="link_url"
                           value="<?php echo e(old('tipe_konten',$pengumuman->tipe_konten)==='link' ? old('link_url',$pengumuman->link_url) : ''); ?>"
                           placeholder="https://..."
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] text-red-500 mt-0.5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                   uppercase tracking-wide mb-1">Label Tombol</label>
                    <input type="text" name="link_label"
                           value="<?php echo e(old('tipe_konten',$pengumuman->tipe_konten)==='link' ? old('link_label',$pengumuman->link_label) : ''); ?>"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                                   uppercase tracking-wide mb-1">Keterangan (opsional)</label>
                    <textarea id="isiLink" rows="3" placeholder="Tambahkan keterangan..."
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                     bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs
                                     focus:outline-none focus:ring-2 focus:ring-indigo-300 transition resize-none"><?php echo e(old('tipe_konten',$pengumuman->tipe_konten)==='link' ? old('isi',$pengumuman->isi) : ''); ?></textarea>
                </div>
            </div>

            <textarea name="isi" id="isiHidden" class="hidden" aria-hidden="true"></textarea>
        </div>

        
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm p-4 space-y-2.5 mt-3">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <span class="w-5 h-5 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center
                             justify-center text-indigo-600 text-[10px] font-bold shrink-0">3</span>
                Pengaturan Tampilan
            </h3>

            <?php $__currentLoopData = [
                ['id'=>'toggleAktif',     'name'=>'is_active',       'val'=>$pengumuman->is_active      ?'1':'0',
                 'title'=>'Status Aktif',       'desc'=>'Pengumuman aktif dapat dilihat oleh penerima'],
                ['id'=>'toggleDashboard', 'name'=>'show_di_dashboard','val'=>$pengumuman->show_di_dashboard?'1':'0',
                 'title'=>'Tampil di Dashboard', 'desc'=>'Muncul di widget dashboard penerima'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toggle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="flex items-center justify-between cursor-pointer p-3
                           bg-slate-50 dark:bg-slate-700/50 rounded-xl border
                           border-slate-200 dark:border-slate-600 hover:border-indigo-300 transition">
                <div>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300"><?php echo e($toggle['title']); ?></p>
                    <p class="text-[10px] text-slate-400 mt-0.5"><?php echo e($toggle['desc']); ?></p>
                </div>
                <div class="relative shrink-0 ml-3">
                    <input type="checkbox" name="<?php echo e($toggle['name']); ?>" value="1"
                           id="<?php echo e($toggle['id']); ?>"
                           <?php echo e(old($toggle['name'], $toggle['val']) == '1' ? 'checked' : ''); ?>

                           class="sr-only peer">
                    <div class="w-9 h-5 bg-slate-300 peer-checked:bg-indigo-500 rounded-full transition-colors cursor-pointer"
                         onclick="document.getElementById('<?php echo e($toggle['id']); ?>').click()"></div>
                    <div class="pointer-events-none absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full
                                shadow transition-transform peer-checked:translate-x-4"></div>
                </div>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="flex items-center justify-between mt-3">
            <a href="<?php echo e(route('admin.pengumuman')); ?>"
               class="text-xs font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400
                      dark:hover:text-slate-200 transition">← Batal</a>
            <button type="submit"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold
                           rounded-xl transition shadow-sm flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
var currentTipe = '<?php echo e(old("tipe_konten", $pengumuman->tipe_konten)); ?>';
function switchTipe(val) {
    currentTipe = val;
    document.querySelectorAll('.tipe-section').forEach(function(el) { el.classList.add('hidden'); });
    document.querySelectorAll('input[type="file"]').forEach(function(inp) { inp.disabled = true; });
    var map = { teks:'Teks', gambar:'Gambar', dokumen:'Dokumen', link:'Link' };
    var section = document.getElementById('section' + map[val]);
    if (section) {
        section.classList.remove('hidden');
        var f = section.querySelector('input[type="file"]');
        if (f) f.disabled = false;
    }
}
function prepareSubmit() {
    var isiMap = { teks: document.getElementById('isiTeks'), gambar: document.getElementById('isiGambar'), dokumen: document.getElementById('isiDokumen'), link: document.getElementById('isiLink') };
    var hidden = document.getElementById('isiHidden');
    var active = isiMap[currentTipe];
    if (active && hidden) { hidden.value = active.value; active.removeAttribute('name'); }
    return true;
}
function previewFile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewGambarImg').src = e.target.result;
            document.getElementById('previewGambarName').textContent = input.files[0].name;
            document.getElementById('previewGambarBox').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function showFileName(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        document.getElementById('fileNameText').textContent = file.name;
        document.getElementById('fileSizeText').textContent = (file.size/1024/1024).toFixed(2) + ' MB';
        document.getElementById('fileNameBox').classList.remove('hidden');
    }
}
switchTipe(currentTipe);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/pengumuman/edit.blade.php ENDPATH**/ ?>
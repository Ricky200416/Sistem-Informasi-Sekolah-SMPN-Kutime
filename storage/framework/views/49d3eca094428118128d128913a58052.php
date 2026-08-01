<?php $__env->startSection('title', isset($galeri) ? 'Edit Media Galeri' : 'Tambah Media Galeri'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 py-4">

    
    <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-4">
        <a href="<?php echo e(route('admin.kelola-website', ['tab'=>'galeri'])); ?>" class="hover:text-indigo-600">Kelola Galeri</a>
        <span>/</span>
        <span class="text-slate-700 font-medium"><?php echo e(isset($galeri) ? 'Edit Media' : 'Tambah Media'); ?></span>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h1 class="text-sm font-semibold text-slate-900 mb-4">
            <?php echo e(isset($galeri) ? 'Edit: '.Str::limit($galeri->judul, 40) : 'Tambah Media Baru'); ?>

        </h1>

        <form
            method="POST"
            action="<?php echo e(isset($galeri) ? route('admin.galeri.update', $galeri) : route('admin.galeri.store')); ?>"
            enctype="multipart/form-data"
            class="space-y-4"
            x-data="galeriForm('<?php echo e(old('tipe', $galeri->tipe ?? 'photo')); ?>')"
        >
            <?php echo csrf_field(); ?>
            <?php if(isset($galeri)): ?> <?php echo method_field('PATCH'); ?> <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700">
                    <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                    Tipe Media <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <?php $__currentLoopData = $tipeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex flex-col items-center gap-1 p-2.5 border-2 rounded-lg cursor-pointer transition-colors"
                           :class="tipe === '<?php echo e($val); ?>' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="tipe" value="<?php echo e($val); ?>" class="sr-only"
                               x-model="tipe" @change="resetFile()">
                        <span class="text-lg"><?php echo e(explode(' ', $label)[0]); ?></span>
                        <span class="text-xs font-medium text-slate-700"><?php echo e(implode(' ', array_slice(explode(' ', $label), 1))); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div x-show="tipe === 'photo' || tipe === 'video'">
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    <span x-text="tipe === 'photo' ? 'Upload Foto' : 'Upload Video'"></span>
                    <?php if(!isset($galeri)): ?><span class="text-red-500">*</span><?php endif; ?>
                    <span class="text-slate-400 font-normal" x-show="tipe === 'photo'">(jpg/png/webp/gif – tidak ada batas ukuran)</span>
                    <span class="text-slate-400 font-normal" x-show="tipe === 'video'">(mp4/mov/avi/mkv/webm – tidak ada batas ukuran)</span>
                </label>

                
                <?php if(isset($galeri) && $galeri->file_path): ?>
                <div class="mb-2">
                    <?php if($galeri->tipe === 'photo'): ?>
                        <img src="<?php echo e($galeri->file_url); ?>" alt="<?php echo e($galeri->judul); ?>"
                             class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                    <?php elseif($galeri->tipe === 'video'): ?>
                        <video src="<?php echo e($galeri->file_url); ?>" controls
                               class="w-full max-h-40 rounded-lg border border-slate-200"></video>
                    <?php endif; ?>
                    <p class="text-xs text-slate-400 mt-1">File saat ini. Upload baru untuk mengganti.</p>
                </div>
                <?php endif; ?>

                <input type="file" name="file_path" id="file-input"
                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                       @change="previewFile($event)"
                       :accept="tipe === 'photo' ? 'image/*' : 'video/mp4,video/mov,video/avi,video/mkv,video/webm'">

                
                <div x-show="previewSrc" class="mt-2">
                    <template x-if="tipe === 'photo'">
                        <img :src="previewSrc" class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                    </template>
                    <template x-if="tipe === 'video'">
                        <video :src="previewSrc" controls class="w-full max-h-40 rounded-lg border border-slate-200"></video>
                    </template>
                </div>

                <?php $__errorArgs = ['file_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div x-show="tipe === 'link_youtube' || tipe === 'link_facebook'">
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    <span x-text="tipe === 'link_youtube' ? 'URL YouTube' : 'URL Video Facebook'"></span>
                    <span class="text-red-500">*</span>
                </label>
                <input type="url" name="link_url"
                       value="<?php echo e(old('link_url', $galeri->link_url ?? '')); ?>"
                       class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500 <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="https://www.youtube.com/watch?v=..."
                       @input="previewYoutube($event.target.value)">
                <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                
                <div x-show="youtubeThumbnail" class="mt-2">
                    <img :src="youtubeThumbnail" class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                    <p class="text-xs text-slate-400 mt-1">Pratinjau thumbnail YouTube</p>
                </div>

                <?php if(isset($galeri) && $galeri->link_url && in_array($galeri->tipe, ['link_youtube','link_facebook'])): ?>
                <div class="mt-2 p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600">
                    URL saat ini: <span class="font-mono break-all"><?php echo e($galeri->link_url); ?></span>
                </div>
                <?php endif; ?>
            </div>

            
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Judul / Keterangan <span class="text-red-500">*</span></label>
                <input type="text" name="judul"
                       value="<?php echo e(old('judul', $galeri->judul ?? '')); ?>"
                       class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500 <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Contoh: Upacara HUT RI ke-80" required>
                <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Deskripsi <span class="text-slate-400 font-normal">(opsional)</span>
                </label>
                <textarea name="deskripsi" rows="2"
                          class="w-full rounded-md border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Deskripsi singkat kegiatan..."><?php echo e(old('deskripsi', $galeri->deskripsi ?? '')); ?></textarea>
            </div>

            
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Thumbnail Kustom
                    <span class="text-slate-400 font-normal">(opsional – untuk video/link, jpg/png maks. 5MB)</span>
                </label>
                <?php if(isset($galeri) && $galeri->thumbnail): ?>
                    <img src="<?php echo e(asset('storage/'.$galeri->thumbnail)); ?>" class="w-24 h-16 object-cover rounded mb-1 border border-slate-200">
                    <p class="text-xs text-slate-400 mb-1">Thumbnail saat ini.</p>
                <?php endif; ?>
                <input type="file" name="thumbnail" accept="image/*"
                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori"
                            class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <?php $__currentLoopData = $kategoriOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php if(old('kategori', $galeri->kategori ?? 'kegiatan') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Urutan <span class="text-slate-400 font-normal">(angka kecil tampil lebih awal)</span>
                    </label>
                    <input type="number" name="urutan" min="0"
                           value="<?php echo e(old('urutan', $galeri->urutan ?? 0)); ?>"
                           class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status"
                        class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="aktif" <?php if(old('status', $galeri->status ?? 'aktif') === 'aktif'): echo 'selected'; endif; ?>>✅ Aktif – tampil di website</option>
                    <option value="draf"  <?php if(old('status', $galeri->status ?? '') === 'draf'): echo 'selected'; endif; ?>>📝 Draf – tidak tampil</option>
                </select>
            </div>

            
            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                <a href="<?php echo e(route('admin.kelola-website', ['tab'=>'galeri'])); ?>"
                   class="px-4 py-2 text-xs text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">← Batal</a>
                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                    <?php echo e(isset($galeri) ? 'Simpan Perubahan' : 'Tambah Media'); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<script>
function galeriForm(initialTipe) {
    return {
        tipe: initialTipe,
        previewSrc: null,
        youtubeThumbnail: null,

        resetFile() {
            this.previewSrc = null;
            this.youtubeThumbnail = null;
            const input = document.getElementById('file-input');
            if (input) input.value = '';
        },

        previewFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            this.previewSrc = url;
        },

        previewYoutube(url) {
            const patterns = [
                /youtu\.be\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
            ];
            for (const p of patterns) {
                const m = url.match(p);
                if (m) {
                    this.youtubeThumbnail = `https://img.youtube.com/vi/${m[1]}/hqdefault.jpg`;
                    return;
                }
            }
            this.youtubeThumbnail = null;
        }
    };
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/galeri/_form.blade.php ENDPATH**/ ?>
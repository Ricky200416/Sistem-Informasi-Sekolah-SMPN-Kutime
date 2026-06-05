

<div
    x-show="tab === 'berita'"
    x-cloak
    class="space-y-4"
    x-data="beritaTab()"
    x-init="init()"
>

    
    <?php if(session('success')): ?>
        <div x-data="{ show: true }" x-show="show"
             class="flex items-center justify-between gap-2 bg-green-50 border border-green-200 text-green-800 text-xs rounded-lg px-3 py-2">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="flex gap-2 bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700">
            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>


    
    <div id="berita-panel"
         class="bg-white rounded-xl border shadow-sm overflow-hidden transition-all duration-200"
         :class="formOpen
             ? (isEdit ? 'border-amber-300 shadow-amber-100' : 'border-indigo-300 shadow-indigo-100')
             : 'border-slate-200'">

        
        <button type="button"
                @click="toggleForm()"
                class="w-full flex items-center justify-between px-5 py-3.5 border-b text-left transition-colors"
                :class="formOpen
                    ? (isEdit ? 'bg-amber-50 border-amber-200' : 'bg-indigo-50 border-indigo-200')
                    : 'bg-slate-50 border-slate-100 hover:bg-slate-100'">

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors shrink-0"
                     :class="formOpen
                         ? (isEdit ? 'bg-amber-500' : 'bg-indigo-600')
                         : 'bg-slate-200'">
                    <svg x-show="!isEdit" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <svg x-show="isEdit" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-bold text-slate-800"
                       x-text="isEdit ? 'Edit Berita / Pengumuman' : 'Tambah Berita / Pengumuman Baru'"></p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        <span x-show="!formOpen">Klik untuk membuka formulir.</span>
                        <span x-show="formOpen && !isEdit">Isi formulir berikut lalu klik Publikasikan.</span>
                        <span x-show="formOpen && isEdit">Ubah data yang diperlukan, lalu klik Simpan Perubahan.</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <span x-show="formOpen && isEdit"
                      class="hidden sm:inline-flex px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                    ✏️ Mode Edit
                </span>
                <span x-show="formOpen && !isEdit"
                      class="hidden sm:inline-flex px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full border border-indigo-200">
                    ➕ Tambah Baru
                </span>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                     :class="formOpen ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </button>

        
        <div x-show="formOpen" x-collapse>
            <div class="px-5 py-5">
                <form
                    id="berita-form"
                    method="POST"
                    action="<?php echo e(route('admin.berita.store')); ?>"
                    enctype="multipart/form-data"
                >
                    <?php echo csrf_field(); ?>
                    
                    <input type="hidden" name="_method" id="f-method" value="POST">

                    
                    <input type="hidden" name="media_tipe" :value="mediaTipe">

                    
                    <input type="hidden" name="media_link" id="f-media-link-hidden" :value="mediaLinkValue">

                    <div class="grid lg:grid-cols-5 gap-5">

                        
                        <div class="lg:col-span-3 space-y-4">

                            
                            <div>
                                <label for="f-judul" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Judul <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="f-judul" name="judul"
                                       value="<?php echo e(old('judul')); ?>"
                                       class="w-full rounded-lg border-slate-300 text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition"
                                       placeholder="Masukkan judul berita atau pengumuman..."
                                       required>
                            </div>

                            
                            <div>
                                <label for="f-ringkasan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Ringkasan
                                    <span class="text-slate-400 font-normal">(opsional, maks. 500 karakter)</span>
                                </label>
                                <textarea id="f-ringkasan" name="ringkasan" rows="2"
                                          class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition"
                                          placeholder="Ringkasan singkat yang muncul di daftar berita..."><?php echo e(old('ringkasan')); ?></textarea>
                            </div>

                            
                            <div>
                                <label for="f-isi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Isi Berita <span class="text-red-500">*</span>
                                </label>
                                <textarea id="f-isi" name="isi" rows="10"
                                          class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 transition"
                                          placeholder="Tulis isi lengkap berita atau pengumuman di sini..."
                                          required><?php echo e(old('isi')); ?></textarea>
                            </div>

                        </div>

                        
                        <div class="lg:col-span-2 space-y-4">

                            
                            <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3.5">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Pengaturan</p>

                                <div>
                                    <label for="f-kategori" class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                                    <select id="f-kategori" name="kategori"
                                            class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="berita">📰 Berita</option>
                                        <option value="pengumuman">📢 Pengumuman</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="f-status" class="block text-xs font-semibold text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                                    <select id="f-status" name="status"
                                            class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="draf">📝 Draf – tidak tampil</option>
                                        <option value="aktif">✅ Aktif – tampil di website</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="f-tanggal" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Publish</label>
                                    <input type="datetime-local" id="f-tanggal" name="tanggal_publish"
                                           value="<?php echo e(old('tanggal_publish', now()->format('Y-m-d\TH:i'))); ?>"
                                           class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <label class="flex items-start gap-2.5 cursor-pointer group pt-0.5">
                                    <input type="checkbox" id="f-penting" name="is_penting" value="1"
                                           class="mt-0.5 rounded border-slate-300 text-red-500 focus:ring-red-400">
                                    <span class="text-xs text-slate-700">
                                        🔴 Tandai sebagai <strong>Penting</strong>
                                        <span class="block text-slate-400 font-normal mt-0.5">Ditampilkan di bagian teratas halaman</span>
                                    </span>
                                </label>
                            </div>

                            
                            <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Media Lampiran</p>

                                
                                <div class="grid grid-cols-2 gap-1.5">
                                    <template x-for="opt in mediaOptions" :key="opt.value">
                                        <label class="flex items-center gap-1.5 px-2.5 py-2 border-2 rounded-lg cursor-pointer transition-all text-xs font-semibold"
                                               :class="mediaTipe === opt.value
                                                   ? 'border-indigo-500 bg-white text-indigo-700 shadow-sm'
                                                   : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-700'">
                                            <input type="radio" class="sr-only"
                                                   :value="opt.value"
                                                   x-model="mediaTipe"
                                                   @change="onTipeChange()">
                                            <span x-text="opt.icon" class="text-sm"></span>
                                            <span x-text="opt.label"></span>
                                        </label>
                                    </template>
                                </div>

                                
                                <div x-show="mediaTipe === 'photo'" class="space-y-2">
                                    <div x-show="existingFileUrl" class="rounded-lg overflow-hidden border border-slate-200 bg-white">
                                        <img :src="existingFileUrl" class="w-full max-h-40 object-cover">
                                        <p class="text-xs text-slate-400 px-2.5 py-1.5 border-t border-slate-100">📷 Foto saat ini. Upload baru untuk mengganti.</p>
                                    </div>
                                    <input type="file" id="f-media-photo" name="media_file"
                                           accept="image/jpeg,image/png,image/webp,image/gif"
                                           :disabled="mediaTipe !== 'photo'"
                                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition"
                                           @change="previewPhoto($event)">
                                    <div x-show="photoPreview" class="rounded-lg overflow-hidden border border-indigo-200 bg-white">
                                        <img :src="photoPreview" class="w-full max-h-40 object-cover">
                                        <p class="text-xs text-indigo-400 px-2.5 py-1.5 border-t border-indigo-100">✨ Pratinjau foto baru</p>
                                    </div>
                                </div>

                                
                                <div x-show="mediaTipe === 'video'" class="space-y-2">
                                    <div x-show="existingFileUrl" class="rounded-lg overflow-hidden border border-slate-200 bg-white">
                                        <video :src="existingFileUrl" controls class="w-full max-h-40"></video>
                                        <p class="text-xs text-slate-400 px-2.5 py-1.5 border-t border-slate-100">🎥 Video saat ini. Upload baru untuk mengganti.</p>
                                    </div>
                                    <input type="file" id="f-media-video" name="media_file"
                                           accept="video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm"
                                           :disabled="mediaTipe !== 'video'"
                                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition"
                                           @change="previewVideo($event)">
                                    <div x-show="videoPreview" class="rounded-lg overflow-hidden border border-indigo-200 bg-white">
                                        <video :src="videoPreview" controls class="w-full max-h-40"></video>
                                        <p class="text-xs text-indigo-400 px-2.5 py-1.5 border-t border-indigo-100">✨ Pratinjau video baru</p>
                                    </div>
                                </div>

                                
                                <div x-show="mediaTipe === 'link_youtube'" class="space-y-2">
                                    <input type="url" id="f-media-youtube"
                                           class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-red-500 focus:ring-red-400"
                                           placeholder="https://www.youtube.com/watch?v=..."
                                           x-model="mediaLinkValue"
                                           @input="previewYoutube($event.target.value)">
                                    <div x-show="youtubeThumbnail" class="rounded-lg overflow-hidden border border-slate-200 bg-white">
                                        <img :src="youtubeThumbnail" class="w-full max-h-40 object-cover">
                                        <p class="text-xs text-slate-400 px-2.5 py-1.5 border-t border-slate-100">▶️ Thumbnail YouTube</p>
                                    </div>
                                </div>

                                
                                <div x-show="mediaTipe === 'link_facebook'" class="space-y-2">
                                    <input type="url" id="f-media-facebook"
                                           class="w-full rounded-lg border-slate-300 text-xs py-2 focus:border-blue-500 focus:ring-blue-400"
                                           placeholder="https://www.facebook.com/watch?v=..."
                                           x-model="mediaLinkValue">
                                    <p class="text-xs text-slate-400">Masukkan URL video Facebook.</p>
                                </div>

                                
                                <div x-show="mediaTipe !== 'none'" class="pt-3 border-t border-slate-200 space-y-2">
                                    <label class="block text-xs font-semibold text-slate-600">
                                        Thumbnail Kustom
                                        <span class="text-slate-400 font-normal">(opsional)</span>
                                    </label>
                                    <div x-show="existingThumbnailUrl" class="rounded-lg overflow-hidden border border-slate-200 bg-white">
                                        <img :src="existingThumbnailUrl" class="w-full max-h-24 object-cover">
                                        <p class="text-xs text-slate-400 px-2.5 py-1 border-t border-slate-100">Thumbnail saat ini.</p>
                                    </div>
                                    <input type="file" id="f-thumbnail" name="media_thumbnail"
                                           accept="image/jpeg,image/png,image/webp"
                                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition">
                                </div>
                            </div>

                        </div>
                    </div>

                    
                    <div class="flex items-center justify-between pt-5 mt-1 border-t border-slate-100">
                        <button type="button" @click="cancelForm()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </button>

                        <div class="flex items-center gap-3">
                            <p x-show="isEdit" class="text-xs text-amber-600 hidden sm:block">
                                Mengedit: <span x-text="editJudul" class="font-bold"></span>
                            </p>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-6 py-2 text-xs font-bold rounded-lg transition shadow-sm"
                                    :class="isEdit
                                        ? 'bg-amber-500 hover:bg-amber-600 text-white'
                                        : 'bg-indigo-600 hover:bg-indigo-700 text-white'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Publikasikan'"></span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>


    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">

        
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Kelola Berita & Pengumuman</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tambah, edit, atau hapus konten yang tampil di website resmi.</p>
            </div>
            <button type="button" @click="openAdd()"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Berita
            </button>
        </div>

        
        <div class="grid grid-cols-4 gap-2 mb-4">
            <?php $__currentLoopData = [
                ['label' => 'Total',   'value' => $beritaStats['total'],   'color' => 'slate'],
                ['label' => 'Aktif',   'value' => $beritaStats['aktif'],   'color' => 'green'],
                ['label' => 'Draf',    'value' => $beritaStats['draf'],    'color' => 'amber'],
                ['label' => 'Penting', 'value' => $beritaStats['penting'], 'color' => 'red'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-<?php echo e($s['color']); ?>-50 border border-<?php echo e($s['color']); ?>-200 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-<?php echo e($s['color']); ?>-700"><?php echo e($s['value']); ?></p>
                <p class="text-xs text-<?php echo e($s['color']); ?>-600 mt-0.5"><?php echo e($s['label']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <form method="GET" action="<?php echo e(route('admin.kelola-website')); ?>" class="flex flex-wrap gap-2 mb-4">
            <input type="hidden" name="tab" value="berita">
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul..."
                       class="rounded-lg border-slate-300 text-xs py-1.5 pl-8 pr-3 w-44 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="status" class="rounded-lg border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="aktif"  <?php if(request('status') === 'aktif'): echo 'selected'; endif; ?>>Aktif</option>
                <option value="draf"   <?php if(request('status') === 'draf'): echo 'selected'; endif; ?>>Draf</option>
            </select>
            <select name="kategori" class="rounded-lg border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                <option value="berita"      <?php if(request('kategori') === 'berita'): echo 'selected'; endif; ?>>Berita</option>
                <option value="pengumuman"  <?php if(request('kategori') === 'pengumuman'): echo 'selected'; endif; ?>>Pengumuman</option>
            </select>
            <button type="submit"
                    class="px-3 py-1.5 bg-slate-700 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 transition">
                Filter
            </button>
            <?php if(request()->hasAny(['search','status','kategori'])): ?>
                <a href="<?php echo e(route('admin.kelola-website', ['tab'=>'berita'])); ?>"
                   class="px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-200 transition">
                   Reset
                </a>
            <?php endif; ?>
        </form>

        
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Judul & Media</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition-colors"
                        :class="editId === <?php echo e($item->id); ?> && isEdit ? 'bg-amber-50 ring-1 ring-inset ring-amber-200' : ''">

                        
                        <td class="px-4 py-3 text-xs">
                            <div class="flex items-center gap-3">

                                <div class="w-14 h-10 rounded-lg overflow-hidden shrink-0 bg-slate-100 relative border border-slate-200">
                                    <?php if($item->media_tipe === 'photo' && $item->media_file): ?>
                                        <img src="<?php echo e($item->media_file_url); ?>" alt="<?php echo e($item->judul); ?>" class="w-full h-full object-cover">

                                    <?php elseif($item->media_tipe === 'video' && $item->media_file): ?>
                                        <?php if($item->media_thumbnail_url): ?>
                                            <img src="<?php echo e($item->media_thumbnail_url); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-purple-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M15 10l4.553-2.277A1 1 0 0121 8.677V15.32a1 1 0 01-1.447.894L15 14v-4z"/>
                                                    <path d="M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-5 h-5 rounded-full bg-black/50 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>

                                    <?php elseif($item->media_tipe === 'link_youtube'): ?>
                                        <?php if($item->media_thumbnail_url): ?>
                                            <img src="<?php echo e($item->media_thumbnail_url); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-red-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-5 h-5 rounded-full bg-red-600/80 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>

                                    <?php elseif($item->media_tipe === 'link_facebook'): ?>
                                        <?php if($item->media_thumbnail_url): ?>
                                            <img src="<?php echo e($item->media_thumbnail_url); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-blue-600 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white/80" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-5 h-5 rounded-full bg-black/40 flex items-center justify-center">
                                                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-slate-800 line-clamp-1 max-w-xs"><?php echo e($item->judul); ?></p>
                                    <div class="flex flex-wrap items-center gap-1 mt-0.5">
                                        <?php if($item->is_penting): ?>
                                            <span class="text-red-500 text-xs font-semibold">● Penting</span>
                                        <?php endif; ?>
                                        <?php if($item->has_media): ?>
                                            <?php
                                                $badgeClass = match($item->media_tipe) {
                                                    'photo'         => 'bg-blue-100 text-blue-700',
                                                    'video'         => 'bg-purple-100 text-purple-700',
                                                    'link_youtube'  => 'bg-red-100 text-red-700',
                                                    'link_facebook' => 'bg-indigo-100 text-indigo-700',
                                                    default         => 'bg-slate-100 text-slate-500',
                                                };
                                            ?>
                                            <span class="px-1.5 py-0.5 text-xs rounded-md font-medium <?php echo e($badgeClass); ?>">
                                                <?php echo e($item->media_tipe_label); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td class="px-4 py-3 whitespace-nowrap">
                            <?php if($item->kategori === 'pengumuman'): ?>
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">📢 Pengumuman</span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">📰 Berita</span>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-500">
                            <?php echo e($item->tanggal_publish?->format('d M Y') ?? '-'); ?>

                        </td>

                        
                        <td class="px-4 py-3 whitespace-nowrap">
                            <?php echo $item->status_badge; ?>

                        </td>

                        
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="inline-flex items-center gap-1">

                                
                                <?php if($item->status === 'aktif'): ?>
                                    <a href="<?php echo e(route('website.berita.show', $item->slug)); ?>" target="_blank"
                                       title="Lihat di website"
                                       class="p-1.5 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>

                                
                                <button type="button"
                                        @click="openEdit(
                                            <?php echo e($item->id); ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->judul)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->ringkasan ?? '')->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->isi ?? '')->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->kategori)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->status)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->tanggal_publish?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'))->toHtml() ?>,
                                            <?php echo e($item->is_penting ? 'true' : 'false'); ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->media_tipe ?? 'none')->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->media_link ?? '')->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->media_file_url ?? '')->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($item->media_thumbnail_url ?? '')->toHtml() ?>
                                        )"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition"
                                        :class="editId === <?php echo e($item->id); ?> && isEdit
                                            ? 'bg-amber-400 text-white'
                                            : 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100'">
                                    ✏️ Edit
                                </button>

                                
                                <form action="<?php echo e(route('admin.berita.toggle-status', $item)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit"
                                            class="px-2.5 py-1.5 rounded-lg transition text-xs font-bold
                                                <?php echo e($item->status === 'aktif'
                                                    ? 'bg-amber-50 text-amber-600 hover:bg-amber-100'
                                                    : 'bg-green-50 text-green-600 hover:bg-green-100'); ?>">
                                        <?php echo e($item->status === 'aktif' ? 'Draf' : 'Aktifkan'); ?>

                                    </button>
                                </form>

                                
                                <form action="<?php echo e(route('admin.berita.destroy', $item)); ?>" method="POST" class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus berita ini?\nTindakan tidak bisa dibatalkan.')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="px-2.5 py-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition text-xs font-bold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-400">Belum ada berita atau pengumuman.</p>
                                <button type="button" @click="openAdd()"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($beritas->hasPages()): ?>
            <div class="mt-4 text-xs"><?php echo e($beritas->links()); ?></div>
        <?php endif; ?>

    </div>


    
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-emerald-800">Pratinjau Halaman Publik</p>
            <p class="text-xs text-emerald-600 mt-0.5">Lihat tampilan halaman berita seperti yang dilihat pengunjung.</p>
        </div>
        <a href="<?php echo e(route('website.berita')); ?>" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition">
            Buka Website →
        </a>
    </div>

</div>



<script>
function beritaTab() {
    return {
        // ── State ──
        formOpen:  false,
        isEdit:    false,
        editId:    null,
        editJudul: '',

        // ── Media state ──
        mediaTipe:            'none',
        mediaLinkValue:       '',
        existingFileUrl:      '',
        existingThumbnailUrl: '',
        photoPreview:         null,
        videoPreview:         null,
        youtubeThumbnail:     null,

        // ── Opsi tipe media ──
        mediaOptions: [
            { value: 'none',          icon: '🚫', label: 'Tanpa Media' },
            { value: 'photo',         icon: '🖼️',  label: 'Foto' },
            { value: 'video',         icon: '🎥',  label: 'Video' },
            { value: 'link_youtube',  icon: '▶️',  label: 'YouTube' },
            { value: 'link_facebook', icon: '📘', label: 'Facebook' },
        ],

        // ── Init: restore jika ada error validasi server ──
        init() {
            <?php if($errors->any()): ?>
                this.formOpen = true;
                <?php if(old('media_tipe')): ?>
                    this.mediaTipe = <?php echo \Illuminate\Support\Js::from(old('media_tipe'))->toHtml() ?>;
                    <?php if(old('media_link')): ?>
                        this.mediaLinkValue = <?php echo \Illuminate\Support\Js::from(old('media_link'))->toHtml() ?>;
                        this.$nextTick(() => {
                            if (<?php echo \Illuminate\Support\Js::from(old('media_tipe'))->toHtml() ?> === 'link_youtube') {
                                this.previewYoutube(<?php echo \Illuminate\Support\Js::from(old('media_link'))->toHtml() ?>);
                            }
                        });
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        },

        // ── Buka form mode Tambah ──
        openAdd() {
            this.resetForm();
            this.isEdit   = false;
            this.formOpen = true;
            this.scrollToPanel();
        },

        // ── Buka form mode Edit ──
        openEdit(id, judul, ringkasan, isi, kategori, status, tanggal, isPenting,
                 mediaTipe, mediaLink, mediaFileUrl, mediaThumbnailUrl) {

            this.resetForm();

            this.isEdit    = true;
            this.editId    = id;
            this.editJudul = judul;
            this.formOpen  = true;

            this.$nextTick(() => {
                this.setVal('f-judul',     judul);
                this.setVal('f-ringkasan', ringkasan);
                this.setVal('f-isi',       isi);
                this.setVal('f-kategori',  kategori);
                this.setVal('f-status',    status);
                this.setVal('f-tanggal',   tanggal);

                const pEl = document.getElementById('f-penting');
                if (pEl) pEl.checked = isPenting;

                this.mediaTipe            = mediaTipe       || 'none';
                this.mediaLinkValue       = mediaLink        || '';
                this.existingFileUrl      = mediaFileUrl     || '';
                this.existingThumbnailUrl = mediaThumbnailUrl || '';

                // Preview thumbnail YouTube jika mode edit link_youtube
                this.$nextTick(() => {
                    if (mediaTipe === 'link_youtube' && mediaLink) {
                        this.previewYoutube(mediaLink);
                    }
                });

                const form = document.getElementById('berita-form');
                if (form) form.action = '<?php echo e(url('admin/berita')); ?>/' + id;

                const methodInput = document.getElementById('f-method');
                if (methodInput) methodInput.value = 'PATCH';
            });

            this.scrollToPanel();
        },

        // ── Toggle panel ──
        toggleForm() {
            this.formOpen = !this.formOpen;
            if (this.formOpen) this.scrollToPanel();
        },

        // ── Batal ──
        cancelForm() {
            this.formOpen = false;
            setTimeout(() => this.resetForm(), 350);
        },

        // ── Reset semua field & state ──
        resetForm() {
            this.isEdit               = false;
            this.editId               = null;
            this.editJudul            = '';
            this.mediaTipe            = 'none';
            this.mediaLinkValue       = '';
            this.existingFileUrl      = '';
            this.existingThumbnailUrl = '';
            this.photoPreview         = null;
            this.videoPreview         = null;
            this.youtubeThumbnail     = null;

            ['f-judul', 'f-ringkasan', 'f-isi'].forEach(id => this.setVal(id, ''));
            this.setVal('f-kategori', 'berita');
            this.setVal('f-status',   'draf');
            this.setVal('f-tanggal',  new Date().toISOString().slice(0, 16));

            const pEl = document.getElementById('f-penting');
            if (pEl) pEl.checked = false;

            ['f-media-photo', 'f-media-video', 'f-thumbnail'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

            const form = document.getElementById('berita-form');
            if (form) form.action = '<?php echo e(route('admin.berita.store')); ?>';
            const methodInput = document.getElementById('f-method');
            if (methodInput) methodInput.value = 'POST';
        },

        // ── Ganti tipe media — reset link & preview ──
        onTipeChange() {
            this.photoPreview     = null;
            this.videoPreview     = null;
            this.youtubeThumbnail = null;
            this.existingFileUrl  = '';
            this.mediaLinkValue   = '';
        },

        // ── Preview foto ──
        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) this.photoPreview = URL.createObjectURL(file);
        },

        // ── Preview video ──
        previewVideo(event) {
            const file = event.target.files[0];
            if (file) this.videoPreview = URL.createObjectURL(file);
        },

        // ── Preview thumbnail YouTube ──
        previewYoutube(url) {
            if (!url) { this.youtubeThumbnail = null; return; }
            const patterns = [
                /youtu\.be\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
            ];
            for (const p of patterns) {
                const m = url.match(p);
                if (m) {
                    this.youtubeThumbnail = `https://img.youtube.com/vi/${m[1]}/hqdefault.jpg`;
                    return;
                }
            }
            this.youtubeThumbnail = null;
        },

        // ── Helper: set value ke elemen by id ──
        setVal(id, val) {
            const el = document.getElementById(id);
            if (el) el.value = val ?? '';
        },

        // ── Scroll ke panel form ──
        scrollToPanel() {
            this.$nextTick(() => {
                const panel = document.getElementById('berita-panel');
                if (panel) panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },
    };
}
</script><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/kelola-website/tabs/berita.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Data Diri Siswa'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $s = $user->siswa;
    // Normalisasi penerima_kps: DB simpan 0/1 integer, tampilkan sebagai Ya/Tidak
    $penerimaKpsVal = ($s?->penerima_kps == 1 || $s?->penerima_kps === 'Ya') ? 'Ya' : 'Tidak';
?>


<?php if(session('success')): ?>
    <div class="px-3 py-2 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs mb-4 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if(session('error') || $errors->any()): ?>
    <div class="px-3 py-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs mb-4">
        <?php if(session('error')): ?>
            <p><?php echo e(session('error')); ?></p>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <ul class="list-disc list-inside mt-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>


<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Profil Akun</h3>
            <button onclick="bukaModal('modalAkun')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>

        <div class="flex items-center gap-3">
            <?php if($user->photo): ?>
                <img src="<?php echo e(Storage::url($user->photo)); ?>" alt="Foto"
                     class="w-14 h-[74px] object-cover rounded-lg border border-slate-200 shrink-0">
            <?php else: ?>
                <div class="w-14 h-[74px] rounded-lg bg-indigo-100 flex items-center justify-center
                            text-indigo-600 text-2xl font-bold shrink-0">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
            <?php endif; ?>
            <div class="min-w-0 space-y-1.5">
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">Nama Akun</p>
                    <p class="text-sm font-semibold text-slate-800 truncate"><?php echo e($user->name); ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">Email</p>
                    <p class="text-xs text-slate-600 truncate"><?php echo e($user->email); ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">NIS / NIDN</p>
                    <p class="text-xs text-slate-600"><?php echo e($s?->nidn ?? '—'); ?></p>
                </div>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100">
            <p class="text-[10px] text-slate-400 font-medium mb-0.5">Kelas</p>
            <?php if($s?->kelas): ?>
                <p class="text-xs font-semibold text-indigo-700"><?php echo e($s->kelas->nama); ?></p>
            <?php else: ?>
                <p class="text-xs text-slate-400 italic">Belum terdaftar di kelas</p>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Identitas Akademik</h3>
            <button onclick="bukaModal('modalIdentitas')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            <?php $__currentLoopData = [
                ['NIS / NIDN',    $s?->nidn      ?? '—'],
                ['Kelas',         $s?->kelas?->nama ?? '—'],
                ['Nama Lengkap',  $s?->nama      ?? '—'],
                ['NIK',           $s?->nik       ?? '—'],
                ['SKHUN',         $s?->shkun     ?? '—'],
                ['No. Telepon',   $s?->no_telp   ?? '—'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <p class="text-[10px] text-slate-400 font-medium"><?php echo e($lbl); ?></p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="<?php echo e($val); ?>"><?php echo e($val); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data Pribadi</h3>
            <button onclick="bukaModal('modalPribadi')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>
        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
            <?php $__currentLoopData = [
                ['Jenis Kelamin', $s?->jk === 'L' ? 'Laki-laki' : ($s?->jk === 'P' ? 'Perempuan' : '—')],
                ['Agama',         $s?->agama        ?? '—'],
                ['Tempat Lahir',  $s?->tempat_lahir ?? '—'],
                ['Tanggal Lahir', $s?->tgl_lahir?->translatedFormat('d F Y') ?? '—'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <p class="text-[10px] text-slate-400 font-medium"><?php echo e($lbl); ?></p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="<?php echo e($val); ?>"><?php echo e($val); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat & Bantuan</h3>
            <button onclick="bukaModal('modalAlamat')"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                           text-xs font-semibold hover:bg-indigo-100 transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
        </div>

        <div class="mb-2.5">
            <p class="text-[10px] text-slate-400 font-medium mb-0.5">Alamat</p>
            <p class="text-xs text-slate-700 line-clamp-2"><?php echo e($s?->alamat ?? '—'); ?></p>
        </div>

        <div class="grid grid-cols-2 gap-x-4 gap-y-2.5">
            <?php $__currentLoopData = [
                ['RT / RW',       ($s?->rt ?? '—') . ' / ' . ($s?->rw ?? '—')],
                ['Dusun',         $s?->dusun              ?? '—'],
                ['Kecamatan',     $s?->kecamatan          ?? '—'],
                ['Kode Pos',      $s?->kode_pos           ?? '—'],
                ['Jenis Tinggal', $s?->jenis_tinggal      ?? '—'],
                ['Transportasi',  $s?->jalan_transportasi ?? '—'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <p class="text-[10px] text-slate-400 font-medium"><?php echo e($lbl); ?></p>
                <p class="text-xs text-slate-700 font-medium mt-0.5 truncate" title="<?php echo e($val); ?>"><?php echo e($val); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2">
            <p class="text-[10px] text-slate-400 font-medium">Penerima KPS:</p>
            <?php if($penerimaKpsVal === 'Ya'): ?>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">Ya</span>
                <?php if($s?->no_kps): ?>
                    <p class="text-xs text-slate-600 font-medium"><?php echo e($s->no_kps); ?></p>
                <?php endif; ?>
            <?php else: ?>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">Tidak</span>
            <?php endif; ?>
        </div>
    </div>

</div>



<div id="modalAkun"
     onclick="if(event.target===this)tutupModal('modalAkun')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Profil Akun</h4>
            <button onclick="tutupModal('modalAkun')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?php echo e(route('siswa.profil.update')); ?>" method="POST"
              enctype="multipart/form-data" class="p-5 space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="akun">

            
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-16 h-[85px] border-2 border-dashed border-slate-300 rounded-xl
                            flex items-center justify-center overflow-hidden cursor-pointer
                            hover:border-indigo-400 transition-colors"
                     onclick="document.getElementById('akun_photoInput').click()">
                    <div id="akun_photoPreviewWrap" class="w-full h-full">
                        <?php if($user->photo): ?>
                            <img src="<?php echo e(Storage::url($user->photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-2xl">📸</div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="photo" id="akun_photoInput" accept="image/*" class="hidden">
                </div>
                <div class="text-xs text-slate-500 leading-relaxed">
                    Klik foto untuk ganti.<br>
                    Maks <strong>2 MB</strong> · JPG, PNG, WebP
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Nama Akun <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name"
                           value="<?php echo e(old('name', $user->name)); ?>" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email"
                           value="<?php echo e(old('email', $user->email)); ?>" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Password Baru</label>
                    <input type="password" name="password"
                           placeholder="Kosongkan jika tidak diubah"
                           autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalAkun')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs
                               font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs
                               font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>



<div id="modalIdentitas"
     onclick="if(event.target===this)tutupModal('modalIdentitas')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Identitas Akademik</h4>
            <button onclick="tutupModal('modalIdentitas')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <form action="<?php echo e(route('siswa.profil.update')); ?>" method="POST" class="p-5 space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="identitas">

            
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama"
                       value="<?php echo e(old('nama', $s?->nama ?? $user->name)); ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <div class="grid grid-cols-2 gap-3">
                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">NIS / NIDN</label>
                    <input type="text" name="nidn"
                           value="<?php echo e(old('nidn', $s?->nidn)); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kelas</label>
                    <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2
                                text-sm text-slate-700 font-medium flex items-center gap-2 min-h-[38px]">
                        <?php if($s?->kelas): ?>
                            <svg class="w-3.5 h-3.5 text-indigo-400 shrink-0" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422A12.083 12.083 0
                                         0121 13c0 5.523-4.477 10-9 10S3 18.523 3 13c0-.538.04-1.066.118-1.578L12 14z"/>
                            </svg>
                            <span><?php echo e($s->kelas->nama); ?></span>
                        <?php else: ?>
                            <span class="text-slate-400 italic text-xs">Belum ada (diatur admin)</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-[10px] text-amber-500 mt-1">⚠ Kelas hanya dapat diubah oleh admin</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">NIK</label>
                    <input type="text" name="nik"
                           value="<?php echo e(old('nik', $s?->nik)); ?>" maxlength="20"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Kelamin</label>
                    <select name="jk"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <option value="L" <?php echo e(old('jk', $s?->jk) === 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                        <option value="P" <?php echo e(old('jk', $s?->jk) === 'P' ? 'selected' : ''); ?>>Perempuan</option>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">SKHUN</label>
                    <input type="text" name="shkun"
                           value="<?php echo e(old('shkun', $s?->shkun)); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">No. Telepon</label>
                    <input type="text" name="no_telp"
                           value="<?php echo e(old('no_telp', $s?->no_telp)); ?>" maxlength="20"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalIdentitas')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs
                               font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs
                               font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>



<div id="modalPribadi"
     onclick="if(event.target===this)tutupModal('modalPribadi')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50">
            <h4 class="text-sm font-bold text-slate-700">Edit Data Pribadi</h4>
            <button onclick="tutupModal('modalPribadi')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?php echo e(route('siswa.profil.update')); ?>" method="POST" class="p-5 space-y-3">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="pribadi">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Kelamin</label>
                    <select name="jk"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <option value="L" <?php echo e(old('jk', $s?->jk) === 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                        <option value="P" <?php echo e(old('jk', $s?->jk) === 'P' ? 'selected' : ''); ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Agama</label>
                    <select name="agama"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <?php $__currentLoopData = ['Islam','Kristen','Katholik','Hindu','Buddha','Konghucu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ag); ?>"
                                <?php echo e(old('agama', $s?->agama) === $ag ? 'selected' : ''); ?>>
                                <?php echo e($ag); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir"
                           value="<?php echo e(old('tempat_lahir', $s?->tempat_lahir)); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir"
                           value="<?php echo e(old('tgl_lahir', $s?->tgl_lahir?->format('Y-m-d'))); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalPribadi')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs
                               font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs
                               font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>



<div id="modalAlamat"
     onclick="if(event.target===this)tutupModal('modalAlamat')"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.45);backdrop-filter:blur(4px)">
    <div class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50 sticky top-0 z-10">
            <h4 class="text-sm font-bold text-slate-700">Edit Alamat & Bantuan</h4>
            <button onclick="tutupModal('modalAlamat')"
                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?php echo e(route('siswa.profil.update')); ?>" method="POST" class="p-5 space-y-3">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="_section" value="alamat">

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat</label>
                <textarea name="alamat" rows="2"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none"><?php echo e(old('alamat', $s?->alamat)); ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">RT</label>
                    <input type="text" name="rt"
                           value="<?php echo e(old('rt', $s?->rt)); ?>" maxlength="10"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">RW</label>
                    <input type="text" name="rw"
                           value="<?php echo e(old('rw', $s?->rw)); ?>" maxlength="10"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Dusun</label>
                    <input type="text" name="dusun"
                           value="<?php echo e(old('dusun', $s?->dusun)); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kecamatan</label>
                    <input type="text" name="kecamatan"
                           value="<?php echo e(old('kecamatan', $s?->kecamatan)); ?>"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kode Pos</label>
                    <input type="text" name="kode_pos"
                           value="<?php echo e(old('kode_pos', $s?->kode_pos)); ?>" maxlength="10"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Tinggal</label>
                    <select name="jenis_tinggal"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <?php $__currentLoopData = ['Bersama Orang Tua','Wali','Kos','Asrama','Panti Asuhan','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($jt); ?>"
                                <?php echo e(old('jenis_tinggal', $s?->jenis_tinggal) === $jt ? 'selected' : ''); ?>>
                                <?php echo e($jt); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Alat Transportasi</label>
                    <select name="jalan_transportasi"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— Pilih —</option>
                        <?php $__currentLoopData = ['Jalan Kaki','Sepeda','Sepeda Motor','Mobil Pribadi','Angkutan Umum','Antar Jemput','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tr); ?>"
                                <?php echo e(old('jalan_transportasi', $s?->jalan_transportasi) === $tr ? 'selected' : ''); ?>>
                                <?php echo e($tr); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            
            <div class="pt-2 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Informasi Bantuan</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Penerima KPS</label>
                        <select name="penerima_kps" id="modal_penerimaKps"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="Tidak"
                                <?php echo e(old('penerima_kps', $penerimaKpsVal) === 'Tidak' ? 'selected' : ''); ?>>
                                Tidak
                            </option>
                            <option value="Ya"
                                <?php echo e(old('penerima_kps', $penerimaKpsVal) === 'Ya' ? 'selected' : ''); ?>>
                                Ya
                            </option>
                        </select>
                    </div>
                    <div id="modal_noKpsGroup"
                         class="<?php echo e(old('penerima_kps', $penerimaKpsVal) !== 'Ya' ? 'opacity-40 pointer-events-none' : ''); ?>">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">No. KPS</label>
                        <input type="text" name="no_kps"
                               value="<?php echo e(old('no_kps', $s?->no_kps)); ?>" maxlength="50"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" onclick="tutupModal('modalAlamat')"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-xs
                               font-semibold hover:bg-slate-50 transition">Batal</button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs
                               font-semibold hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
/* ── Modal helpers ── */
function bukaModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function tutupModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
}

/* Tutup modal dengan tombol Escape */
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        ['modalAkun', 'modalIdentitas', 'modalPribadi', 'modalAlamat'].forEach(tutupModal);
    }
});

/* ── Toggle No. KPS ── */
const kpsSelect = document.getElementById('modal_penerimaKps');
const kpsGroup  = document.getElementById('modal_noKpsGroup');
if (kpsSelect && kpsGroup) {
    kpsSelect.addEventListener('change', function () {
        if (this.value === 'Ya') {
            kpsGroup.classList.remove('opacity-40', 'pointer-events-none');
        } else {
            kpsGroup.classList.add('opacity-40', 'pointer-events-none');
        }
    });
}

/* ── Preview foto sebelum upload ── */
const photoInput = document.getElementById('akun_photoInput');
if (photoInput) {
    photoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrap = document.getElementById('akun_photoPreviewWrap');
            wrap.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
        };
        reader.readAsDataURL(file);
    });
}

/* ── Buka modal otomatis jika ada error validasi ── */
<?php if($errors->any()): ?>
    <?php $section = old('_section', '') ?>
    <?php if($section === 'akun'): ?>
        bukaModal('modalAkun');
    <?php elseif($section === 'identitas'): ?>
        bukaModal('modalIdentitas');
    <?php elseif($section === 'pribadi'): ?>
        bukaModal('modalPribadi');
    <?php elseif($section === 'alamat'): ?>
        bukaModal('modalAlamat');
    <?php endif; ?>
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/siswa/profil.blade.php ENDPATH**/ ?>
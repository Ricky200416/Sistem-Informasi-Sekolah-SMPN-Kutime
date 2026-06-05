<?php $__env->startSection('title', 'Edit Profil Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-lg">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm
                border border-slate-200 dark:border-slate-700 p-4">

        
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4">
            Edit Profil Admin
        </h2>

        <form action="<?php echo e(route('admin.profil.update')); ?>" method="POST"
              enctype="multipart/form-data" class="space-y-3.5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div>
                <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400
                           uppercase tracking-wide mb-1.5">Foto Profil</p>
                <div class="flex items-center gap-3">
                    
                    <?php if($user->photo): ?>
                        <img src="<?php echo e(Storage::url($user->photo)); ?>" alt=""
                             class="w-10 h-10 rounded-xl object-cover
                                    border border-slate-200 dark:border-slate-600 shrink-0">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40
                                    flex items-center justify-center
                                    text-indigo-600 dark:text-indigo-400
                                    text-sm font-bold shrink-0">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>
                    <div class="flex-1 min-w-0">
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="block text-xs text-slate-500 dark:text-slate-400 w-full
                                      file:mr-2 file:py-1 file:px-2.5
                                      file:rounded-lg file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG — maks. 2MB</p>
                    </div>
                </div>
                <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="border-t border-slate-100 dark:border-slate-700"></div>

            
            <div>
                <label for="name"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="<?php echo e(old('name', $user->name)); ?>" required
                       class="w-full px-3 py-2 rounded-xl border text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 dark:bg-red-900/20
                              <?php else: ?> border-slate-200 dark:border-slate-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="email"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email"
                       value="<?php echo e(old('email', $user->email)); ?>" required
                       class="w-full px-3 py-2 rounded-xl border text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 dark:bg-red-900/20
                              <?php else: ?> border-slate-200 dark:border-slate-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="border-t border-slate-100 dark:border-slate-700 pt-0.5">
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500
                           uppercase tracking-wide mb-3">Ubah Password</p>
            </div>

            
            <div>
                <label for="password"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Password Baru
                    <span class="normal-case font-normal text-[10px] text-slate-400">
                        (kosongkan jika tidak diubah)
                    </span>
                </label>
                <input type="password" name="password" id="password"
                       placeholder="Min. 8 karakter"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200
                              dark:border-slate-600 text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-[10px] text-red-500 mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="password_confirmation"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       placeholder="Ulangi password baru"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200
                              dark:border-slate-600 text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
            </div>

            
            <div class="flex gap-2 pt-1">
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                               hover:bg-indigo-700 active:scale-95 transition">
                    Simpan Perubahan
                </button>
                <a href="<?php echo e(route('admin.profil')); ?>"
                   class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                          text-slate-600 dark:text-slate-400 text-xs font-medium
                          hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/profil-edit.blade.php ENDPATH**/ ?>
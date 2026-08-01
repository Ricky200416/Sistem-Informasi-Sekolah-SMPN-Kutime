
<div id="modalTambahUser"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog" aria-modal="true" aria-labelledby="titleTambahUser">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        onclick="closeModal('modalTambahUser')"></div>

    
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-2xl
                max-h-[92vh] flex flex-col transition-all duration-200">

        
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b
                    border-slate-100 dark:border-slate-700 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50
                            flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018
                               0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h2 id="titleTambahUser"
                        class="text-base font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        Tambah User Baru
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Buat akun login guru atau siswa</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambahUser')"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600
                       hover:bg-slate-100 dark:hover:bg-slate-700 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        
        <div class="overflow-y-auto px-6 py-5 flex-1 space-y-4">

            <div class="flex items-start gap-2.5 px-3 py-2.5 bg-indigo-50 dark:bg-indigo-950/30
                        border border-indigo-100 dark:border-indigo-800 rounded-xl
                        text-xs text-indigo-700 dark:text-indigo-400">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0
                           012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0
                           00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span>Profil lengkap dapat diperbarui melalui tombol Edit setelah akun dibuat.</span>
            </div>

            <form id="formTambahUser"
                  action="<?php echo e(route('admin.users.store')); ?>"
                  method="POST"
                  class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_form_context" value="tambah">

                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Role Pengguna <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        
                        <label id="roleCardGuru"
                            class="role-card flex items-center gap-3 px-4 py-2.5 rounded-xl
                                   border-2 cursor-pointer transition-all
                                   border-indigo-500 bg-indigo-50 dark:bg-indigo-950/30
                                   dark:border-indigo-500">
                            <input type="radio" name="role" value="guru" class="sr-only" checked>
                            <div class="rc-icon w-8 h-8 rounded-lg bg-indigo-100
                                        dark:bg-indigo-900/60 flex items-center
                                        justify-center shrink-0">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0
                                             00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="rc-title text-xs font-bold text-indigo-700
                                          dark:text-indigo-400 uppercase tracking-wide">Guru</p>
                                <p class="rc-sub text-[11px] text-indigo-400">Tenaga pengajar</p>
                            </div>
                        </label>

                        
                        <label id="roleCardSiswa"
                            class="role-card flex items-center gap-3 px-4 py-2.5 rounded-xl
                                   border-2 cursor-pointer transition-all
                                   border-slate-200 dark:border-slate-600
                                   bg-white dark:bg-slate-700/30">
                            <input type="radio" name="role" value="siswa" class="sr-only">
                            <div class="rc-icon w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700
                                        flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5
                                             5S4.168 5.477 3 6.253v13C4.168 18.477 5.754
                                             18 7.5 18s3.332.477 4.5 1.253m0-13C13.168
                                             5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5
                                             1.253v13C19.832 18.477 18.247 18 16.5
                                             18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <p class="rc-title text-xs font-bold text-slate-600
                                          dark:text-slate-300 uppercase tracking-wide">Siswa</p>
                                <p class="rc-sub text-[11px] text-slate-400">Peserta didik</p>
                            </div>
                        </label>
                    </div>
                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-700 my-2"></div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3.5">
                    
                    
                    <div>
                        <label for="inp_name"
                               class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="inp_name"
                               value="<?php echo e(old('name')); ?>" required
                               placeholder="Nama lengkap pengguna"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-sm bg-white dark:bg-slate-900
                                      text-slate-700 dark:text-slate-300
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition
                                      <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="inp_email"
                               class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="inp_email"
                               value="<?php echo e(old('email')); ?>" required
                               placeholder="contoh@sekolah.sch.id"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                      px-3 py-2 text-sm bg-white dark:bg-slate-900
                                      text-slate-700 dark:text-slate-300
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 transition
                                      <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="pwdNew"
                               class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="pwdNew"
                                   required placeholder="Min. 6 karakter"
                                   class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                          px-3 py-2 pr-10 text-sm bg-white dark:bg-slate-900
                                          text-slate-700 dark:text-slate-300
                                          focus:outline-none focus:ring-2 focus:ring-indigo-300 transition
                                          <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <button type="button" onclick="togglePwd('pwdNew')" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-slate-400 hover:text-slate-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                             9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943
                                             -9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="pwdConfirm"
                               class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pwdConfirm"
                                   required placeholder="Ulangi password"
                                   class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                          px-3 py-2 pr-10 text-sm bg-white dark:bg-slate-900
                                          text-slate-700 dark:text-slate-300
                                          focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                            <button type="button" onclick="togglePwd('pwdConfirm')" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-slate-400 hover:text-slate-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                             9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943
                                             -9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    
                    <div id="kelasFieldWrap" class="hidden md:col-span-2">
                        <label for="inp_kelas"
                               class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Penempatan Kelas <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>

                        <?php if(isset($kelasList) && $kelasList->count()): ?>
                            <select name="kelas_id" id="inp_kelas"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                           px-3 py-2 text-sm bg-white dark:bg-slate-900
                                           text-slate-700 dark:text-slate-300
                                           focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                                <option value="">— Pilih Kelas (opsional) —</option>
                                <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kelas->id); ?>"
                                        <?php echo e(old('kelas_id') == $kelas->id ? 'selected' : ''); ?>>
                                        <?php echo e($kelas->name); ?>

                                        <?php if($kelas->grade): ?>
                                            — Kelas <?php echo e($kelas->grade); ?><?php echo e($kelas->section ? ' '.$kelas->section : ''); ?>

                                        <?php endif; ?>
                                        <?php if($kelas->academic_year): ?>
                                            (<?php echo e($kelas->academic_year); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php else: ?>
                            <div class="flex items-start gap-2 px-3 py-2.5 bg-amber-50 dark:bg-amber-950/30
                                        border border-amber-200 dark:border-amber-800 rounded-xl
                                        text-xs text-amber-700 dark:text-amber-400">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                                           001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                                <span>Belum ada data kelas pada sistem. Buat terlebih dahulu di 
                                    <a href="<?php echo e(route('admin.kelas.index')); ?>"
                                       class="font-semibold underline" target="_blank">
                                        Kelola Kelas
                                    </a>.
                                </span>
                            </div>
                            <input type="hidden" name="kelas_id" value="">
                        <?php endif; ?>

                        <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                </div>

            </form>
        </div>

        
        <div class="flex gap-2 px-6 py-4 border-t border-slate-100 dark:border-slate-700
                    bg-slate-50/50 dark:bg-slate-900/20 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeModal('modalTambahUser')"
                class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                       text-slate-600 dark:text-slate-400 text-sm font-medium
                       hover:bg-white dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <button type="submit" form="formTambahUser"
                class="flex-1 px-4 py-2.5 rounded-xl bg-indigo-600 text-white
                       text-sm font-semibold hover:bg-indigo-700 active:scale-95 transition">
                Buat Akun
            </button>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    'use strict';

    const kelasWrap = document.getElementById('kelasFieldWrap');
    const roleCards = document.querySelectorAll('#formTambahUser .role-card');

    function toggleKelas(role) {
        if (!kelasWrap) return;
        kelasWrap.style.display = role === 'siswa' ? 'block' : 'none';
    }

    function styleRoleCards(selectedRole) {
        roleCards.forEach(card => {
            const input    = card.querySelector('input[type="radio"]');
            const isActive = input && input.value === selectedRole;
            const icon     = card.querySelector('.rc-icon');
            const title    = card.querySelector('.rc-title');
            const sub      = card.querySelector('.rc-sub');
            const svg      = icon?.querySelector('svg');

            if (isActive) {
                card.classList.add('border-indigo-500','bg-indigo-50',
                    'dark:bg-indigo-950/30','dark:border-indigo-500');
                card.classList.remove('border-slate-200','bg-white',
                    'dark:bg-slate-700/30','dark:border-slate-600');
                title?.classList.replace('text-slate-600','text-indigo-700');
                sub?.classList.replace('text-slate-400','text-indigo-400');
                icon?.classList.replace('bg-slate-100','bg-indigo-100');
                svg?.classList.replace('text-slate-500','text-indigo-600');
            } else {
                card.classList.remove('border-indigo-500','bg-indigo-50',
                    'dark:bg-indigo-950/30','dark:border-indigo-500');
                card.classList.add('border-slate-200','bg-white',
                    'dark:bg-slate-700/30','dark:border-slate-600');
                title?.classList.replace('text-indigo-700','text-slate-600');
                sub?.classList.replace('text-indigo-400','text-slate-400');
                icon?.classList.replace('bg-indigo-100','bg-slate-100');
                svg?.classList.replace('text-indigo-600','text-slate-500');
            }
        });
    }

    roleCards.forEach(card => {
        card.addEventListener('click', () => {
            const input = card.querySelector('input[type="radio"]');
            if (!input) return;
            input.checked = true;
            toggleKelas(input.value);
            styleRoleCards(input.value);
        });
    });

    // Init default
    const checked = document.querySelector('#formTambahUser input[name="role"]:checked');
    if (checked) {
        toggleKelas(checked.value);
        styleRoleCards(checked.value);
    }

    // Restore old value setelah validation error
    <?php if($errors->any() && old('role')): ?>
        const oldRole  = "<?php echo e(old('role')); ?>";
        const oldInput = document.querySelector(
            `#formTambahUser input[name="role"][value="${oldRole}"]`
        );
        if (oldInput) {
            oldInput.checked = true;
            toggleKelas(oldRole);
            styleRoleCards(oldRole);
        }
    <?php endif; ?>

})();

function togglePwd(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.type = el.type === 'password' ? 'text' : 'password';
}
</script>
<?php $__env->stopPush(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/users/_modal_detail.blade.php ENDPATH**/ ?>
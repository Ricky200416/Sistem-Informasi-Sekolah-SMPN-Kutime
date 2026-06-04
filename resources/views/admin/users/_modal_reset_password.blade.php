{{--
    resources/views/admin/users/_modal_reset_password.blade.php
    Partial: overlay reset password user.
--}}
<div id="modalResetPwd"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalResetPwd')"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm flex flex-col animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11
                                 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0
                                 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800" style="font-family:'Outfit',sans-serif;">
                    Reset Password
                </h2>
            </div>
            <button onclick="closeModal('modalResetPwd')"
                    class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            <p class="text-sm text-slate-500 mb-4">
                Atur ulang password untuk akun
                <strong id="resetUserName" class="text-slate-700"></strong>.
            </p>

            <form id="formResetPwd" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="resetPwdInput" class="block text-sm font-medium text-slate-700 mb-1">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="resetPwdInput"
                               required placeholder="Min. 8 karakter"
                               class="w-full rounded-xl border border-slate-200 px-3 py-2.5 pr-10 text-sm
                                      transition focus:outline-none focus:ring-2 focus:ring-amber-300">
                        <button type="button" onclick="togglePwd('resetPwdInput')" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400
                                       hover:text-slate-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542
                                         7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="resetPwdConfirm" class="block text-sm font-medium text-slate-700 mb-1">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="resetPwdConfirm"
                           required placeholder="Ulangi password"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm
                                  transition focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-2 px-6 pb-5">
            <button type="button" onclick="closeModal('modalResetPwd')"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600
                           text-sm font-medium hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="submit" form="formResetPwd"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-amber-500 text-white text-sm font-semibold
                           hover:bg-amber-600 active:scale-95 transition">
                Reset Password
            </button>
        </div>

    </div>
</div>
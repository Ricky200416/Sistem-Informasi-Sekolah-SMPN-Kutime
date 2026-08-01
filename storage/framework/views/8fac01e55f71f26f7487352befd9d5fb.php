
<div id="modalHapusAlumni"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900/50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus Data Alumni</h3>
            </div>
            <button onclick="closeModal('modalHapusAlumni')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div class="flex items-start gap-3 bg-red-50 dark:bg-red-950/30 border border-red-200
                        dark:border-red-800 rounded-xl p-3.5">
                <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-xs font-bold text-red-700 dark:text-red-400">Konfirmasi Penghapusan</p>
                    <p class="text-[11px] text-red-600 dark:text-red-500 mt-0.5 leading-relaxed">
                        Data alumni <span id="hapusAlumniNama" class="font-bold">-</span> akan dihapus permanen
                        dari arsip. Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <form id="formHapusAlumni" method="POST" action="#">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700 active:scale-95
                                   text-white py-2.5 rounded-xl text-xs font-bold transition shadow-sm">
                        Ya, Hapus Sekarang
                    </button>
                    <button type="button" onclick="closeModal('modalHapusAlumni')"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                                   text-xs font-semibold text-slate-600 dark:text-slate-400
                                   hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/alumni/_modal_hapus.blade.php ENDPATH**/ ?>
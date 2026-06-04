{{-- resources/views/admin/kelas/_modal_hapus.blade.php --}}
<div id="modalHapusKelas"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         onclick="closeModal('modalHapusKelas')"></div>

    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-modal">

        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/40
                        flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732
                             4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Hapus Kelas?</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed max-w-xs">
                Kelas <strong id="deleteKelasName" class="text-slate-700 dark:text-slate-200"></strong>
                dan seluruh jadwal terkait akan dihapus permanen.
                <span class="text-red-600 font-semibold">Data siswa tidak terhapus</span>,
                namun kelas_id mereka akan menjadi kosong.
            </p>
        </div>

        <form id="formHapusKelas" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-2">
                <button type="button" onclick="closeModal('modalHapusKelas')"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                               text-slate-600 dark:text-slate-400 text-xs font-medium
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 text-white text-xs font-semibold
                               hover:bg-red-700 active:scale-95 transition">
                    Ya, Hapus
                </button>
            </div>
        </form>

    </div>
</div>
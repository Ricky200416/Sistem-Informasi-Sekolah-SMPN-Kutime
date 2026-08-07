{{-- resources/views/admin/alumni/_modal_edit.blade.php --}}
<div id="modalEditAlumni"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center sticky top-0 bg-white dark:bg-slate-800 z-10">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Data Alumni</h3>
                    <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold" id="ea_sisaWaktu">—</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalEditAlumni')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formEditAlumni" method="POST" action="#" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nama" id="ea_nama" required
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">NISN / NIDN</label>
                    <input type="text" name="nidn" id="ea_nidn"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">NIK</label>
                    <input type="text" name="nik" id="ea_nik"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                    <select name="jk" id="ea_jk"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                   bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                   focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="">—</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Agama</label>
                    <input type="text" name="agama" id="ea_agama"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="ea_tempat_lahir"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="ea_tgl_lahir"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">No. Telepon</label>
                    <input type="text" name="no_telp" id="ea_no_telp"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Kelas Terakhir</label>
                    <input type="text" name="kelas_terakhir" id="ea_kelas_terakhir"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Alamat</label>
                    <input type="text" name="alamat" id="ea_alamat"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">RT</label>
                    <input type="text" name="rt" id="ea_rt"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">RW</label>
                    <input type="text" name="rw" id="ea_rw"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Dusun</label>
                    <input type="text" name="dusun" id="ea_dusun"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Kecamatan</label>
                    <input type="text" name="kecamatan" id="ea_kecamatan"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Tahun Lulus <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="tahun_lulus" id="ea_tahun_lulus" required min="2000" max="2100"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Tanggal Lulus <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="tanggal_lulus" id="ea_tanggal_lulus" required
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">No. Ijazah</label>
                    <input type="text" name="no_ijazah" id="ea_no_ijazah"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                  bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                  focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Catatan</label>
                    <textarea name="catatan" id="ea_catatan" rows="2"
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                     bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                     focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                </div>
            </div>

            <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                <button type="submit"
                        class="flex-1 bg-amber-500 hover:bg-amber-600 active:scale-95
                               text-white py-2 rounded-xl text-xs font-bold transition shadow-sm">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="closeModal('modalEditAlumni')"
                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                               text-xs font-semibold text-slate-600 dark:text-slate-400
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
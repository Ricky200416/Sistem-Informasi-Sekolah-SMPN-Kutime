{{-- resources/views/admin/alumni/_modal_detail.blade.php --}}
<div id="modalDetailAlumni"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center sticky top-0 bg-white dark:bg-slate-800 z-10">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 13c0
                                 5.523-4.477 10-9 10S3 18.523 3 13c0-.538.04-1.066.118-1.578L12 14z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Detail Data Alumni</h3>
            </div>
            <button type="button" onclick="closeModal('modalDetailAlumni')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">

            {{-- Foto & Nama --}}
            <div class="flex items-center gap-3">
                <div id="da_fotoWrap" class="w-14 h-[74px] rounded-lg overflow-hidden border border-slate-200 dark:border-slate-600 shrink-0"></div>
                <div class="min-w-0">
                    <p id="da_nama" class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">—</p>
                    <p class="text-[11px] text-slate-400" id="da_email">—</p>
                </div>
            </div>

            {{-- Identitas Akademik --}}
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Identitas Akademik</p>
                <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                    <div><p class="text-[10px] text-slate-400 font-medium">NISN / NIDN</p><p id="da_nidn" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">NIK</p><p id="da_nik" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">Kelas Terakhir</p><p id="da_kelas" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">No. Telepon</p><p id="da_telp" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                </div>
            </div>

            {{-- Data Pribadi --}}
            <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Data Pribadi</p>
                <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                    <div><p class="text-[10px] text-slate-400 font-medium">Jenis Kelamin</p><p id="da_jk" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">Agama</p><p id="da_agama" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div class="col-span-2"><p class="text-[10px] text-slate-400 font-medium">Tempat, Tanggal Lahir</p><p id="da_ttl" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat</p>
                <p id="da_alamat" class="text-xs text-slate-700 dark:text-slate-300 mb-2">—</p>
                <div class="grid grid-cols-3 gap-x-4 gap-y-3">
                    <div><p class="text-[10px] text-slate-400 font-medium">RT / RW</p><p id="da_rtrw" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">Dusun</p><p id="da_dusun" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">Kecamatan</p><p id="da_kecamatan" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                </div>
            </div>

            {{-- Kelulusan & Bantuan --}}
            <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Kelulusan & Bantuan</p>
                <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                    <div><p class="text-[10px] text-slate-400 font-medium">Tahun Lulus</p><p id="da_tahun" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">Tanggal Lulus</p><p id="da_tgl_lulus" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">No. Ijazah</p><p id="da_ijazah" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                    <div><p class="text-[10px] text-slate-400 font-medium">Penerima KPS</p><p id="da_kps" class="text-xs text-slate-700 dark:text-slate-300 font-medium">—</p></div>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan</p>
                <p id="da_catatan" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">—</p>
            </div>

        </div>

        <div class="px-5 py-3.5 border-t border-slate-100 dark:border-slate-700 flex justify-end">
            <button type="button" onclick="closeModal('modalDetailAlumni')"
                    class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                           text-xs font-semibold text-slate-600 dark:text-slate-400
                           hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                Tutup
            </button>
        </div>
    </div>
</div>
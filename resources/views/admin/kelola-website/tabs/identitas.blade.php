{{-- resources/views/admin/kelola-website/tabs/identitas.blade.php --}}
{{-- Ditempatkan di dalam file index.blade.php admin kelola website --}}
{{-- Panggil dengan: x-show="tab === 'identitas'" --}}

<div x-show="tab === 'identitas'" x-cloak class="space-y-4 max-w-4xl mx-auto">

    @if(session('success'))
    <div class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl">
        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @php
        $logoUrl    = \App\Models\SchoolSetting::logoUrl();
        $faviconUrl = \App\Models\SchoolSetting::faviconUrl();
        $namaSekolah   = \App\Models\SchoolSetting::get('nama_sekolah',   'SMP Negeri Kutime');
        $singkatan     = \App\Models\SchoolSetting::get('singkatan',      'SMPN Kutime');
        $taglineFooter = \App\Models\SchoolSetting::get('tagline_footer', 'Menyiapkan generasi unggul, berakhlak, dan berprestasi.');
    @endphp

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-3 bg-slate-50 border-b border-slate-100">
            <div class="w-6 h-6 rounded-md bg-amber-100 text-amber-600 flex items-center justify-center text-xs">🏫</div>
            <div>
                <p class="text-xs font-semibold text-slate-800">Identitas & Logo Sekolah</p>
                <p class="text-xs text-slate-400 mt-0.5">Logo tampil di navbar, footer, dan seluruh halaman website</p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('admin.kelola-website.update-school-settings') }}"
              enctype="multipart/form-data"
              class="p-5 space-y-5">
            @csrf @method('PATCH')

            {{-- Logo + Preview --}}
            <div class="grid sm:grid-cols-2 gap-5">

                {{-- Logo utama --}}
                <div x-data="{ prev: null }" class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700">
                        Logo Sekolah
                        <span class="font-normal text-slate-400 ml-1">(PNG/JPG/WEBP, maks 2 MB)</span>
                    </label>

                    {{-- Preview logo saat ini --}}
                    @if($logoUrl)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <img src="{{ $logoUrl }}" alt="Logo saat ini" class="h-12 w-12 object-contain rounded-lg bg-white p-1 border border-slate-200">
                        <div>
                            <p class="text-xs font-semibold text-slate-700">Logo aktif</p>
                            <p class="text-xs text-slate-400">Upload baru untuk mengganti</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                        <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-2xl">🏫</div>
                        <p class="text-xs text-slate-400">Belum ada logo. Upload logo sekolah.</p>
                    </div>
                    @endif

                    {{-- Preview upload baru --}}
                    <template x-if="prev">
                        <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-xl border border-indigo-200">
                            <img :src="prev" class="h-12 w-12 object-contain rounded-lg bg-white p-1 border border-indigo-200">
                            <p class="text-xs text-indigo-700 font-medium">Pratinjau logo baru</p>
                        </div>
                    </template>

                    <input type="file" name="logo" accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                           @change="prev = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                </div>

                {{-- Favicon --}}
                <div x-data="{ prev: null }" class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700">
                        Favicon
                        <span class="font-normal text-slate-400 ml-1">(PNG/ICO, maks 512 KB, rekomendasi 32×32)</span>
                    </label>

                    @if($faviconUrl)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <img src="{{ $faviconUrl }}" alt="Favicon saat ini" class="h-8 w-8 object-contain rounded border border-slate-200 bg-white p-0.5">
                        <div>
                            <p class="text-xs font-semibold text-slate-700">Favicon aktif</p>
                            <p class="text-xs text-slate-400">Tampil di tab browser</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                        <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-sm">🌐</div>
                        <p class="text-xs text-slate-400">Belum ada favicon.</p>
                    </div>
                    @endif

                    <template x-if="prev">
                        <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-xl border border-indigo-200">
                            <img :src="prev" class="h-8 w-8 object-contain rounded bg-white p-0.5 border border-indigo-200">
                            <p class="text-xs text-indigo-700 font-medium">Pratinjau favicon baru</p>
                        </div>
                    </template>

                    <input type="file" name="favicon" accept="image/png,image/jpeg,image/x-icon,image/vnd.microsoft.icon"
                           class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
                           @change="prev = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- Nama & Singkatan --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Sekolah Lengkap</label>
                    <input type="text" name="nama_sekolah"
                           value="{{ old('nama_sekolah', $namaSekolah) }}"
                           class="w-full rounded-xl border-slate-300 text-sm py-2 focus:border-amber-500 focus:ring-amber-400"
                           placeholder="SMP Negeri Kutime">
                    <p class="text-xs text-slate-400 mt-1">Tampil di title browser & beberapa bagian halaman</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Singkatan / Nama Pendek</label>
                    <input type="text" name="singkatan"
                           value="{{ old('singkatan', $singkatan) }}"
                           class="w-full rounded-xl border-slate-300 text-sm py-2 focus:border-amber-500 focus:ring-amber-400"
                           placeholder="SMPN Kutime">
                    <p class="text-xs text-slate-400 mt-1">Tampil di navbar dan footer</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tagline Footer</label>
                <textarea name="tagline_footer" rows="2"
                          class="w-full rounded-xl border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-400"
                          placeholder="Menyiapkan generasi unggul...">{{ old('tagline_footer', $taglineFooter) }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Kalimat pendek di bawah logo footer</p>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100">
                <button type="submit"
                        class="px-5 py-2 bg-amber-500 text-white text-xs font-semibold rounded-xl hover:bg-amber-600 transition shadow-sm">
                    Simpan Identitas Sekolah
                </button>
            </div>
        </form>
    </div>

    {{-- Pratinjau --}}
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
        <p class="text-xs font-semibold text-amber-800 mb-1">💡 Petunjuk</p>
        <ul class="text-xs text-amber-700 space-y-1 list-disc list-inside">
            <li>Logo akan langsung tampil di <strong>navbar</strong> dan <strong>footer</strong> setelah disimpan.</li>
            <li>Gunakan logo format <strong>PNG transparan</strong> untuk hasil terbaik.</li>
            <li>Favicon adalah ikon kecil di <strong>tab browser</strong> — gunakan ukuran 32×32 atau 64×64 piksel.</li>
            <li>Setelah update, tekan <strong>Ctrl+Shift+R</strong> (hard refresh) untuk melihat perubahan logo.</li>
        </ul>
    </div>

</div>
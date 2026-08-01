{{-- resources/views/admin/kelola-website/tabs/galeri.blade.php --}}
<div x-data="galeriManager()" x-cloak class="space-y-4">

    @if(session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 text-xs rounded-lg px-3 py-2">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Kelola Galeri Media</h2>
                <p class="text-xs text-slate-500 mt-0.5">Upload foto, video, atau tambahkan link YouTube/Facebook.</p>
            </div>
            <button type="button" @click="openCreate()"
               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Media
            </button>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-3 gap-2 mb-4">
            @foreach([
                ['label'=>'Total', 'value'=>$galeriStats['total'], 'color'=>'slate'],
                ['label'=>'Aktif', 'value'=>$galeriStats['aktif'], 'color'=>'green'],
                ['label'=>'Draf',  'value'=>$galeriStats['draf'],  'color'=>'amber'],
            ] as $s)
            <div class="bg-{{ $s['color'] }}-50 border border-{{ $s['color'] }}-200 rounded-lg p-2.5 text-center">
                <p class="text-lg font-bold text-{{ $s['color'] }}-700">{{ $s['value'] }}</p>
                <p class="text-xs text-{{ $s['color'] }}-600">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.kelola-website') }}" class="flex flex-wrap gap-2 mb-4">
            <input type="hidden" name="tab" value="galeri">

            <select name="galeri_kategori"
                    class="rounded-md border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($galeriKategoriOptions as $val => $label)
                    <option value="{{ $val }}" @selected(request('galeri_kategori') === $val)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="galeri_tipe"
                    class="rounded-md border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Tipe</option>
                <option value="photo"         @selected(request('galeri_tipe') === 'photo')>🖼️ Foto</option>
                <option value="video"         @selected(request('galeri_tipe') === 'video')>🎥 Video</option>
                <option value="link_youtube"  @selected(request('galeri_tipe') === 'link_youtube')>▶️ YouTube</option>
                <option value="link_facebook" @selected(request('galeri_tipe') === 'link_facebook')>📘 Facebook</option>
            </select>

            <select name="galeri_status"
                    class="rounded-md border-slate-300 text-xs py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('galeri_status') === 'aktif')>Aktif</option>
                <option value="draf"  @selected(request('galeri_status') === 'draf')>Draf</option>
            </select>

            <button type="submit"
                    class="px-3 py-1.5 bg-slate-700 text-white text-xs rounded-lg hover:bg-slate-800 transition">Filter</button>

            @if(request()->hasAny(['galeri_kategori','galeri_tipe','galeri_status']))
                <a href="{{ route('admin.kelola-website', ['tab'=>'galeri']) }}"
                   class="px-3 py-1.5 bg-slate-100 text-slate-600 text-xs rounded-lg hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>

        {{-- Grid Media --}}
        @if($galeris->isEmpty())
            <div class="text-center py-10 text-slate-400 text-xs border border-dashed border-slate-300 rounded-lg">
                <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Belum ada media. <button type="button" @click="openCreate()" class="text-indigo-600 hover:underline">Tambah sekarang →</button>
            </div>
        @else
            <div class="grid sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($galeris as $item)
                <div class="group relative bg-slate-100 rounded-lg overflow-hidden aspect-[4/3] border border-slate-200 {{ $item->status === 'draf' ? 'opacity-60' : '' }}">

                    {{-- Thumbnail --}}
                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->judul }}"
                         class="w-full h-full object-cover">

                    {{-- Badge tipe --}}
                    <div class="absolute top-1.5 left-1.5">
                        <span class="px-1.5 py-0.5 text-xs bg-black/60 text-white rounded backdrop-blur-sm">
                            {{ $item->tipe_label }}
                        </span>
                    </div>

                    {{-- Badge draf --}}
                    @if($item->status === 'draf')
                    <div class="absolute top-1.5 right-1.5">
                        <span class="px-1.5 py-0.5 text-xs bg-amber-500/80 text-white rounded">Draf</span>
                    </div>
                    @endif

                    {{-- Play icon untuk video --}}
                    @if($item->is_video)
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-8 h-8 rounded-full bg-black/40 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                    @endif

                    {{-- Overlay aksi --}}
                    <div class="absolute inset-0 bg-black/55 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-2">
                        <p class="text-white text-xs font-medium line-clamp-2 leading-tight">{{ $item->judul }}</p>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-white/70 capitalize">{{ $item->kategori }}</span>
                            <div class="flex gap-1.5">
                                {{-- Edit --}}
                                <button type="button"
                                   @click="openEdit({
                                        id: {{ $item->id }},
                                        tipe: '{{ $item->tipe }}',
                                        judul: {{ Js::from($item->judul) }},
                                        deskripsi: {{ Js::from($item->deskripsi) }},
                                        kategori: '{{ $item->kategori }}',
                                        urutan: {{ $item->urutan ?? 0 }},
                                        status: '{{ $item->status }}',
                                        link_url: '{{ $item->link_url }}',
                                        file_url: '{{ $item->file_url }}',
                                        thumbnail: '{{ $item->thumbnail }}',
                                        update_url: '{{ route('admin.galeri.update', $item) }}'
                                   })"
                                   class="p-1 bg-white/20 hover:bg-white/30 rounded text-white transition" title="Edit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Toggle status --}}
                                <form action="{{ route('admin.galeri.toggle-status', $item) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-1 bg-white/20 hover:bg-white/30 rounded text-white transition"
                                            title="{{ $item->status === 'aktif' ? 'Jadikan Draf' : 'Aktifkan' }}">
                                        @if($item->status === 'aktif')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268-2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.galeri.destroy', $item) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus media ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 bg-red-500/60 hover:bg-red-500/80 rounded text-white transition" title="Hapus">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($galeris->hasPages())
                <div class="mt-3 text-xs">{{ $galeris->links() }}</div>
            @endif
        @endif

    </div>

    {{-- Pratinjau --}}
    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-xs">
        <p class="font-medium text-green-800 mb-1">Pratinjau Halaman Publik:</p>
        <a href="{{ route('website.galeri') }}" target="_blank" class="text-indigo-600 hover:underline flex items-center gap-1">
            Buka halaman Galeri sekarang →
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>

    {{-- MODAL OVERLAY: CREATE & EDIT FORM --}}
    <div 
        x-show="isOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        {{-- Modal Card --}}
        <div 
            @click.away="closeModal()"
            class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-2xl max-h-[90vh] flex flex-col"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900" x-text="isEdit ? 'Edit Media Galeri' : 'Tambah Media Galeri Baru'"></h3>
                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Wrapper --}}
            <form :action="formAction" method="POST" enctype="multipart/form-data" class="flex flex-col h-full overflow-hidden">
                @csrf
                <template x-if="methodField === 'PATCH'">
                    <input type="hidden" name="_method" value="PATCH">
                </template>
                <input type="hidden" name="id" :value="id">

                {{-- Modal Body (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-4 max-h-[calc(90vh-130px)]">
                    
                    {{-- Alert Error Validation --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700">
                            <p class="font-semibold mb-1">Terdapat kesalahan data input:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- TIPE MEDIA --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Tipe Media <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach($tipeOptions as $val => $label)
                            <label class="flex flex-col items-center gap-1 p-2 border-2 rounded-lg cursor-pointer transition-colors"
                                   :class="tipe === '{{ $val }}' ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="tipe" value="{{ $val }}" class="sr-only"
                                       x-model="tipe" @change="resetFile()">
                                <span class="text-base">{{ explode(' ', $label)[0] }}</span>
                                <span class="text-[11px] font-medium text-slate-700">{{ implode(' ', array_slice(explode(' ', $label), 1)) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- UPLOAD FILE (foto/video) --}}
                    <div x-show="tipe === 'photo' || tipe === 'video'">
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            <span x-text="tipe === 'photo' ? 'Upload Foto' : 'Upload Video'"></span>
                            <span class="text-red-500" x-show="!isEdit">*</span>
                            <span class="text-slate-400 font-normal" x-show="tipe === 'photo'">(jpg/png/webp/gif – tidak ada batas ukuran)</span>
                            <span class="text-slate-400 font-normal" x-show="tipe === 'video'">(mp4/mov/avi/webm – tidak ada batas ukuran)</span>
                        </label>

                        {{-- Pratinjau file saat ini (ketika Edit) --}}
                        <div class="mb-2" x-show="isEdit && existingFileUrl">
                            <template x-if="existingFileType === 'photo'">
                                <img :src="existingFileUrl" class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                            </template>
                            <template x-if="existingFileType === 'video'">
                                <video :src="existingFileUrl" controls class="w-full max-h-40 rounded-lg border border-slate-200"></video>
                            </template>
                            <p class="text-[10px] text-slate-400 mt-1">File saat ini. Upload baru jika ingin mengganti.</p>
                        </div>

                        <input type="file" name="file_path" id="modal-file-input"
                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                               @change="previewFile($event)"
                               :accept="tipe === 'photo' ? 'image/*' : 'video/mp4,video/mov,video/avi,video/mkv,video/webm'">

                        {{-- Preview sebelum upload --}}
                        <div x-show="previewSrc" class="mt-2">
                            <template x-if="tipe === 'photo'">
                                <img :src="previewSrc" class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                            </template>
                            <template x-if="tipe === 'video'">
                                <video :src="previewSrc" controls class="w-full max-h-40 rounded-lg border border-slate-200"></video>
                            </template>
                        </div>
                    </div>

                    {{-- LINK URL (YouTube / Facebook) --}}
                    <div x-show="tipe === 'link_youtube' || tipe === 'link_facebook'">
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            <span x-text="tipe === 'link_youtube' ? 'URL YouTube' : 'URL Video Facebook'"></span>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="url" name="link_url" x-model="link_url"
                               class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="https://..."
                               @input="previewYoutube($event.target.value)">

                        {{-- Preview YouTube --}}
                        <div x-show="youtubeThumbnail" class="mt-2">
                            <img :src="youtubeThumbnail" class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">Pratinjau thumbnail YouTube</p>
                        </div>
                    </div>

                    {{-- JUDUL --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Judul / Keterangan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" x-model="judul"
                               class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Contoh: Upacara HUT RI ke-80" required>
                    </div>

                    {{-- DESKRIPSI --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            Deskripsi <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="deskripsi" rows="2" x-model="deskripsi"
                                  class="w-full rounded-md border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Deskripsi singkat kegiatan..."></textarea>
                    </div>

                    {{-- THUMBNAIL MANUAL --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">
                            Thumbnail Kustom
                            <span class="text-slate-400 font-normal">(opsional – untuk video/link, jpg/png maks. 5MB)</span>
                        </label>
                        
                        <div class="mb-2" x-show="isEdit && existingThumbnailUrl">
                            <img :src="existingThumbnailUrl" class="w-24 h-16 object-cover rounded border border-slate-200">
                            <p class="text-[10px] text-slate-400 mt-0.5">Thumbnail saat ini.</p>
                        </div>

                        <input type="file" name="thumbnail" id="modal-thumb-input" accept="image/*"
                               @change="previewThumb($event)"
                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                        
                        <div x-show="thumbPreviewSrc" class="mt-2">
                            <img :src="thumbPreviewSrc" class="w-24 h-16 object-cover rounded border border-slate-200">
                        </div>
                    </div>

                    {{-- KATEGORI & URUTAN --}}
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" x-model="kategori"
                                    class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach($kategoriOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">
                                Urutan <span class="text-slate-400 font-normal">(angka kecil tampil lebih awal)</span>
                            </label>
                            <input type="number" name="urutan" min="0" x-model="urutan"
                                   class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" x-model="status"
                                class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="aktif">✅ Aktif – tampil di website</option>
                            <option value="draf">📝 Draf – tidak tampil</option>
                        </select>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between rounded-b-xl">
                    <button type="button" @click="closeModal()"
                            class="px-4 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                        <span x-text="isEdit ? 'Simpan Perubahan' : 'Tambah Media'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('galeriManager', () => {
        // Deteksi jika halaman reload membawa error validasi laravel
        const hasErrors = @json($errors->any());
        const isEditMode = @json(old('_method') === 'PATCH');
        const oldId = @json(old('id', ''));
        const galeris = @json($galeris instanceof \Illuminate\Pagination\LengthAwarePaginator ? $galeris->items() : $galeris);
        
        let initialAction = @json(route('admin.galeri.store'));
        let existingFileUrl = '';
        let existingFileType = '';
        let existingThumbnailUrl = '';

        // Jikalau ada error saat mode edit, cari item terkait untuk memulihkan preview asset
        if (isEditMode && oldId) {
            initialAction = `{{ route('admin.galeri.update', ':id') }}`.replace(':id', oldId);
            const matchedItem = galeris.find(item => item.id == oldId);
            if (matchedItem) {
                existingFileUrl = matchedItem.file_url || '';
                existingFileType = matchedItem.tipe;
                existingThumbnailUrl = matchedItem.thumbnail ? `{{ asset('storage') }}/${matchedItem.thumbnail}` : '';
            }
        }

        return {
            isOpen: hasErrors,
            isEdit: isEditMode,
            formAction: initialAction,
            methodField: isEditMode ? 'PATCH' : 'POST',

            // Form properties yang disinkronkan dengan x-model
            id: oldId,
            tipe: @json(old('tipe', 'photo')),
            judul: @json(old('judul', '')),
            deskripsi: @json(old('deskripsi', '')),
            kategori: @json(old('kategori', 'kegiatan')),
            urutan: @json(old('urutan', 0)),
            status: @json(old('status', 'aktif')),
            link_url: @json(old('link_url', '')),
            
            // Preview States
            previewSrc: null,
            thumbPreviewSrc: null,
            youtubeThumbnail: null,
            existingFileUrl: existingFileUrl,
            existingFileType: existingFileType,
            existingThumbnailUrl: existingThumbnailUrl,

            openCreate() {
                this.isEdit = false;
                this.formAction = "{{ route('admin.galeri.store') }}";
                this.methodField = 'POST';
                this.resetForm();
                this.isOpen = true;
            },

            openEdit(item) {
                this.isEdit = true;
                this.formAction = item.update_url;
                this.methodField = 'PATCH';
                
                this.id = item.id;
                this.tipe = item.tipe;
                this.judul = item.judul;
                this.deskripsi = item.deskripsi || '';
                this.kategori = item.kategori;
                this.urutan = item.urutan || 0;
                this.status = item.status || 'aktif';
                this.link_url = item.link_url || '';
                
                this.existingFileUrl = item.file_url || '';
                this.existingFileType = item.tipe;
                this.existingThumbnailUrl = item.thumbnail ? `{{ asset('storage') }}/${item.thumbnail}` : '';
                
                this.previewSrc = null;
                this.thumbPreviewSrc = null;
                this.youtubeThumbnail = null;
                if (this.tipe === 'link_youtube' && this.link_url) {
                    this.previewYoutube(this.link_url);
                }
                this.isOpen = true;
            },

            closeModal() {
                this.isOpen = false;
                this.resetForm();
            },

            resetForm() {
                this.id = '';
                this.tipe = 'photo';
                this.judul = '';
                this.deskripsi = '';
                this.kategori = 'kegiatan';
                this.urutan = 0;
                this.status = 'aktif';
                this.link_url = '';
                this.previewSrc = null;
                this.thumbPreviewSrc = null;
                this.youtubeThumbnail = null;
                this.existingFileUrl = '';
                this.existingFileType = '';
                this.existingThumbnailUrl = '';
                
                const fileInput = document.getElementById('modal-file-input');
                if (fileInput) fileInput.value = '';
                const thumbInput = document.getElementById('modal-thumb-input');
                if (thumbInput) thumbInput.value = '';
            },

            previewFile(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.previewSrc = URL.createObjectURL(file);
            },

            previewThumb(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.thumbPreviewSrc = URL.createObjectURL(file);
            },

            previewYoutube(url) {
                const patterns = [
                    /youtu\.be\/([a-zA-Z0-9_-]{11})/,
                    /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
                    /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
                ];
                for (const p of patterns) {
                    const m = url.match(p);
                    if (m) {
                        this.youtubeThumbnail = `https://img.youtube.com/vi/${m[1]}/mqdefault.jpg`;
                        return;
                    }
                }
                this.youtubeThumbnail = null;
            }
        };
    });
});
</script>
{{-- resources/views/admin/galeri/_form.blade.php --}}
@extends('layouts.app')
@section('title', isset($galeri) ? 'Edit Media Galeri' : 'Tambah Media Galeri')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-4">
        <a href="{{ route('admin.kelola-website', ['tab'=>'galeri']) }}" class="hover:text-indigo-600">Kelola Galeri</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">{{ isset($galeri) ? 'Edit Media' : 'Tambah Media' }}</span>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h1 class="text-sm font-semibold text-slate-900 mb-4">
            {{ isset($galeri) ? 'Edit: '.Str::limit($galeri->judul, 40) : 'Tambah Media Baru' }}
        </h1>

        <form
            method="POST"
            action="{{ isset($galeri) ? route('admin.galeri.update', $galeri) : route('admin.galeri.store') }}"
            enctype="multipart/form-data"
            class="space-y-4"
            x-data="galeriForm('{{ old('tipe', $galeri->tipe ?? 'photo') }}')"
        >
            @csrf
            @if(isset($galeri)) @method('PATCH') @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700">
                    <p class="font-semibold mb-1">Terdapat kesalahan:</p>
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
                    <label class="flex flex-col items-center gap-1 p-2.5 border-2 rounded-lg cursor-pointer transition-colors"
                           :class="tipe === '{{ $val }}' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="tipe" value="{{ $val }}" class="sr-only"
                               x-model="tipe" @change="resetFile()">
                        <span class="text-lg">{{ explode(' ', $label)[0] }}</span>
                        <span class="text-xs font-medium text-slate-700">{{ implode(' ', array_slice(explode(' ', $label), 1)) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- UPLOAD FILE (foto/video) --}}
            <div x-show="tipe === 'photo' || tipe === 'video'">
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    <span x-text="tipe === 'photo' ? 'Upload Foto' : 'Upload Video'"></span>
                    @if(!isset($galeri))<span class="text-red-500">*</span>@endif
                    <span class="text-slate-400 font-normal" x-show="tipe === 'photo'">(jpg/png/webp/gif – tidak ada batas ukuran)</span>
                    <span class="text-slate-400 font-normal" x-show="tipe === 'video'">(mp4/mov/avi/mkv/webm – tidak ada batas ukuran)</span>
                </label>

                {{-- Pratinjau file saat ini --}}
                @if(isset($galeri) && $galeri->file_path)
                <div class="mb-2">
                    @if($galeri->tipe === 'photo')
                        <img src="{{ $galeri->file_url }}" alt="{{ $galeri->judul }}"
                             class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                    @elseif($galeri->tipe === 'video')
                        <video src="{{ $galeri->file_url }}" controls
                               class="w-full max-h-40 rounded-lg border border-slate-200"></video>
                    @endif
                    <p class="text-xs text-slate-400 mt-1">File saat ini. Upload baru untuk mengganti.</p>
                </div>
                @endif

                <input type="file" name="file_path" id="file-input"
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

                @error('file_path')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- LINK URL (YouTube / Facebook) --}}
            <div x-show="tipe === 'link_youtube' || tipe === 'link_facebook'">
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    <span x-text="tipe === 'link_youtube' ? 'URL YouTube' : 'URL Video Facebook'"></span>
                    <span class="text-red-500">*</span>
                </label>
                <input type="url" name="link_url"
                       value="{{ old('link_url', $galeri->link_url ?? '') }}"
                       class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500 @error('link_url') border-red-400 @enderror"
                       placeholder="https://www.youtube.com/watch?v=..."
                       @input="previewYoutube($event.target.value)">
                @error('link_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                {{-- Preview YouTube --}}
                <div x-show="youtubeThumbnail" class="mt-2">
                    <img :src="youtubeThumbnail" class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                    <p class="text-xs text-slate-400 mt-1">Pratinjau thumbnail YouTube</p>
                </div>

                @if(isset($galeri) && $galeri->link_url && in_array($galeri->tipe, ['link_youtube','link_facebook']))
                <div class="mt-2 p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600">
                    URL saat ini: <span class="font-mono break-all">{{ $galeri->link_url }}</span>
                </div>
                @endif
            </div>

            {{-- JUDUL --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Judul / Keterangan <span class="text-red-500">*</span></label>
                <input type="text" name="judul"
                       value="{{ old('judul', $galeri->judul ?? '') }}"
                       class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500 @error('judul') border-red-400 @enderror"
                       placeholder="Contoh: Upacara HUT RI ke-80" required>
                @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- DESKRIPSI --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Deskripsi <span class="text-slate-400 font-normal">(opsional)</span>
                </label>
                <textarea name="deskripsi" rows="2"
                          class="w-full rounded-md border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Deskripsi singkat kegiatan...">{{ old('deskripsi', $galeri->deskripsi ?? '') }}</textarea>
            </div>

            {{-- THUMBNAIL MANUAL --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Thumbnail Kustom
                    <span class="text-slate-400 font-normal">(opsional – untuk video/link, jpg/png maks. 5MB)</span>
                </label>
                @if(isset($galeri) && $galeri->thumbnail)
                    <img src="{{ asset('storage/'.$galeri->thumbnail) }}" class="w-24 h-16 object-cover rounded mb-1 border border-slate-200">
                    <p class="text-xs text-slate-400 mb-1">Thumbnail saat ini.</p>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                @error('thumbnail')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- KATEGORI & URUTAN --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori"
                            class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach($kategoriOptions as $val => $label)
                            <option value="{{ $val }}" @selected(old('kategori', $galeri->kategori ?? 'kegiatan') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Urutan <span class="text-slate-400 font-normal">(angka kecil tampil lebih awal)</span>
                    </label>
                    <input type="number" name="urutan" min="0"
                           value="{{ old('urutan', $galeri->urutan ?? 0) }}"
                           class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status"
                        class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="aktif" @selected(old('status', $galeri->status ?? 'aktif') === 'aktif')>✅ Aktif – tampil di website</option>
                    <option value="draf"  @selected(old('status', $galeri->status ?? '') === 'draf')>📝 Draf – tidak tampil</option>
                </select>
            </div>

            {{-- TOMBOL --}}
            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                <a href="{{ route('admin.kelola-website', ['tab'=>'galeri']) }}"
                   class="px-4 py-2 text-xs text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">← Batal</a>
                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                    {{ isset($galeri) ? 'Simpan Perubahan' : 'Tambah Media' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function galeriForm(initialTipe) {
    return {
        tipe: initialTipe,
        previewSrc: null,
        youtubeThumbnail: null,

        resetFile() {
            this.previewSrc = null;
            this.youtubeThumbnail = null;
            const input = document.getElementById('file-input');
            if (input) input.value = '';
        },

        previewFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            this.previewSrc = url;
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
                    this.youtubeThumbnail = `https://img.youtube.com/vi/${m[1]}/hqdefault.jpg`;
                    return;
                }
            }
            this.youtubeThumbnail = null;
        }
    };
}
</script>
@endsection
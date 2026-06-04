{{-- resources/views/admin/berita/_form.blade.php --}}
@extends('layouts.app')
@section('title', isset($berita) ? 'Edit Berita' : 'Tambah Berita')

@php
    $isEdit         = isset($berita);
    $mediaTipe      = old('media_tipe',    $isEdit ? ($berita->media_tipe    ?? 'none') : 'none');
    $mediaLink      = old('media_link',    $isEdit ? ($berita->media_link    ?? '')     : '');
    $mediaFileSrc   = $isEdit ? ($berita->media_file_url  ?? null) : null;
    $mediaThumbnail = $isEdit ? ($berita->media_thumbnail ?? null) : null;
    $existingTipe   = $isEdit ? ($berita->media_tipe ?? 'none') : 'none';
@endphp

@section('content')
<div class="max-w-3xl mx-auto px-4 py-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-4">
        <a href="{{ route('admin.kelola-website', ['tab'=>'berita']) }}" class="hover:text-indigo-600">Kelola Berita</a>
        <span>/</span>
        <span class="text-slate-700 font-medium">
            {{ $isEdit ? 'Edit Berita' : 'Tambah Berita Baru' }}
        </span>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
        <h1 class="text-sm font-semibold text-slate-900 mb-4">
            {{ $isEdit ? 'Edit: ' . Str::limit($berita->judul, 50) : 'Tambah Berita Baru' }}
        </h1>

        {{-- ============================================================
             FORM – enctype multipart wajib untuk file upload
        ============================================================ --}}
        <form
            method="POST"
            action="{{ $isEdit ? route('admin.berita.update', $berita) : route('admin.berita.store') }}"
            enctype="multipart/form-data"
            class="space-y-4"
            id="berita-form"
            x-data="beritaFormData('{{ $mediaTipe }}')"
            @submit="prepareSubmit()"
        >
            @csrf
            @if($isEdit) @method('PATCH') @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700">
                    <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- JUDUL --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Judul <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul"
                       value="{{ old('judul', $isEdit ? $berita->judul : '') }}"
                       class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500 @error('judul') border-red-400 @enderror"
                       placeholder="Masukkan judul berita..." required>
                @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- RINGKASAN --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Ringkasan <span class="text-slate-400 font-normal">(maks. 500 karakter, auto-isi jika kosong)</span>
                </label>
                <textarea name="ringkasan" rows="2"
                          class="w-full rounded-md border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="Ringkasan singkat...">{{ old('ringkasan', $isEdit ? ($berita->ringkasan ?? '') : '') }}</textarea>
            </div>

            {{-- ISI --}}
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Isi Berita <span class="text-red-500">*</span>
                </label>
                <textarea name="isi" rows="10"
                          class="w-full rounded-md border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('isi') border-red-400 @enderror"
                          placeholder="Tulis isi berita..." required>{{ old('isi', $isEdit ? ($berita->isi ?? '') : '') }}</textarea>
                @error('isi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- ============================================================
                 BAGIAN MEDIA
                 PENTING: Setiap tipe punya section terpisah.
                 Gunakan x-if (bukan x-show) agar input yang tidak aktif
                 tidak ikut dikirim saat submit → mencegah nilai kosong
                 menimpa file yang seharusnya terkirim.
            ============================================================ --}}
            <fieldset class="border border-slate-200 rounded-lg p-4">
                <legend class="text-xs font-semibold text-slate-700 px-1.5">Media Lampiran (opsional)</legend>

                {{-- Pilih Tipe Media --}}
                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Tipe Media</label>

                    {{-- Hidden input yang selalu terkirim --}}
                    <input type="hidden" name="media_tipe" :value="mediaTipe">

                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            'none'          => '🚫 Tanpa Media',
                            'photo'         => '🖼️ Upload Foto',
                            'video'         => '🎥 Upload Video',
                            'link_youtube'  => '▶️ Link YouTube',
                            'link_facebook' => '📘 Link Facebook',
                        ] as $val => $label)
                        <label class="flex items-center gap-1.5 px-3 py-1.5 border-2 rounded-lg cursor-pointer transition-colors text-xs font-medium"
                               :class="mediaTipe === '{{ $val }}'
                                   ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                                   : 'border-slate-200 text-slate-600 hover:border-slate-300'">
                            <input type="radio" value="{{ $val }}" class="sr-only"
                                   x-model="mediaTipe" @change="onTipeChange()">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ── Upload FOTO ── --}}
                <template x-if="mediaTipe === 'photo'">
                    <div class="space-y-2">
                        <label class="block text-xs font-medium text-slate-600">
                            File Foto
                            @if(!$isEdit)<span class="text-red-500">*</span>@endif
                            <span class="text-slate-400 font-normal">(jpg/png/webp/gif – tidak ada batas ukuran)</span>
                        </label>

                        {{-- Tampilkan foto yang sudah ada saat edit --}}
                        @if($isEdit && $existingTipe === 'photo' && $mediaFileSrc)
                            <div class="mb-2">
                                <img src="{{ $mediaFileSrc }}" alt="Foto saat ini"
                                     class="w-full max-h-48 object-cover rounded-lg border border-slate-200">
                                <p class="text-xs text-slate-400 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                            </div>
                        @endif

                        <input type="file" name="media_file"
                               id="input-photo"
                               accept="image/jpeg,image/png,image/webp,image/gif"
                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                               @change="previewPhoto($event)">

                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full max-h-48 object-cover rounded-lg border border-slate-200 mt-1">
                        </template>

                        @error('media_file')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                    </div>
                </template>

                {{-- ── Upload VIDEO ── --}}
                <template x-if="mediaTipe === 'video'">
                    <div class="space-y-2">
                        <label class="block text-xs font-medium text-slate-600">
                            File Video
                            @if(!$isEdit)<span class="text-red-500">*</span>@endif
                            <span class="text-slate-400 font-normal">(mp4/mov/avi/mkv/webm – tidak ada batas ukuran)</span>
                        </label>

                        @if($isEdit && $existingTipe === 'video' && $mediaFileSrc)
                            <div class="mb-2">
                                <video src="{{ $mediaFileSrc }}" controls
                                       class="w-full max-h-48 rounded-lg border border-slate-200"></video>
                                <p class="text-xs text-slate-400 mt-1">Video saat ini. Upload baru untuk mengganti.</p>
                            </div>
                        @endif

                        <input type="file" name="media_file"
                               id="input-video"
                               accept="video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm"
                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                               @change="previewVideo($event)">

                        <template x-if="videoPreview">
                            <video :src="videoPreview" controls class="w-full max-h-48 rounded-lg border border-slate-200 mt-1"></video>
                        </template>

                        @error('media_file')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                    </div>
                </template>

                {{-- ── Link YOUTUBE ── --}}
                <template x-if="mediaTipe === 'link_youtube'">
                    <div class="space-y-2">
                        <label class="block text-xs font-medium text-slate-600">
                            URL YouTube <span class="text-red-500">*</span>
                        </label>

                        <input type="url" name="media_link"
                               id="input-youtube"
                               value="{{ $mediaTipe === 'link_youtube' ? $mediaLink : '' }}"
                               class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="https://www.youtube.com/watch?v=..."
                               @input="previewYoutube($event.target.value)"
                               x-init="$nextTick(() => { if ($el.value) previewYoutube($el.value) })">

                        <template x-if="youtubeThumbnail">
                            <div>
                                <img :src="youtubeThumbnail" class="w-full max-h-40 object-cover rounded-lg border border-slate-200">
                                <p class="text-xs text-slate-400 mt-1">Pratinjau thumbnail YouTube</p>
                            </div>
                        </template>

                        @error('media_link')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                    </div>
                </template>

                {{-- ── Link FACEBOOK ── --}}
                <template x-if="mediaTipe === 'link_facebook'">
                    <div class="space-y-2">
                        <label class="block text-xs font-medium text-slate-600">
                            URL Video Facebook <span class="text-red-500">*</span>
                        </label>

                        <input type="url" name="media_link"
                               id="input-facebook"
                               value="{{ $mediaTipe === 'link_facebook' ? $mediaLink : '' }}"
                               class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="https://www.facebook.com/watch?v=...">

                        @if($isEdit && $existingTipe === 'link_facebook' && $mediaLink)
                            <p class="text-xs text-slate-500">Link saat ini: <span class="font-mono break-all">{{ $mediaLink }}</span></p>
                        @endif

                        @error('media_link')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                    </div>
                </template>

                {{-- ── Thumbnail Kustom (untuk video / link) ── --}}
                <template x-if="mediaTipe !== 'none'">
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Thumbnail Kustom
                            <span class="text-slate-400 font-normal">(opsional – jpg/png, maks. 5MB)</span>
                        </label>
                        @if($mediaThumbnail)
                            <img src="{{ asset('storage/' . $mediaThumbnail) }}"
                                 class="w-24 h-16 object-cover rounded mb-1 border border-slate-200">
                            <p class="text-xs text-slate-400 mb-1">Thumbnail saat ini.</p>
                        @endif
                        <input type="file" name="media_thumbnail"
                               accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                        @error('media_thumbnail')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </template>

            </fieldset>

            {{-- KATEGORI & TANGGAL --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori"
                            class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="berita"
                            @selected(old('kategori', $isEdit ? ($berita->kategori ?? 'berita') : 'berita') === 'berita')>
                            📰 Berita
                        </option>
                        <option value="pengumuman"
                            @selected(old('kategori', $isEdit ? ($berita->kategori ?? '') : '') === 'pengumuman')>
                            📢 Pengumuman
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Tanggal Publish</label>
                    <input type="datetime-local" name="tanggal_publish"
                           value="{{ old('tanggal_publish', $isEdit
                               ? ($berita->tanggal_publish?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'))
                               : now()->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            {{-- STATUS & PENTING --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full rounded-md border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="aktif"
                            @selected(old('status', $isEdit ? ($berita->status ?? 'draf') : 'draf') === 'aktif')>
                            ✅ Aktif – tampil di website
                        </option>
                        <option value="draf"
                            @selected(old('status', $isEdit ? ($berita->status ?? 'draf') : 'draf') === 'draf')>
                            📝 Draf – tidak tampil
                        </option>
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-5">
                    <input type="checkbox" name="is_penting" id="is_penting" value="1"
                           class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                           @checked(old('is_penting', $isEdit ? ($berita->is_penting ?? false) : false))>
                    <label for="is_penting" class="text-xs font-medium text-slate-700 cursor-pointer">
                        🔴 Tandai sebagai <strong>Penting</strong>
                        <span class="text-slate-400 font-normal">(tampil di bagian atas)</span>
                    </label>
                </div>
            </div>

            {{-- TOMBOL --}}
            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                <a href="{{ route('admin.kelola-website', ['tab'=>'berita']) }}"
                   class="px-4 py-2 text-xs text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">
                    ← Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Publikasikan Berita' }}
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function beritaFormData(initialTipe) {
    return {
        mediaTipe:       initialTipe,
        photoPreview:    null,
        videoPreview:    null,
        youtubeThumbnail: null,

        onTipeChange() {
            // Reset preview saat ganti tipe
            this.photoPreview     = null;
            this.videoPreview     = null;
            this.youtubeThumbnail = null;
        },

        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) this.photoPreview = URL.createObjectURL(file);
        },

        previewVideo(event) {
            const file = event.target.files[0];
            if (file) this.videoPreview = URL.createObjectURL(file);
        },

        previewYoutube(url) {
            if (!url) { this.youtubeThumbnail = null; return; }
            const patterns = [
                /youtu\.be\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
            ];
            for (const p of patterns) {
                const m = url.match(p);
                if (m) {
                    this.youtubeThumbnail = `https://img.youtube.com/vi/${m[1]}/hqdefault.jpg`;
                    return;
                }
            }
            this.youtubeThumbnail = null;
        },

        // Karena x-if me-render/destroy elemen, media_link dan media_file
        // hanya ada di DOM saat tipe yang sesuai aktif.
        // Tidak perlu prepareSubmit, tapi tetap ada untuk safety.
        prepareSubmit() {
            return true;
        }
    };
}
</script>
@endsection
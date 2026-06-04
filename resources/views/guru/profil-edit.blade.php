@extends('layouts.app')

@section('title', 'Edit Profil Guru')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.profil.update') }}" method="POST" enctype="multipart/form-data"
          class="space-y-6" id="profilForm">
        @csrf
        @method('PUT')

        {{-- ======================== INFORMASI AKUN ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Informasi Akun
            </h3>

            {{-- Area Foto Profil dengan Drag & Drop --}}
            <div class="flex flex-col sm:flex-row sm:items-start gap-6 mb-8">
                
                <!-- Drop Zone + Preview -->
                <div id="photoDropZone"
                     class="shrink-0 w-40 h-[213px] border-2 border-dashed border-slate-300 rounded-xl
                            flex items-center justify-center overflow-hidden cursor-pointer
                            hover:border-indigo-400 transition-colors relative"
                     ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)"
                     onclick="document.getElementById('photoInput').click()">

                    <div id="photoPreviewWrap" class="w-full h-full">
                        @if($user->photo)
                            <img id="photoPreview" 
                                 src="{{ Storage::url($user->photo) }}" 
                                 alt="Foto Profil"
                                 class="w-full h-full object-cover">
                        @else
                            <div id="photoPlaceholder"
                                 class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                <div class="text-6xl mb-2">📸</div>
                                <p class="text-xs font-medium text-center px-4">
                                    Tarik foto ke sini<br>atau klik untuk memilih
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Overlay saat drag over -->
                    <div id="dropOverlay" 
                         class="absolute inset-0 hidden flex-col items-center justify-center bg-indigo-600/10 border-2 border-indigo-400 rounded-xl backdrop-blur-sm">
                        <div class="text-4xl mb-2">📥</div>
                        <p class="text-indigo-700 font-semibold text-sm">Lepaskan foto di sini</p>
                    </div>

                    <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                </div>

                <div class="flex-1 pt-2">
                    <label for="photo" 
                           class="inline-flex items-center gap-3 cursor-pointer px-6 py-3.5 rounded-xl
                                  border border-slate-300 text-sm font-medium text-slate-700 
                                  hover:bg-slate-50 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Pilih Foto dari File</span>
                    </label>

                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                        Ukuran maksimal <strong>2 MB</strong>.<br>
                        Format: JPG, PNG, WebP.<br>
                        Disarankan rasio <strong>3:4 (potret)</strong>.
                    </p>

                    @error('photo')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Akun Lainnya --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-form-group label="Nama Akun" name="name" required>
                    <x-form-input name="name" :value="old('name', $user->name)" required autofocus />
                </x-form-group>

                <x-form-group label="Email" name="email" required>
                    <x-form-input type="email" name="email" :value="old('email', $user->email)" required />
                </x-form-group>

                <x-form-group label="Password Baru" name="password">
                    <x-form-input type="password" name="password"
                                  placeholder="Kosongkan jika tidak ingin mengubah" autocomplete="new-password" />
                </x-form-group>

                <x-form-group label="Konfirmasi Password Baru" name="password_confirmation">
                    <x-form-input type="password" name="password_confirmation" autocomplete="new-password" />
                </x-form-group>
            </div>
        </div>

        {{-- ======================== IDENTITAS & TUGAS ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Identitas & Tugas
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-form-group label="NIP" name="nip">
                    <x-form-input name="nip" :value="old('nip', $user->guru?->nip)"
                                  placeholder="Nomor Induk Pegawai" maxlength="30" />
                </x-form-group>

                <x-form-group label="Wali Kelas" name="wali_kelas">
                    <select name="wali_kelas" id="wali_kelas"
                            class="w-full rounded-lg border @error('wali_kelas') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        <option value="">— Tidak Menjadi Wali Kelas —</option>
                        @foreach($kelasList ?? [] as $kelas)
                            <option value="{{ $kelas->id }}"
                                {{ old('wali_kelas', $user->guru?->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama }} • {{ $kelas->tingkat }} • {{ $kelas->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                    @error('wali_kelas')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1.5">
                        Pilih kelas yang akan Anda menjadi wali kelasnya (hanya satu kelas yang diperbolehkan)
                    </p>
                </x-form-group>
            </div>
        </div>

        {{-- ======================== DATA PRIBADI ======================== --}}
        @php $g = $user->guru; @endphp
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Data Pribadi
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-form-group label="Nama Lengkap" name="nama" class="sm:col-span-2">
                    <x-form-input name="nama" :value="old('nama', $g?->nama)" />
                </x-form-group>

                <x-form-group label="Jenis Kelamin" name="jk">
                    <select name="jk"
                            class="w-full rounded-lg border @error('jk') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('jk', $g?->jk) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk', $g?->jk) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jk')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </x-form-group>

                <x-form-group label="Tempat Lahir" name="tempat_lahir">
                    <x-form-input name="tempat_lahir" :value="old('tempat_lahir', $g?->tempat_lahir)" />
                </x-form-group>

                <x-form-group label="Tanggal Lahir" name="tanggal_lahir">
                    <x-form-input type="date" name="tanggal_lahir"
                                  :value="old('tanggal_lahir', $g?->tanggal_lahir?->format('Y-m-d'))" />
                </x-form-group>

                <x-form-group label="Pendidikan Terakhir" name="pendidikan_terakhir" class="sm:col-span-2">
                    <select name="pendidikan_terakhir"
                            class="w-full rounded-lg border @error('pendidikan_terakhir') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        <option value="">— Pilih —</option>
                        @foreach(['SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $pend)
                            <option value="{{ $pend }}"
                                {{ old('pendidikan_terakhir', $g?->pendidikan_terakhir) === $pend ? 'selected' : '' }}>
                                {{ $pend }}
                            </option>
                        @endforeach
                    </select>
                    @error('pendidikan_terakhir')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </x-form-group>
            </div>
        </div>

        {{-- ======================== DATA KEPEGAWAIAN ======================== --}}
        <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-700 mb-5 pb-2 border-b border-slate-100">
                Data Kepegawaian
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-form-group label="Status Pegawai" name="status_pegawai">
                    <select name="status_pegawai"
                            class="w-full rounded-lg border @error('status_pegawai') border-red-400 @else border-slate-300 @enderror
                                   px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        <option value="">— Pilih —</option>
                        @foreach(['PNS','PPPK','Honorer','Kontrak','GTT','Lainnya'] as $sp)
                            <option value="{{ $sp }}"
                                {{ old('status_pegawai', $g?->status_pegawai) === $sp ? 'selected' : '' }}>
                                {{ $sp }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_pegawai')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </x-form-group>

                <x-form-group label="Pangkat / Gol. Ruang" name="pangkat_gol_ruang">
                    <x-form-input name="pangkat_gol_ruang"
                                  :value="old('pangkat_gol_ruang', $g?->pangkat_gol_ruang)"
                                  placeholder="Contoh: Penata Muda / III-a" maxlength="100" />
                </x-form-group>

                <x-form-group label="No. SK Pertama" name="no_sk_pertama">
                    <x-form-input name="no_sk_pertama"
                                  :value="old('no_sk_pertama', $g?->no_sk_pertama)"
                                  placeholder="Nomor SK CPNS / Kontrak / Honor pertama" maxlength="150" />
                </x-form-group>

                <x-form-group label="No. SK Terakhir" name="no_sk_terakhir">
                    <x-form-input name="no_sk_terakhir"
                                  :value="old('no_sk_terakhir', $g?->no_sk_terakhir)"
                                  placeholder="Nomor SK terakhir" maxlength="150" />
                </x-form-group>
            </div>
        </div>

        {{-- ======================== TOMBOL AKSI ======================== --}}
        <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4">
            <a href="{{ route('guru.profil') }}"
               class="px-6 py-3 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium
                      hover:bg-slate-50 transition text-center">
                Batal
            </a>
            <button type="submit"
                    class="px-8 py-3 rounded-lg bg-indigo-600 text-white text-sm font-medium
                           hover:bg-indigo-700 transition shadow-sm">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone       = document.getElementById('photoDropZone');
        const photoInput     = document.getElementById('photoInput');
        const previewWrap    = document.getElementById('photoPreviewWrap');
        const dropOverlay    = document.getElementById('dropOverlay');

        if (!dropZone || !photoInput) return;

        // Klik area untuk memilih file
        dropZone.addEventListener('click', function(e) {
            // Jangan trigger jika klik di dalam input (untuk menghindari double click)
            if (e.target.tagName !== 'INPUT') {
                photoInput.click();
            }
        });

        // Pilih file melalui input
        photoInput.addEventListener('change', function(e) {
            handleFile(e.target.files[0]);
        });

        // Drag & Drop Events
        function handleDragOver(e) {
            e.preventDefault();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
            dropOverlay.classList.remove('hidden');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
            dropOverlay.classList.add('hidden');
        }

        function handleDrop(e) {
            e.preventDefault();
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
            dropOverlay.classList.add('hidden');

            const file = e.dataTransfer.files[0];
            if (file) handleFile(file);
        }

        // Fungsi utama memproses file
        function handleFile(file) {
            if (!file) return;

            // Validasi tipe
            if (!file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan (JPG, PNG, WebP).');
                return;
            }

            // Validasi ukuran maksimal 2MB
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar!\nMaksimal 2 MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(ev) {
                previewWrap.innerHTML = `
                    <img src="${ev.target.result}" 
                         alt="Pratinjau Foto"
                         class="w-full h-full object-cover">
                `;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

@endsection
{{-- resources/views/admin/profil.blade.php --}}
@extends('layouts.app')
@section('title', 'Profil Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6" x-data="{ openEdit: false }">
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Manajemen Profil</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola informasi akun dan pengaturan keamanan Admin Anda.</p>
        </div>
        {{-- Tombol untuk memicu Overlay (Modal) --}}
        <button onclick="toggleEditModal(true)"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                  bg-indigo-600 text-white text-xs font-semibold shadow-sm
                  hover:bg-indigo-700 active:scale-95 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin=\"round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Edit Profil Admin
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Kartu Avatar & Status --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 text-center">
                <div class="relative inline-block mb-4">
                    @if($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="Avatar"
                             class="w-24 h-24 rounded-2xl object-cover mx-auto border-2 border-indigo-500 shadow-md">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white mx-auto flex items-center justify-center font-bold text-3xl shadow-md">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute bottom-1 right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                </div>
                
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">{{ $user->name }}</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-3 truncate">{{ $user->email }}</p>
                
                <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                    Administrator
                </span>

                <div class="border-t border-slate-100 dark:border-slate-700/50 my-4 pt-4 text-left space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Status Akun</span>
                        <span class="font-medium text-emerald-600 dark:text-emerald-400">Aktif</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Terdaftar</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->created_at ? $user->created_at->format('d M Y') : '07 Jun 2026' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-800 to-indigo-950 dark:from-slate-900 dark:to-slate-950 rounded-2xl p-5 text-white shadow-sm">
                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-300 mb-2">Sistem Info</h4>
                <p class="text-xs text-slate-300 leading-relaxed mb-3">Anda memiliki hak akses penuh ke dashboard akademik, manajemen guru, siswa, dan konfigurasi aplikasi.</p>
                <div class="flex items-center gap-2 text-[11px] text-indigo-200">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Sesi Aman terenkripsi</span>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Informasi Akun --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 dark:border-slate-700">
                    Informasi Pribadi & Autentikasi
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-1">Nama Lengkap</p>
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-700/50 px-3 py-2 rounded-xl">
                            {{ $user->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-1">Alamat Email</p>
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-700/50 px-3 py-2 rounded-xl break-all">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-1">Role/Tingkat Akses</p>
                        <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/30 px-3 py-2 rounded-xl">
                            Super Administrator
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-1">Zona Waktu / Lokasi</p>
                        <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-700/50 px-3 py-2 rounded-xl">
                            Asia/Jakarta (WIB)
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 dark:border-slate-700">
                    Log Aktivitas Terakhir
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3 text-xs">
                        <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 mt-0.5">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800 dark:text-slate-200">Berhasil masuk ke dalam sistem</p>
                            <p class="text-[10px] text-slate-400">Hari ini, {{ date('H:i') }} WIB • IP: 192.168.1.1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= OVERLAY MODAL (EDIT PROFIL) ================= --}}
    <div id="editProfilModal" 
         class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
        
        {{-- Konten Box Modal (Berada di tengah layar) --}}
        <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 transform transition-all overflow-hidden max-h-[90vh] flex flex-col">
            
            {{-- Header Modal --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50 dark:bg-slate-900/20">
                <div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Edit Profil Admin</h2>
                    <p class="text-[11px] text-slate-400">Perbarui informasi dasar dan kredensial keamanan Anda.</p>
                </div>
                <button onclick="toggleEditModal(false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg p-1 transition cursor-pointer">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            {{-- Form Input --}}
            <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Upload Foto --}}
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">Foto Profil</p>
                    <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900/30 p-3 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                        @if($user->photo)
                            <img src="{{ Storage::url($user->photo) }}" alt="Preview"
                                 class="w-14 h-14 rounded-xl object-cover border border-slate-200 dark:border-slate-600 shadow-sm shrink-0">
                        @else
                            <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-300 flex items-center justify-center font-bold text-lg shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="text-left flex-1 min-w-0">
                            <input type="file" name="photo" id="photo"
                                   class="block w-full text-xs text-slate-500 dark:text-slate-400
                                          file:mr-3 file:py-1.5 file:px-3
                                          file:rounded-xl file:border-0
                                          file:text-[11px] file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          dark:file:bg-slate-700 dark:file:text-slate-200
                                          hover:file:bg-indigo-100 cursor-pointer transition">
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                        </div>
                    </div>
                </div>

                {{-- Grid Form Input Utama --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               placeholder="Masukkan nama lengkap"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                    </div>

                    <div>
                        <label for="email" class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
                            Alamat Email
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               placeholder="nama@domain.com"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-xs transition
                                      focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                {{-- Ubah Password Box --}}
                <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700/60 space-y-3">
                    <div>
                        <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Ubah Kata Sandi (Opsional)</h3>
                        <p class="text-[10px] text-slate-400">Kosongkan jika Anda tidak ingin mengganti kata sandi saat ini.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
                                Password Baru
                            </label>
                            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-xs transition
                                          focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-xs transition
                                          focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                        </div>
                    </div>
                </div>

                {{-- Footer Modal / Tombol Submit --}}
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="toggleEditModal(false)"
                            class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 active:scale-95 transition shadow-sm cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Javascript Sederhana untuk Menjalankan Overlay Modal --}}
<script>
    function toggleEditModal(show) {
        const modal = document.getElementById('editProfilModal');
        if (show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Kunci scroll halaman belakang
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Aktifkan kembali scroll
        }
    }

    // Menutup modal otomatis jika pengguna mengklik area luar modal box
    window.onclick = function(event) {
        const modal = document.getElementById('editProfilModal');
        if (event.target == modal) {
            toggleEditModal(false);
        }
    }
</script>
@endsection
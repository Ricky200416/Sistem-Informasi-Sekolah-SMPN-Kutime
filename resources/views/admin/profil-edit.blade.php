{{-- resources/views/admin/profil-edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Profil Admin')

@section('content')
<div class="max-w-lg">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm
                border border-slate-200 dark:border-slate-700 p-4">

        {{-- Header --}}
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4">
            Edit Profil Admin
        </h2>

        <form action="{{ route('admin.profil.update') }}" method="POST"
              enctype="multipart/form-data" class="space-y-3.5">
            @csrf
            @method('PUT')

            {{-- Foto Profil --}}
            <div>
                <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400
                           uppercase tracking-wide mb-1.5">Foto Profil</p>
                <div class="flex items-center gap-3">
                    {{-- Preview foto saat ini --}}
                    @if($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt=""
                             class="w-10 h-10 rounded-xl object-cover
                                    border border-slate-200 dark:border-slate-600 shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40
                                    flex items-center justify-center
                                    text-indigo-600 dark:text-indigo-400
                                    text-sm font-bold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="block text-xs text-slate-500 dark:text-slate-400 w-full
                                      file:mr-2 file:py-1 file:px-2.5
                                      file:rounded-lg file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-0.5">JPG, PNG — maks. 2MB</p>
                    </div>
                </div>
                @error('photo')
                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Divider --}}
            <div class="border-t border-slate-100 dark:border-slate-700"></div>

            {{-- Nama --}}
            <div>
                <label for="name"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $user->name) }}" required
                       class="w-full px-3 py-2 rounded-xl border text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              @error('name') border-red-400 bg-red-50 dark:bg-red-900/20
                              @else border-slate-200 dark:border-slate-600 @enderror
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
                @error('name')
                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}" required
                       class="w-full px-3 py-2 rounded-xl border text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              @error('email') border-red-400 bg-red-50 dark:bg-red-900/20
                              @else border-slate-200 dark:border-slate-600 @enderror
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
                @error('email')
                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Divider — ubah password --}}
            <div class="border-t border-slate-100 dark:border-slate-700 pt-0.5">
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500
                           uppercase tracking-wide mb-3">Ubah Password</p>
            </div>

            {{-- Password Baru --}}
            <div>
                <label for="password"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Password Baru
                    <span class="normal-case font-normal text-[10px] text-slate-400">
                        (kosongkan jika tidak diubah)
                    </span>
                </label>
                <input type="password" name="password" id="password"
                       placeholder="Min. 8 karakter"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200
                              dark:border-slate-600 text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
                @error('password')
                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation"
                       class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                              uppercase tracking-wide mb-1">
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       placeholder="Ulangi password baru"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200
                              dark:border-slate-600 text-xs transition
                              focus:outline-none focus:ring-2 focus:ring-indigo-300
                              bg-white dark:bg-slate-700
                              text-slate-800 dark:text-slate-200">
            </div>

            {{-- Tombol aksi --}}
            <div class="flex gap-2 pt-1">
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold
                               hover:bg-indigo-700 active:scale-95 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.profil') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                          text-slate-600 dark:text-slate-400 text-xs font-medium
                          hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
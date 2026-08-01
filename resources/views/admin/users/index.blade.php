{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')

<div class="space-y-4">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Kelola User</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Manajemen akun guru dan siswa pada sistem.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">

            {{-- Tombol Import --}}
            <button onclick="openModal('modalImport')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl
                           bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold
                           transition shadow-sm active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Excel
            </button>

            {{-- Dropdown Export --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl
                               bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600
                               text-slate-700 dark:text-slate-300 text-xs font-semibold
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                    <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-1.5 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-xl
                            border border-slate-200 dark:border-slate-700 py-1 z-30 origin-top-right">

                    <div class="px-3 py-1.5">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Excel</p>
                    </div>
                    <a href="{{ route('admin.users.export-excel', ['role' => 'guru']) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                     01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                        Data Guru (.xlsx)
                    </a>
                    <a href="{{ route('admin.users.export-excel', ['role' => 'siswa']) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                     01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                        Data Siswa (.xlsx)
                    </a>

                    <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>

                    <div class="px-3 py-1.5">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">PDF</p>
                    </div>
                    <a href="{{ route('admin.pdf.guru', ['role' => 'guru']) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1
                                     0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Data Guru (.pdf)
                    </a>
                    <a href="{{ route('admin.pdf.siswa', ['role' => 'siswa']) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1
                                     0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Data Siswa (.pdf)
                    </a>
                </div>
            </div>

            {{-- Tombol Tambah User --}}
            <button onclick="openModal('modalTambahUser')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl
                           bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </button>

        </div>
    </div>

    {{-- ===== ALERT SUCCESS ===== --}}
    @if(session('success'))
    <div class="flex items-start gap-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200
                dark:border-emerald-800 rounded-2xl p-4">
        <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
    </div>
    @endif

    {{-- ===== ALERT ERROR ===== --}}
    @if(session('error'))
    <div class="flex items-start gap-3 bg-red-50 dark:bg-red-950/40 border border-red-200
                dark:border-red-800 rounded-2xl p-4">
        <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs font-semibold text-red-600 dark:text-red-400">{{ session('error') }}</p>
    </div>
    @endif

    {{-- ===== ALERT IMPORT ERRORS ===== --}}
    @if(session('import_errors'))
    <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464
                         0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-xs font-bold text-amber-700 dark:text-amber-400">
                {{ count(session('import_errors')) }} baris gagal diimpor:
            </p>
        </div>
        <ul class="space-y-1 pl-6">
            @foreach(session('import_errors') as $err)
            <li class="text-[11px] text-amber-600 dark:text-amber-500 list-disc">{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ===== VALIDATION ERRORS ===== --}}
    @if($errors->any())
    <div class="bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs font-bold text-red-600 dark:text-red-400">Terdapat kesalahan:</p>
        </div>
        <ul class="space-y-1 pl-6">
            @foreach($errors->all() as $error)
            <li class="text-[11px] text-red-600 dark:text-red-400 list-disc">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ===== SEARCH ===== --}}
    <div class="relative max-w-xs">
        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari nama, email, NIP/NIS..."
               class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                      bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-300
                      placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-300
                      dark:focus:ring-indigo-700 transition">
    </div>

    {{-- ===== TABS ===== --}}
    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl w-fit">
        <a href="{{ route('admin.users.index', ['tab' => 'guru']) }}"
           class="px-4 py-1.5 rounded-lg text-xs font-semibold transition
                  {{ $activeTab === 'guru'
                     ? 'bg-white dark:bg-slate-700 shadow-sm text-indigo-700 dark:text-indigo-400'
                     : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Guru
            <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold
                         {{ $activeTab === 'guru' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-200 text-slate-500' }}">
                {{ $gurus->count() }}
            </span>
        </a>
        <a href="{{ route('admin.users.index', ['tab' => 'siswa']) }}"
           class="px-4 py-1.5 rounded-lg text-xs font-semibold transition
                  {{ $activeTab === 'siswa'
                     ? 'bg-white dark:bg-slate-700 shadow-sm text-indigo-700 dark:text-indigo-400'
                     : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Siswa
            <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold
                         {{ $activeTab === 'siswa' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-200 text-slate-500' }}">
                {{ $siswas->count() }}
            </span>
        </a>
    </div>

    {{-- ===== TABEL KONTEN ===== --}}
    <div id="tab-guru" class="{{ $activeTab !== 'guru' ? 'hidden' : '' }}">
        @include('admin.users._table_guru', ['users' => $gurus])
    </div>
    <div id="tab-siswa" class="{{ $activeTab !== 'siswa' ? 'hidden' : '' }}">
        @include('admin.users._table_siswa', ['users' => $siswas])
    </div>

</div>

@php
    $kelasForJs = ($kelasList ?? collect())->map(function ($k) {
        return [
            'id'            => $k->id,
            'name'          => $k->name,
            'grade'         => (string) ($k->grade ?? ''),
            'semester'      => (string) ($k->semester ?? ''),
            'academic_year' => $k->academic_year ?? '',
            'is_active'     => (bool) ($k->is_active ?? true),
        ];
    })->values();
@endphp
<script id="kelasListData" type="application/json">{!! json_encode($kelasForJs) !!}</script>

{{-- =========================================================== --}}
{{-- MODAL IMPORT (UPDATED: RESPONSIVE TWO-COLUMN LAYOUT)        --}}
{{-- =========================================================== --}}
<div id="modalImport"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-2xl overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Import Data via Excel</h3>
            </div>
            <button type="button" onclick="closeModal('modalImport')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.users.import') }}" method="POST"
              enctype="multipart/form-data" class="p-5 flex flex-col gap-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                
                {{-- KOLOM KIRI: Pengaturan Role & File --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                            Role Tujuan
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="guru" id="importRoleGuru" class="peer hidden" checked>
                                <div class="text-center py-2 rounded-xl border-2 border-slate-100 dark:border-slate-700
                                            peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-950/50
                                            text-xs font-semibold text-slate-600 dark:text-slate-400
                                            peer-checked:text-indigo-700 dark:peer-checked:text-indigo-400
                                            transition-all duration-150">
                                    👨‍🏫 Guru
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="siswa" id="importRoleSiswa" class="peer hidden">
                                <div class="text-center py-2 rounded-xl border-2 border-slate-100 dark:border-slate-700
                                            peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-950/50
                                            text-xs font-semibold text-slate-600 dark:text-slate-400
                                            peer-checked:text-indigo-700 dark:peer-checked:text-indigo-400
                                            transition-all duration-150">
                                    🎒 Siswa
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                            Password Default <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="password_import" id="passwordImport" required
                               placeholder="Password untuk semua akun baru..."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                                      bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300
                                      placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Min. 5 karakter.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                            File Excel (.xlsx / .xls) <span class="text-red-400">*</span>
                        </label>
                        <input type="file" name="import_file" id="importFileInput" required accept=".xlsx,.xls"
                               class="w-full text-xs text-slate-500 dark:text-slate-400
                                      file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                      file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950/50
                                      file:text-indigo-700 dark:file:text-indigo-400
                                      hover:file:bg-indigo-100 dark:hover:file:bg-indigo-950
                                      file:cursor-pointer cursor-pointer file:transition-colors">
                        <div id="filePreview" class="hidden mt-2 flex items-center gap-2 px-3 py-1.5 rounded-lg
                                                      bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200
                                                      dark:border-emerald-800">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                         a1 1 0 01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span id="filePreviewName" class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium truncate"></span>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Template & Penempatan Kelas Dinamis --}}
                <div class="space-y-4">
                    <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-600
                                bg-slate-50 dark:bg-slate-900/50 p-3">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                            Download Template Excel
                        </p>
                        <div class="flex gap-2">
                            <a id="btnTemplateGuru"
                               href="{{ route('admin.users.template-import', ['role' => 'guru']) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-xl
                                      border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-950/50
                                      text-indigo-700 dark:text-indigo-400 text-xs font-semibold
                                      hover:bg-indigo-100 dark:hover:bg-indigo-950 transition-colors duration-150">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Guru
                            </a>
                            <a id="btnTemplateSiswa"
                               href="{{ route('admin.users.template-import', ['role' => 'siswa']) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-xl
                                      border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/50
                                      text-emerald-700 dark:text-emerald-400 text-xs font-semibold
                                      hover:bg-emerald-100 dark:hover:bg-emerald-950 transition-colors duration-150">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Siswa
                            </a>
                        </div>
                        <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-1.5 leading-tight">
                            Mulai isi dari baris ke-3 pada berkas template.
                        </p>
                    </div>

                    <div id="sectionSiswaImport"
                         class="hidden space-y-3 bg-violet-50 dark:bg-violet-950/20
                                border border-violet-200 dark:border-violet-800 rounded-2xl p-3.5">
                        <p class="text-[10px] font-bold text-violet-600 dark:text-violet-400 uppercase tracking-wider">
                            📋 Penempatan Kelas Siswa
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
                                    Tingkat <span class="text-red-400">*</span>
                                </label>
                                <select name="import_grade" id="importGrade"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                               px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-300
                                               bg-white dark:bg-slate-700 dark:text-slate-200">
                                    <option value="">— Pilih —</option>
                                    <option value="7">VII (7)</option>
                                    <option value="8">VIII (8)</option>
                                    <option value="9">IX (9)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
                                    Semester <span class="text-red-400">*</span>
                                </label>
                                <select name="import_semester" id="importSemester"
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                               px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-300
                                               bg-white dark:bg-slate-700 dark:text-slate-200">
                                    <option value="">— Pilih —</option>
                                    <option value="1">Ganjil (1)</option>
                                    <option value="2">Genap (2)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">
                                Kelas <span class="text-red-400">*</span>
                            </label>
                            <select name="import_kelas_id" id="importKelasId"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-600
                                           px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-300
                                           bg-white dark:bg-slate-700 dark:text-slate-200">
                                <option value="">— Pilih tingkat &amp; semester dulu —</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                <button type="submit"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 active:scale-95
                               text-white py-2 rounded-xl text-xs font-bold transition shadow-sm">
                    Mulai Proses Import
                </button>
                <button type="button" onclick="closeModal('modalImport')"
                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                               text-xs font-semibold text-slate-600 dark:text-slate-400
                               hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
            </div>

        </form>
    </div>
</div>

{{-- =========================================================== --}}
{{-- MODAL BULK DELETE                                             --}}
{{-- =========================================================== --}}
<div id="modalBulkHapus"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200
                dark:border-slate-700 w-full max-w-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900/50 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus User Terpilih</h3>
            </div>
            <button onclick="closeModal('modalBulkHapus')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div class="flex items-start gap-3 bg-red-50 dark:bg-red-950/30 border border-red-200
                        dark:border-red-800 rounded-xl p-3.5">
                <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-xs font-bold text-red-700 dark:text-red-400">Konfirmasi Penghapusan</p>
                    <p class="text-[11px] text-red-600 dark:text-red-500 mt-0.5 leading-relaxed">
                        Akan menghapus <span id="bulkDeleteCount" class="font-bold">0</span> user yang dipilih.
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <form id="formBulkHapus" method="POST" action="{{ route('admin.users.bulk-destroy') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="ids" id="bulkDeleteIds">
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700 active:scale-95
                                   text-white py-2.5 rounded-xl text-xs font-bold transition shadow-sm">
                        Ya, Hapus Sekarang
                    </button>
                    <button type="button" onclick="closeModal('modalBulkHapus')"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                                   text-xs font-semibold text-slate-600 dark:text-slate-400
                                   hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL LAIN ===== --}}
@include('admin.users._modal_detail')
@include('admin.users._modal_tambah')
@include('admin.users._modal_edit')
@include('admin.users._modal_hapus')
@include('admin.users._modal_reset_password')

@endsection

@push('scripts')
<script>
// ── Buka / tutup modal ──────────────────────────────────────────────────────
function openModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
}

document.querySelectorAll('[id^="modal"]').forEach(modal => {
    modal.addEventListener('click', function (e) {
        if (e.target === this) closeModal(this.id);
    });
});

// ── Live Search ──────────────────────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.searchable-row').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
});

// ── Preview file ─────────────────────────────────────────────────────────────
document.getElementById('importFileInput').addEventListener('change', function () {
    const preview = document.getElementById('filePreview');
    const label   = document.getElementById('filePreviewName');
    if (this.files && this.files[0]) {
        label.textContent = this.files[0].name;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});

// ── Toggle password visibility ────────────────────────────────────────────────
function togglePwd(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

// ── Buka modal detail ─────────────────────────────────────────────────────────
function openDetailModal(userId) {
    fetch(`/admin/users/${userId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        buildDetailModal(data);
        openModal('modalDetail');
    })
    .catch(() => alert('Gagal memuat data user.'));
}

// ── Buka modal edit ───────────────────────────────────────────────────────────
function openEditModal(userId) {
    fetch(`/admin/users/${userId}/edit`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        populateEditModal(data);
        openModal('modalEditUser');
    })
    .catch(() => alert('Gagal memuat data user.'));
}

// ── Buka modal reset password ─────────────────────────────────────────────────
function openResetModal(userId, userName) {
    document.getElementById('resetUserName').textContent = userName;
    document.getElementById('formResetPwd').action = `/admin/users/${userId}/reset-password`;
    openModal('modalResetPwd');
}

// ── Buka modal hapus ──────────────────────────────────────────────────────────
function openDeleteModal(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('formHapus').action = `/admin/users/${userId}`;
    openModal('modalHapus');
}

// ── Bulk Delete ───────────────────────────────────────────────────────────────
function openBulkDeleteModal(tab) {
    const checkboxes = document.querySelectorAll(`.bulk-checkbox-${tab}:checked`);
    const ids = Array.from(checkboxes).map(cb => cb.value);

    if (ids.length === 0) {
        alert('Pilih minimal satu user yang akan dihapus.');
        return;
    }

    document.getElementById('bulkDeleteCount').textContent = ids.length;
    document.getElementById('bulkDeleteIds').value = ids.join(',');
    openModal('modalBulkHapus');
}

// ── Auto-open modal jika ada error validasi ──────────────────────────────────
@if($errors->any() && old('_form_context') === 'tambah')
    document.addEventListener('DOMContentLoaded', () => openModal('modalTambahUser'));
@endif

@if($errors->any() && old('_form_context') === 'edit')
    document.addEventListener('DOMContentLoaded', () => openModal('modalEditUser'));
@endif

// ── Import Modal: Toggle section siswa & filter kelas dinamis ────────────────
document.addEventListener('DOMContentLoaded', function () {

    var rawJson  = document.getElementById('kelasListData');
    var allKelas = rawJson ? JSON.parse(rawJson.textContent) : [];

    var roleGuru       = document.getElementById('importRoleGuru');
    var roleSiswa      = document.getElementById('importRoleSiswa');
    var sectionSiswa   = document.getElementById('sectionSiswaImport');
    var selectGrade    = document.getElementById('importGrade');
    var selectSemester = document.getElementById('importSemester');
    var selectKelas    = document.getElementById('importKelasId');

    if (!roleGuru || !roleSiswa || !sectionSiswa ||
        !selectGrade || !selectSemester || !selectKelas) {
        return;
    }

    function toggleSiswaSection() {
        var isSiswa = roleSiswa.checked;
        if (isSiswa) {
            sectionSiswa.classList.remove('hidden');
            selectGrade.setAttribute('required', 'required');
            selectSemester.setAttribute('required', 'required');
            selectKelas.setAttribute('required', 'required');
        } else {
            sectionSiswa.classList.add('hidden');
            selectGrade.removeAttribute('required');
            selectSemester.removeAttribute('required');
            selectKelas.removeAttribute('required');
            selectGrade.value     = '';
            selectSemester.value  = '';
            selectKelas.innerHTML = '<option value="">— Pilih tingkat &amp; semester dulu —</option>';
        }
    }

    function filterKelas() {
        var grade    = selectGrade.value;
        var semester = selectSemester.value;

        if (!grade || !semester) {
            selectKelas.innerHTML = '<option value="">— Pilih tingkat &amp; semester dulu —</option>';
            return;
        }

        var filtered = allKelas.filter(function (k) {
            return k.grade === String(grade) && k.semester === String(semester);
        });

        if (filtered.length === 0) {
            selectKelas.innerHTML =
                '<option value="">Tidak ada kelas untuk pilihan ini — tambahkan di Kelola Kelas</option>';
            return;
        }

        var html = '<option value="">— Pilih Kelas —</option>';
        filtered.forEach(function (k) {
            var label = k.name;
            if (k.academic_year) label += ' \u2014 ' + k.academic_year;
            html += '<option value="' + k.id + '">' + label + '</option>';
        });
        selectKelas.innerHTML = html;
    }

    roleGuru.addEventListener('change', toggleSiswaSection);
    roleSiswa.addEventListener('change', toggleSiswaSection);
    selectGrade.addEventListener('change', filterKelas);
    selectSemester.addEventListener('change', filterKelas);

    toggleSiswaSection();
});
</script>
@endpush
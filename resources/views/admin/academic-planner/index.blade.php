{{--
    resources/views/admin/academic-planner/index.blade.php
    Data diambil dari tabel study_groups — sama persis dengan Kelola Kelas.
--}}
@extends('layouts.app')
@section('title', 'Academic Planner - Dashboard')

@section('content')
<div class="space-y-4">

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data Akademik</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Kelola jadwal, kelas, dan mata pelajaran sekolah.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.academic-planner.study-subjects.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200
                      dark:border-slate-600 text-slate-600 dark:text-slate-400 text-xs font-medium
                      hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168
                             18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754
                             5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247
                             18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Manajemen Mapel
            </a>
            <a href="{{ route('admin.kelas.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-indigo-200
                      dark:border-indigo-700 text-indigo-600 dark:text-indigo-400 text-xs font-medium
                      hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9
                             0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1
                             1 0 011 1v5m-4 0h4"/>
                </svg>
                Kelola Kelas
            </a>
            <button onclick="openModal('modalTambahKelas')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600
                           text-white text-xs font-semibold hover:bg-indigo-700
                           active:scale-95 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kelas
            </button>
        </div>
    </div>

    {{-- ── Alert session ──────────────────────────────────────── --}}
    @if(session('success'))
        <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50
                    dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-[11px] text-emerald-700 dark:text-emerald-300 font-medium">
                {{ session('success') }}
            </p>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-red-50
                    dark:bg-red-900/20 border border-red-100 dark:border-red-800">
            <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                         3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-[11px] text-red-700 dark:text-red-300 font-medium">
                {{ session('error') }}
            </p>
        </div>
    @endif

    {{-- ── KPI Cards ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2
                                 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-slate-800 dark:text-slate-100 leading-none">
                        {{ $stats['total_groups'] }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Total Kelas</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477
                                 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-slate-800 dark:text-slate-100 leading-none">
                        {{ $stats['total_subjects'] }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Mata Pelajaran</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-slate-800 dark:text-slate-100 leading-none">
                        {{ $stats['total_timetables'] }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Total Jadwal</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                    dark:border-slate-700 shadow-sm px-4 py-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-sky-100 dark:bg-sky-900/40
                            flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-sky-600 dark:text-sky-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283
                                 -.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283
                                 .356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-slate-800 dark:text-slate-100 leading-none">
                        {{ $stats['total_teachers'] }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Guru</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Daftar Kelas per Tingkat ─────────────────────────────── --}}
    @forelse ($groupsByGrade as $grade => $groups)
        <div class="space-y-2">

            {{-- Label tingkat --}}
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px]
                             font-bold bg-indigo-600 text-white">
                    Kelas {{ $grade }}
                </span>
                <span class="text-[10px] text-slate-400">{{ $groups->count() }} kelas</span>
                <div class="flex-1 h-px bg-slate-100 dark:bg-slate-700"></div>
            </div>

            {{-- Grid kelas --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">

                @foreach ($groups as $group)
                    <a href="{{ route('admin.academic-planner.study-group.show', $group->id) }}"
                       class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                              dark:border-slate-700 shadow-sm p-3.5 flex flex-col
                              hover:border-indigo-300 dark:hover:border-indigo-600
                              hover:shadow-md transition-all duration-150 text-decoration-none">

                        {{-- Nama kelas besar --}}
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-xl font-black text-indigo-600 dark:text-indigo-400
                                         leading-none tracking-tight">
                                {{ $group->name }}
                            </span>
                            {{-- Badge semester --}}
                            @if($group->semester)
                                <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px] font-bold
                                             {{ $group->semester == 1
                                                ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                : 'bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400' }}">
                                    S{{ $group->semester }}
                                </span>
                            @endif
                        </div>

                        {{-- Wali kelas --}}
                        <div class="flex items-center gap-1.5 mb-1 min-w-0">
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 truncate">
                                {{ $group->homeroomTeacher?->name ?? 'Belum ada wali kelas' }}
                            </span>
                        </div>

                        {{-- Ruang --}}
                        @if($group->room)
                            <div class="flex items-center gap-1.5 mb-2">
                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8
                                             8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 truncate">
                                    {{ $group->room }}
                                </span>
                            </div>
                        @endif

                        {{-- Footer: tahun ajaran + panah --}}
                        <div class="flex items-center justify-between mt-auto pt-2
                                    border-t border-slate-100 dark:border-slate-700">
                            <span class="text-[9px] text-slate-400">
                                {{ $group->academic_year }}
                            </span>
                            <span class="text-[10px] text-indigo-500 dark:text-indigo-400
                                         font-semibold group-hover:translate-x-0.5 transition-transform
                                         flex items-center gap-0.5">
                                Jadwal
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>

                    </a>
                @endforeach

                {{-- Tombol tambah kelas di tingkat ini --}}
                <button onclick="openModal('modalTambahKelas')"
                        class="flex flex-col items-center justify-center rounded-2xl
                               border-2 border-dashed border-slate-200 dark:border-slate-600
                               p-3.5 text-slate-400 dark:text-slate-500
                               hover:border-indigo-300 dark:hover:border-indigo-600
                               hover:text-indigo-500 dark:hover:text-indigo-400
                               hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10
                               transition min-h-[90px]">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-[10px] font-medium">Tambah Kelas</span>
                </button>

            </div>
        </div>
    @empty
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700
                        flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-slate-300 dark:text-slate-600"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3M5 11h14M5 19h14M5 5h2m10 0h2"/>
                </svg>
            </div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                Belum ada kelas
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5 mb-4">
                Mulai dengan menambahkan kelas pertama.
            </p>
            <button onclick="openModal('modalTambahKelas')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600
                           text-white text-xs font-semibold hover:bg-indigo-700 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kelas Pertama
            </button>
        </div>
    @endforelse

</div>

{{-- ── Modal Tambah Kelas (sama persis dengan Kelola Kelas) ────── --}}
@php $gurus = \App\Models\User::whereIn('role', ['guru','kepala_sekolah'])->where('is_active', true)->orderBy('name')->get(); @endphp
@include('admin.kelas._modal_tambah', ['gurus' => $gurus])

@push('styles')
<style>
    .animate-modal {
        animation: modalPop .22s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes modalPop {
        from { opacity:0; transform:scale(.92) translateY(10px); }
        to   { opacity:1; transform:scale(1)   translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
function openModal(id) {
    var el = document.getElementById(id);
    el.classList.remove('hidden'); el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    var el = document.getElementById(id);
    el.classList.add('hidden'); el.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal('modalTambahKelas');
});
</script>
@endpush

@endsection
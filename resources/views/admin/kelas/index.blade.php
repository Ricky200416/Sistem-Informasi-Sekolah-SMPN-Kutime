{{-- resources/views/admin/kelas/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Kelola Kelas')

@section('content')

<div class="space-y-4">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Kelola Kelas</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Manajemen data kelas dan wali kelas — tersinkron dengan Data Akademik.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.academic-planner.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200
                      dark:border-slate-600 text-slate-600 dark:text-slate-400 text-xs font-medium
                      hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0
                             012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0
                             01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Data Akademik
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

    {{-- ── Alerts ──────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50
                    dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
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
            <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0
                         001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-[11px] text-red-700 dark:text-red-300 font-medium">
                {{ session('error') }}
            </p>
        </div>
    @endif

    @if($errors->any())
        <div class="px-3.5 py-2.5 rounded-xl bg-red-50 dark:bg-red-900/20
                    border border-red-100 dark:border-red-800">
            <p class="text-[10px] font-bold text-red-600 dark:text-red-400 mb-1">
                Terdapat kesalahan input:
            </p>
            <ul class="space-y-0.5">
                @foreach($errors->all() as $error)
                    <li class="text-[10px] text-red-600 dark:text-red-400 list-disc ml-4">
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── Info Banner ─────────────────────────────────────────── --}}
    <div class="flex items-start gap-2.5 px-3.5 py-2.5 rounded-xl bg-indigo-50
                dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800">
        <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400 mt-0.5 shrink-0"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-[10px] text-indigo-700 dark:text-indigo-300 leading-relaxed">
            Data kelas tersinkron dengan
            <a href="{{ route('admin.academic-planner.index') }}"
               class="font-semibold underline underline-offset-2">Data Akademik</a>
            dan
            <a href="{{ route('admin.users.index') }}"
               class="font-semibold underline underline-offset-2">Kelola User</a>.
        </p>
    </div>

    {{-- ── Filter & Search ─────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row gap-2">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" id="searchInput"
                   placeholder="Cari kelas, wali kelas, ruang..."
                   class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200
                          dark:border-slate-600 bg-white dark:bg-slate-800 text-xs
                          focus:outline-none focus:ring-2 focus:ring-indigo-300
                          placeholder:text-slate-400 transition">
        </div>
        <select id="filterSemester"
                class="rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-xs
                       bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300
                       focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Semua Semester</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
        </select>
        <select id="filterTingkat"
                class="rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-xs
                       bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300
                       focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Semua Tingkat</option>
            <option value="7">Kelas 7</option>
            <option value="8">Kelas 8</option>
            <option value="9">Kelas 9</option>
        </select>
    </div>

    {{-- ── Bulk Action Bar (muncul saat ada checkbox yang dipilih) ── --}}
    <div id="bulkActionBar"
         class="hidden items-center justify-between gap-3 px-4 py-2.5 rounded-xl
                bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
                transition-all">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-semibold text-red-700 dark:text-red-300">
                <span id="selectedCount">0</span> kelas dipilih
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="deselectAll()"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-300
                           dark:border-slate-600 text-slate-600 dark:text-slate-300 text-xs font-medium
                           hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                Batal Pilih
            </button>
            <button onclick="openBulkDeleteModal()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-600
                           text-white text-xs font-semibold hover:bg-red-700 active:scale-95 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                             01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                             00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus yang Dipilih
            </button>
        </div>
    </div>

    {{-- ── Tabel ───────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200
                               dark:border-slate-700 text-left">
                        {{-- ── Checkbox Select All ── --}}
                        <th class="px-3 py-2.5 w-8">
                            <input type="checkbox" id="checkboxSelectAll"
                                   onclick="toggleSelectAll(this)"
                                   class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600
                                          text-indigo-600 focus:ring-indigo-300 cursor-pointer
                                          accent-indigo-600">
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide w-7">#</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            Nama Kelas
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Tingkat</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Semester</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">
                            Tahun Ajaran
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[160px]">
                            Wali Kelas
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[80px]">
                            Ruang
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center">
                            Jadwal
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center">
                            Status
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center min-w-[90px]">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">

                    @forelse($kelas as $i => $k)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors searchable-row
                                   selected:bg-red-50 dark:selected:bg-red-900/10"
                            id="row-{{ $k->id }}"
                            data-semester="{{ $k->semester }}"
                            data-tingkat="{{ $k->grade }}">

                            {{-- ── Checkbox per baris ── --}}
                            <td class="px-3 py-2.5">
                                <input type="checkbox"
                                       class="row-checkbox w-3.5 h-3.5 rounded border-slate-300
                                              dark:border-slate-600 text-indigo-600
                                              focus:ring-indigo-300 cursor-pointer accent-indigo-600"
                                       value="{{ $k->id }}"
                                       onchange="onRowCheckboxChange()">
                            </td>

                            <td class="px-3 py-2.5 text-[10px] text-slate-400">{{ $i + 1 }}</td>

                            {{-- Nama Kelas --}}
                            <td class="px-3 py-2.5">
                                <a href="{{ route('admin.academic-planner.study-group.show', $k->id) }}"
                                   class="text-xs font-semibold text-indigo-600
                                          dark:text-indigo-400 hover:underline underline-offset-2">
                                    {{ $k->name }}
                                </a>
                                @if($k->section)
                                    <span class="text-[10px] text-slate-400 ml-1">
                                        ({{ $k->section }})
                                    </span>
                                @endif
                            </td>

                            {{-- Tingkat --}}
                            <td class="px-3 py-2.5">
                                <span class="inline-flex px-1.5 py-0.5 rounded-lg text-[10px]
                                             font-semibold bg-indigo-50 text-indigo-700
                                             dark:bg-indigo-900/30 dark:text-indigo-300
                                             border border-indigo-100 dark:border-indigo-800">
                                    {{ $k->grade ? 'Kelas '.$k->grade : '—' }}
                                </span>
                            </td>

                            {{-- Semester --}}
                            <td class="px-3 py-2.5">
                                @if($k->semester)
                                    <span class="inline-flex px-1.5 py-0.5 rounded-lg text-[10px] font-semibold
                                                 {{ $k->semester == 1
                                                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-800'
                                                    : 'bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300 border border-violet-100 dark:border-violet-800' }}">
                                        Sem {{ $k->semester }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600">—</span>
                                @endif
                            </td>

                            {{-- Tahun Ajaran --}}
                            <td class="px-3 py-2.5 text-[10px] text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                {{ $k->academic_year ?? '—' }}
                            </td>

                            {{-- Wali Kelas --}}
                            <td class="px-3 py-2.5">
                                @if($k->homeroomTeacher)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                                                    flex items-center justify-center text-indigo-600
                                                    dark:text-indigo-400 text-[10px] font-bold shrink-0">
                                            {{ strtoupper(substr($k->homeroomTeacher->name, 0, 1)) }}
                                        </div>
                                        <span class="text-xs text-slate-700 dark:text-slate-300
                                                     truncate max-w-[120px]">
                                            {{ $k->homeroomTeacher->name }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600 italic">
                                        Belum ditentukan
                                    </span>
                                @endif
                            </td>

                            {{-- Ruang --}}
                            <td class="px-3 py-2.5 text-[10px] text-slate-600 dark:text-slate-400">
                                {{ $k->room ?? '—' }}
                            </td>

                            {{-- Jadwal --}}
                            <td class="px-3 py-2.5 text-center">
                                <a href="{{ route('admin.academic-planner.study-group.show', $k->id) }}"
                                   class="inline-flex items-center justify-center min-w-[1.6rem] h-5 px-1.5
                                          rounded-full text-[10px] font-semibold
                                          bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30
                                          dark:text-indigo-300 hover:bg-indigo-100 transition">
                                    {{ $k->timetables_count ?? 0 }}
                                </a>
                            </td>

                            {{-- Status --}}
                            <td class="px-3 py-2.5 text-center">
                                @if($k->is_active)
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5
                                                 rounded-full text-[10px] font-semibold
                                                 bg-emerald-50 text-emerald-700
                                                 dark:bg-emerald-900/30 dark:text-emerald-300">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 inline-block"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5
                                                 rounded-full text-[10px] font-semibold
                                                 bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                                        <span class="w-1 h-1 rounded-full bg-slate-400 inline-block"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-3 py-2.5">
                                <div class="flex items-center justify-center gap-0.5">
                                    <a href="{{ route('admin.academic-planner.study-group.show', $k->id) }}"
                                       title="Lihat Jadwal"
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600
                                              hover:bg-blue-50 dark:hover:text-blue-400
                                              dark:hover:bg-blue-900/30 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732
                                                     7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542
                                                     7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <button onclick="openEditKelasModal({{ $k->id }})"
                                            title="Edit Kelas"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600
                                                   hover:bg-amber-50 dark:hover:text-amber-400
                                                   dark:hover:bg-amber-900/30 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0
                                                     002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828
                                                     15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="openDeleteModal({{ $k->id }}, '{{ addslashes($k->name) }}')"
                                            title="Hapus Kelas"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-600
                                                   hover:bg-red-50 dark:hover:text-red-400
                                                   dark:hover:bg-red-900/30 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                     01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                                     00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700
                                                flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-300 dark:text-slate-600"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14
                                                     0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1
                                                     4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                            Belum ada data kelas
                                        </p>
                                        <p class="text-[10px] mt-0.5 text-slate-400">
                                            Gunakan tombol <strong class="text-slate-600 dark:text-slate-300">
                                            + Tambah Kelas</strong> untuk menambahkan.
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ── Data JSON untuk modal edit ── --}}
<script id="kelasData" type="application/json">
{!! json_encode(
    $kelas->map(fn($k) => [
        'id'                  => $k->id,
        'name'                => $k->name,
        'grade'               => (string) $k->grade,
        'section'             => $k->section        ?? '',
        'academic_year'       => $k->academic_year  ?? '',
        'semester'            => (string) ($k->semester ?? ''),
        'homeroom_teacher_id' => $k->homeroom_teacher_id ? (string) $k->homeroom_teacher_id : '',
        'room'                => $k->room            ?? '',
        'is_active'           => (bool) $k->is_active,
        'capacity'            => $k->capacity        ?? 30,
    ])->keyBy('id')
) !!}
</script>

@include('admin.kelas._modal_tambah', ['gurus' => $gurus])
@include('admin.kelas._modal_edit',   ['gurus' => $gurus])
@include('admin.kelas._modal_hapus')

{{-- ── Modal Hapus Massal ─────────────────────────────────────────────────── --}}
<div id="modalHapusMassal"
     class="hidden fixed inset-0 z-50 items-center justify-center p-4
            bg-black/40 backdrop-blur-sm">
    <div class="animate-modal bg-white dark:bg-slate-800 rounded-2xl shadow-xl
                border border-slate-200 dark:border-slate-700 w-full max-w-sm p-5">

        {{-- Icon --}}
        <div class="flex items-center justify-center w-12 h-12 rounded-2xl
                    bg-red-100 dark:bg-red-900/30 mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                         01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                         00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 text-center mb-1">
            Konfirmasi Hapus Massal
        </h3>
        <p class="text-[11px] text-slate-500 dark:text-slate-400 text-center mb-1">
            Kamu akan menghapus
            <strong class="text-red-600 dark:text-red-400" id="bulkDeleteCount">0</strong>
            kelas yang dipilih:
        </p>

        {{-- Daftar nama kelas yang akan dihapus --}}
        <div id="bulkDeleteList"
             class="mt-2 mb-4 max-h-36 overflow-y-auto rounded-xl border border-slate-200
                    dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 px-3 py-2 space-y-1">
        </div>

        <p class="text-[10px] text-slate-400 dark:text-slate-500 text-center mb-4">
            Tindakan ini <strong class="text-red-500">tidak dapat dibatalkan</strong>.
            Pastikan data yang dihapus sudah benar.
        </p>

        <div class="flex gap-2">
            <button onclick="closeModal('modalHapusMassal')"
                    class="flex-1 px-4 py-2 rounded-xl border border-slate-200
                           dark:border-slate-700 text-xs font-semibold text-slate-600
                           dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                Batal
            </button>
            <form id="formHapusMassal"
                  action="{{ route('admin.kelas.bulk-destroy') }}"
                  method="POST"
                  class="flex-1">
                @csrf
                @method('DELETE')
                {{-- Hidden inputs untuk IDs akan diisi oleh JS --}}
                <div id="bulkDeleteInputs"></div>
                <button type="submit"
                        class="w-full px-4 py-2 rounded-xl bg-red-600 text-white text-xs
                               font-semibold hover:bg-red-700 active:scale-95 transition">
                    Ya, Hapus Semua
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .animate-modal {
        animation: modalPop .22s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes modalPop {
        from { opacity:0; transform:scale(.92) translateY(10px); }
        to   { opacity:1; transform:scale(1)   translateY(0); }
    }

    /* Row highlight saat checkbox dicentang */
    .row-selected {
        background-color: rgb(254 242 242) !important;  /* red-50 */
    }
    .dark .row-selected {
        background-color: rgb(127 29 29 / 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
// ── Data kelas dari server ────────────────────────────────────────────────────
const KELAS_DATA = JSON.parse(document.getElementById('kelasData').textContent);

/* ────────────────────────────────────────────────────────────────────────────
   Modal helpers
──────────────────────────────────────────────────────────────────────────── */
function openModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['modalTambahKelas', 'modalEditKelas', 'modalHapusKelas', 'modalHapusMassal']
            .forEach(closeModal);
    }
});

/* ────────────────────────────────────────────────────────────────────────────
   Checkbox — Select All / Per Baris
──────────────────────────────────────────────────────────────────────────── */

/** Kembalikan semua checkbox baris yang sedang terlihat (tidak hidden oleh filter) */
function getVisibleCheckboxes() {
    return Array.from(document.querySelectorAll('.row-checkbox')).filter(cb => {
        const row = cb.closest('tr');
        return row && row.style.display !== 'none';
    });
}

/** Kembalikan semua checkbox yang sudah dicentang */
function getCheckedCheckboxes() {
    return Array.from(document.querySelectorAll('.row-checkbox:checked'));
}

/** Toggle highlight warna pada baris */
function updateRowHighlight(checkbox) {
    const row = checkbox.closest('tr');
    if (!row) return;
    if (checkbox.checked) {
        row.classList.add('row-selected');
    } else {
        row.classList.remove('row-selected');
    }
}

/** Sinkron state checkbox "select all" di header */
function syncSelectAllCheckbox() {
    const all      = getVisibleCheckboxes();
    const checked  = all.filter(cb => cb.checked);
    const selectAll = document.getElementById('checkboxSelectAll');
    if (!selectAll) return;

    if (checked.length === 0) {
        selectAll.checked       = false;
        selectAll.indeterminate = false;
    } else if (checked.length === all.length) {
        selectAll.checked       = true;
        selectAll.indeterminate = false;
    } else {
        selectAll.checked       = false;
        selectAll.indeterminate = true;  // sebagian terpilih
    }
}

/** Update bulk action bar — tampilkan/sembunyikan dan update counter */
function updateBulkBar() {
    const checked = getCheckedCheckboxes();
    const bar     = document.getElementById('bulkActionBar');
    const counter = document.getElementById('selectedCount');

    if (!bar || !counter) return;

    if (checked.length > 0) {
        bar.classList.remove('hidden');
        bar.classList.add('flex');
    } else {
        bar.classList.add('hidden');
        bar.classList.remove('flex');
    }
    counter.textContent = checked.length;
}

/** Dipanggil saat checkbox header diklik */
function toggleSelectAll(masterCb) {
    const visible = getVisibleCheckboxes();
    visible.forEach(cb => {
        cb.checked = masterCb.checked;
        updateRowHighlight(cb);
    });
    syncSelectAllCheckbox();
    updateBulkBar();
}

/** Dipanggil saat checkbox per baris berubah */
function onRowCheckboxChange() {
    // update highlight baris yang berubah
    document.querySelectorAll('.row-checkbox').forEach(updateRowHighlight);
    syncSelectAllCheckbox();
    updateBulkBar();
}

/** Batalkan semua pilihan */
function deselectAll() {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.checked = false;
        updateRowHighlight(cb);
    });
    const selectAll = document.getElementById('checkboxSelectAll');
    if (selectAll) {
        selectAll.checked       = false;
        selectAll.indeterminate = false;
    }
    updateBulkBar();
}

/* ────────────────────────────────────────────────────────────────────────────
   Modal Hapus Massal
──────────────────────────────────────────────────────────────────────────── */
function openBulkDeleteModal() {
    const checked = getCheckedCheckboxes();
    if (checked.length === 0) return;

    const ids   = checked.map(cb => cb.value);
    const names = ids.map(id => KELAS_DATA[id]?.name ?? 'Kelas #' + id);

    // Update counter
    document.getElementById('bulkDeleteCount').textContent = ids.length;

    // Render daftar nama
    const listEl = document.getElementById('bulkDeleteList');
    listEl.innerHTML = names.map(name => `
        <div class="flex items-center gap-1.5">
            <span class="w-1 h-1 rounded-full bg-red-400 shrink-0 inline-block"></span>
            <span class="text-[11px] text-slate-700 dark:text-slate-300">${name}</span>
        </div>
    `).join('');

    // Isi hidden inputs untuk form
    const inputsEl = document.getElementById('bulkDeleteInputs');
    inputsEl.innerHTML = ids.map(id =>
        `<input type="hidden" name="ids[]" value="${id}">`
    ).join('');

    openModal('modalHapusMassal');
}

/* ────────────────────────────────────────────────────────────────────────────
   Modal Edit Kelas
──────────────────────────────────────────────────────────────────────────── */
function openEditKelasModal(kelasId) {
    const d = KELAS_DATA[kelasId];
    if (!d) {
        alert('Data kelas tidak ditemukan.');
        return;
    }

    const set = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = (val !== null && val !== undefined) ? String(val) : '';
    };

    const setCheck = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.checked = !!val;
    };

    set('editKelasName',            d.name);
    set('editKelasGrade',           d.grade);
    set('editKelasSection',         d.section);
    set('editKelasAcademicYear',    d.academic_year);
    set('editKelasSemester',        d.semester);
    set('editKelasRoom',            d.room);
    set('editKelasCapacity',        d.capacity);
    set('editKelasHomeroomTeacher', d.homeroom_teacher_id);
    setCheck('editKelasIsActive',   d.is_active);

    document.getElementById('formEditKelas').action = '/admin/kelas/' + kelasId;

    openModal('modalEditKelas');
}

/* ────────────────────────────────────────────────────────────────────────────
   Modal Hapus (single)
──────────────────────────────────────────────────────────────────────────── */
function openDeleteModal(kelasId, kelasNama) {
    document.getElementById('deleteKelasName').textContent = kelasNama;
    document.getElementById('formHapusKelas').action = '/admin/kelas/' + kelasId;
    openModal('modalHapusKelas');
}

/* ────────────────────────────────────────────────────────────────────────────
   Search & Filter
──────────────────────────────────────────────────────────────────────────── */
function filterRows() {
    const q   = (document.getElementById('searchInput')?.value ?? '').toLowerCase();
    const sem = document.getElementById('filterSemester')?.value ?? '';
    const tkt = document.getElementById('filterTingkat')?.value  ?? '';

    document.querySelectorAll('.searchable-row').forEach(row => {
        const matchText = row.textContent.toLowerCase().includes(q);
        const matchSem  = !sem || row.dataset.semester === sem;
        const matchTkt  = !tkt || row.dataset.tingkat  === tkt;
        row.style.display = (matchText && matchSem && matchTkt) ? '' : 'none';
    });

    // Setelah filter, sinkron kembali checkbox select-all dan bulk bar
    syncSelectAllCheckbox();
    updateBulkBar();
}

document.getElementById('searchInput')?.addEventListener('input', filterRows);
document.getElementById('filterSemester')?.addEventListener('change', filterRows);
document.getElementById('filterTingkat')?.addEventListener('change', filterRows);

/* ────────────────────────────────────────────────────────────────────────────
   Auto-buka modal tambah jika ada validation error
──────────────────────────────────────────────────────────────────────────── */
@if($errors->any())
    document.addEventListener('DOMContentLoaded', () => openModal('modalTambahKelas'));
@endif
</script>
@endpush

@endsection
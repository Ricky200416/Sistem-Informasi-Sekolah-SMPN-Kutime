{{-- resources/views/admin/pengumuman/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Kelola Pengumuman')

@section('content')

@php
    if (!isset($pengumuman)) {
        $q = \App\Models\Pengumuman::with('creator')->latest();
        if (request()->filled('filter_audience')) $q->where('target_audience', request('filter_audience'));
        if (request()->filled('filter_status'))   $q->where('is_active', request('filter_status') === 'aktif');
        if (request()->filled('search'))           $q->where('judul', 'like', '%'.request('search').'%');
        $pengumuman = $q->paginate(15)->withQueryString();
    }
    $total = \App\Models\Pengumuman::count();
    $aktif = \App\Models\Pengumuman::where('is_active', true)->count();
    $guru  = \App\Models\Pengumuman::where('target_audience', 'guru')->count();
    $siswa = \App\Models\Pengumuman::where('target_audience', 'siswa')->count();
    $semua = \App\Models\Pengumuman::where('target_audience', 'semua')->count();
@endphp

{{-- Modal Detail --}}
<div id="pgModal"
     onclick="if(event.target===this)pgTutup()"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.55);backdrop-filter:blur(5px)">
    <div class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-2xl shadow-2xl
                border border-slate-200 dark:border-slate-700">
        <button onclick="pgTutup()"
                class="absolute top-3 right-3 z-10 w-6 h-6 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-500 rounded-lg transition-all">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div id="pgModalKonten" class="p-4"></div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Massal --}}
<div id="pgBulkDeleteModal"
     onclick="if(event.target===this)pgTutupBulkModal()"
     class="fixed inset-0 z-[1000] hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,.6);backdrop-filter:blur(6px)">
    <div class="w-full max-w-sm bg-white dark:bg-slate-800 rounded-2xl shadow-2xl
                border border-slate-200 dark:border-slate-700 p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-xl shrink-0">
                🗑️
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Hapus Pengumuman</h3>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
            </div>
        </div>
        <p class="text-xs text-slate-600 dark:text-slate-300 mb-4" id="pgBulkDeleteDesc">
            Yakin ingin menghapus <strong id="pgBulkDeleteCount">0</strong> pengumuman yang dipilih?
        </p>
        <div class="flex gap-2">
            <button onclick="pgTutupBulkModal()"
                    class="flex-1 px-4 py-2 rounded-xl text-xs font-semibold
                           bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600
                           text-slate-700 dark:text-slate-200 transition">
                Batal
            </button>
            <button onclick="pgKonfirmasiBulkDelete()"
                    class="flex-1 px-4 py-2 rounded-xl text-xs font-semibold
                           bg-red-600 hover:bg-red-700 text-white transition">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<div class="space-y-4">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">📢 Kelola Pengumuman</h2>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                Buat &amp; kelola pengumuman untuk Guru dan Siswa
            </p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600
                  hover:bg-indigo-700 text-white text-xs font-semibold transition shadow-sm w-fit">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengumuman
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach([
            ['label'=>'Total',  'val'=>$total,       'icon'=>'📋','c'=>'text-slate-700 dark:text-slate-200'],
            ['label'=>'Aktif',  'val'=>$aktif,       'icon'=>'✅','c'=>'text-emerald-600 dark:text-emerald-400'],
            ['label'=>'Guru',   'val'=>$guru+$semua, 'icon'=>'👨‍🏫','c'=>'text-violet-600 dark:text-violet-400'],
            ['label'=>'Siswa',  'val'=>$siswa+$semua,'icon'=>'🎓','c'=>'text-sky-600 dark:text-sky-400'],
        ] as $st)
        <div class="bg-white dark:bg-slate-800 rounded-2xl px-3.5 py-3
                    border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="mb-0.5" style="font-size:1.1rem">{{ $st['icon'] }}</div>
            <div class="text-base font-bold {{ $st['c'] }}">{{ $st['val'] }}</div>
            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ $st['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 p-3.5 shadow-sm">
        <form method="GET" action="{{ route('admin.pengumuman') }}"
              class="flex flex-wrap gap-2.5 items-end">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                               mb-1 uppercase tracking-wide">Cari Judul</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Ketik judul..."
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200
                              dark:border-slate-600 bg-slate-50 dark:bg-slate-700
                              text-slate-800 dark:text-slate-200
                              focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div class="min-w-[120px]">
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                               mb-1 uppercase tracking-wide">Target</label>
                <select name="filter_audience"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200
                               dark:border-slate-600 bg-slate-50 dark:bg-slate-700
                               text-slate-800 dark:text-slate-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">Semua Target</option>
                    <option value="semua" {{ request('filter_audience')=='semua'?'selected':'' }}>Semua</option>
                    <option value="guru"  {{ request('filter_audience')=='guru' ?'selected':'' }}>Guru</option>
                    <option value="siswa" {{ request('filter_audience')=='siswa'?'selected':'' }}>Siswa</option>
                </select>
            </div>
            <div class="min-w-[120px]">
                <label class="block text-[10px] font-semibold text-slate-500 dark:text-slate-400
                               mb-1 uppercase tracking-wide">Status</label>
                <select name="filter_status"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200
                               dark:border-slate-600 bg-slate-50 dark:bg-slate-700
                               text-slate-800 dark:text-slate-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('filter_status')=='aktif'   ?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('filter_status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white
                               text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search','filter_audience','filter_status']))
                <a href="{{ route('admin.pengumuman') }}"
                   class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700
                          dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300
                          text-xs font-semibold rounded-xl transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Bulk Action Bar (muncul saat ada yang dicentang) --}}
    <div id="pgBulkBar"
         class="hidden items-center justify-between gap-3 px-4 py-3 rounded-2xl
                bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200
                dark:border-indigo-700 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center text-indigo-600 dark:text-indigo-300 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-300">
                <span id="pgSelectedCount">0</span> pengumuman dipilih
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="pgDeselectAll()"
                    class="px-3 py-1.5 text-xs font-semibold rounded-xl
                           bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600
                           text-slate-600 dark:text-slate-300 border border-slate-200
                           dark:border-slate-600 transition">
                Batal Pilih
            </button>
            <button onclick="pgBukaBulkModal()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold
                           rounded-xl bg-red-600 hover:bg-red-700 text-white transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                             01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                             00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus yang Dipilih
            </button>
        </div>
    </div>

    {{-- Form Bulk Delete (hidden, di-submit via JS) --}}
    <form id="pgBulkDeleteForm" method="POST" action="{{ route('admin.pengumuman.bulkDestroy') }}" class="hidden">
        @csrf
        @method('DELETE')
        <div id="pgBulkDeleteIds"></div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden">

        @if($pengumuman->isEmpty())
        <div class="text-center py-14">
            <div class="text-4xl mb-2">📭</div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Belum ada pengumuman.</p>
            <a href="{{ route('admin.pengumuman.create') }}"
               class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl
                      bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition">
                + Tambah Sekarang
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700
                               bg-slate-50 dark:bg-slate-900/50 text-left">
                        {{-- Checkbox Pilih Semua --}}
                        <th class="px-4 py-2.5 w-8">
                            <div class="flex items-center justify-center">
                                <input type="checkbox" id="pgCheckAll"
                                       onchange="pgToggleAll(this)"
                                       title="Pilih semua"
                                       class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600
                                              text-indigo-600 bg-white dark:bg-slate-700
                                              focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer
                                              accent-indigo-600">
                            </div>
                        </th>
                        <th class="px-4 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Pengumuman</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Target</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Tipe</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Dashboard</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Status</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Tanggal</th>
                        <th class="px-3 py-2.5 text-right text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50" id="pgTableBody">
                    @foreach($pengumuman as $item)
                    @php
                        $pgFileUrl = $item->file_path ? asset('storage/' . $item->file_path) : '';
                        $pgData = [
                            'judul'         => (string)($item->judul ?? ''),
                            'isi'           => (string)($item->isi ?? ''),
                            'tipe'          => (string)($item->tipe_konten ?? 'teks'),
                            'tipeIcon'      => (string)($item->tipeIcon()),
                            'audience'      => (string)($item->audienceLabel()),
                            'audienceColor' => (string)($item->audienceBadgeColor()),
                            'fileUrl'       => $pgFileUrl,
                            'fileName'      => (string)($item->file_name ?? ''),
                            'fileExt'       => (string)($item->fileExtension() ?? ''),
                            'linkUrl'       => (string)($item->link_url ?? ''),
                            'linkLabel'     => (string)($item->link_label ?? 'Buka Link'),
                            'tanggal'       => $item->created_at->isoFormat('D MMMM Y, HH:mm'),
                            'diffHumans'    => $item->created_at->diffForHumans(),
                            'creator'       => (string)(optional($item->creator)->name ?? 'Admin'),
                            'tglSelesai'    => $item->tanggal_selesai
                                                ? $item->tanggal_selesai->isoFormat('D MMM Y, HH:mm')
                                                : '',
                        ];
                        $pgJson = json_encode($pgData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group
                               pg-row" data-id="{{ $item->id }}">

                        {{-- Checkbox baris --}}
                        <td class="px-4 py-3 w-8">
                            <div class="flex items-center justify-center">
                                <input type="checkbox"
                                       class="pg-row-check w-3.5 h-3.5 rounded border-slate-300
                                              dark:border-slate-600 text-indigo-600 bg-white
                                              dark:bg-slate-700 focus:ring-indigo-500
                                              focus:ring-offset-0 cursor-pointer accent-indigo-600"
                                       value="{{ $item->id }}"
                                       onchange="pgUpdateBulkBar()">
                            </div>
                        </td>

                        {{-- Judul --}}
                        <td class="px-4 py-3 max-w-xs">
                            <button type="button" onclick='pgBuka({{ $pgJson }})'
                                    class="flex items-start gap-2 text-left w-full focus:outline-none">
                                <span class="mt-0.5 shrink-0 leading-none" style="font-size:.85rem">
                                    {{ $item->tipeIcon() }}
                                </span>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate
                                              group-hover:text-indigo-600 dark:group-hover:text-indigo-400
                                              transition-colors">
                                        {{ $item->judul }}
                                    </p>
                                    @if($item->isi)
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 line-clamp-1">
                                        {{ strip_tags($item->isi) }}
                                    </p>
                                    @endif
                                    @if($item->tipe_konten === 'gambar' && $item->file_path)
                                    <div class="mt-1.5 w-12 h-7 rounded-lg overflow-hidden
                                                bg-slate-100 dark:bg-slate-700">
                                        <img src="{{ asset('storage/' . $item->file_path) }}" alt=""
                                             class="w-full h-full object-cover"
                                             onerror="pgThumbError(this)">
                                    </div>
                                    @endif
                                </div>
                            </button>
                        </td>

                        {{-- Target --}}
                        <td class="px-3 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         {{ $item->audienceBadgeColor() }}">
                                {{ $item->audienceLabel() }}
                            </span>
                        </td>

                        {{-- Tipe --}}
                        <td class="px-3 py-3">
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full
                                         text-[10px] font-semibold bg-slate-100 text-slate-600
                                         dark:bg-slate-700 dark:text-slate-300 capitalize">
                                {{ $item->tipeIcon() }} {{ $item->tipe_konten }}
                            </span>
                        </td>

                        {{-- Dashboard --}}
                        <td class="px-3 py-3">
                            @if($item->show_di_dashboard)
                            <span class="inline-flex items-center gap-1 text-[10px] font-medium
                                         text-emerald-600 dark:text-emerald-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0
                                             01-1.414 0l-4-4a1 1 0 011.414-1.414L8
                                             12.586l7.293-7.293a1 1 0 011.414 0z"
                                          clip-rule="evenodd"/>
                                </svg>
                                Tampil
                            </span>
                            @else
                            <span class="text-[10px] text-slate-400">—</span>
                            @endif
                        </td>

                        {{-- Toggle Status --}}
                        <td class="px-3 py-3">
                            <button type="button"
                                    onclick="pgToggle({{ $item->id }}, this)"
                                    data-active="{{ $item->is_active ? '1' : '0' }}"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full
                                           transition-colors focus:outline-none
                                           {{ $item->is_active ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-slate-600' }}">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow
                                             transition-transform"
                                      style="{{ $item->is_active ? 'transform:translateX(18px)' : 'transform:translateX(2px)' }}">
                                </span>
                            </button>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-3 py-3 text-[10px] text-slate-400 dark:text-slate-500 whitespace-nowrap">
                            {{ $item->created_at->format('d M Y') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-3 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick='pgBuka({{ $pgJson }})' title="Lihat"
                                        class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100
                                               dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                               transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <a href="{{ route('admin.pengumuman.edit', $item) }}" title="Edit"
                                   class="p-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100
                                          dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400
                                          transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0
                                                 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.pengumuman.destroy', $item) }}"
                                      onsubmit="return confirm('Yakin hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100
                                                   dark:bg-red-900/30 text-red-600 dark:text-red-400
                                                   transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                     01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                                     00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pengumuman->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">
            {{ $pengumuman->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<script>
(function () {
    'use strict';

    /* ─────────────────────────────────────────
     * FIX UTAMA: semua onerror pakai fungsi
     * global — tidak ada escaped-quote hell
     * ───────────────────────────────────────── */

    /** Thumbnail kecil di baris tabel gagal → sembunyikan wrapper */
    window.pgThumbError = function (img) {
        var wrap = img.parentElement;
        if (wrap) wrap.classList.add('hidden');
    };

    /** Gambar di dalam modal gagal → tampilkan placeholder */
    window.pgModalImgError = function (img) {
        var wrap = img.closest('div');
        if (!wrap) return;
        wrap.innerHTML =
            '<div class="p-6 text-center">' +
                '<div class="text-3xl mb-1.5">🖼️</div>' +
                '<p class="text-xs text-slate-400">Gambar tidak dapat dimuat.</p>' +
            '</div>';
    };

    /* ── Buka / Tutup modal detail ── */
    window.pgBuka = function (d) {
        var k = document.getElementById('pgModalKonten');
        if (!k) return;
        k.innerHTML = pgHtml(d);
        var o = document.getElementById('pgModal');
        o.classList.remove('hidden');
        o.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.pgTutup = function () {
        var o = document.getElementById('pgModal');
        o.classList.add('hidden');
        o.classList.remove('flex');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape') {
            pgTutup();
            pgTutupBulkModal();
        }
    });

    /* ── Toggle aktif / nonaktif ── */
    window.pgToggle = function (id, btn) {
        var token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
        fetch('/admin/pengumuman/' + id + '/toggle', {
            method : 'PATCH',
            headers: {
                'X-CSRF-TOKEN' : token,
                'Content-Type' : 'application/json',
                'Accept'       : 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) return;
            var on = data.is_active;
            btn.className =
                'relative inline-flex h-5 w-9 items-center rounded-full ' +
                'transition-colors focus:outline-none ' +
                (on ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-slate-600');
            btn.querySelector('span').style.transform =
                on ? 'translateX(18px)' : 'translateX(2px)';
            btn.dataset.active = on ? '1' : '0';
        })
        .catch(function (err) { console.error('Toggle error:', err); });
    };

    /* ════════════════════════════════════════
     * BULK SELECT / DELETE
     * ════════════════════════════════════════ */

    /** Pilih / Batal pilih semua checkbox di halaman ini */
    window.pgToggleAll = function (masterCb) {
        var checks = document.querySelectorAll('.pg-row-check');
        checks.forEach(function (cb) { cb.checked = masterCb.checked; });
        pgUpdateBulkBar();
        pgHighlightRows();
    };

    /** Update tampilan bulk bar berdasar jumlah yang dicentang */
    window.pgUpdateBulkBar = function () {
        var checked = document.querySelectorAll('.pg-row-check:checked');
        var bar     = document.getElementById('pgBulkBar');
        var countEl = document.getElementById('pgSelectedCount');
        var master  = document.getElementById('pgCheckAll');
        var all     = document.querySelectorAll('.pg-row-check');

        if (!bar || !countEl) return;

        countEl.textContent = checked.length;

        if (checked.length > 0) {
            bar.classList.remove('hidden');
            bar.classList.add('flex');
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('flex');
        }

        /* State checkbox master: checked / indeterminate / unchecked */
        if (master) {
            if (checked.length === 0) {
                master.checked       = false;
                master.indeterminate = false;
            } else if (checked.length === all.length) {
                master.checked       = true;
                master.indeterminate = false;
            } else {
                master.checked       = false;
                master.indeterminate = true;
            }
        }

        pgHighlightRows();
    };

    /** Highlight baris yang dicentang */
    function pgHighlightRows() {
        document.querySelectorAll('.pg-row').forEach(function (row) {
            var cb = row.querySelector('.pg-row-check');
            if (cb && cb.checked) {
                row.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
                row.classList.remove('hover:bg-slate-50');
            } else {
                row.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                row.classList.add('hover:bg-slate-50');
            }
        });
    }

    /** Batal pilih semua */
    window.pgDeselectAll = function () {
        var master = document.getElementById('pgCheckAll');
        if (master) { master.checked = false; master.indeterminate = false; }
        document.querySelectorAll('.pg-row-check').forEach(function (cb) {
            cb.checked = false;
        });
        pgUpdateBulkBar();
        pgHighlightRows();
    };

    /** Buka modal konfirmasi hapus massal */
    window.pgBukaBulkModal = function () {
        var checked = document.querySelectorAll('.pg-row-check:checked');
        if (checked.length === 0) return;
        document.getElementById('pgBulkDeleteCount').textContent = checked.length;
        var m = document.getElementById('pgBulkDeleteModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    /** Tutup modal konfirmasi hapus massal */
    window.pgTutupBulkModal = function () {
        var m = document.getElementById('pgBulkDeleteModal');
        if (!m) return;
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = '';
    };

    /** Eksekusi hapus massal: inject hidden input → submit form */
    window.pgKonfirmasiBulkDelete = function () {
        var checked = document.querySelectorAll('.pg-row-check:checked');
        if (checked.length === 0) return;

        var container = document.getElementById('pgBulkDeleteIds');
        container.innerHTML = '';

        checked.forEach(function (cb) {
            var inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'ids[]';
            inp.value = cb.value;
            container.appendChild(inp);
        });

        document.getElementById('pgBulkDeleteForm').submit();
    };

    /* ── Bangun HTML modal detail ── */
    function pgHtml(d) {
        var h = '';

        /* Header judul + badge */
        h += '<div class="flex items-start gap-2.5 mb-3.5 pr-7">';
        h +=   '<div class="text-xl shrink-0 mt-0.5">' + d.tipeIcon + '</div>';
        h +=   '<div class="flex-1 min-w-0">';
        h +=     '<h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-snug break-words">' + e(d.judul) + '</h2>';
        h +=     '<div class="flex gap-1.5 mt-1.5 flex-wrap">';
        h +=       '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold ' + d.audienceColor + '">' + e(d.audience) + '</span>';
        h +=       '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 capitalize">' + e(d.tipe) + '</span>';
        h +=     '</div>';
        h +=   '</div>';
        h += '</div>';

        /* Meta */
        h += '<div class="flex flex-wrap gap-x-3 gap-y-1 text-[10px] text-slate-400 mb-3.5 pb-3.5 border-b border-slate-200 dark:border-slate-700">';
        h +=   '<span>📅 ' + e(d.tanggal) + '</span>';
        h +=   '<span>👤 ' + e(d.creator) + '</span>';
        h +=   '<span>🕐 ' + e(d.diffHumans) + '</span>';
        h += '</div>';

        /* Gambar */
        if (d.tipe === 'gambar' && d.fileUrl) {
            h += '<div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 mb-3.5 bg-slate-50 dark:bg-slate-900 flex items-center justify-center">';
            h +=   '<img src="' + d.fileUrl + '"' +
                       ' alt="' + e(d.judul) + '"' +
                       ' class="w-full max-h-60 object-contain block"' +
                       ' onerror="pgModalImgError(this)">';
            h += '</div>';
        }

        /* Isi teks */
        if (d.isi && d.isi.trim()) {
            var adaTag = /<[a-z][\s\S]*>/i.test(d.isi);
            h += adaTag
                ? '<div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-3.5 prose prose-sm dark:prose-invert max-w-none">' + s(d.isi) + '</div>'
                : '<div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-3.5 whitespace-pre-line">' + e(d.isi) + '</div>';
        }

        /* Dokumen */
        if (d.tipe === 'dokumen' && d.fileUrl) {
            h += '<div class="flex items-center justify-between gap-2 p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-200 dark:border-indigo-700 mb-3.5">';
            h +=   '<div class="flex items-center gap-2">';
            h +=     '<div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-800 rounded-lg flex items-center justify-center text-sm">📄</div>';
            h +=     '<div>';
            h +=       '<p class="text-xs font-bold text-indigo-700 dark:text-indigo-300">' + e(d.fileExt || 'FILE') + '</p>';
            h +=       '<p class="text-[10px] text-slate-400 max-w-[150px] truncate">' + e(d.fileName) + '</p>';
            h +=     '</div>';
            h +=   '</div>';
            h +=   '<a href="' + d.fileUrl + '" target="_blank" download onclick="event.stopPropagation()"' +
                      ' class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg no-underline">';
            h +=     '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>';
            h +=     'Unduh';
            h +=   '</a>';
            h += '</div>';
        }

        /* Link */
        if (d.tipe === 'link' && d.linkUrl) {
            h += '<div class="p-3 bg-sky-50 dark:bg-sky-900/30 rounded-xl border border-sky-200 dark:border-sky-700 mb-3.5">';
            h +=   '<p class="text-[10px] text-slate-500 mb-2 font-medium">🔗 Tautan</p>';
            h +=   '<a href="' + d.linkUrl + '" target="_blank" rel="noopener" onclick="event.stopPropagation()"' +
                      ' class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg no-underline">';
            h +=     '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>';
            h +=     e(d.linkLabel || 'Buka Link');
            h +=   '</a>';
            h += '</div>';
        }

        /* Tanggal selesai */
        if (d.tglSelesai) {
            h += '<div class="flex items-center gap-2 px-3 py-2 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-700 mb-3">';
            h +=   '<span>⏰</span>';
            h +=   '<p class="text-[10px] text-amber-700 dark:text-amber-300 font-medium">Berakhir: <strong>' + e(d.tglSelesai) + '</strong></p>';
            h += '</div>';
        }

        /* Tombol tutup */
        h += '<div class="flex justify-end pt-1">';
        h +=   '<button onclick="pgTutup()"' +
                   ' class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600' +
                   ' text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl transition-colors">Tutup</button>';
        h += '</div>';

        return h;
    }

    /* Escape HTML untuk output aman */
    function e(v) {
        if (v == null) return '';
        return String(v)
            .replace(/&/g,  '&amp;')
            .replace(/</g,  '&lt;')
            .replace(/>/g,  '&gt;')
            .replace(/"/g,  '&quot;')
            .replace(/'/g,  '&#039;');
    }

    /* Sanitasi HTML rich-text (buang script/iframe/on*) */
    function s(h) {
        return (h || '')
            .replace(/<script[\s\S]*?<\/script>/gi, '')
            .replace(/<iframe[\s\S]*?<\/iframe>/gi, '')
            .replace(/\bon\w+=["'][^"']*["']/gi, '')
            .replace(/javascript:/gi, '#');
    }
})();
</script>

@endsection
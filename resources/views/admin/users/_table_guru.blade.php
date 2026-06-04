{{-- resources/views/admin/users/_table_guru.blade.php --}}
<div class="space-y-3">

    {{-- ── Bulk Action Bar Guru ──────────────────────────────────── --}}
    <div id="bulkBarGuru"
         class="hidden items-center justify-between gap-3
                bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800
                rounded-2xl px-4 py-3">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-semibold text-red-700 dark:text-red-400">
                <span id="selectedCountGuru">0</span> guru dipilih
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="selectAllGuru(true)"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold
                           bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600
                           text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                Pilih Semua
            </button>
            <button onclick="selectAllGuru(false)"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold
                           bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600
                           text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                Batal Pilih
            </button>
            <button onclick="openBulkDeleteModal('guru')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold
                           bg-red-600 hover:bg-red-700 text-white transition active:scale-95 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Terpilih
            </button>
        </div>
    </div>

    {{-- ── Tabel Guru ────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200
                               dark:border-slate-700 text-left">

                        {{-- Checkbox Header --}}
                        <th class="px-3 py-2.5 w-8">
                            <input type="checkbox" id="checkAllGuru"
                                   class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-600
                                          text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0
                                          cursor-pointer"
                                   onchange="toggleAllGuru(this.checked)">
                        </th>

                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide w-7">#</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[180px]">Guru</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[110px]">NIP</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide">Kelamin</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[130px]">
                            Tempat / Tgl Lahir
                        </th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[90px]">Pendidikan</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[100px]">Status</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[120px]">Pangkat/Gol</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[120px]">No. SK Pertama</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[120px]">No. SK Terakhir</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide min-w-[90px]">Wali Kelas</th>
                        <th class="px-3 py-2.5 text-[10px] font-semibold text-slate-500
                                   dark:text-slate-400 uppercase tracking-wide text-center min-w-[90px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">

                    @forelse($users as $i => $user)
                    @php
                    $g = $user->guru;
                    $waliKelas = $user->homeroomGroups->first()
                    ?? ($g && $g->kelas_id
                    ? \App\Models\StudyGroup::find($g->kelas_id)
                    : null);
                    @endphp

                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors searchable-row
                               bulk-row-guru"
                        data-id="{{ $user->id }}">

                        {{-- Checkbox --}}
                        <td class="px-3 py-2.5">
                            <input type="checkbox"
                                   class="bulk-checkbox-guru w-3.5 h-3.5 rounded border-slate-300
                                          dark:border-slate-600 text-indigo-600 focus:ring-indigo-500
                                          focus:ring-offset-0 cursor-pointer"
                                   value="{{ $user->id }}"
                                   onchange="onGuruCheckboxChange()">
                        </td>

                        <td class="px-3 py-2.5 text-[10px] text-slate-400">{{ $i + 1 }}</td>

                        {{-- Nama + Email --}}
                        <td class="px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                @if($user->photo)
                                <img src="{{ Storage::url($user->photo) }}" alt=""
                                    class="w-7 h-7 rounded-lg object-cover border border-slate-200
                                                    dark:border-slate-600 shrink-0">
                                @else
                                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40
                                                    flex items-center justify-center text-indigo-600
                                                    dark:text-indigo-400 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-100
                                                  leading-tight truncate">
                                        {{ ($g && $g->nama) ? $g->nama : $user->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- NIP --}}
                        <td class="px-3 py-2.5">
                            @if($g && $g->nip)
                            <span class="font-mono text-[10px] text-slate-600 dark:text-slate-400
                                                 bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded-md">
                                {{ $g->nip }}
                            </span>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- JK --}}
                        <td class="px-3 py-2.5">
                            @if($g && $g->jk)
                            <span class="inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-semibold
                                                 {{ $g->jk==='L'
                                                    ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                                    : 'bg-pink-50 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' }}">
                                {{ $g->jk==='L' ? '♂ L' : '♀ P' }}
                            </span>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- Tempat/Tgl Lahir --}}
                        <td class="px-3 py-2.5">
                            @if($g && ($g->tempat_lahir || $g->tanggal_lahir))
                            <p class="text-[10px] text-slate-700 dark:text-slate-300 leading-tight">
                                {{ $g->tempat_lahir ?? '-' }}
                            </p>
                            <p class="text-[10px] text-slate-400">
                                {{ $g->tanggal_lahir
                                            ? \Carbon\Carbon::parse($g->tanggal_lahir)->translatedFormat('d F Y')
                                            : '-' }}
                            </p>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- Pendidikan --}}
                        <td class="px-3 py-2.5">
                            @if($g && $g->pendidikan_terakhir)
                            <span class="inline-flex px-1.5 py-0.5 rounded-lg text-[10px] font-medium
                                                 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                {{ $g->pendidikan_terakhir }}
                            </span>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- Status Pegawai --}}
                        <td class="px-3 py-2.5">
                            @if($g && $g->status_pegawai)
                            @php
                            $spColor = match($g->status_pegawai) {
                            'PNS'             => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'PPPK'            => 'bg-teal-50 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                            'Honorer', 'GTT'  => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            default           => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400',
                            };
                            @endphp
                            <span class="inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $spColor }}">
                                {{ $g->status_pegawai }}
                            </span>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- Pangkat --}}
                        <td class="px-3 py-2.5 text-[10px] text-slate-600 dark:text-slate-400 whitespace-nowrap">
                            {{ ($g && $g->pangkat_gol_ruang) ? $g->pangkat_gol_ruang : '—' }}
                        </td>

                        {{-- SK Pertama --}}
                        <td class="px-3 py-2.5">
                            @if($g && $g->no_sk_pertama)
                            <span class="font-mono text-[10px] text-slate-600 dark:text-slate-400">
                                {{ $g->no_sk_pertama }}
                            </span>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- SK Terakhir --}}
                        <td class="px-3 py-2.5">
                            @if($g && $g->no_sk_terakhir)
                            <span class="font-mono text-[10px] text-slate-600 dark:text-slate-400">
                                {{ $g->no_sk_terakhir }}
                            </span>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- Wali Kelas --}}
                        <td class="px-3 py-2.5">
                            @if($waliKelas)
                            <a href="{{ route('admin.academic-planner.study-group.show', $waliKelas->id) }}"
                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-lg text-[10px]
                                              font-medium bg-violet-50 text-violet-700 dark:bg-violet-900/30
                                              dark:text-violet-400 border border-violet-100 dark:border-violet-800
                                              whitespace-nowrap hover:bg-violet-100 transition">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                                {{ $waliKelas->name }}
                            </a>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-[10px]">—</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-3 py-2.5">
                            <div class="flex items-center justify-center gap-0.5">
                                <button onclick="openDetailModal({{ $user->id }})" title="Lihat Detail"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600
                                                   hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button onclick="openEditModal({{ $user->id }})" title="Edit User"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600
                                                   hover:bg-amber-50 dark:hover:bg-amber-900/30 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="openResetModal({{ $user->id }}, '{{ addslashes(($g&&$g->nama)?$g->nama:$user->name) }}')"
                                    title="Reset Password"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-sky-600
                                                   hover:bg-sky-50 dark:hover:bg-sky-900/30 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes(($g&&$g->nama)?$g->nama:$user->name) }}')"
                                    title="Hapus User"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-600
                                                   hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700
                                                flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-300 dark:text-slate-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                    Belum ada data guru
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    Gunakan tombol <strong>+ Tambah User</strong> untuk menambahkan akun guru.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
// ── Guru Bulk Select ───────────────────────────────────────────────────────
function onGuruCheckboxChange() {
    const all       = document.querySelectorAll('.bulk-checkbox-guru');
    const checked   = document.querySelectorAll('.bulk-checkbox-guru:checked');
    const bar       = document.getElementById('bulkBarGuru');
    const countEl   = document.getElementById('selectedCountGuru');
    const checkAll  = document.getElementById('checkAllGuru');

    countEl.textContent = checked.length;

    if (checked.length > 0) {
        bar.classList.remove('hidden');
        bar.classList.add('flex');
    } else {
        bar.classList.add('hidden');
        bar.classList.remove('flex');
    }

    // State indeterminate
    if (checked.length === 0) {
        checkAll.indeterminate = false;
        checkAll.checked = false;
    } else if (checked.length === all.length) {
        checkAll.indeterminate = false;
        checkAll.checked = true;
    } else {
        checkAll.indeterminate = true;
    }

    // Highlight baris yang dipilih
    document.querySelectorAll('.bulk-row-guru').forEach(row => {
        const cb = row.querySelector('.bulk-checkbox-guru');
        if (cb && cb.checked) {
            row.classList.add('bg-red-50', 'dark:bg-red-950/20');
        } else {
            row.classList.remove('bg-red-50', 'dark:bg-red-950/20');
        }
    });
}

function toggleAllGuru(checked) {
    document.querySelectorAll('.bulk-checkbox-guru').forEach(cb => {
        cb.checked = checked;
    });
    onGuruCheckboxChange();
}

function selectAllGuru(select) {
    toggleAllGuru(select);
}
</script>
@endpush
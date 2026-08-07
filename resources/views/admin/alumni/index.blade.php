{{-- resources/views/admin/alumni/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Data Alumni')

@section('content')

<div class="space-y-4">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data Alumni</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Arsip siswa/i SMP Negeri Kutime yang telah lulus.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">

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
                     class="absolute right-0 mt-1.5 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl
                            border border-slate-200 dark:border-slate-700 py-1 z-30 origin-top-right">
                    <a href="{{ route('admin.alumni.export-excel', request()->only('tahun_lulus')) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                     01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel (.xlsx)
                    </a>
                    <a href="{{ route('admin.alumni.export-pdf', request()->only('tahun_lulus')) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1
                                     0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF
                    </a>
                </div>
            </div>

            {{-- Tombol Luluskan Siswa --}}
            <button onclick="openModal('modalLuluskan')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl
                           bg-indigo-600 text-white text-xs font-semibold
                           hover:bg-indigo-700 active:scale-95 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 13c0
                             5.523-4.477 10-9 10S3 18.523 3 13c0-.538.04-1.066.118-1.578L12 14z"/>
                </svg>
                Luluskan Siswa
            </button>
        </div>
    </div>

    {{-- ===== KPI CARDS ===== --}}
    <div class="grid-kpi-3">
        <div class="stat-card">
            <div class="stat-value">{{ $totalAlumni }}</div>
            <div class="stat-label">Total Alumni</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalTahunIni }}</div>
            <div class="stat-label">Lulus Tahun {{ date('Y') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $siswaAktifCount }}</div>
            <div class="stat-label">Siswa Aktif Saat Ini</div>
        </div>
    </div>

    {{-- ===== ALERTS ===== --}}
    @if(session('success'))
    <div class="flex items-start gap-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200
                dark:border-emerald-800 rounded-2xl p-4">
        <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
    </div>
    @endif

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

    {{-- ===== SEARCH & FILTER ===== --}}
    <form method="GET" action="{{ route('admin.alumni.index') }}"
          class="flex flex-col sm:flex-row gap-2 sm:items-center">
        <div class="relative max-w-xs w-full">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari nama, NISN, NIK, no. ijazah..."
                   class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600
                          bg-white dark:bg-slate-800 text-xs text-slate-700 dark:text-slate-300
                          placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-300
                          dark:focus:ring-indigo-700 transition">
        </div>

        <select name="tahun_lulus" onchange="this.form.submit()"
                class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800
                       text-xs text-slate-700 dark:text-slate-300 px-3 py-2 focus:outline-none
                       focus:ring-2 focus:ring-indigo-300">
            <option value="">Semua Tahun Lulus</option>
            @foreach($tahunList as $t)
                <option value="{{ $t }}" {{ (string) request('tahun_lulus') === (string) $t ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300
                       text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
            Terapkan
        </button>

        @if(request('q') || request('tahun_lulus'))
            <a href="{{ route('admin.alumni.index') }}"
               class="px-3 py-2 rounded-xl text-xs font-semibold text-red-500 hover:bg-red-50
                      dark:hover:bg-red-900/20 transition">
                Reset
            </a>
        @endif
    </form>

    {{-- ===== TABEL ALUMNI ===== --}}
    <div class="card">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th class="w-10">No</th>
                        <th class="w-12">Foto</th>
                        <th>Nama</th>
                        <th>NISN/NIDN</th>
                        <th>Kelas Terakhir</th>
                        <th>Tahun Lulus</th>
                        <th>No. Ijazah</th>
                        <th>Status Edit</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumni as $i => $a)
                    <tr data-alumni-id="{{ $a->id }}">
                        <td>{{ $alumni->firstItem() + $i }}</td>
                        <td>
                            <div class="avatar avatar-sm bg-indigo-100 text-indigo-600">
                                @if($a->foto_url)
                                    <img src="{{ $a->foto_url }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($a->nama, 0, 1)) }}
                                @endif
                            </div>
                        </td>
                        <td class="font-semibold text-slate-700 dark:text-slate-200">{{ $a->nama }}</td>
                        <td>{{ $a->nidn ?? '—' }}</td>
                        <td>{{ $a->kelas_terakhir ?? '—' }}</td>
                        <td><span class="badge badge-info">{{ $a->tahun_lulus }}</span></td>
                        <td>{{ $a->no_ijazah ?? '—' }}</td>
                        <td>
                            @if($a->is_editable)
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-semibold
                                             bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400"
                                      title="Bisa diedit hingga {{ $a->edit_deadline->translatedFormat('d M Y, H:i') }}">
                                    <i class="bi bi-pencil-square"></i> {{ $a->edit_time_left_label }} lagi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-semibold
                                             bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                                    <i class="bi bi-lock-fill"></i> Terkunci
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <button onclick="openDetailAlumni({{ $a->id }})"
                                        class="icon-btn" title="Detail">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                                 -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                @if($a->is_editable)
                                <button onclick="openEditAlumni({{ $a->id }})"
                                        class="icon-btn hover:!text-amber-500 hover:!border-amber-300" title="Edit">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                @endif

                                <form action="{{ route('admin.alumni.batalkan', $a->id) }}" method="POST"
                                      onsubmit="return confirm('Batalkan status alumni? Akun siswa akan dipulihkan (aktif kembali) dan muncul lagi di Kelola User.');">
                                    @csrf
                                    <button type="submit" class="icon-btn" title="Batalkan status alumni (pulihkan akun)">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                                        </svg>
                                    </button>
                                </form>

                                <button onclick="openHapusAlumni({{ $a->id }}, '{{ addslashes($a->nama) }}')"
                                        class="icon-btn hover:!text-red-500 hover:!border-red-300" title="Hapus">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5
                                                 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 13c0
                                             5.523-4.477 10-9 10S3 18.523 3 13c0-.538.04-1.066.118-1.578L12 14z"/>
                                </svg>
                                <p>Belum ada data alumni{{ request('q') || request('tahun_lulus') ? ' untuk filter ini' : '' }}.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($alumni->hasPages())
        <div>{{ $alumni->links('pagination::bootstrap-5') }}</div>
    @endif

</div>

@include('admin.alumni._modal_detail')
@include('admin.alumni._modal_edit')
@include('admin.alumni._modal_luluskan')
@include('admin.alumni._modal_hapus')

@endsection

@push('scripts')
<script>
// ── Buka / tutup modal (dipakai jika layout belum mendefinisikan global) ──
if (typeof openModal !== 'function') {
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
}

document.querySelectorAll('[id^="modal"]').forEach(modal => {
    modal.addEventListener('click', function (e) {
        if (e.target === this) closeModal(this.id);
    });
});

// ── Modal Detail Alumni ─────────────────────────────────────────────────
let __currentAlumniId = null;

function openDetailAlumni(id) {
    __currentAlumniId = id;
    fetch(`{{ url('admin/alumni') }}/${id}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        buildAlumniDetailModal(data);
        openModal('modalDetailAlumni');
    })
    .catch(() => alert('Gagal memuat data alumni.'));
}

function buildAlumniDetailModal(a) {
    document.getElementById('da_nama').textContent      = a.nama || '—';
    document.getElementById('da_nidn').textContent       = a.nidn || '—';
    document.getElementById('da_nik').textContent         = a.nik || '—';
    document.getElementById('da_email').textContent       = a.email || '—';
    document.getElementById('da_jk').textContent           = a.jk === 'L' ? 'Laki-laki' : (a.jk === 'P' ? 'Perempuan' : '—');
    document.getElementById('da_agama').textContent        = a.agama || '—';
    document.getElementById('da_ttl').textContent           = (a.tempat_lahir || '—') + ', ' + (a.tgl_lahir ? new Date(a.tgl_lahir).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'}) : '—');
    document.getElementById('da_telp').textContent           = a.no_telp || '—';
    document.getElementById('da_kelas').textContent           = a.kelas_terakhir || '—';
    document.getElementById('da_tahun').textContent            = a.tahun_lulus || '—';
    document.getElementById('da_tgl_lulus').textContent         = a.tanggal_lulus ? new Date(a.tanggal_lulus).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'}) : '—';
    document.getElementById('da_ijazah').textContent             = a.no_ijazah || '—';
    document.getElementById('da_alamat').textContent              = a.alamat || '—';
    document.getElementById('da_rtrw').textContent                 = (a.rt || '—') + ' / ' + (a.rw || '—');
    document.getElementById('da_dusun').textContent                 = a.dusun || '—';
    document.getElementById('da_kecamatan').textContent              = a.kecamatan || '—';
    document.getElementById('da_kps').textContent                     = a.penerima_kps ? ('Ya' + (a.no_kps ? ' • ' + a.no_kps : '')) : 'Tidak';
    document.getElementById('da_catatan').textContent                  = a.catatan || '—';

    const fotoWrap = document.getElementById('da_fotoWrap');
    fotoWrap.innerHTML = a.foto
        ? `<img src="/storage/${a.foto}" class="w-full h-full object-cover">`
        : `<div class="w-full h-full flex items-center justify-center text-xl font-bold text-indigo-600 bg-indigo-100">${(a.nama || '?').charAt(0).toUpperCase()}</div>`;

    // Badge & tombol edit sesuai status is_editable
    const badge   = document.getElementById('da_editBadge');
    const btnEdit = document.getElementById('da_btnEdit');
    if (a.is_editable) {
        badge.className = 'flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800';
        badge.innerHTML  = `<i class="bi bi-pencil-square text-amber-500"></i>
                             <p class="text-[11px] text-amber-700 dark:text-amber-300 font-medium">
                                Data ini masih bisa diedit — sisa waktu ${a.edit_time_left_label ?? ''}.
                             </p>`;
        btnEdit.classList.remove('hidden');
    } else {
        badge.className = 'flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-700';
        badge.innerHTML  = `<i class="bi bi-lock-fill text-slate-400"></i>
                             <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                Batas waktu edit (2 hari) sudah berakhir. Data ini hanya dapat dilihat.
                             </p>`;
        btnEdit.classList.add('hidden');
    }
    badge.classList.remove('hidden');
}

function openEditFromDetail() {
    if (!__currentAlumniId) return;
    closeModal('modalDetailAlumni');
    openEditAlumni(__currentAlumniId);
}

// ── Modal Edit Alumni ────────────────────────────────────────────────────
function openEditAlumni(id) {
    fetch(`{{ url('admin/alumni') }}/${id}/edit`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async r => {
        const data = await r.json();
        if (!r.ok) {
            alert(data.message || 'Data ini sudah tidak dapat diedit.');
            return;
        }
        buildAlumniEditModal(data);
        openModal('modalEditAlumni');
    })
    .catch(() => alert('Gagal memuat data alumni.'));
}

function buildAlumniEditModal(a) {
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val ?? ''; };

    set('ea_nama', a.nama);
    set('ea_nidn', a.nidn);
    set('ea_nik', a.nik);
    set('ea_jk', a.jk);
    set('ea_agama', a.agama);
    set('ea_tempat_lahir', a.tempat_lahir);
    set('ea_tgl_lahir', a.tgl_lahir ? a.tgl_lahir.substring(0, 10) : '');
    set('ea_no_telp', a.no_telp);
    set('ea_kelas_terakhir', a.kelas_terakhir);
    set('ea_alamat', a.alamat);
    set('ea_rt', a.rt);
    set('ea_rw', a.rw);
    set('ea_dusun', a.dusun);
    set('ea_kecamatan', a.kecamatan);
    set('ea_tahun_lulus', a.tahun_lulus);
    set('ea_tanggal_lulus', a.tanggal_lulus ? a.tanggal_lulus.substring(0, 10) : '');
    set('ea_no_ijazah', a.no_ijazah);
    set('ea_catatan', a.catatan);

    document.getElementById('ea_sisaWaktu').textContent =
        'Sisa waktu edit: ' + (a.edit_time_left_label ?? '—');

    document.getElementById('formEditAlumni').action = `{{ url('admin/alumni') }}/${a.id}`;
}

// ── Modal Hapus Alumni ───────────────────────────────────────────────────
function openHapusAlumni(id, nama) {
    document.getElementById('hapusAlumniNama').textContent = nama;
    document.getElementById('formHapusAlumni').action = `{{ url('admin/alumni') }}/${id}`;
    openModal('modalHapusAlumni');
}

// ── Modal Luluskan Siswa: load semua siswa aktif, kelompokkan per kelas ──
let __daftarSiswaAktif = [];

function loadSiswaAktif() {
    const wrap = document.getElementById('luluskan_listSiswa');
    wrap.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Memuat data siswa...</p>';

    fetch(`{{ route('admin.alumni.siswa-aktif') }}`, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            __daftarSiswaAktif = data.siswa;
            renderSiswaGrouped(data.siswa);
        })
        .catch(() => {
            wrap.innerHTML = '<p class="text-xs text-red-500 text-center py-4">Gagal memuat daftar siswa.</p>';
        });
}

/**
 * Render daftar siswa dikelompokkan per kelas. Setiap grup punya
 * checkbox "pilih semua kelas ini", dan setiap siswa punya checkbox
 * sendiri — sehingga admin bisa memilih satu kelas penuh, sebagian
 * siswa saja, atau gabungan lintas kelas sekaligus.
 */
function renderSiswaGrouped(list) {
    const wrap = document.getElementById('luluskan_listSiswa');

    if (!list.length) {
        wrap.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Tidak ada siswa aktif untuk saat ini.</p>';
        updateTotalTerpilih();
        return;
    }

    const groups = {};
    list.forEach(s => {
        const key = s.kelas_id ?? 'none';
        if (!groups[key]) groups[key] = { nama: s.kelas || 'Tanpa Kelas', siswa: [] };
        groups[key].siswa.push(s);
    });

    let html = '';
    Object.entries(groups).forEach(([kelasId, group]) => {
        html += `
        <div class="kelas-group" data-kelas-id="${kelasId}">
            <div class="flex items-center justify-between px-2.5 py-1.5 bg-slate-50 dark:bg-slate-900/40 sticky top-0">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="kelas-group-check rounded border-slate-300" data-kelas-id="${kelasId}">
                    <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300">${group.nama}</span>
                    <span class="text-[9px] text-slate-400">(${group.siswa.length} siswa)</span>
                </label>
            </div>
            <div>
                ${group.siswa.map(s => `
                    <label class="flex items-center gap-2 px-4 py-1.5 hover:bg-slate-50 dark:hover:bg-slate-700/60 cursor-pointer siswa-row" data-nama="${(s.nama||'').toLowerCase()}" data-nidn="${(s.nidn||'').toLowerCase()}">
                        <input type="checkbox" name="siswa_ids[]" value="${s.id}"
                               class="siswa-checkbox rounded border-slate-300" data-kelas-id="${kelasId}">
                        <span class="text-xs text-slate-700 dark:text-slate-300 flex-1 truncate">${s.nama}</span>
                        <span class="text-[10px] text-slate-400">${s.nidn ?? '-'}</span>
                    </label>
                `).join('')}
            </div>
        </div>`;
    });

    wrap.innerHTML = html;
    attachSiswaListeners();
    updateTotalTerpilih();
}

function attachSiswaListeners() {
    document.querySelectorAll('.siswa-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            syncKelasGroupCheckbox(this.dataset.kelasId);
            syncPilihSemuaCheckbox();
            updateTotalTerpilih();
        });
    });

    document.querySelectorAll('.kelas-group-check').forEach(cb => {
        cb.addEventListener('change', function () {
            const kelasId = this.dataset.kelasId;
            document.querySelectorAll(`.siswa-checkbox[data-kelas-id="${kelasId}"]`).forEach(sc => {
                sc.checked = this.checked;
            });
            syncPilihSemuaCheckbox();
            updateTotalTerpilih();
        });
    });
}

function syncKelasGroupCheckbox(kelasId) {
    const groupCb  = document.querySelector(`.kelas-group-check[data-kelas-id="${kelasId}"]`);
    const siswaCbs = document.querySelectorAll(`.siswa-checkbox[data-kelas-id="${kelasId}"]`);
    const checked  = Array.from(siswaCbs).filter(cb => cb.checked);
    if (!groupCb) return;

    if (checked.length === 0) {
        groupCb.checked = false;
        groupCb.indeterminate = false;
    } else if (checked.length === siswaCbs.length) {
        groupCb.checked = true;
        groupCb.indeterminate = false;
    } else {
        groupCb.checked = false;
        groupCb.indeterminate = true;
    }
}

function syncPilihSemuaCheckbox() {
    const all     = document.querySelectorAll('.siswa-checkbox');
    const checked = document.querySelectorAll('.siswa-checkbox:checked');
    const master  = document.getElementById('luluskan_pilihSemua');
    if (!master) return;

    if (checked.length === 0) {
        master.checked = false;
        master.indeterminate = false;
    } else if (checked.length === all.length) {
        master.checked = true;
        master.indeterminate = false;
    } else {
        master.checked = false;
        master.indeterminate = true;
    }
}

function updateTotalTerpilih() {
    const total = document.querySelectorAll('.siswa-checkbox:checked').length;
    const el = document.getElementById('luluskan_totalTerpilih');
    if (el) el.textContent = `${total} dipilih`;
}

document.getElementById('luluskan_pilihSemua')?.addEventListener('change', function () {
    document.querySelectorAll('.siswa-checkbox').forEach(cb => cb.checked = this.checked);
    document.querySelectorAll('.kelas-group-check').forEach(cb => {
        cb.checked = this.checked;
        cb.indeterminate = false;
    });
    updateTotalTerpilih();
});

document.getElementById('luluskan_searchSiswa')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.siswa-row').forEach(row => {
        const match = row.dataset.nama.includes(q) || row.dataset.nidn.includes(q);
        row.style.display = match ? '' : 'none';
    });
    // Sembunyikan grup kelas yang seluruh siswanya ter-filter out
    document.querySelectorAll('.kelas-group').forEach(group => {
        const visibleRows = group.querySelectorAll('.siswa-row:not([style*="display: none"])');
        group.style.display = visibleRows.length ? '' : 'none';
    });
});

// Load daftar siswa pertama kali tombol "Luluskan Siswa" diklik
const btnLuluskan = document.querySelector('[onclick="openModal(\'modalLuluskan\')"]');
let __siswaLoaded = false;
btnLuluskan?.addEventListener('click', function () {
    if (!__siswaLoaded) {
        loadSiswaAktif();
        __siswaLoaded = true;
    }
});

document.getElementById('formLuluskan')?.addEventListener('submit', function (e) {
    const checked = document.querySelectorAll('.siswa-checkbox:checked');
    if (checked.length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu siswa yang akan diluluskan.');
        return;
    }
    if (!confirm(`Luluskan ${checked.length} siswa terpilih? Akun mereka akan dihapus dari Kelola User dan datanya disalin ke Data Alumni.`)) {
        e.preventDefault();
    }
});
</script>
@endpush
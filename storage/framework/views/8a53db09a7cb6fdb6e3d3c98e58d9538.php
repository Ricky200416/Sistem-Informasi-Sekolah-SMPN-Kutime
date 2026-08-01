<?php $__env->startSection('title', 'Data Alumni'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-4">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data Alumni</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                Arsip siswa/i SMP Negeri Kutime yang telah lulus.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">

            
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
                    <a href="<?php echo e(route('admin.alumni.export-excel', request()->only('tahun_lulus'))); ?>"
                       class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium
                              text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                     01.707.293l5.414 5.414A1 1 0 0120 9.414V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel (.xlsx)
                    </a>
                    <a href="<?php echo e(route('admin.alumni.export-pdf', request()->only('tahun_lulus'))); ?>"
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

    
    <div class="grid-kpi-3">
        <div class="stat-card">
            <div class="stat-value"><?php echo e($totalAlumni); ?></div>
            <div class="stat-label">Total Alumni</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo e($totalTahunIni); ?></div>
            <div class="stat-label">Lulus Tahun <?php echo e(date('Y')); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo e($siswaAktifCount); ?></div>
            <div class="stat-label">Siswa Aktif Saat Ini</div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="flex items-start gap-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200
                dark:border-emerald-800 rounded-2xl p-4">
        <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="flex items-start gap-3 bg-red-50 dark:bg-red-950/40 border border-red-200
                dark:border-red-800 rounded-2xl p-4">
        <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs font-semibold text-red-600 dark:text-red-400"><?php echo e(session('error')); ?></p>
    </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('admin.alumni.index')); ?>"
          class="flex flex-col sm:flex-row gap-2 sm:items-center">
        <div class="relative max-w-xs w-full">
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="q" value="<?php echo e(request('q')); ?>"
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
            <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t); ?>" <?php echo e((string) request('tahun_lulus') === (string) $t ? 'selected' : ''); ?>>
                    <?php echo e($t); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <button type="submit"
                class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300
                       text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
            Terapkan
        </button>

        <?php if(request('q') || request('tahun_lulus')): ?>
            <a href="<?php echo e(route('admin.alumni.index')); ?>"
               class="px-3 py-2 rounded-xl text-xs font-semibold text-red-500 hover:bg-red-50
                      dark:hover:bg-red-900/20 transition">
                Reset
            </a>
        <?php endif; ?>
    </form>

    
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
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $alumni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($alumni->firstItem() + $i); ?></td>
                        <td>
                            <div class="avatar avatar-sm bg-indigo-100 text-indigo-600">
                                <?php if($a->foto_url): ?>
                                    <img src="<?php echo e($a->foto_url); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo e(strtoupper(substr($a->nama, 0, 1))); ?>

                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="font-semibold text-slate-700 dark:text-slate-200"><?php echo e($a->nama); ?></td>
                        <td><?php echo e($a->nidn ?? '—'); ?></td>
                        <td><?php echo e($a->kelas_terakhir ?? '—'); ?></td>
                        <td><span class="badge badge-info"><?php echo e($a->tahun_lulus); ?></span></td>
                        <td><?php echo e($a->no_ijazah ?? '—'); ?></td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <button onclick="openDetailAlumni(<?php echo e($a->id); ?>)"
                                        class="icon-btn" title="Detail">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                                 -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <form action="<?php echo e(route('admin.alumni.batalkan', $a->id)); ?>" method="POST"
                                      onsubmit="return confirm('Batalkan status alumni dan kembalikan siswa ini ke status aktif?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="icon-btn" title="Batalkan status alumni">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                                        </svg>
                                    </button>
                                </form>

                                <button onclick="openHapusAlumni(<?php echo e($a->id); ?>, '<?php echo e(addslashes($a->nama)); ?>')"
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 13c0
                                             5.523-4.477 10-9 10S3 18.523 3 13c0-.538.04-1.066.118-1.578L12 14z"/>
                                </svg>
                                <p>Belum ada data alumni<?php echo e(request('q') || request('tahun_lulus') ? ' untuk filter ini' : ''); ?>.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($alumni->hasPages()): ?>
        <div><?php echo e($alumni->links('pagination::bootstrap-5')); ?></div>
    <?php endif; ?>

</div>

<?php echo $__env->make('admin.alumni._modal_detail', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.alumni._modal_luluskan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.alumni._modal_hapus', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
function openDetailAlumni(id) {
    fetch(`<?php echo e(url('admin/alumni')); ?>/${id}`, {
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
}

// ── Modal Hapus Alumni ───────────────────────────────────────────────────
function openHapusAlumni(id, nama) {
    document.getElementById('hapusAlumniNama').textContent = nama;
    document.getElementById('formHapusAlumni').action = `<?php echo e(url('admin/alumni')); ?>/${id}`;
    openModal('modalHapusAlumni');
}

// ── Modal Luluskan Siswa: load daftar siswa aktif & filter kelas ─────────
let __daftarSiswaAktif = [];

function loadSiswaAktif(kelasId = '') {
    const wrap = document.getElementById('luluskan_listSiswa');
    wrap.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Memuat data siswa...</p>';

    const url = new URL(`<?php echo e(route('admin.alumni.siswa-aktif')); ?>`);
    if (kelasId) url.searchParams.set('kelas_id', kelasId);

    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            __daftarSiswaAktif = data.siswa;
            renderSiswaAktif(data.siswa);

            const selKelas = document.getElementById('luluskan_kelasFilter');
            if (selKelas.options.length <= 1) {
                data.kelas.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.id;
                    opt.textContent = k.name;
                    selKelas.appendChild(opt);
                });
            }
        })
        .catch(() => {
            wrap.innerHTML = '<p class="text-xs text-red-500 text-center py-4">Gagal memuat daftar siswa.</p>';
        });
}

function renderSiswaAktif(list) {
    const wrap = document.getElementById('luluskan_listSiswa');
    if (!list.length) {
        wrap.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Tidak ada siswa aktif untuk filter ini.</p>';
        return;
    }
    wrap.innerHTML = list.map(s => `
        <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/60 cursor-pointer">
            <input type="checkbox" name="siswa_ids[]" value="${s.id}" class="siswa-checkbox rounded border-slate-300">
            <span class="text-xs text-slate-700 dark:text-slate-300 flex-1 truncate">${s.nama}</span>
            <span class="text-[10px] text-slate-400">${s.nidn ?? '-'}</span>
            <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 shrink-0">${s.kelas}</span>
        </label>
    `).join('');
}

document.getElementById('luluskan_kelasFilter')?.addEventListener('change', function () {
    loadSiswaAktif(this.value);
});

document.getElementById('luluskan_pilihSemua')?.addEventListener('change', function () {
    document.querySelectorAll('.siswa-checkbox').forEach(cb => cb.checked = this.checked);
});

document.getElementById('luluskan_searchSiswa')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    const filtered = __daftarSiswaAktif.filter(s => s.nama.toLowerCase().includes(q) || (s.nidn ?? '').toLowerCase().includes(q));
    renderSiswaAktif(filtered);
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
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/alumni/index.blade.php ENDPATH**/ ?>
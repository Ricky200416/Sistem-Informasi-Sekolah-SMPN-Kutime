<?php $__env->startSection('title', 'Kelola Pengumuman'); ?>

<?php $__env->startSection('content'); ?>

<?php
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
?>


<div id="pgModal"
     onclick="if(event.target===this)pgTutup()"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(15, 23, 42, 0.6); backdrop-filter:blur(8px)">
    <div class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto
                bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700 transform transition-all">
        <button onclick="pgTutup()"
                class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center
                       bg-slate-100 hover:bg-red-100 dark:bg-slate-700 dark:hover:bg-red-900/40
                       text-slate-500 hover:text-red-500 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div id="pgModalKonten" class="p-6 sm:p-8"></div>
    </div>
</div>


<div id="pgBulkDeleteModal"
     onclick="if(event.target===this)pgTutupBulkModal()"
     class="fixed inset-0 z-[1000] hidden items-center justify-center p-4"
     style="background:rgba(15, 23, 42, 0.7); backdrop-filter:blur(8px)">
    <div class="w-full max-w-sm bg-white dark:bg-slate-800 rounded-3xl shadow-2xl
                border border-slate-200 dark:border-slate-700 p-6 text-center">
        <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-3xl mx-auto mb-4">
            🗑️
        </div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Hapus Pengumuman?</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 mb-6" id="pgBulkDeleteDesc">
            Yakin ingin menghapus <span id="pgBulkDeleteCount" class="font-bold text-red-600">0</span> pengumuman yang dipilih? Tindakan ini permanen.
        </p>
        <div class="flex gap-3">
            <button onclick="pgTutupBulkModal()"
                    class="flex-1 px-4 py-3 rounded-2xl text-sm font-semibold
                           bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600
                           text-slate-700 dark:text-slate-200 transition">
                Batal
            </button>
            <button onclick="pgKonfirmasiBulkDelete()"
                    class="flex-1 px-4 py-3 rounded-2xl text-sm font-semibold
                           bg-red-600 hover:bg-red-700 text-white transition shadow-lg shadow-red-200 dark:shadow-none">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<div class="space-y-6">

    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                📢 Kelola Pengumuman
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Publikasikan informasi penting untuk Guru dan Siswa di sini.
            </p>
        </div>
        <a href="<?php echo e(route('admin.pengumuman.create')); ?>"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-600
                  hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-lg shadow-indigo-200 dark:shadow-none w-fit">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pengumuman
        </a>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <?php $__currentLoopData = [
            ['label'=>'Total',  'val'=>$total,       'icon'=>'📋','c'=>'text-slate-700 dark:text-slate-200', 'bg'=>'bg-slate-100'],
            ['label'=>'Aktif',  'val'=>$aktif,       'icon'=>'✅','c'=>'text-emerald-600 dark:text-emerald-400', 'bg'=>'bg-emerald-50'],
            ['label'=>'Guru',   'val'=>$guru+$semua, 'icon'=>'👨‍🏫','c'=>'text-violet-600 dark:text-violet-400', 'bg'=>'bg-violet-50'],
            ['label'=>'Siswa',  'val'=>$siswa+$semua,'icon'=>'🎓','c'=>'text-sky-600 dark:text-sky-400', 'bg'=>'bg-sky-50'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 <?php echo e($st['bg']); ?> dark:bg-slate-700 rounded-2xl flex items-center justify-center text-2xl">
                <?php echo e($st['icon']); ?>

            </div>
            <div>
                <div class="text-xl font-bold <?php echo e($st['c']); ?>"><?php echo e($st['val']); ?></div>
                <div class="text-xs text-slate-400 dark:text-slate-500 font-medium"><?php echo e($st['label']); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200
                dark:border-slate-700 p-5 shadow-sm">
        <form method="GET" action="<?php echo e(route('admin.pengumuman')); ?>"
              class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Cari Judul</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Cari pengumuman..."
                       class="w-full px-4 py-2.5 text-sm rounded-2xl border border-slate-200
                              dark:border-slate-600 bg-slate-50 dark:bg-slate-700
                              text-slate-800 dark:text-slate-200
                              focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition">
            </div>
            <div class="w-full sm:w-auto min-w-[140px]">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Target</label>
                <select name="filter_audience"
                        class="w-full px-4 py-2.5 text-sm rounded-2xl border border-slate-200
                               dark:border-slate-600 bg-slate-50 dark:bg-slate-700
                               text-slate-800 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition">
                    <option value="">Semua Target</option>
                    <option value="semua" <?php echo e(request('filter_audience')=='semua'?'selected':''); ?>>Semua</option>
                    <option value="guru"  <?php echo e(request('filter_audience')=='guru' ?'selected':''); ?>>Guru</option>
                    <option value="siswa" <?php echo e(request('filter_audience')=='siswa'?'selected':''); ?>>Siswa</option>
                </select>
            </div>
            <div class="w-full sm:w-auto min-w-[140px]">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Status</label>
                <select name="filter_status"
                        class="w-full px-4 py-2.5 text-sm rounded-2xl border border-slate-200
                               dark:border-slate-600 bg-slate-50 dark:bg-slate-700
                               text-slate-800 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition">
                    <option value="">Semua Status</option>
                    <option value="aktif"    <?php echo e(request('filter_status')=='aktif'   ?'selected':''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(request('filter_status')=='nonaktif'?'selected':''); ?>>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="flex-1 sm:flex-none px-6 py-2.5 bg-slate-800 dark:bg-indigo-600 hover:bg-slate-900 dark:hover:bg-indigo-700 text-white
                               text-sm font-semibold rounded-2xl transition shadow-md">
                    Filter
                </button>
                <?php if(request()->hasAny(['search','filter_audience','filter_status'])): ?>
                <a href="<?php echo e(route('admin.pengumuman')); ?>"
                   class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700
                          dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300
                          text-sm font-semibold rounded-2xl transition">
                    Reset
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <div id="pgBulkBar"
         class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[50] items-center gap-6 px-6 py-3 rounded-3xl
                bg-slate-900 dark:bg-indigo-900 border border-slate-800 dark:border-indigo-700
                shadow-2xl animate-in slide-in-from-bottom-4 duration-300">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-white shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="text-sm font-bold text-white whitespace-nowrap">
                <span id="pgSelectedCount">0</span> Item Terpilih
            </span>
        </div>
        <div class="h-8 w-px bg-white/20"></div>
        <div class="flex items-center gap-3">
            <button onclick="pgDeselectAll()"
                    class="text-xs font-bold text-slate-300 hover:text-white transition">
                Batal
            </button>
            <button onclick="pgBukaBulkModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold
                           rounded-xl bg-red-500 hover:bg-red-600 text-white transition shadow-lg shadow-red-500/20">
                Hapus Massal
            </button>
        </div>
    </div>

    
    <form id="pgBulkDeleteForm" method="POST" action="<?php echo e(route('admin.pengumuman.bulkDestroy')); ?>" class="hidden">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <div id="pgBulkDeleteIds"></div>
    </form>

    
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200
                dark:border-slate-700 shadow-sm overflow-hidden transition-all">

        <?php if($pengumuman->isEmpty()): ?>
        <div class="text-center py-20">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Belum Ada Pengumuman</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 mb-6">Mulai buat pengumuman pertama Anda untuk dibagikan.</p>
            <a href="<?php echo e(route('admin.pengumuman.create')); ?>"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl
                      bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                + Tambah Pengumuman
            </a>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700
                               bg-slate-50/50 dark:bg-slate-900/50 text-left">
                        <th class="px-6 py-4 w-10">
                            <div class="flex items-center justify-center">
                                <input type="checkbox" id="pgCheckAll"
                                       onchange="pgToggleAll(this)"
                                       class="w-4 h-4 rounded-lg border-slate-300 dark:border-slate-600
                                              text-indigo-600 focus:ring-indigo-500 cursor-pointer transition">
                            </div>
                        </th>
                        <th class="px-4 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px]">Pengumuman</th>
                        <th class="px-4 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px]">Target</th>
                        <th class="px-4 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px]">Detail</th>
                        <th class="px-4 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px]">Status</th>
                        <th class="px-4 py-4 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px]">Terbit</th>
                        <th class="px-6 py-4 text-right font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px]">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50" id="pgTableBody">
                    <?php $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
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
                    ?>
                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors group pg-row" data-id="<?php echo e($item->id); ?>">
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                <input type="checkbox"
                                       class="pg-row-check w-4 h-4 rounded-lg border-slate-300
                                              dark:border-slate-600 text-indigo-600 focus:ring-indigo-500
                                              cursor-pointer transition accent-indigo-600"
                                       value="<?php echo e($item->id); ?>"
                                       onchange="pgUpdateBulkBar()">
                            </div>
                        </td>
                        <td class="px-4 py-4 max-w-xs">
                            <button type="button" onclick='pgBuka(<?php echo e($pgJson); ?>)'
                                    class="flex items-center gap-3 text-left w-full group/btn">
                                <span class="w-10 h-10 shrink-0 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xl group-hover/btn:bg-indigo-100 dark:group-hover/btn:bg-indigo-900 transition-colors">
                                    <?php echo e($item->tipeIcon()); ?>

                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 dark:text-slate-100 truncate group-hover/btn:text-indigo-600 transition-colors">
                                        <?php echo e($item->judul); ?>

                                    </p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 line-clamp-1">
                                        <?php echo e(strip_tags($item->isi) ?: 'Tanpa keterangan teks'); ?>

                                    </p>
                                </div>
                            </button>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                         <?php echo e($item->audienceBadgeColor()); ?>">
                                <?php echo e($item->audienceLabel()); ?>

                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 flex items-center gap-1">
                                    📁 <?php echo e(ucfirst($item->tipe_konten)); ?>

                                </span>
                                <?php if($item->show_di_dashboard): ?>
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                    📌 Dashboard
                                </span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <button type="button"
                                    onclick="pgToggle(<?php echo e($item->id); ?>, this)"
                                    data-active="<?php echo e($item->is_active ? '1' : '0'); ?>"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full
                                           transition-all duration-300 focus:outline-none
                                           <?php echo e($item->is_active ? 'bg-indigo-500 shadow-lg shadow-indigo-500/30' : 'bg-slate-300 dark:bg-slate-600'); ?>">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform duration-300"
                                      style="<?php echo e($item->is_active ? 'transform:translateX(24px)' : 'transform:translateX(4px)'); ?>">
                                </span>
                            </button>
                        </td>
                        <td class="px-4 py-4 text-[11px] text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            <?php echo e($item->created_at->format('d M Y')); ?>

                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick='pgBuka(<?php echo e($pgJson); ?>)' title="Pratinjau"
                                        class="p-2 rounded-xl bg-slate-50 hover:bg-emerald-50 dark:bg-slate-700/50 dark:hover:bg-emerald-900/30
                                               text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <a href="<?php echo e(route('admin.pengumuman.edit', $item)); ?>" title="Edit"
                                   class="p-2 rounded-xl bg-slate-50 hover:bg-indigo-50 dark:bg-slate-700/50 dark:hover:bg-indigo-900/30
                                          text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.pengumuman.destroy', $item)); ?>"
                                      onsubmit="return confirm('Hapus pengumuman ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" title="Hapus"
                                            class="p-2 rounded-xl bg-slate-50 hover:bg-red-50 dark:bg-slate-700/50 dark:hover:bg-red-900/30
                                                   text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($pengumuman->hasPages()): ?>
        <div class="px-6 py-5 border-t border-slate-200 dark:border-slate-700 bg-slate-50/30 dark:bg-transparent">
            <?php echo e($pengumuman->links()); ?>

        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    'use strict';

    window.pgThumbError = function (img) {
        var wrap = img.parentElement;
        if (wrap) wrap.classList.add('hidden');
    };

    window.pgModalImgError = function (img) {
        var wrap = img.closest('div');
        if (!wrap) return;
        wrap.innerHTML =
            '<div class="p-10 text-center bg-slate-50 dark:bg-slate-900 rounded-2xl">' +
                '<div class="text-4xl mb-3">🖼️</div>' +
                '<p class="text-sm text-slate-400 font-medium">Gagal memuat gambar.</p>' +
            '</div>';
    };

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

    window.pgToggle = function (id, btn) {
        var token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
        fetch('/admin/pengumuman/' + id + '/toggle', {
            method : 'PATCH',
            headers: { 'X-CSRF-TOKEN' : token, 'Content-Type' : 'application/json', 'Accept' : 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) return;
            var on = data.is_active;
            btn.className = 'relative inline-flex h-6 w-11 items-center rounded-full transition-all duration-300 focus:outline-none ' +
                (on ? 'bg-indigo-500 shadow-lg shadow-indigo-500/30' : 'bg-slate-300 dark:bg-slate-600');
            btn.querySelector('span').style.transform = on ? 'translateX(24px)' : 'translateX(4px)';
        })
        .catch(function (err) { console.error('Toggle error:', err); });
    };

    window.pgToggleAll = function (masterCb) {
        var checks = document.querySelectorAll('.pg-row-check');
        checks.forEach(function (cb) { cb.checked = masterCb.checked; });
        pgUpdateBulkBar();
    };

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

        if (master) {
            master.checked = checked.length === all.length && all.length > 0;
            master.indeterminate = checked.length > 0 && checked.length < all.length;
        }

        document.querySelectorAll('.pg-row').forEach(function (row) {
            var cb = row.querySelector('.pg-row-check');
            if (cb && cb.checked) {
                row.classList.add('bg-indigo-50/50', 'dark:bg-indigo-900/20');
            } else {
                row.classList.remove('bg-indigo-50/50', 'dark:bg-indigo-900/20');
            }
        });
    };

    window.pgDeselectAll = function () {
        document.querySelectorAll('.pg-row-check').forEach(function (cb) { cb.checked = false; });
        if (document.getElementById('pgCheckAll')) document.getElementById('pgCheckAll').checked = false;
        pgUpdateBulkBar();
    };

    window.pgBukaBulkModal = function () {
        var checked = document.querySelectorAll('.pg-row-check:checked');
        if (checked.length === 0) return;
        document.getElementById('pgBulkDeleteCount').textContent = checked.length;
        var m = document.getElementById('pgBulkDeleteModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    };

    window.pgTutupBulkModal = function () {
        var m = document.getElementById('pgBulkDeleteModal');
        if (m) m.classList.add('hidden');
    };

    window.pgKonfirmasiBulkDelete = function () {
        var checked = document.querySelectorAll('.pg-row-check:checked');
        var container = document.getElementById('pgBulkDeleteIds');
        container.innerHTML = '';
        checked.forEach(function (cb) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = cb.value;
            container.appendChild(inp);
        });
        document.getElementById('pgBulkDeleteForm').submit();
    };

    function pgHtml(d) {
        var h = '';
        h += '<div class="flex items-start gap-4 mb-6 pr-8">';
        h +=   '<div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center text-3xl shrink-0">' + d.tipeIcon + '</div>';
        h +=   '<div class="flex-1 min-w-0">';
        h +=     '<h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 leading-tight break-words">' + e(d.judul) + '</h2>';
        h +=     '<div class="flex gap-2 mt-2 flex-wrap">';
        h +=       '<span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider ' + d.audienceColor + '">' + e(d.audience) + '</span>';
        h +=       '<span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 capitalize">' + e(d.tipe) + '</span>';
        h +=     '</div>';
        h +=   '</div>';
        h += '</div>';

        h += '<div class="flex flex-wrap gap-4 text-[11px] text-slate-400 mb-6 py-4 border-y border-slate-100 dark:border-slate-700">';
        h +=   '<span class="flex items-center gap-1.5">📅 ' + e(d.tanggal) + '</span>';
        h +=   '<span class="flex items-center gap-1.5">👤 ' + e(d.creator) + '</span>';
        h +=   '<span class="flex items-center gap-1.5">🕐 ' + e(d.diffHumans) + '</span>';
        h += '</div>';

        if (d.tipe === 'gambar' && d.fileUrl) {
            h += '<div class="rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 mb-6 bg-slate-50 dark:bg-slate-900">';
            h +=   '<img src="' + d.fileUrl + '" class="w-full max-h-72 object-contain block mx-auto" onerror="pgModalImgError(this)">';
            h += '</div>';
        }

        if (d.isi && d.isi.trim()) {
            var hasTags = /<[a-z][\s\S]*>/i.test(d.isi);
            h += '<div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6 ' + (hasTags ? 'prose dark:prose-invert max-w-none' : 'whitespace-pre-line') + '">' + (hasTags ? s(d.isi) : e(d.isi)) + '</div>';
        }

        if (d.tipe === 'dokumen' && d.fileUrl) {
            h += '<div class="flex items-center justify-between gap-4 p-4 bg-indigo-50/50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800 mb-6">';
            h +=   '<div class="flex items-center gap-3">';
            h +=     '<div class="w-10 h-10 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center text-xl shadow-sm">📄</div>';
            h +=     '<div><p class="text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase">' + e(d.fileExt || 'FILE') + '</p><p class="text-[10px] text-slate-400 truncate max-w-[140px]">' + e(d.fileName) + '</p></div>';
            h +=   '</div>';
            h +=   '<a href="' + d.fileUrl + '" target="_blank" download class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-xl transition shadow-lg shadow-indigo-200 dark:shadow-none">Unduh</a>';
            h += '</div>';
        }

        if (d.tipe === 'link' && d.linkUrl) {
            h += '<div class="p-4 bg-sky-50 dark:bg-sky-900/20 rounded-2xl border border-sky-100 dark:border-sky-800 mb-6">';
            h +=   '<p class="text-[10px] font-bold text-sky-600 uppercase mb-3 tracking-widest">Tautan Terkait</p>';
            h +=   '<a href="' + d.linkUrl + '" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-sky-200 dark:shadow-none">';
            h +=     e(d.linkLabel || 'Buka Tautan') + ' ↗';
            h +=   '</a>';
            h += '</div>';
        }

        if (d.tglSelesai) {
            h += '<div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-800 mb-6">';
            h +=   '<span class="text-lg">⏰</span>';
            h +=   '<p class="text-[11px] text-amber-700 dark:text-amber-300 font-medium">Berakhir pada: <strong class="ml-1">' + e(d.tglSelesai) + '</strong></p>';
            h += '</div>';
        }

        h += '<div class="flex justify-end"><button onclick="pgTutup()" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-2xl transition">Tutup Detail</button></div>';
        return h;
    }

    function e(v) { return v ? String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;') : ''; }
    function s(h) { return (h || '').replace(/<script[\s\S]*?<\/script>/gi,'').replace(/<iframe[\s\S]*?<\/iframe>/gi,'').replace(/\bon\w+=["'][^"']*["']/gi,'').replace(/javascript:/gi,'#'); }
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/pengumuman/index.blade.php ENDPATH**/ ?>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-[520px]">

    
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/60 bg-white dark:bg-slate-800 sticky top-0 z-10 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md">
                📢
            </div>
            <div>
                <p class="text-xs font-black text-slate-800 dark:text-slate-100 uppercase tracking-wider">Pengumuman Internal</p>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Update terbaru sekolah</p>
            </div>
        </div>
        <?php if(Route::has('admin.pengumuman')): ?>
        <a href="<?php echo e(route('admin.pengumuman')); ?>" class="text-[10px] font-black text-indigo-600 uppercase hover:underline">Kelola</a>
        <?php endif; ?>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <?php $__empty_1 = true; $__currentLoopData = $widgetPengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $wData = [
                    'judul' => $item->judul, 'isi' => $item->isi, 'tipe' => $item->tipe_konten, 'tipeIcon' => $item->tipeIcon(),
                    'audience' => $item->audienceLabel(), 'audienceColor' => $item->audienceBadgeColor(),
                    'fileUrl' => $item->file_path ? asset('storage/'.$item->file_path) : '',
                    'tanggal' => $item->created_at->isoFormat('D MMMM Y'), 'diffHumans' => $item->created_at->diffForHumans(),
                    'creator' => optional($item->creator)->name ?? 'Admin', 'widgetRole' => 'admin',
                    'linkUrl' => $item->link_url ?? '', 'linkLabel' => $item->link_label ?? '',
                    'fileName' => $item->file_name ?? '',
                ];
                $ikBg = match($item->tipe_konten) {
                    'gambar' => 'bg-rose-50 text-rose-500', 'dokumen' => 'bg-indigo-50 text-indigo-500',
                    'link' => 'bg-sky-50 text-sky-500', default => 'bg-emerald-50 text-emerald-500',
                };
            ?>
            <button onclick='wdgBuka(<?php echo json_encode($wData, 15, 512) ?>)' class="w-full text-left p-4 border-b border-slate-50 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all group">
                <div class="flex gap-4">
                    <?php if($item->tipe_konten === 'gambar' && $item->file_path): ?>
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-600 shrink-0 bg-slate-100">
                        <img src="<?php echo e(asset('storage/'.$item->file_path)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <?php else: ?>
                    <div class="w-14 h-14 rounded-2xl <?php echo e($ikBg); ?> dark:bg-slate-700 flex items-center justify-center text-2xl shrink-0 border border-current opacity-60">
                        <?php echo e($item->tipeIcon()); ?>

                    </div>
                    <?php endif; ?>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md <?php echo e($item->audienceBadgeColor()); ?>">
                                <?php echo e($item->audienceLabel()); ?>

                            </span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase"><?php echo e($item->created_at->diffForHumans()); ?></span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 dark:text-white leading-tight group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2"><?php echo e($item->judul); ?></h4>
                        <p class="text-[10px] text-slate-400 leading-relaxed line-clamp-2"><?php echo e(strip_tags($item->isi)); ?></p>
                    </div>
                </div>
            </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="h-full flex flex-col items-center justify-center text-center p-10 opacity-30">
            <div class="text-5xl mb-4">📭</div>
            <p class="text-xs font-black uppercase tracking-[0.2em]">Belum ada pengumuman</p>
        </div>
        <?php endif; ?>
    </div>
</div>


<div id="wdgModal"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
     style="display:none !important; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px);"
     onclick="wdgTutup(event)">

    <div id="wdgPanel"
         class="relative bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-2xl w-full max-w-lg max-h-[85vh] flex flex-col overflow-hidden"
         onclick="event.stopPropagation()">

        
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-start justify-between gap-4 shrink-0">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div id="wdgIcon" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0">📢</div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span id="wdgBadge" class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md bg-slate-100 text-slate-500">Semua</span>
                        <span id="wdgDiff" class="text-[9px] font-bold text-slate-400 uppercase"></span>
                    </div>
                    <p id="wdgCreator" class="text-[10px] text-slate-400">oleh Admin</p>
                </div>
            </div>
            <button onclick="wdgTutup()" class="shrink-0 w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center text-slate-500 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <div class="flex-1 overflow-y-auto p-6 space-y-5">

            
            <div id="wdgImgWrap" class="hidden rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                <img id="wdgImg" src="" alt="" class="w-full h-56 object-cover">
            </div>

            
            <h2 id="wdgJudul" class="text-xl font-black text-slate-800 dark:text-white leading-tight"></h2>

            
            <p id="wdgTanggal" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"></p>

            
            <div id="wdgIsi" class="prose prose-sm prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 leading-relaxed"></div>

            
            <div id="wdgDocWrap" class="hidden p-5 bg-indigo-600 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-3xl">📄</div>
                    <div class="text-white">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Berkas Lampiran</p>
                        <p id="wdgFileName" class="text-sm font-black truncate max-w-[200px]"></p>
                    </div>
                </div>
                <a id="wdgDocLink" href="#" target="_blank" download
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-white hover:bg-slate-100 text-indigo-600 text-xs font-black rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </a>
            </div>

            
            <div id="wdgLinkWrap" class="hidden p-5 bg-slate-900 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-sky-500/20 text-sky-400 rounded-xl flex items-center justify-center text-2xl">🔗</div>
                    <div class="text-white">
                        <p class="text-[10px] font-black uppercase tracking-widest text-sky-400 mb-0.5">Tautan Eksternal</p>
                        <p id="wdgLinkLabel" class="text-sm font-black"></p>
                    </div>
                </div>
                <a id="wdgLinkUrl" href="#" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white text-xs font-black rounded-xl transition-all">
                    Buka ↗
                </a>
            </div>

        </div>

        
        <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between shrink-0">
            <p class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-widest">Sekolah Digital App</p>
            <button onclick="wdgTutup()" class="text-[10px] font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">Tutup</button>
        </div>
    </div>
</div>

<script>
function wdgBuka(d) {
    // Isi konten
    document.getElementById('wdgJudul').textContent   = d.judul || '';
    document.getElementById('wdgIsi').innerHTML       = d.isi   || '';
    document.getElementById('wdgDiff').textContent    = d.diffHumans || '';
    document.getElementById('wdgTanggal').textContent = d.tanggal ? 'Dibuat ' + d.tanggal : '';
    document.getElementById('wdgCreator').textContent = 'oleh ' + (d.creator || 'Admin');
    document.getElementById('wdgIcon').textContent    = d.tipeIcon || '📢';

    // Badge audience
    const badge = document.getElementById('wdgBadge');
    badge.textContent  = d.audience || '';
    badge.className    = 'text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md ' + (d.audienceColor || 'bg-slate-100 text-slate-500');

    // Gambar
    const imgWrap = document.getElementById('wdgImgWrap');
    const img     = document.getElementById('wdgImg');
    if (d.tipe === 'gambar' && d.fileUrl) {
        img.src = d.fileUrl;
        imgWrap.classList.remove('hidden');
    } else {
        imgWrap.classList.add('hidden');
        img.src = '';
    }

    // Dokumen
    const docWrap = document.getElementById('wdgDocWrap');
    if (d.tipe === 'dokumen' && d.fileUrl) {
        document.getElementById('wdgFileName').textContent = d.fileName || 'Unduh Berkas';
        document.getElementById('wdgDocLink').href         = d.fileUrl;
        docWrap.classList.remove('hidden');
    } else {
        docWrap.classList.add('hidden');
    }

    // Link
    const linkWrap = document.getElementById('wdgLinkWrap');
    if (d.tipe === 'link' && d.linkUrl) {
        document.getElementById('wdgLinkLabel').textContent = d.linkLabel || d.linkUrl;
        document.getElementById('wdgLinkUrl').href          = d.linkUrl;
        linkWrap.classList.remove('hidden');
    } else {
        linkWrap.classList.add('hidden');
    }

    // Tampilkan modal
    const modal = document.getElementById('wdgModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Animasi masuk
    const panel = document.getElementById('wdgPanel');
    panel.style.transform = 'translateY(16px)';
    panel.style.opacity   = '0';
    panel.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
    requestAnimationFrame(() => {
        panel.style.transform = 'translateY(0)';
        panel.style.opacity   = '1';
    });
}

function wdgTutup(event) {
    if (event && event.target !== document.getElementById('wdgModal')) return;
    const modal = document.getElementById('wdgModal');
    const panel = document.getElementById('wdgPanel');
    panel.style.transform = 'translateY(16px)';
    panel.style.opacity   = '0';
    setTimeout(() => {
        modal.style.display  = 'none';
        document.body.style.overflow = '';
    }, 200);
}

// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') wdgTutup();
});
</script><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/dashboard/announcement.blade.php ENDPATH**/ ?>
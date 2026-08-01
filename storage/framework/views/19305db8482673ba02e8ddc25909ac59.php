<?php $__env->startSection('title', 'Berita & Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 py-5 lg:py-9">

    
    <header class="mb-4 text-center sm:text-left">
        <h1 class="text-base lg:text-2xl font-semibold text-black dark:text-black">
            Berita & Pengumuman SMPN Kutime
        </h1>
        <p class="mt-0.5 text-[10px] text-slate-500 dark:text-slate-400">
            Informasi terbaru, pengumuman penting, dan kegiatan sekolah.
        </p>
    </header>

    
    <?php if($pengumuman->isNotEmpty()): ?>
    <div class="mb-6">
        <h2 class="text-xs font-semibold text-red-700 dark:text-red-400 mb-2.5 flex items-center gap-1.5">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
            Pengumuman Penting
        </h2>

        <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 sm:gap-3">
            <?php $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('website.berita.show', $item->slug)); ?>"
               class="group flex flex-col bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-950/30 dark:to-rose-950/30
                      rounded-lg border border-red-200 dark:border-red-800 shadow-sm overflow-hidden
                      transition-all hover:shadow-md hover:-translate-y-0.5">

                
                <?php if($item->has_media): ?>
                <div class="relative h-16 sm:h-28 bg-black overflow-hidden">

                    <?php if($item->media_tipe === 'photo' && $item->media_file): ?>
                        <img src="<?php echo e($item->media_file_url); ?>"
                             alt="<?php echo e($item->judul); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <?php elseif($item->media_tipe === 'video' && $item->media_file): ?>
                        <video src="<?php echo e($item->media_file_url); ?>"
                               class="w-full h-full object-cover" muted></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-white/80 flex items-center justify-center">
                                <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-red-600 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                    <?php elseif($item->media_tipe === 'link_youtube'): ?>
                        <img src="<?php echo e($item->media_thumbnail_url); ?>"
                             alt="<?php echo e($item->judul); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-red-600 flex items-center justify-center">
                                <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                    <?php elseif($item->media_tipe === 'link_facebook'): ?>
                        <div class="w-full h-full bg-blue-700 flex items-center justify-center">
                            <svg class="w-6 h-6 sm:w-10 sm:h-10 text-white/60" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                            </svg>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                            <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-white/80 flex items-center justify-center">
                                <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-blue-700 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div class="absolute top-1 right-1">
                        <?php if($item->media_tipe === 'link_youtube'): ?>
                            <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-red-600 text-white rounded leading-tight">▶ YT</span>
                        <?php elseif($item->media_tipe === 'link_facebook'): ?>
                            <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-blue-600 text-white rounded leading-tight">📘</span>
                        <?php elseif($item->media_tipe === 'video'): ?>
                            <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-black/60 text-white rounded leading-tight">🎥</span>
                        <?php elseif($item->media_tipe === 'photo'): ?>
                            <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-black/60 text-white rounded leading-tight">🖼️</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="px-1.5 sm:px-3 py-0.5 sm:py-1 bg-red-600 text-white text-[7px] sm:text-[10px] font-medium uppercase tracking-wide">
                    PENGUMUMAN
                </div>

                <div class="p-1.5 sm:p-3 flex-1 flex flex-col">
                    <h3 class="font-semibold text-black dark:text-black mb-1 text-[9px] sm:text-xs group-hover:text-red-700 transition-colors line-clamp-2 leading-tight">
                        <?php echo e($item->judul); ?>

                    </h3>
                    <p class="text-[8px] sm:text-[11px] text-slate-600 dark:text-slate-300 mb-1.5 flex-1 line-clamp-2 leading-tight hidden sm:block">
                        <?php echo e($item->ringkasan); ?>

                    </p>
                    <div class="flex items-center justify-between text-[7px] sm:text-[10px] text-slate-400">
                        <span><?php echo e($item->tanggal_publish?->format('d M Y')); ?></span>
                        <span class="font-medium text-red-600">Baca →</span>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('website.berita')); ?>" class="flex flex-wrap gap-1.5 mb-4">
        <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" placeholder="Cari berita..."
               class="rounded-lg border-slate-300 text-[10px] py-1 px-2.5 w-full sm:w-44 focus:border-indigo-500 focus:ring-indigo-500">

        <div class="flex gap-1.5 w-full sm:w-auto">
            <select name="kategori" class="flex-1 sm:flex-none rounded-lg border-slate-300 text-[10px] py-1 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                <option value="berita"     <?php if(request('kategori') === 'berita'): echo 'selected'; endif; ?>>Berita</option>
                <option value="pengumuman" <?php if(request('kategori') === 'pengumuman'): echo 'selected'; endif; ?>>Pengumuman</option>
            </select>

            <button type="submit" class="px-2.5 py-1 bg-indigo-600 text-white text-[10px] rounded-lg hover:bg-indigo-700 transition">
                Cari
            </button>

            <?php if(request()->hasAny(['cari','kategori'])): ?>
                <a href="<?php echo e(route('website.berita')); ?>"
                   class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] rounded-lg hover:bg-slate-200 transition">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

    
    <div>
        <h2 class="text-xs font-semibold text-black dark:text-black mb-3">Berita Terbaru</h2>

        <?php if($beritas->isEmpty()): ?>
            <div class="text-center py-12 text-slate-400 text-xs">
                <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Belum ada berita ditemukan.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-1.5 sm:gap-3">
                <?php $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('website.berita.show', $item->slug)); ?>"
                   class="group flex flex-col bg-white dark:bg-slate-800 rounded-lg shadow-sm
                          border border-slate-200 dark:border-slate-700 overflow-hidden
                          transition-all hover:shadow-md hover:-translate-y-0.5">

                    
                    <div class="relative h-16 sm:h-32 bg-slate-100 dark:bg-slate-700 overflow-hidden">

                        <?php if($item->media_tipe === 'photo' && $item->media_file): ?>
                            <img src="<?php echo e($item->media_file_url); ?>"
                                 alt="<?php echo e($item->judul); ?>"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy">

                        <?php elseif($item->media_tipe === 'video' && $item->media_file): ?>
                            <?php if($item->media_thumbnail): ?>
                                <img src="<?php echo e($item->media_thumbnail_url); ?>"
                                     alt="<?php echo e($item->judul); ?>"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            <?php else: ?>
                                <video src="<?php echo e($item->media_file_url); ?>"
                                       class="w-full h-full object-cover" muted preload="metadata"></video>
                            <?php endif; ?>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-black/50 flex items-center justify-center group-hover:bg-black/70 transition">
                                    <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>

                        <?php elseif($item->media_tipe === 'link_youtube'): ?>
                            <img src="<?php echo e($item->media_thumbnail_url); ?>"
                                 alt="<?php echo e($item->judul); ?>"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy">
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-red-600 flex items-center justify-center group-hover:bg-red-700 transition">
                                    <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>

                        <?php elseif($item->media_tipe === 'link_facebook'): ?>
                            <?php if($item->media_thumbnail): ?>
                                <img src="<?php echo e($item->media_thumbnail_url); ?>"
                                     alt="<?php echo e($item->judul); ?>"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full bg-blue-700 flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-8 sm:h-8 text-white/40" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                <div class="w-5 h-5 sm:w-8 sm:h-8 rounded-full bg-blue-600 flex items-center justify-center group-hover:bg-blue-700 transition">
                                    <svg class="w-2.5 h-2.5 sm:w-4 sm:h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                <svg class="w-5 h-5 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($item->has_media && $item->media_tipe !== 'none'): ?>
                        <div class="absolute top-1 left-1">
                            <?php if($item->media_tipe === 'link_youtube'): ?>
                                <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-red-600 text-white rounded leading-tight">▶ YT</span>
                            <?php elseif($item->media_tipe === 'link_facebook'): ?>
                                <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-blue-600 text-white rounded leading-tight">📘</span>
                            <?php elseif($item->media_tipe === 'video'): ?>
                                <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-black/60 text-white rounded leading-tight">🎥</span>
                            <?php elseif($item->media_tipe === 'photo'): ?>
                                <span class="px-1 py-0.5 text-[7px] sm:text-[10px] bg-black/60 text-white rounded leading-tight">🖼️</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="p-1.5 sm:p-3 flex-1 flex flex-col">
                        <div class="flex items-center gap-1 mb-1">
                            <span class="text-[7px] sm:text-[10px] text-slate-400 dark:text-slate-500 shrink-0">
                                <?php echo e($item->tanggal_publish?->format('d M Y')); ?>

                            </span>
                            <?php if($item->kategori === 'pengumuman'): ?>
                                <span class="px-1 py-0.5 text-[7px] sm:text-[10px] rounded bg-red-100 text-red-600 leading-tight shrink-0">Pngm</span>
                            <?php else: ?>
                                <span class="px-1 py-0.5 text-[7px] sm:text-[10px] rounded bg-blue-100 text-blue-600 leading-tight shrink-0">Berita</span>
                            <?php endif; ?>
                        </div>

                        <h3 class="font-semibold text-black dark:text-black mb-1 text-[9px] sm:text-xs line-clamp-2 group-hover:text-indigo-600 transition-colors leading-tight">
                            <?php echo e($item->judul); ?>

                        </h3>

                        <p class="text-[8px] sm:text-[11px] text-slate-500 dark:text-slate-300 mb-1 flex-1 line-clamp-2 leading-tight hidden sm:block">
                            <?php echo e($item->ringkasan); ?>

                        </p>

                        <span class="inline-flex items-center text-[8px] sm:text-[11px] font-medium text-indigo-600 dark:text-indigo-400 mt-auto">
                            Baca
                            <svg class="ml-0.5 w-2 h-2 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($beritas->hasPages()): ?>
                <div class="mt-6"><?php echo e($beritas->links()); ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/website/berita.blade.php ENDPATH**/ ?>
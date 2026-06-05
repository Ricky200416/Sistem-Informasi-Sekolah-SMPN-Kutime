<?php $__env->startSection('title', 'Berita & Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 py-10 lg:py-12">

    
    <header class="mb-8 text-center sm:text-left">
        <h1 class="text-2xl lg:text-3xl font-semibold text-slate-900 dark:text-slate-100">
            Berita & Pengumuman SMPN Kutime
        </h1>
        <p class="mt-1.5 text-sm text-slate-600 dark:text-slate-400">
            Informasi terbaru, pengumuman penting, dan kegiatan sekolah.
        </p>
    </header>

    
    <?php if($pengumuman->isNotEmpty()): ?>
    <div class="mb-10">
        <h2 class="text-base font-semibold text-red-700 dark:text-red-400 mb-4 flex items-center gap-2">
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
            Pengumuman Penting
        </h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('website.berita.show', $item->slug)); ?>"
               class="group flex flex-col bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-950/30 dark:to-rose-950/30
                      rounded-xl border border-red-200 dark:border-red-800 shadow-sm overflow-hidden
                      transition-all hover:shadow-md hover:-translate-y-1">

                
                <?php if($item->has_media): ?>
                <div class="relative h-36 bg-black overflow-hidden">

                    <?php if($item->media_tipe === 'photo' && $item->media_file): ?>
                        <img src="<?php echo e($item->media_file_url); ?>"
                             alt="<?php echo e($item->judul); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <?php elseif($item->media_tipe === 'video' && $item->media_file): ?>
                        <video src="<?php echo e($item->media_file_url); ?>"
                               class="w-full h-full object-cover" muted></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                    <?php elseif($item->media_tipe === 'link_youtube'): ?>
                        <img src="<?php echo e($item->media_thumbnail_url); ?>"
                             alt="<?php echo e($item->judul); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>

                    <?php elseif($item->media_tipe === 'link_facebook'): ?>
                        <div class="w-full h-full bg-blue-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/60" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                            </svg>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                            <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-700 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div class="absolute top-2 right-2">
                        <?php if($item->media_tipe === 'link_youtube'): ?>
                            <span class="px-1.5 py-0.5 text-xs bg-red-600 text-white rounded">▶ YouTube</span>
                        <?php elseif($item->media_tipe === 'link_facebook'): ?>
                            <span class="px-1.5 py-0.5 text-xs bg-blue-600 text-white rounded">📘 FB</span>
                        <?php elseif($item->media_tipe === 'video'): ?>
                            <span class="px-1.5 py-0.5 text-xs bg-black/60 text-white rounded">🎥 Video</span>
                        <?php elseif($item->media_tipe === 'photo'): ?>
                            <span class="px-1.5 py-0.5 text-xs bg-black/60 text-white rounded">🖼️ Foto</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="px-4 py-1.5 bg-red-600 text-white text-xs font-medium uppercase tracking-wide">
                    PENGUMUMAN
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2 text-sm group-hover:text-red-700 transition-colors">
                        <?php echo e($item->judul); ?>

                    </h3>
                    <p class="text-xs text-slate-700 dark:text-slate-300 mb-3 flex-1 line-clamp-3">
                        <?php echo e($item->ringkasan); ?>

                    </p>
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span><?php echo e($item->tanggal_publish?->format('d M Y')); ?></span>
                        <span class="font-medium text-red-600">Baca →</span>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('website.berita')); ?>" class="flex flex-wrap gap-2 mb-6">
        <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" placeholder="Cari berita..."
               class="rounded-lg border-slate-300 text-sm py-1.5 px-3 w-52 focus:border-indigo-500 focus:ring-indigo-500">

        <select name="kategori" class="rounded-lg border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Kategori</option>
            <option value="berita"     <?php if(request('kategori') === 'berita'): echo 'selected'; endif; ?>>Berita</option>
            <option value="pengumuman" <?php if(request('kategori') === 'pengumuman'): echo 'selected'; endif; ?>>Pengumuman</option>
        </select>

        <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
            Cari
        </button>

        <?php if(request()->hasAny(['cari','kategori'])): ?>
            <a href="<?php echo e(route('website.berita')); ?>"
               class="px-4 py-1.5 bg-slate-100 text-slate-600 text-sm rounded-lg hover:bg-slate-200 transition">
                Reset
            </a>
        <?php endif; ?>
    </form>

    
    <div>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-5">Berita Terbaru</h2>

        <?php if($beritas->isEmpty()): ?>
            <div class="text-center py-16 text-slate-400 text-sm">
                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Belum ada berita ditemukan.
            </div>
        <?php else: ?>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                <?php $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('website.berita.show', $item->slug)); ?>"
                   class="group flex flex-col bg-white dark:bg-slate-800 rounded-xl shadow-sm
                          border border-slate-200 dark:border-slate-700 overflow-hidden
                          transition-all hover:shadow-md hover:-translate-y-1">

                    
                    <div class="relative h-40 bg-slate-100 dark:bg-slate-700 overflow-hidden">

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
                                <div class="w-10 h-10 rounded-full bg-black/50 flex items-center justify-center group-hover:bg-black/70 transition">
                                    <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
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
                                <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center group-hover:bg-red-700 transition">
                                    <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
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
                                    <svg class="w-10 h-10 text-white/40" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center group-hover:bg-blue-700 transition">
                                    <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>

                        <?php else: ?>
                            
                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6M5 8h14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($item->has_media && $item->media_tipe !== 'none'): ?>
                        <div class="absolute top-1.5 left-1.5">
                            <?php if($item->media_tipe === 'link_youtube'): ?>
                                <span class="px-1.5 py-0.5 text-xs bg-red-600 text-white rounded">▶ YT</span>
                            <?php elseif($item->media_tipe === 'link_facebook'): ?>
                                <span class="px-1.5 py-0.5 text-xs bg-blue-600 text-white rounded">📘</span>
                            <?php elseif($item->media_tipe === 'video'): ?>
                                <span class="px-1.5 py-0.5 text-xs bg-black/60 text-white rounded">🎥</span>
                            <?php elseif($item->media_tipe === 'photo'): ?>
                                <span class="px-1.5 py-0.5 text-xs bg-black/60 text-white rounded">🖼️</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs text-slate-400 dark:text-slate-500">
                                <?php echo e($item->tanggal_publish?->format('d M Y')); ?>

                            </span>
                            <?php if($item->kategori === 'pengumuman'): ?>
                                <span class="px-1.5 py-0.5 text-xs rounded bg-red-100 text-red-600">Pengumuman</span>
                            <?php else: ?>
                                <span class="px-1.5 py-0.5 text-xs rounded bg-blue-100 text-blue-600">Berita</span>
                            <?php endif; ?>
                        </div>

                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-2 text-sm line-clamp-2 group-hover:text-indigo-600 transition-colors">
                            <?php echo e($item->judul); ?>

                        </h3>

                        <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 flex-1 line-clamp-3">
                            <?php echo e($item->ringkasan); ?>

                        </p>

                        <span class="inline-flex items-center text-xs font-medium text-indigo-600 dark:text-indigo-400 mt-auto">
                            Baca selengkapnya
                            <svg class="ml-1 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($beritas->hasPages()): ?>
                <div class="mt-8"><?php echo e($beritas->links()); ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/website/berita.blade.php ENDPATH**/ ?>
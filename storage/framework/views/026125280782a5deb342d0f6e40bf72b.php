<?php $__env->startSection('title', 'Galeri Kegiatan'); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 py-10 lg:py-12">

    
    <header class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl lg:text-3xl font-semibold !text-black">
                Galeri Kegiatan SMPN Kutime
            </h1>
            <p class="mt-1.5 text-sm text-slate-600 dark:text-slate-400">
                Dokumentasi foto, video, dan kegiatan sekolah.
            </p>
        </div>
        <p class="text-xs text-slate-400 shrink-0"><?php echo e($galeris->total()); ?> media</p>
    </header>

    
    <form method="GET" action="<?php echo e(route('website.galeri')); ?>" class="flex flex-wrap gap-2 mb-8">
        <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" placeholder="Cari media..."
               class="rounded-lg border-slate-300 text-sm py-1.5 px-3 w-44 focus:border-indigo-500 focus:ring-indigo-500">

        <select name="kategori" class="rounded-lg border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Kategori</option>
            <?php $__currentLoopData = $kategoriOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php if(request('kategori') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="tipe" class="rounded-lg border-slate-300 text-sm py-1.5 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Semua Tipe</option>
            <option value="photo"         <?php if(request('tipe') === 'photo'): echo 'selected'; endif; ?>>🖼️ Foto</option>
            <option value="video"         <?php if(request('tipe') === 'video'): echo 'selected'; endif; ?>>🎥 Video</option>
            <option value="link_youtube"  <?php if(request('tipe') === 'link_youtube'): echo 'selected'; endif; ?>>▶️ YouTube</option>
            <option value="link_facebook" <?php if(request('tipe') === 'link_facebook'): echo 'selected'; endif; ?>>📘 Facebook</option>
        </select>

        <button type="submit"
                class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
            Cari
        </button>

        <?php if(request()->hasAny(['cari','kategori','tipe'])): ?>
            <a href="<?php echo e(route('website.galeri')); ?>"
               class="px-4 py-1.5 bg-slate-100 text-slate-600 text-sm rounded-lg hover:bg-slate-200 transition">
                Reset
            </a>
        <?php endif; ?>
    </form>

    
    <?php if($galeris->isEmpty()): ?>
        <div class="text-center py-20 text-slate-400 text-sm">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Belum ada media tersedia.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php $__currentLoopData = $galeris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('website.galeri.show', $item)); ?>"
               class="group relative block bg-white dark:bg-slate-800 rounded-xl shadow-sm
                      border border-slate-200 dark:border-slate-700 overflow-hidden
                      transition-all duration-300 hover:shadow-md hover:-translate-y-1">

                
                <div class="aspect-[4/3] overflow-hidden bg-slate-100 dark:bg-slate-700 relative">
                    <img src="<?php echo e($item->thumbnail_url); ?>"
                         alt="<?php echo e($item->judul); ?>"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         loading="lazy">

                    
                    <div class="absolute top-1.5 left-1.5">
                        <span class="px-1.5 py-0.5 text-xs rounded bg-black/60 text-white backdrop-blur-sm">
                            <?php echo e($item->tipe_label); ?>

                        </span>
                    </div>

                    
                    <?php if($item->is_video): ?>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-10 h-10 rounded-full bg-black/50 flex items-center justify-center
                                    group-hover:bg-black/70 transition backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/10 transition-colors duration-300"></div>
                </div>

                
                <div class="p-3">
                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                        <?php echo e($item->judul); ?>

                    </p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-slate-400 capitalize"><?php echo e($item->kategori); ?></span>
                        <span class="text-xs text-slate-400"><?php echo e($item->created_at->format('d M Y')); ?></span>
                    </div>
                    <?php if($item->deskripsi): ?>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                            <?php echo e($item->deskripsi); ?>

                        </p>
                    <?php endif; ?>
                </div>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($galeris->hasPages()): ?>
            <div class="mt-10"><?php echo e($galeris->links()); ?></div>
        <?php endif; ?>
    <?php endif; ?>

</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/website/galeri.blade.php ENDPATH**/ ?>
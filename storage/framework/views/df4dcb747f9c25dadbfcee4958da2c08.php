<?php $__env->startSection('title', 'Kelola Website'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-4xl mx-auto px-4 py-4">

    <div
        x-data="{ tab: '<?php echo e(request('tab','home')); ?>' }"
        class="space-y-4"
    >

        
        <div>
            <h1 class="text-base font-semibold text-slate-900">
                Kelola Website Resmi Sekolah
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Atur tampilan halaman publik SMPN Kutime: Home, Berita, dan Galeri.
            </p>
        </div>


        
        <div class="inline-flex rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-medium text-slate-600 shadow-sm">

            
            <button
                type="button"
                @click="tab = 'home'"
                :class="tab === 'home'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-600 hover:bg-slate-50'"
                class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Beranda (Hero)</span>
            </button>

            
            <button
                type="button"
                @click="tab = 'identitas'"
                :class="tab === 'identitas'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-600 hover:bg-slate-50'"
                class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Identitas</span>
            </button>

            
            <button
                type="button"
                @click="tab = 'berita'"
                :class="tab === 'berita'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-600 hover:bg-slate-50'"
                class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span>Berita</span>
            </button>

            
            <button
                type="button"
                @click="tab = 'galeri'"
                :class="tab === 'galeri'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-600 hover:bg-slate-50'"
                class="px-3 py-1.5 rounded-md flex items-center gap-1.5 transition-colors"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Galeri</span>
            </button>

        </div>


        
        <div class="mt-2">
            
            
            <div x-show="tab === 'home'" x-cloak>
                <?php echo $__env->make('admin.kelola-website.tabs.home', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <div x-show="tab === 'identitas'" x-cloak>
                <?php echo $__env->make('admin.kelola-website.tabs.identitas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <div x-show="tab === 'berita'" x-cloak>
                <?php echo $__env->make('admin.kelola-website.tabs.berita', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            
            <div x-show="tab === 'galeri'" x-cloak>
                <?php echo $__env->make('admin.kelola-website.tabs.galeri', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/kelola-website/index.blade.php ENDPATH**/ ?>
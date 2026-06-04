
<?php
    $namaHariIni = \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y');
    $timeNow     = now()->format('H:i');
    $jadwal      = collect($jadwalHariIni);

    // Ambil daftar guru_id yang hadir hari ini dari tabel guru_absensis
    try {
        $guruHadirIds = \Illuminate\Support\Facades\DB::table('guru_absensis')
            ->where('tanggal', today())
            ->whereIn('status', ['P', 'L'])
            ->pluck('status', 'guru_id')
            ->toArray();
    } catch (\Throwable $e) {
        $guruHadirIds = [];
    }

    // Helper closure: hitung status sesi berdasarkan jam sekarang
    $statusSesi = function($start, $end) use ($timeNow) {
        $s = substr($start ?? '', 0, 5);
        $e = substr($end   ?? '', 0, 5);
        if (!$s || !$e) return 'akan';
        if ($timeNow >= $s && $timeNow <= $e) return 'sekarang';
        if ($timeNow > $e) return 'selesai';
        return 'akan';
    };
?>

<div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-[520px]">
    
    
    <div class="p-5 border-b border-slate-50 dark:border-slate-700/50 shrink-0">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Jadwal Hari Ini
            </h3>
            <span class="text-[10px] font-medium text-slate-400 bg-slate-50 dark:bg-slate-900 px-2 py-0.5 rounded-md">
                <?php echo e($jadwal->count()); ?> Sesi
            </span>
        </div>
        <p class="text-[10px] text-slate-400 font-medium"><?php echo e($namaHariIni); ?></p>
    </div>

    
    <div class="flex-1 overflow-y-auto p-2 space-y-2 bg-slate-50/50 dark:bg-slate-900/20">
        <?php $__empty_1 = true; $__currentLoopData = $jadwal->sortBy('start_time'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php 
                $status = $statusSesi($j->start_time, $j->end_time); 
                $isHadir = isset($guruHadirIds[$j->guru_id]);
            ?>
            
            <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl border transition-all duration-200
                        <?php echo e($status === 'sekarang' 
                            ? 'border-amber-400 shadow-md ring-1 ring-amber-400/20 bg-amber-50/10' 
                            : 'border-slate-100 dark:border-slate-700 hover:shadow-sm'); ?>

                        <?php echo e($status === 'selesai' ? 'opacity-60' : ''); ?> flex items-start gap-3">
                
                
                <div class="w-1.5 h-11 rounded-full shrink-0 mt-0.5" 
                     style="background-color: <?php echo e($j->mapel_color ?? '#6366f1'); ?>;">
                </div>

                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">
                            <?php echo e($j->mapel_nama); ?>

                        </h4>
                        
                        
                        <?php if($status === 'sekarang'): ?>
                            <span class="text-[8px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded bg-amber-500 text-white animate-pulse shrink-0">
                                Berlangsung
                            </span>
                        <?php elseif($status === 'selesai'): ?>
                            <span class="text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 shrink-0">
                                Selesai
                            </span>
                        <?php else: ?>
                            <span class="text-[8px] font-semibold px-1.5 py-0.5 rounded bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 shrink-0">
                                Nanti
                            </span>
                        <?php endif; ?>
                    </div>

                    
                    <div class="flex items-center gap-2 text-[10px] font-medium text-slate-500 dark:text-slate-400 mb-1">
                        <span class="text-slate-700 dark:text-slate-300 font-bold">
                            <?php echo e(substr($j->start_time, 0, 5)); ?> - <?php echo e(substr($j->end_time, 0, 5)); ?>

                        </span>
                        <span>•</span>
                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold bg-indigo-50/60 dark:bg-indigo-950/30 px-1.5 py-0.2 rounded">
                            Kelas <?php echo e($j->kelas_nama); ?>

                        </span>
                        <?php if(!empty($j->room)): ?>
                            <span>•</span>
                            <span class="truncate"><i class="bi bi-geo-alt me-0.5"></i><?php echo e($j->room); ?></span>
                        <?php endif; ?>
                    </div>

                    
                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-50 dark:border-slate-700/50">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <?php if(!empty($j->guru_foto)): ?>
                                <img src="<?php echo e(asset('storage/' . $j->guru_foto)); ?>" class="w-4 h-4 rounded-full object-cover shrink-0" alt="">
                            <?php else: ?>
                                <div class="w-4 h-4 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 flex items-center justify-center font-bold text-[8px] shrink-0">
                                    <?php echo e(strtoupper(substr($j->guru_nama ?? 'G', 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                            <span class="text-[10px] text-slate-600 dark:text-slate-300 truncate font-medium">
                                <?php echo e($j->guru_nama); ?>

                            </span>
                        </div>

                        
                        <div class="shrink-0 flex items-center">
                            <?php if($isHadir): ?>
                                <span class="w-2 h-2 rounded-full bg-emerald-500" title="Guru sudah absen hadir hari ini"></span>
                            <?php else: ?>
                                <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600" title="Guru belum melakukan absensi"></span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="flex flex-col items-center justify-center h-full py-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-2">
                    <i class="bi bi-calendar-x text-lg"></i>
                </div>
                <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Tidak Ada Jadwal</p>
                <p class="text-[10px] text-slate-400 max-w-[180px] mt-0.5">Sesi mengajar untuk hari ini belum diatur atau kosong.</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="p-3.5 border-t border-slate-50 dark:border-slate-700/50 bg-slate-50/30 dark:bg-slate-900/10 flex items-center justify-between gap-3 shrink-0 flex-wrap">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[9px] text-slate-400">Kehadiran guru hari ini:</span>
            <?php
                $totalGuruDijadwal = $jadwal->unique('guru_id')->count();
                $guruSudahHadir    = $jadwal->unique('guru_id')
                    ->filter(fn($j) => isset($guruHadirIds[$j->guru_id]))
                    ->count();
                $guruBelumHadir    = $totalGuruDijadwal - $guruSudahHadir;
            ?>
            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                ✓ <?php echo e($guruSudahHadir); ?> hadir
            </span>
            <?php if($guruBelumHadir > 0): ?>
                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400">
                    · <?php echo e($guruBelumHadir); ?> belum absen
                </span>
            <?php endif; ?>
            <span class="text-[9px] text-slate-400">dari <?php echo e($totalGuruDijadwal); ?> guru dijadwal</span>
        </div>

        <?php if(Route::has('admin.academic-planner.index')): ?>
            <a href="<?php echo e(route('admin.academic-planner.index')); ?>"
               class="flex items-center gap-1 text-[10px] font-semibold
                      text-amber-600 hover:text-amber-700 transition-colors">
                Kelola semua jadwal
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        <?php endif; ?>
    </div>

</div><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/dashboard/schedule.blade.php ENDPATH**/ ?>
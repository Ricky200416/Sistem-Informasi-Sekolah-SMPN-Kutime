

<?php
    $bulanList = [
        1  => 'Januari',   2 => 'Februari', 3  => 'Maret',     4  => 'April',
        5  => 'Mei',       6 => 'Juni',     7  => 'Juli',       8  => 'Agustus',
        9  => 'September', 10=> 'Oktober',  11 => 'November',   12 => 'Desember',
    ];

    $bulanAktif = $rekapBulan ?? date('n');
    $tahunAktif = $rekapTahun ?? date('Y');
    $siswaRekap = $siswaRekapDashboard  ?? collect();
    $rekapDash  = $rekapDataDashboard   ?? [];
    $kelasWali  = $kelasWaliData        ?? null;

    // Nama kelas — defensive
    $namaKelasWali = $kelasWali?->nama ?? $kelasWali?->name ?? '—';

    // Hitung total keseluruhan
    $totH   = array_sum(array_column($rekapDash, 'hadir'));
    $totS   = array_sum(array_column($rekapDash, 'sakit'));
    $totI   = array_sum(array_column($rekapDash, 'izin'));
    $totA   = array_sum(array_column($rekapDash, 'alpha'));
    $totAll = $totH + $totS + $totI + $totA;

    // Rata-rata kehadiran
    $avgHadir = 0;
    if ($siswaRekap->count() > 0 && count($rekapDash) > 0) {
        $avgHadir = collect($rekapDash)->map(function ($r) {
            $tot = ($r['hadir'] + $r['sakit'] + $r['izin'] + $r['alpha']);
            return $tot > 0 ? round($r['hadir'] / $tot * 100) : 0;
        })->avg();
    }

    // Siswa dengan kehadiran terendah (max 5)
    $siswaRendah = $siswaRekap->map(function ($s) use ($rekapDash) {
        $r = $rekapDash[$s->id] ?? ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
        $tot = array_sum($r);
        $pct = $tot > 0 ? round($r['hadir'] / $tot * 100) : 0;
        return (object) [
            'id'    => $s->id,
            'nama'  => $s->nama ?? $s->name ?? '—',
            'pct'   => $pct,
            'hadir' => $r['hadir'],
            'alpha' => $r['alpha'],
            'total' => $tot,
        ];
    })->sortBy('pct')->take(5);

    // Bangun param untuk route rekap
    $kelasWaliId = $kelasWali?->id ?? '';
    $rekapParams = array_filter([
        'kelas_id' => $kelasWaliId,
        'bulan'    => $bulanAktif,
        'tahun'    => $tahunAktif,
    ]);
?>

<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    
    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-base">
                📋
            </div>
            <div>
                <p class="font-semibold text-slate-800 dark:text-slate-100" style="font-size:.8rem;">
                    Rekap Absensi Kelas
                </p>
                <p class="text-slate-400" style="font-size:.6rem;">
                    <?php echo e($namaKelasWali); ?>

                    &nbsp;·&nbsp;
                    <?php echo e($bulanList[$bulanAktif] ?? $bulanAktif); ?> <?php echo e($tahunAktif); ?>

                </p>
            </div>
        </div>
        <?php if(Route::has('guru.absensi-siswa.rekap') && $kelasWaliId): ?>
            <a href="<?php echo e(route('guru.absensi-siswa.rekap', $rekapParams)); ?>"
               class="shrink-0"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;
                      background:#eef2ff;border:1px solid #c7d2fe;border-radius:6px;padding:4px 10px;
                      display:inline-flex;align-items:center;gap:4px;">
                Lihat Lengkap →
            </a>
        <?php endif; ?>
    </div>

    
    <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/20">
        <form method="GET" action="<?php echo e(Route::has('guru.dashboard') ? route('guru.dashboard') : '#'); ?>"
              class="flex items-center gap-2 flex-wrap">
            <?php if($kelasWaliId): ?>
                <input type="hidden" name="kelas_id" value="<?php echo e($kelasWaliId); ?>">
            <?php endif; ?>
            <label style="font-size:.6rem;font-weight:600;color:#64748b;">Bulan:</label>
            <select name="rekap_bulan" onchange="this.form.submit()"
                    style="font-size:.65rem;padding:4px 8px;border:1px solid #e2e8f0;border-radius:6px;
                           background:#fff;color:#374151;outline:none;cursor:pointer;">
                <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $nm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($n); ?>" <?php echo e((int)$bulanAktif === $n ? 'selected' : ''); ?>>
                        <?php echo e($nm); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="rekap_tahun" onchange="this.form.submit()"
                    style="font-size:.65rem;padding:4px 8px;border:1px solid #e2e8f0;border-radius:6px;
                           background:#fff;color:#374151;outline:none;cursor:pointer;">
                <?php $__currentLoopData = range(date('Y'), date('Y') - 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e((int)$tahunAktif === $t ? 'selected' : ''); ?>>
                        <?php echo e($t); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
    </div>

    
    <div class="grid grid-cols-4 gap-0 border-b border-slate-100 dark:border-slate-700">
        <?php
            $totStats = [
                ['label' => 'Hadir', 'val' => $totH, 'color' => '#059669'],
                ['label' => 'Sakit', 'val' => $totS, 'color' => '#a16207'],
                ['label' => 'Izin',  'val' => $totI, 'color' => '#0369a1'],
                ['label' => 'Alpha', 'val' => $totA, 'color' => '#b91c1c'],
            ];
        ?>
        <?php $__currentLoopData = $totStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $ts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="py-3 text-center <?php echo e($idx < 3 ? 'border-r border-slate-100 dark:border-slate-700' : ''); ?>">
            <p class="font-bold" style="font-size:1.1rem;color:<?php echo e($ts['color']); ?>;">
                <?php echo e($ts['val']); ?>

            </p>
            <p style="font-size:.55rem;font-weight:600;color:<?php echo e($ts['color']); ?>;opacity:.8;text-transform:uppercase;">
                <?php echo e($ts['label']); ?>

            </p>
            <?php if($totAll > 0): ?>
                <p style="font-size:.48rem;color:<?php echo e($ts['color']); ?>;opacity:.5;">
                    <?php echo e(round($ts['val'] / $totAll * 100)); ?>%
                </p>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between mb-1.5">
            <p style="font-size:.65rem;font-weight:600;color:#475569;">Rata-rata Kehadiran Kelas</p>
            <?php
                $avgColor = $avgHadir >= 80 ? '#059669' : ($avgHadir >= 60 ? '#a16207' : '#b91c1c');
            ?>
            <p style="font-size:.75rem;font-weight:800;color:<?php echo e($avgColor); ?>;">
                <?php echo e(round($avgHadir)); ?>%
            </p>
        </div>
        <div style="height:6px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
            <div style="height:100%;width:<?php echo e(round($avgHadir)); ?>%;background:<?php echo e($avgColor); ?>;
                         border-radius:99px;transition:width .4s;"></div>
        </div>
        <?php if($totAll === 0): ?>
            <p style="font-size:.58rem;color:#94a3b8;margin-top:6px;text-align:center;">
                Belum ada data absensi pada periode ini
            </p>
        <?php endif; ?>
    </div>

    
    <?php if($siswaRendah->isNotEmpty()): ?>
    <div class="px-4 pt-2 pb-1">
        <p style="font-size:.6rem;font-weight:700;color:#64748b;text-transform:uppercase;
                  letter-spacing:.05em;margin-bottom:4px;">
            ⚠️ Kehadiran Terendah
        </p>
    </div>
    <div class="overflow-x-auto">
        <table style="width:100%;border-collapse:collapse;font-size:.7rem;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:5px 14px;text-align:left;font-size:.58rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;border-bottom:1px solid #f1f5f9;">
                        Nama Siswa
                    </th>
                    <th style="padding:5px 10px;text-align:center;font-size:.58rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;border-bottom:1px solid #f1f5f9;">
                        Hadir
                    </th>
                    <th style="padding:5px 10px;text-align:center;font-size:.58rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;border-bottom:1px solid #f1f5f9;">
                        Alpha
                    </th>
                    <th style="padding:5px 14px;text-align:center;font-size:.58rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;border-bottom:1px solid #f1f5f9;">
                        % Hadir
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $siswaRendah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $srColor = $sr->pct >= 80 ? '#059669' : ($sr->pct >= 60 ? '#a16207' : '#b91c1c');
                ?>
                <tr style="border-bottom:1px solid #f8fafc;"
                    onmouseover="this.style.background='#fafafa'"
                    onmouseout="this.style.background='transparent'">
                    <td style="padding:7px 14px;font-weight:600;color:#1e293b;">
                        <?php echo e($sr->nama); ?>

                        <?php if($sr->pct < 75): ?>
                            <span style="display:inline-block;margin-left:4px;padding:1px 6px;
                                         background:#fee2e2;color:#b91c1c;border-radius:4px;
                                         font-size:.5rem;font-weight:700;">RENDAH</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:7px 10px;text-align:center;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;
                                     min-width:28px;height:20px;border-radius:5px;font-size:.62rem;
                                     font-weight:700;background:#ecfdf5;color:#059669;padding:0 6px;">
                            <?php echo e($sr->hadir); ?>

                        </span>
                    </td>
                    <td style="padding:7px 10px;text-align:center;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;
                                     min-width:28px;height:20px;border-radius:5px;font-size:.62rem;
                                     font-weight:700;background:#fee2e2;color:#b91c1c;padding:0 6px;">
                            <?php echo e($sr->alpha); ?>

                        </span>
                    </td>
                    <td style="padding:7px 14px;text-align:center;">
                        <div style="display:flex;align-items:center;gap:5px;justify-content:center;">
                            <div style="width:50px;height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                                <div style="height:100%;width:<?php echo e($sr->pct); ?>%;background:<?php echo e($srColor); ?>;border-radius:99px;"></div>
                            </div>
                            <span style="font-size:.65rem;font-weight:700;color:<?php echo e($srColor); ?>;min-width:28px;">
                                <?php echo e($sr->pct); ?>%
                            </span>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="px-4 py-2 flex items-center justify-between">
        <p style="font-size:.58rem;color:#94a3b8;">
            Menampilkan <?php echo e($siswaRendah->count()); ?> dari <?php echo e($siswaRekap->count()); ?> siswa
        </p>
        <?php if(Route::has('guru.absensi-siswa.rekap') && $kelasWaliId): ?>
            <a href="<?php echo e(route('guru.absensi-siswa.rekap', $rekapParams)); ?>"
               style="font-size:.6rem;font-weight:700;color:#4f46e5;text-decoration:none;">
                Lihat semua siswa →
            </a>
        <?php endif; ?>
    </div>

    <?php else: ?>
    
    <div style="padding:28px;text-align:center;">
        <?php if($siswaRekap->isEmpty()): ?>
            <div style="font-size:1.5rem;margin-bottom:8px;">📂</div>
            <p style="font-size:.72rem;font-weight:600;color:#475569;margin:0 0 4px;">
                Belum ada data siswa
            </p>
            <p style="font-size:.62rem;color:#94a3b8;margin:0;">
                Siswa belum terdaftar pada kelas ini.
            </p>
        <?php else: ?>
            <div style="font-size:1.5rem;margin-bottom:8px;">🎉</div>
            <p style="font-size:.72rem;font-weight:600;color:#059669;margin:0 0 4px;">
                Semua siswa hadir dengan baik!
            </p>
            <p style="font-size:.62rem;color:#94a3b8;margin:0;">
                Tidak ada siswa dengan kehadiran di bawah 75% pada periode ini.
            </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/guru/dashboard/rekap-absensi.blade.php ENDPATH**/ ?>
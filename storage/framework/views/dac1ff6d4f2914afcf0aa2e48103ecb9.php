
<?php if(isset($siswaBerisiko) && $siswaBerisiko->isNotEmpty()): ?>
<div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:10px 14px;border-bottom:1px solid #f1f5f9;">
        <div style="display:flex;align-items:center;gap:7px;">
            <div style="width:28px;height:28px;border-radius:8px;background:#fef2f2;
                        display:flex;align-items:center;justify-content:center;font-size:.85rem;">
                ⚠️
            </div>
            <div>
                <p style="font-size:.75rem;font-weight:700;color:#1e293b;margin:0;line-height:1.2;">
                    Siswa Perlu Perhatian
                </p>
                <p style="font-size:.58rem;color:#94a3b8;margin:0;">
                    Kehadiran &lt; 75% bulan <?php echo e(now()->isoFormat('MMMM')); ?>

                </p>
            </div>
        </div>
        <span style="font-size:.58rem;font-weight:700;background:#fee2e2;color:#b91c1c;
                     border:1px solid #fecaca;border-radius:99px;padding:2px 8px;">
            <?php echo e($siswaBerisiko->count()); ?> siswa
        </span>
    </div>

    
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:420px;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:7px 14px;text-align:left;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.04em;
                               border-bottom:1.5px solid #e2e8f0;">
                        Nama Siswa
                    </th>
                    <th style="padding:7px 10px;text-align:center;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.04em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:80px;">
                        Kelas
                    </th>
                    <th style="padding:7px 10px;text-align:center;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.04em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:70px;">
                        Alpha
                    </th>
                    <th style="padding:7px 14px;text-align:center;font-size:.575rem;font-weight:700;
                               color:#64748b;text-transform:uppercase;letter-spacing:.04em;
                               border-bottom:1.5px solid #e2e8f0;white-space:nowrap;width:130px;">
                        % Hadir
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $siswaBerisiko; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $kehadiran  = $siswa->kehadiran  ?? 0;
                    $alpha      = $siswa->alpha      ?? 0;
                    // Nama kelas — controller menyimpan di namaKelas
                    $namaKelas  = $siswa->namaKelas  ?? $siswa->kelas_nama ?? '—';
                    if (is_object($namaKelas)) {
                        $namaKelas = $namaKelas->nama ?? $namaKelas->name ?? '—';
                    }
                    $namaSiswa  = $siswa->nama        ?? $siswa->name ?? '—';
                    $hadirColor = $kehadiran >= 60 ? '#a16207' : '#b91c1c';
                    $rowBg      = $idx % 2 === 0 ? '#fff' : '#fafbfd';
                ?>
                <tr style="background:<?php echo e($rowBg); ?>;border-bottom:1px solid #f1f5f9;"
                    onmouseover="this.style.background='#eef2ff'"
                    onmouseout="this.style.background='<?php echo e($rowBg); ?>'">

                    
                    <td style="padding:8px 14px;">
                        <div style="display:flex;align-items:center;gap:7px;">
                            <div style="width:28px;height:28px;border-radius:8px;flex-shrink:0;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.58rem;font-weight:800;color:#fff;
                                         background:<?php echo e($hadirColor); ?>;">
                                <?php echo e(strtoupper(substr($namaSiswa, 0, 2))); ?>

                            </div>
                            <p style="font-size:.7rem;font-weight:700;color:#1e293b;margin:0;
                                       white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                                       max-width:160px;">
                                <?php echo e($namaSiswa); ?>

                            </p>
                        </div>
                    </td>

                    
                    <td style="padding:8px 10px;text-align:center;">
                        <span style="font-size:.62rem;color:#475569;font-weight:600;">
                            <?php echo e($namaKelas); ?>

                        </span>
                    </td>

                    
                    <td style="padding:8px 10px;text-align:center;">
                        <?php if($alpha > 0): ?>
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                          min-width:24px;height:20px;border-radius:5px;font-size:.62rem;
                                          font-weight:700;background:#fee2e2;color:#b91c1c;padding:0 6px;">
                                <?php echo e($alpha); ?>x
                            </span>
                        <?php else: ?>
                            <span style="font-size:.65rem;color:#cbd5e1;">—</span>
                        <?php endif; ?>
                    </td>

                    
                    <td style="padding:8px 14px;text-align:center;">
                        <div style="display:flex;align-items:center;gap:5px;justify-content:center;">
                            <div style="width:55px;height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;flex-shrink:0;">
                                <div style="height:100%;width:<?php echo e(min($kehadiran, 100)); ?>%;
                                             background:<?php echo e($hadirColor); ?>;border-radius:99px;"></div>
                            </div>
                            <span style="font-size:.65rem;font-weight:700;
                                          color:<?php echo e($hadirColor); ?>;min-width:32px;text-align:left;">
                                <?php echo e($kehadiran); ?>%
                            </span>
                        </div>
                    </td>

                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    
    <?php if($siswaBerisiko->count() >= 5): ?>
    <div style="padding:7px 14px;border-top:1px solid #f1f5f9;background:#fef2f2;text-align:right;">
        <?php if(Route::has('guru.absensi-siswa.rekap')): ?>
            <a href="<?php echo e(route('guru.absensi-siswa.rekap')); ?>"
               style="font-size:.6rem;font-weight:700;color:#b91c1c;text-decoration:none;">
                Lihat rekap absensi lengkap →
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>
<?php endif; ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/guru/dashboard/at-risk-students.blade.php ENDPATH**/ ?>
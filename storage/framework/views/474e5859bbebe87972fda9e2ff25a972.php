<?php $__env->startSection('title', 'Rekap Absensi Siswa'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; }
body { background: #f1f5fb; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; }

.rk-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px; }
.rk-title { font-size:16px;font-weight:800;color:#0f172a;display:flex;align-items:center;gap:8px;margin:0; }
.rk-title i { color:#6366f1; }
.rk-back { display:inline-flex;align-items:center;gap:5px;background:#eef2ff;border:1px solid #c7d2fe;border-radius:7px;padding:5px 12px;font-size:11px;font-weight:700;color:#4338ca;text-decoration:none; }
.rk-back:hover { background:#e0e7ff; }

.rk-info-strip {
    background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;
    padding:8px 12px;margin-bottom:12px;font-size:11.5px;color:#1e40af;
    display:flex;align-items:center;gap:7px;
}
.rk-info-strip i { color:#3b82f6;flex-shrink:0; }

.rk-warn-strip {
    background:#fffbeb;border:1px solid #fde68a;border-radius:8px;
    padding:8px 12px;margin-bottom:12px;font-size:11.5px;color:#92400e;
    display:flex;align-items:center;gap:7px;
}
.rk-warn-strip i { color:#d97706;flex-shrink:0; }

.rk-filter {
    background:#fff;border:1px solid #e2e8f0;border-radius:12px;
    padding:14px 16px;margin-bottom:14px;
    display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap;
}
.rk-fg { display:flex;flex-direction:column;gap:4px; }
.rk-fg label { font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em; }
.rk-fg select,
.rk-fg input[type="text"] {
    border:1.5px solid #e2e8f0;border-radius:8px;padding:7px 10px;font-size:12px;
    font-family:inherit;color:#1e293b;background:#f8fafc;outline:none;min-width:140px;
}
.rk-fg select:focus,
.rk-fg input:focus { border-color:#6366f1;background:#fff; }
.rk-btn { background:#6366f1;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:12px;font-weight:700;font-family:inherit;cursor:pointer;display:flex;align-items:center;gap:6px; }
.rk-btn:hover { background:#4f46e5; }

.rk-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.05); }
.rk-table-scroll { overflow-x:auto; }
.rk-table { border-collapse:separate;border-spacing:0;width:100%;font-size:11.5px; }
.rk-table thead th {
    background:#f8fafc;color:#64748b;font-size:10px;font-weight:700;
    text-transform:uppercase;letter-spacing:.04em;padding:8px 10px;
    border-bottom:1.5px solid #e2e8f0;white-space:nowrap;text-align:center;
}
.rk-table thead th:first-child { text-align:left;padding-left:14px;position:sticky;left:0;background:#f8fafc;z-index:2;min-width:220px; }
.rk-table tbody td { padding:9px 10px;border-bottom:1px solid #f1f5f9;text-align:center;vertical-align:middle; }
.rk-table tbody td:first-child { text-align:left;padding-left:14px;position:sticky;left:0;background:inherit;z-index:1; }
.rk-table tbody tr:nth-child(even) td { background:#fafbfd; }
.rk-table tbody tr:nth-child(odd)  td { background:#fff; }
.rk-table tbody tr:hover td { background:#eef2ff !important; }
.rk-table tbody tr:nth-child(even) td:first-child { background:#fafbfd; }
.rk-table tbody tr:nth-child(odd)  td:first-child { background:#fff; }
.rk-table tbody tr:hover td:first-child { background:#eef2ff !important; }

.chip { display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:24px;border-radius:6px;font-size:10.5px;font-weight:700;padding:0 8px; }
.chip-h { background:#dcfce7;color:#15803d; }
.chip-s { background:#fef9c3;color:#a16207; }
.chip-i { background:#e0f2fe;color:#0369a1; }
.chip-a { background:#fee2e2;color:#b91c1c; }
.chip-total { background:#ede9fe;color:#7c3aed; }

.rk-empty { padding:48px;text-align:center;color:#94a3b8; }
.rk-empty i { font-size:36px;display:block;margin-bottom:10px;color:#cbd5e1; }
.rk-empty p { font-size:12px;margin:0; }

.guru-info-row { padding:8px 14px;background:#fafbff;border-bottom:1px solid #f1f5f9;font-size:11.5px;color:#64748b; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $kelasList     = $kelasList     ?? collect();
    $kelasId       = $kelasId       ?? null;
    $bulan         = $bulan         ?? date('n');
    $tahun         = $tahun         ?? date('Y');
    $bulanList     = $bulanList     ?? [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $tahunList     = range(date('Y'), date('Y')-3);
    $siswaList     = $siswaList     ?? collect();
    $rekapData     = $rekapData     ?? [];
    $mataPelajaran = $mataPelajaran ?? '';
    $mapelList     = $mapelList     ?? collect();
    $daftarMapel   = $daftarMapel   ?? collect(); // ← BARU: semua mapel untuk dropdown
    $selKelas      = $kelasList->firstWhere('id', $kelasId);
?>

<div class="container-fluid px-0">

<div class="rk-header">
    <h5 class="rk-title"><i class="bi bi-bar-chart-line-fill"></i> Rekap Absensi Siswa</h5>
    <a href="<?php echo e(route('guru.absensi-siswa.index')); ?>" class="rk-back">
        <i class="bi bi-arrow-left"></i> Kembali Absensi
    </a>
</div>

<div class="rk-info-strip">
    <i class="bi bi-info-circle-fill"></i>
    <span>
        Rekap ini hanya menampilkan absensi yang <strong>Anda input sendiri</strong>.
        Gunakan filter Mata Pelajaran (dropdown) untuk melihat rekap per mapel yang Anda ajar.
        Wali kelas dapat melihat rekap gabungan semua guru melalui menu <strong>Rekap Semua Mapel</strong> di halaman Wali Kelas.
    </span>
</div>

<?php if($daftarMapel->isEmpty()): ?>
    <div class="rk-warn-strip">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>Belum ada data master Mata Pelajaran. Hubungi admin untuk melengkapi data mata pelajaran.</span>
    </div>
<?php endif; ?>

<form method="GET" action="<?php echo e(route('guru.absensi-siswa.rekap')); ?>" class="rk-filter">
    <div class="rk-fg">
        <label>Kelas</label>
        <select name="kelas_id">
            <option value="">— Pilih Kelas —</option>
            <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k->id); ?>" <?php echo e($kelasId == $k->id ? 'selected':''); ?>><?php echo e($k->nama); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="rk-fg">
        <label>Mata Pelajaran</label>
        
        <select name="mata_pelajaran">
            <option value="">— Semua Mapel Anda —</option>
            <?php $__currentLoopData = $daftarMapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($mp); ?>" <?php echo e($mataPelajaran === $mp ? 'selected' : ''); ?>>
                    <?php echo e($mp); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="rk-fg">
        <label>Bulan</label>
        <select name="bulan">
            <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $nm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($n); ?>" <?php echo e($bulan==$n?'selected':''); ?>><?php echo e($nm); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="rk-fg">
        <label>Tahun</label>
        <select name="tahun">
            <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t); ?>" <?php echo e($tahun==$t?'selected':''); ?>><?php echo e($t); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <button type="submit" class="rk-btn"><i class="bi bi-funnel-fill"></i> Tampilkan</button>
</form>

<div class="rk-card">
    <?php if($selKelas): ?>
        <div class="guru-info-row">
            <i class="bi bi-mortarboard-fill me-1" style="color:#6366f1;"></i>
            <strong><?php echo e($selKelas->nama); ?></strong> &nbsp;·&nbsp;
            <?php echo e($bulanList[$bulan]); ?> <?php echo e($tahun); ?> &nbsp;·&nbsp;
            <?php echo e($siswaList->count()); ?> siswa
            <?php if($mataPelajaran): ?>
                &nbsp;·&nbsp; Mapel: <strong><?php echo e($mataPelajaran); ?></strong>
            <?php else: ?>
                &nbsp;·&nbsp; <span style="color:#94a3b8;">Semua mapel yang Anda ajar</span>
            <?php endif; ?>
            <span style="margin-left:8px;color:#94a3b8;">(hanya absensi yang Anda input)</span>
        </div>
    <?php endif; ?>

    <div class="rk-table-scroll">
        <table class="rk-table">
            <thead>
                <tr>
                    <th style="min-width:240px;text-align:left;">Nama Siswa</th>
                    <th><span style="color:#15803d;">Hadir</span></th>
                    <th><span style="color:#a16207;">Sakit</span></th>
                    <th><span style="color:#0369a1;">Izin</span></th>
                    <th><span style="color:#b91c1c;">Alpha</span></th>
                    <th>Total</th>
                    <th style="min-width:110px;">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $siswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $r     = $rekapData[$siswa->id] ?? ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0];
                        $total = array_sum($r);
                        $pct   = $total > 0 ? round($r['hadir'] / $total * 100) : 0;
                        $pctColor = $pct >= 80 ? '#15803d' : ($pct >= 60 ? '#a16207' : '#b91c1c');
                    ?>
                    <tr>
                        <td>
                            <div style="font-weight:700;color:#1e293b;font-size:13px;"><?php echo e($siswa->nama ?: '—'); ?></div>
                        </td>
                        <td><span class="chip chip-h"><?php echo e($r['hadir']); ?></span></td>
                        <td><span class="chip chip-s"><?php echo e($r['sakit']); ?></span></td>
                        <td><span class="chip chip-i"><?php echo e($r['izin']); ?></span></td>
                        <td><span class="chip chip-a"><?php echo e($r['alpha']); ?></span></td>
                        <td><span class="chip chip-total"><?php echo e($total); ?></span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div style="flex:1;height:6px;background:#e2e8f0;border-radius:10px;overflow:hidden;min-width:60px;">
                                    <div style="height:100%;width:<?php echo e($pct); ?>%;background:<?php echo e($pctColor); ?>;border-radius:10px;"></div>
                                </div>
                                <span style="font-size:11px;font-weight:700;color:<?php echo e($pctColor); ?>;min-width:35px;"><?php echo e($pct); ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7">
                        <div class="rk-empty">
                            <i class="bi bi-table"></i>
                            <p>Pilih kelas dan periode untuk melihat rekap absensi.</p>
                        </div>
                    </td></tr>
                <?php endif; ?>
            </tbody>
            <?php if($siswaList->count() > 0): ?>
                <?php
                    $totH = array_sum(array_column($rekapData,'hadir'));
                    $totS = array_sum(array_column($rekapData,'sakit'));
                    $totI = array_sum(array_column($rekapData,'izin'));
                    $totA = array_sum(array_column($rekapData,'alpha'));
                    $totAll = $totH+$totS+$totI+$totA;
                ?>
                <tfoot>
                    <tr style="background:#f8fafc;">
                        <td style="font-weight:700;color:#1e293b;padding:9px 10px 9px 14px;border-top:1.5px solid #e2e8f0;">Total</td>
                        <td style="border-top:1.5px solid #e2e8f0;"><span class="chip chip-h"><?php echo e($totH); ?></span></td>
                        <td style="border-top:1.5px solid #e2e8f0;"><span class="chip chip-s"><?php echo e($totS); ?></span></td>
                        <td style="border-top:1.5px solid #e2e8f0;"><span class="chip chip-i"><?php echo e($totI); ?></span></td>
                        <td style="border-top:1.5px solid #e2e8f0;"><span class="chip chip-a"><?php echo e($totA); ?></span></td>
                        <td style="border-top:1.5px solid #e2e8f0;"><span class="chip chip-total"><?php echo e($totAll); ?></span></td>
                        <td style="border-top:1.5px solid #e2e8f0;"></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/guru/absensi-siswa/rekap.blade.php ENDPATH**/ ?>
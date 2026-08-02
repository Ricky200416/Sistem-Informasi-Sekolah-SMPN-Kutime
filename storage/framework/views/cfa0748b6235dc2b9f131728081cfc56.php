<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Siswa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 7.5pt;
            color: #1e293b;
            background: #ffffff;
        }

        /* ── Header ─────────────────────────────────────── */
        .page-header {
            background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 18px 20px;
            margin-bottom: 14px;
        }

        .header-inner { display: flex; align-items: center; justify-content: space-between; }
        .header-title { font-size: 15pt; font-weight: bold; letter-spacing: -0.3px; }
        .header-subtitle { font-size: 7.5pt; opacity: 0.85; margin-top: 3px; }
        .header-meta { text-align: right; font-size: 7pt; opacity: 0.9; }
        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.35);
            color: #fff;
            font-size: 6.5pt;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 4px;
        }

        /* ── Summary ─────────────────────────────────────── */
        .summary-bar {
            display: flex;
            gap: 10px;
            margin: 0 20px 12px;
            padding: 9px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .summary-item { flex: 1; text-align: center; }
        .summary-number { font-size: 12pt; font-weight: bold; color: #6d28d9; }
        .summary-label { font-size: 6.5pt; color: #64748b; margin-top: 1px; }
        .summary-divider { width: 1px; background: #e2e8f0; }

        /* ── Table ───────────────────────────────────────── */
        .table-wrap { margin: 0 20px; }

        table { width: 100%; border-collapse: collapse; }

        thead tr { background: #6d28d9; color: #ffffff; }

        thead th {
            padding: 6px 5px;
            text-align: left;
            font-size: 6.5pt;
            font-weight: bold;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        thead th:first-child { border-radius: 6px 0 0 0; }
        thead th:last-child  { border-radius: 0 6px 0 0; }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #ffffff; }

        tbody td {
            padding: 5px 5px;
            font-size: 7pt;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        tbody tr:last-child td { border-bottom: none; }

        .td-no    { text-align: center; color: #94a3b8; font-size: 6.5pt; width: 18px; }
        .td-nama  { font-weight: 600; color: #1e293b; font-size: 7pt; }
        .td-email { color: #64748b; font-size: 6.5pt; }
        .td-mono  { font-family: 'DejaVu Sans Mono', monospace; font-size: 6.5pt; }
        .td-gray  { color: #94a3b8; }

        .badge {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 20px;
            font-size: 6pt;
            font-weight: bold;
        }

        .badge-l    { background: #dbeafe; color: #1d4ed8; }
        .badge-p    { background: #fce7f3; color: #be185d; }
        .badge-kelas{ background: #ede9fe; color: #6d28d9; }
        .badge-kps  { background: #d1fae5; color: #065f46; }
        .badge-no   { background: #f1f5f9; color: #64748b; }

        /* ── Footer ─────────────────────────────────────── */
        .page-footer {
            margin: 14px 20px 0;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 6.5pt;
            color: #94a3b8;
        }

        .empty-state { text-align: center; padding: 40px; color: #94a3b8; font-style: italic; }
    </style>
</head>
<body>

    
    <div class="page-header">
        <div class="header-inner">
            <div>
                <div class="header-title">Data Siswa</div>
                <div class="header-subtitle">Daftar Peserta Didik — Sistem Informasi Sekolah</div>
            </div>
            <div class="header-meta">
                <div>Dicetak: <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIB</div>
                <div class="header-badge"><?php echo e($users->count()); ?> Siswa Terdaftar</div>
            </div>
        </div>
    </div>

    
    <?php
        $totalLaki   = $users->filter(fn($u) => $u->siswa?->jk === 'L')->count();
        $totalPeremp = $users->filter(fn($u) => $u->siswa?->jk === 'P')->count();
        $totalKps    = $users->filter(fn($u) => $u->siswa?->penerima_kps === 'Ya')->count();

        // Kelompokkan per kelas
        $perKelas = $users->groupBy(fn($u) => $u->siswa?->kelas?->nama ?? 'Tanpa Kelas')->map->count();
        $kelasCount = $perKelas->count();
    ?>
    <div class="summary-bar">
        <div class="summary-item">
            <div class="summary-number"><?php echo e($users->count()); ?></div>
            <div class="summary-label">Total Siswa</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number"><?php echo e($totalLaki); ?></div>
            <div class="summary-label">Laki-laki</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number"><?php echo e($totalPeremp); ?></div>
            <div class="summary-label">Perempuan</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number"><?php echo e($kelasCount); ?></div>
            <div class="summary-label">Kelas</div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <div class="summary-number"><?php echo e($totalKps); ?></div>
            <div class="summary-label">Penerima KPS</div>
        </div>
    </div>

    
    <div class="table-wrap">
        <?php if($users->count()): ?>
        <table>
            <thead>
                <tr>
                    <th class="td-no">#</th>
                    <th style="min-width:110px;">Nama / Email</th>
                    <th>Kelas</th>
                    <th>NIS/NIPD</th>
                    <th>NIK</th>
                    <th>JK</th>
                    <th>Tempat / Tgl Lahir</th>
                    <th>Agama</th>
                    <th>No. Telp</th>
                    <th>Alamat</th>
                    <th>Kecamatan</th>
                    <th>KPS</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $s = $user->siswa; ?>
                    <tr>
                        <td class="td-no"><?php echo e($i + 1); ?></td>

                        
                        <td>
                            <div class="td-nama"><?php echo e($s?->nama ?? $user->name); ?></div>
                            <div class="td-email"><?php echo e($user->email); ?></div>
                        </td>

                        
                        <td>
                            <?php if($s?->kelas?->nama): ?>
                                <span class="badge badge-kelas"><?php echo e($s->kelas->nama); ?></span>
                            <?php else: ?>
                                <span class="td-gray">—</span>
                            <?php endif; ?>
                        </td>

                        
                        <td class="td-mono"><?php echo e($s?->nidn ?? '—'); ?></td>

                        
                        <td class="td-mono"><?php echo e($s?->nik ?? '—'); ?></td>

                        
                        <td>
                            <?php if($s?->jk === 'L'): ?>
                                <span class="badge badge-l">♂ L</span>
                            <?php elseif($s?->jk === 'P'): ?>
                                <span class="badge badge-p">♀ P</span>
                            <?php else: ?>
                                <span class="td-gray">—</span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <div><?php echo e($s?->tempat_lahir ?? '—'); ?></div>
                            <?php if($s?->tgl_lahir): ?>
                                <div style="color:#64748b;font-size:6.5pt;"><?php echo e($s->tgl_lahir->format('d/m/Y')); ?></div>
                            <?php endif; ?>
                        </td>

                        <td><?php echo e($s?->agama ?? '—'); ?></td>
                        <td class="td-mono"><?php echo e($s?->no_telp ?? '—'); ?></td>

                        
                        <td style="max-width:100px;">
                            <?php if($s?->alamat): ?>
                                <span title="<?php echo e($s->alamat); ?>">
                                    <?php echo e(mb_strimwidth($s->alamat, 0, 35, '...')); ?>

                                </span>
                                <?php if($s->rt && $s->rw): ?>
                                    <div style="color:#94a3b8;font-size:6pt;">RT <?php echo e($s->rt); ?> / RW <?php echo e($s->rw); ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="td-gray">—</span>
                            <?php endif; ?>
                        </td>

                        <td><?php echo e($s?->kecamatan ?? '—'); ?></td>

                        
                        <td>
                            <?php if($s?->penerima_kps === 'Ya'): ?>
                                <span class="badge badge-kps">Ya</span>
                            <?php else: ?>
                                <span class="badge badge-no">Tdk</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="empty-state">Belum ada data siswa yang terdaftar.</div>
        <?php endif; ?>
    </div>

    
    <div class="page-footer">
        <span>Sistem Informasi Sekolah — Data Bersifat Rahasia</span>
        <span>Dicetak oleh Administrator pada <?php echo e(now()->format('d/m/Y H:i')); ?></span>
    </div>

</body>
</html><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/pdf/siswa.blade.php ENDPATH**/ ?>
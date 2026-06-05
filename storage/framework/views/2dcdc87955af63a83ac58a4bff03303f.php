<?php $__env->startSection('title', 'Absensi Guru'); ?>

<?php $__env->startPush('styles'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; }
    body { background: #f0f2f8; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; }

    .ag-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px; }
    .ag-title  { font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; ma rgin:0; }
    .ag-title i { color:#4f46e5; font-size:16px; }
    .ag-title .sub { font-size:11px; font-weight:500; color:#94a3b8; }
    .ag-btn-kelola {
        display:inline-flex; align-items:center; gap:6px;
        background:#eef2ff; border:1px solid #c7d2fe; border-radius:7px;
        padding:5px 12px; font-size:11px; font-weight:600; color:#4338ca; text-decoration:none;
        transition:background .15s;
    }
    .ag-btn-kelola:hover { background:#e0e7ff; }
    .ag-btn-kelola .badge { background:#c7d2fe; border-radius:10px; padding:1px 6px; font-size:10px; }

    .ag-filter {
        background:#fff; border:1px solid #e2e8f0; border-radius:10px;
        padding:10px 14px; display:flex; align-items:center;
        gap:8px; flex-wrap:wrap; margin-bottom:12px;
    }
    .ag-filter label { font-size:11px; font-weight:600; color:#64748b; white-space:nowrap; }
    .ag-filter select, .ag-filter input[type="text"] {
        border:1px solid #e2e8f0; border-radius:6px; padding:5px 8px;
        font-size:11.5px; font-family:inherit; color:#1e293b; background:#f8fafc; outline:none; transition:border .15s;
    }
    .ag-filter select:focus, .ag-filter input:focus { border-color:#4f46e5; background:#fff; }
    .ag-btn-filter {
        background:#4f46e5; color:#fff; border:none; border-radius:6px; padding:5px 14px;
        font-size:11.5px; font-weight:600; font-family:inherit; cursor:pointer;
        display:flex; align-items:center; gap:5px; transition:background .15s;
    }
    .ag-btn-filter:hover { background:#4338ca; }

    .ag-stats { display:flex; gap:8px; margin-bottom:12px; flex-wrap:wrap; }
    .ag-stat  { background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:8px 14px; display:flex; align-items:center; gap:8px; flex:1; min-width:90px; }
    .ag-dot   { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .ag-val   { font-size:15px; font-weight:700; color:#1e293b; font-family:'JetBrains Mono',monospace; line-height:1; }
    .ag-lbl   { font-size:10px; color:#94a3b8; margin-top:1px; font-weight:500; }

    .ag-wrap  { background:#fff; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.05); }
    .ag-scroll { overflow-x:auto; overflow-y:auto; max-height:calc(100vh - 300px); }
    .ag-scroll::-webkit-scrollbar { width:5px; height:5px; }
    .ag-scroll::-webkit-scrollbar-track { background:#f1f5f9; }
    .ag-scroll::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:10px; }

    .ag-tbl { border-collapse:separate; border-spacing:0; width:100%; font-size:11.5px; }
    .ag-tbl thead th { position:sticky; top:0; z-index:3; }
    .ag-cn { position:sticky; left:0; z-index:4 !important; }
    .ag-tbl thead .ag-cn { z-index:5 !important; }
    .ag-tbl thead th {
        background:#f8fafc; color:#64748b; font-size:10px; font-weight:700;
        text-transform:uppercase; letter-spacing:.04em; padding:7px 5px;
        border-bottom:1.5px solid #e2e8f0; white-space:nowrap; text-align:center; user-select:none;
    }
    .ag-tbl thead th.ag-cn { text-align:left; padding-left:12px; min-width:215px; }

    .ag-tbl tbody tr:nth-child(even) td { background:#fafbfd; }
    .ag-tbl tbody tr:nth-child(odd)  td { background:#ffffff; }
    .ag-tbl tbody tr:hover td { background:#eef2ff !important; transition:background .1s; }
    .ag-tbl tbody tr:nth-child(even) td.ag-cn { background:#fafbfd; }
    .ag-tbl tbody tr:nth-child(odd)  td.ag-cn { background:#ffffff; }
    .ag-tbl tbody tr:hover           td.ag-cn { background:#eef2ff !important; }
    .ag-tbl tbody td { padding:5px 4px; border-bottom:1px solid #f1f5f9; text-align:center; vertical-align:middle; }
    .ag-tbl tbody td.ag-cn { text-align:left; padding-left:12px; white-space:nowrap; min-width:215px; }

    .ag-av { display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; border-radius:7px; background:#e0e7ff; color:#4f46e5; font-size:10px; font-weight:700; margin-right:7px; flex-shrink:0; vertical-align:middle; }
    .ag-gw { display:inline-flex; align-items:center; }
    .ag-gn { font-size:11.5px; font-weight:600; color:#334155; line-height:1.25; }
    .ag-gs { display:block; font-size:9.5px; color:#94a3b8; font-family:'JetBrains Mono',monospace; }

    .ag-tbl thead th.ag-wk { background:#fffbeb !important; color:#d97706 !important; }
    .ag-tbl thead th.ag-td { background:#eef2ff !important; color:#4f46e5 !important; font-weight:800 !important; }
    .ag-tbl tbody td.ag-td { background:#eef2ff !important; }
    .ag-tbl tbody tr:hover td.ag-td { background:#e0e7ff !important; }
    .ag-cr { position:sticky; right:0; background:inherit; border-left:1.5px solid #f1f5f9; z-index:2; }
    .ag-tbl thead th.ag-cr { background:#f8fafc; border-left:1.5px solid #e2e8f0; z-index:4; }

    .ag-b { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:5px; font-size:10px; font-weight:700; cursor:pointer; border:none; font-family:'Plus Jakarta Sans',sans-serif; transition:transform .1s,box-shadow .1s; }
    .ag-b:hover { transform:scale(1.18); box-shadow:0 2px 8px rgba(0,0,0,.15); }
    .ag-b-P{background:#dcfce7;color:#15803d;} .ag-b-A{background:#fee2e2;color:#b91c1c;}
    .ag-b-S{background:#fef9c3;color:#a16207;} .ag-b-I{background:#e0f2fe;color:#0369a1;}
    .ag-b-L{background:#fce7f3;color:#be185d;} .ag-b-W{background:#f3e8ff;color:#7e22ce;}
    .ag-be { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:5px; color:#e2e8f0; font-size:14px; cursor:pointer; transition:background .1s,color .1s,transform .1s; }
    .ag-be:hover { background:#eef2ff; color:#4f46e5; transform:scale(1.15); }
    .ag-rc { border-radius:4px; padding:1px 4px; font-size:9px; font-weight:700; display:inline-block; }

    .ag-legend { display:flex; gap:10px; flex-wrap:wrap; padding:8px 14px; border-top:1px solid #f1f5f9; background:#fafbfd; align-items:center; }
    .ag-li { display:flex; align-items:center; gap:4px; font-size:10px; color:#64748b; font-weight:500; }
    .ag-ld { width:16px; height:16px; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:700; }

    .ag-mov { display:none; position:fixed; inset:0; z-index:1000; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; }
    .ag-mov.show { display:flex; }
    .ag-mbox { background:#fff; border-radius:14px; padding:20px; width:275px; box-shadow:0 24px 60px rgba(0,0,0,.18); animation:agPop .2s cubic-bezier(.34,1.56,.64,1); }
    @keyframes agPop { from{transform:scale(.88);opacity:0} to{transform:scale(1);opacity:1} }
    .ag-mh { display:flex; align-items:center; gap:10px; margin-bottom:13px; }
    .ag-mav { width:34px; height:34px; border-radius:9px; background:#e0e7ff; color:#4f46e5; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; }
    .ag-mn  { font-size:13px; font-weight:700; color:#1e293b; line-height:1.2; }
    .ag-ms  { font-size:10px; color:#64748b; margin-top:2px; }
    .ag-sg  { display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:14px; }
    .ag-sb  { display:flex; flex-direction:column; align-items:center; gap:3px; padding:9px 4px; border-radius:9px; border:2px solid #f1f5f9; cursor:pointer; transition:all .15s; background:#f8fafc; font-family:inherit; }
    .ag-sb .sc { font-size:14px; font-weight:800; color:var(--sc); }
    .ag-sb .sl { font-size:9px; font-weight:600; color:#94a3b8; }
    .ag-sb:hover { border-color:var(--sc); transform:translateY(-1px); }
    .ag-sb.active { border-color:var(--sc); background:var(--sbg); }
    .ag-sb.active .sl { color:var(--sc); }
    .ag-sb[data-s="P"]{--sc:#15803d;--sbg:#dcfce7} .ag-sb[data-s="A"]{--sc:#b91c1c;--sbg:#fee2e2}
    .ag-sb[data-s="S"]{--sc:#a16207;--sbg:#fef9c3} .ag-sb[data-s="I"]{--sc:#0369a1;--sbg:#e0f2fe}
    .ag-sb[data-s="L"]{--sc:#be185d;--sbg:#fce7f3} .ag-sb[data-s="W"]{--sc:#7e22ce;--sbg:#f3e8ff}
    .ag-ma { display:flex; gap:6px; }
    .ag-mb { flex:1; padding:8px; border-radius:7px; border:none; font-size:11.5px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:4px; }
    .ag-mb-c{background:#f1f5f9;color:#64748b;} .ag-mb-c:hover{background:#e2e8f0;}
    .ag-mb-s{background:#4f46e5;color:#fff;}    .ag-mb-s:hover{background:#4338ca;}
    .ag-mb-d{background:#fee2e2;color:#b91c1c;flex:none;padding:8px 10px;} .ag-mb-d:hover{background:#fecaca;}

    .ag-toast { position:fixed; bottom:20px; right:20px; z-index:9999; background:#1e293b; color:#fff; border-radius:9px; padding:10px 16px; font-size:12px; font-weight:500; display:flex; align-items:center; gap:8px; box-shadow:0 8px 24px rgba(0,0,0,.15); transform:translateY(80px); opacity:0; transition:all .28s cubic-bezier(.34,1.56,.64,1); pointer-events:none; }
    .ag-toast.show { transform:translateY(0); opacity:1; }
    .ag-toast.success i{color:#4ade80;} .ag-toast.error i{color:#f87171;}
    .ag-empty { padding:48px; text-align:center; color:#94a3b8; }
    .ag-empty i { font-size:36px; display:block; margin-bottom:10px; color:#cbd5e1; }
    .ag-empty p { font-size:12px; margin:0; line-height:1.7; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $daftarGuru  = $daftarGuru  ?? collect();
    $absensiData = $absensiData ?? [];
    $bulan       = $bulan       ?? date('n');
    $tahun       = $tahun       ?? date('Y');
    $jumlahHari  = $jumlahHari  ?? cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
    $bulanList   = $bulanList   ?? [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

    // UPDATE: Tahun sekarang tanpa batasan (dari 2000 sampai tahun ini + 10 tahun ke depan)
    $tahunList   = range(2000, date('Y') + 10);
    $daftarKelas = $daftarKelas ?? collect();
    $kelasFilter = $kelasFilter ?? null;
    $ringkasan   = $ringkasan   ?? ['hadir'=>0,'alpha'=>0,'sakit'=>0,'izin'=>0,'telat'=>0,'total'=>0];

    $namaHari  = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    $todayDay  = (date('Y')==$tahun && date('n')==$bulan) ? (int)date('j') : 0;
    $wm = [];
    for ($d=1;$d<=$jumlahHari;$d++) {
        $dw=(int)date('w',mktime(0,0,0,$bulan,$d,$tahun));
        $wm[$d]=($dw===0||$dw===6);
    }
    $tanpaProfil = $daftarGuru->filter(fn($u)=>!$u->guru)->count();
?>

<div class="container-fluid px-0">

    
    <div class="ag-header">
        <h5 class="ag-title">
            <i class="bi bi-calendar-check-fill"></i>
            Absensi Guru
            <span class="sub">— <?php echo e($bulanList[$bulan]); ?> <?php echo e($tahun); ?></span>
        </h5>

        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <a href="<?php echo e(route('admin.absensi-guru.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun])); ?>"
               class="ag-btn-kelola" style="background:#ef4444; border-color:#ef4444; color:white;">
                <i class="bi bi-file-pdf-fill"></i>
                PDF
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success py-2 px-3 mb-2" style="font-size:12px;border-radius:8px;">
            <i class="bi bi-check-circle-fill me-1"></i><?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($tanpaProfil > 0): ?>
        <div style="background:#fefce8;border:1px solid #fde68a;border-radius:8px;padding:8px 12px;margin-bottom:10px;font-size:11.5px;color:#92400e;display:flex;align-items:center;gap:7px;">
            <i class="bi bi-exclamation-triangle-fill" style="color:#d97706;flex-shrink:0;"></i>
            <span>
                <strong><?php echo e($tanpaProfil); ?> guru</strong> belum punya profil — sel absensinya tidak bisa diisi.
                <a href="<?php echo e(route('admin.users.index', ['tab'=>'guru'])); ?>" style="color:#4f46e5;font-weight:600;">→ Lengkapi profil</a>
            </span>
        </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('admin.absensi-guru.index')); ?>" class="ag-filter">
        <label><i class="bi bi-calendar3 me-1"></i>Periode:</label>
        <select name="bulan">
            <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num=>$nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($num); ?>" <?php echo e($bulan==$num?'selected':''); ?>><?php echo e($nama); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="tahun">
            <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t); ?>" <?php echo e($tahun==$t?'selected':''); ?>><?php echo e($t); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php if(is_iterable($daftarKelas) && count($daftarKelas)): ?>
            <select name="kelas">
                <option value="">Semua Kelas</option>
                <?php $__currentLoopData = $daftarKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php echo e(($kelasFilter??'')==$k?'selected':''); ?>><?php echo e($k); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        <?php endif; ?>
        <input type="text" id="agSearch" placeholder="Cari nama guru…"
               style="min-width:150px;" oninput="agFil(this.value)" autocomplete="off">
        <button type="submit" class="ag-btn-filter">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>
    </form>

    
    <div class="ag-stats">
        <div class="ag-stat"><span class="ag-dot" style="background:#4f46e5;"></span><div><div class="ag-val"><?php echo e($daftarGuru->count()); ?></div><div class="ag-lbl">Total Guru</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#15803d;"></span><div><div class="ag-val"><?php echo e($ringkasan['hadir']); ?></div><div class="ag-lbl">Hadir</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#b91c1c;"></span><div><div class="ag-val"><?php echo e($ringkasan['alpha']); ?></div><div class="ag-lbl">Alpha</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#a16207;"></span><div><div class="ag-val"><?php echo e($ringkasan['sakit']); ?></div><div class="ag-lbl">Sakit</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#0369a1;"></span><div><div class="ag-val"><?php echo e($ringkasan['izin']); ?></div><div class="ag-lbl">Izin</div></div></div>
        <div class="ag-stat"><span class="ag-dot" style="background:#be185d;"></span><div><div class="ag-val"><?php echo e($ringkasan['telat']); ?></div><div class="ag-lbl">Terlambat</div></div></div>
    </div>

    
    <div class="ag-wrap">
        <div class="ag-scroll" id="agScroll">
            <table class="ag-tbl">
                <thead>
                    <tr>
                        <th class="ag-cn">
                            Nama Guru
                            <span style="font-weight:400;color:#94a3b8;margin-left:4px;">(<?php echo e($daftarGuru->count()); ?>)</span>
                        </th>
                        <?php for($d=1;$d<=$jumlahHari;$d++): ?>
                            <?php $dw=(int)date('w',mktime(0,0,0,$bulan,$d,$tahun)); ?>
                            <th class="<?php echo e($wm[$d]?'ag-wk':''); ?> <?php echo e($todayDay===$d?'ag-td':''); ?>">
                                <?php echo e($d); ?>

                                <div style="font-size:8px;font-weight:500;opacity:.65;"><?php echo e($namaHari[$dw]); ?></div>
                            </th>
                        <?php endfor; ?>
                        <th class="ag-cr" style="min-width:80px;text-align:center;">Rekap</th>
                    </tr>
                </thead>
                <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $daftarGuru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $guru = $user->guru;
                    $namaTampil = ($guru && $guru->nama) ? $guru->nama : $user->name;
                    $subInfo    = ($guru && $guru->nip)  ? $guru->nip  : $user->email;
                    $fotoUrl    = $user->photo ? Storage::url($user->photo) : null;
                    $inisial    = strtoupper(mb_substr($namaTampil, 0, 1));
                    $gid        = ($guru && $guru->id) ? (int)$guru->id : null;

                    $rP=0;$rA=0;$rS=0;$rI=0;$rL=0;$rW=0;
                    if ($gid && !empty($absensiData[$gid])) {
                        foreach ($absensiData[$gid] as $ai) {
                            match($ai->status){ 'P'=>$rP++,'A'=>$rA++,'S'=>$rS++,'I'=>$rI++,'L'=>$rL++,'W'=>$rW++,default=>null };
                        }
                    }
                ?>

                <tr class="ag-row" data-nama="<?php echo e(strtolower($namaTampil)); ?>">

                    <td class="ag-cn">
                        <div class="ag-gw">
                            <?php if($fotoUrl): ?>
                                <img src="<?php echo e($fotoUrl); ?>" alt=""
                                     style="width:26px;height:26px;border-radius:7px;object-fit:cover;margin-right:7px;flex-shrink:0;border:1px solid #e2e8f0;">
                            <?php else: ?>
                                <span class="ag-av"><?php echo e($inisial); ?></span>
                            <?php endif; ?>
                            <div>
                                <div class="ag-gn"><?php echo e($namaTampil); ?></div>
                                <span class="ag-gs"><?php echo e($subInfo); ?></span>
                            </div>
                        </div>
                    </td>

                    <?php for($d=1;$d<=$jumlahHari;$d++): ?>
                        <?php $abs=($gid&&isset($absensiData[$gid][$d]))?$absensiData[$gid][$d]:null; ?>
                        <td id="ag-c-<?php echo e($gid??'u'.$user->id); ?>-<?php echo e($d); ?>"
                            class="<?php echo e($todayDay===$d?'ag-td':''); ?>">
                            <?php if(!$gid): ?>
                                <span style="color:#f1f5f9;font-size:10px;" title="Profil belum ada">·</span>
                            <?php elseif($abs): ?>
                                <button class="ag-b ag-b-<?php echo e($abs->status); ?>"
                                    onclick="agM(<?php echo e($gid); ?>,<?php echo e($d); ?>,'<?php echo e($abs->status); ?>',<?php echo \Illuminate\Support\Js::from($namaTampil)->toHtml() ?>,<?php echo e($abs->id); ?>)"
                                    title="<?php echo e($namaTampil); ?> · <?php echo e($d); ?> <?php echo e($bulanList[$bulan]); ?>"><?php echo e($abs->status); ?></button>
                            <?php else: ?>
                                <span class="ag-be"
                                    onclick="agM(<?php echo e($gid); ?>,<?php echo e($d); ?>,'',<?php echo \Illuminate\Support\Js::from($namaTampil)->toHtml() ?>,null)"
                                    title="Isi absensi <?php echo e($d); ?> <?php echo e($bulanList[$bulan]); ?>">+</span>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>

                    <td class="ag-cr">
                        <?php if(!$gid): ?>
                            <span style="font-size:9px;color:#fca5a5;">Profil kosong</span>
                        <?php else: ?>
                            <div style="display:flex;flex-wrap:wrap;gap:2px;justify-content:center;min-width:70px;">
                                <?php if($rP): ?><span class="ag-rc" style="background:#dcfce7;color:#15803d;">P:<?php echo e($rP); ?></span><?php endif; ?>
                                <?php if($rA): ?><span class="ag-rc" style="background:#fee2e2;color:#b91c1c;">A:<?php echo e($rA); ?></span><?php endif; ?>
                                <?php if($rS): ?><span class="ag-rc" style="background:#fef9c3;color:#a16207;">S:<?php echo e($rS); ?></span><?php endif; ?>
                                <?php if($rI): ?><span class="ag-rc" style="background:#e0f2fe;color:#0369a1;">I:<?php echo e($rI); ?></span><?php endif; ?>
                                <?php if($rL): ?><span class="ag-rc" style="background:#fce7f3;color:#be185d;">L:<?php echo e($rL); ?></span><?php endif; ?>
                                <?php if($rW): ?><span class="ag-rc" style="background:#f3e8ff;color:#7e22ce;">W:<?php echo e($rW); ?></span><?php endif; ?>
                                <?php if(!$rP&&!$rA&&!$rS&&!$rI&&!$rL&&!$rW): ?>
                                    <span style="color:#cbd5e1;font-size:10px;">—</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="<?php echo e($jumlahHari+2); ?>">
                    <div class="ag-empty">
                        <i class="bi bi-people"></i>
                        <p>
                            Belum ada akun dengan role <strong>guru</strong>.<br>
                            Tambahkan via <a href="<?php echo e(route('admin.users.index', ['tab'=>'guru'])); ?>" style="color:#4f46e5;font-weight:600;">Kelola User → Tambah User</a>,
                            pilih role <strong>Guru</strong>.
                        </p>
                    </div>
                </td></tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="ag-legend">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;">Keterangan:</span>
            <?php $__currentLoopData = ['P'=>['#dcfce7','#15803d','Hadir'],'A'=>['#fee2e2','#b91c1c','Alpha'],'S'=>['#fef9c3','#a16207','Sakit'],'I'=>['#e0f2fe','#0369a1','Izin'],'L'=>['#fce7f3','#be185d','Terlambat'],'W'=>['#f3e8ff','#7e22ce','WFH']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>[$bg,$fc,$lb]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="ag-li">
                    <span class="ag-ld" style="background:<?php echo e($bg); ?>;color:<?php echo e($fc); ?>;"><?php echo e($k); ?></span><?php echo e($lb); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span style="margin-left:auto;font-size:10px;color:#cbd5e1;"><i class="bi bi-info-circle me-1"></i>Klik sel untuk isi / ubah</span>
        </div>
    </div>
</div>


<div class="ag-mov" id="agModal" onclick="if(event.target.id==='agModal')agMC()">
    <div class="ag-mbox">
        <div class="ag-mh">
            <div class="ag-mav" id="agMav">G</div>
            <div><div class="ag-mn" id="agMn">—</div><div class="ag-ms" id="agMs">—</div></div>
        </div>
        <div class="ag-sg">
            <?php $__currentLoopData = ['P'=>'Hadir','A'=>'Alpha','S'=>'Sakit','I'=>'Izin','L'=>'Terlambat','W'=>'WFH']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$lb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="ag-sb" data-s="<?php echo e($k); ?>" onclick="agSS('<?php echo e($k); ?>')">
                    <span class="sc"><?php echo e($k); ?></span><span class="sl"><?php echo e($lb); ?></span>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="ag-ma">
            <button class="ag-mb ag-mb-c" onclick="agMC()"><i class="bi bi-x"></i> Batal</button>
            <button class="ag-mb ag-mb-d" id="agDelBtn" onclick="agDel()" style="display:none;"><i class="bi bi-trash3"></i></button>
            <button class="ag-mb ag-mb-s" id="agSaveBtn" onclick="agSave()"><i class="bi bi-check2"></i> Simpan</button>
        </div>
    </div>
</div>

<div class="ag-toast" id="agToast"><i id="agTi"></i><span id="agTm"></span></div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const AG_B = <?php echo e((int)$bulan); ?>, AG_Y = <?php echo e((int)$tahun); ?>;
const AG_BN = <?php echo \Illuminate\Support\Js::from($bulanList[$bulan])->toHtml() ?>;
const AG_SU = "<?php echo e(route('admin.absensi-guru.store')); ?>";
const AG_DB = "<?php echo e(url('admin/absensi-guru')); ?>";
const agCS  = () => document.querySelector('meta[name="csrf-token"]').content;
let ag = {gid:null,hari:null,status:null,aid:null,nama:''};

function agFil(v){
    const q=v.toLowerCase().trim();
    document.querySelectorAll('.ag-row').forEach(r=>{r.style.display=(!q||r.dataset.nama.includes(q))?'':'none';});
}
function agM(gid,hari,status,nama,aid){
    ag={gid,hari,status:status||null,aid,nama};
    document.getElementById('agMav').textContent=nama.charAt(0).toUpperCase();
    document.getElementById('agMn').textContent=nama;
    document.getElementById('agMs').textContent=`${hari} ${AG_BN} ${AG_Y}`+(status?` · ${status}`:' · Belum diisi');
    document.querySelectorAll('.ag-sb').forEach(b=>b.classList.toggle('active',b.dataset.s===status));
    document.getElementById('agDelBtn').style.display=aid?'inline-flex':'none';
    document.getElementById('agModal').classList.add('show');
}
function agMC(){document.getElementById('agModal').classList.remove('show');}
function agSS(k){ag.status=k;document.querySelectorAll('.ag-sb').forEach(b=>b.classList.toggle('active',b.dataset.s===k));}

async function agSave(){
    if(!ag.status){agT('Pilih status','error');return;}
    agBL(true);
    const tgl=`${AG_Y}-${String(AG_B).padStart(2,'0')}-${String(ag.hari).padStart(2,'0')}`;
    try{
        const r=await fetch(AG_SU,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':agCS(),'Accept':'application/json'},body:JSON.stringify({guru_id:ag.gid,tanggal:tgl,status:ag.status,keterangan:''})});
        const d=await r.json();
        if(r.ok&&d.success){agMC();agT('Tersimpan ✓','success');agUC(ag.gid,ag.hari,ag.status,d.id??ag.aid);}
        else agT(d.message??(d.errors?Object.values(d.errors).flat().join(', '):'Gagal'),'error');
    }catch(e){agT('Gagal terhubung','error');}finally{agBL(false);}
}
async function agDel(){
    if(!ag.aid)return;agBL(true);
    try{
        const r=await fetch(`${AG_DB}/${ag.aid}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':agCS(),'Accept':'application/json'}});
        const d=await r.json();
        if(r.ok&&d.success){agMC();agT('Dihapus','success');agCC(ag.gid,ag.hari);}
        else agT(d.message??'Gagal','error');
    }catch(e){agT('Gagal terhubung','error');}finally{agBL(false);}
}
const AGS={P:['#dcfce7','#15803d'],A:['#fee2e2','#b91c1c'],S:['#fef9c3','#a16207'],I:['#e0f2fe','#0369a1'],L:['#fce7f3','#be185d'],W:['#f3e8ff','#7e22ce']};
function agUC(gid,hari,status,aid){
    const c=document.getElementById(`ag-c-${gid}-${hari}`);if(!c)return;
    const[bg,fc]=AGS[status]??['#f1f5f9','#64748b'];
    c.innerHTML=`<button class="ag-b" style="background:${bg};color:${fc};" onclick="agM(${gid},${hari},'${status}',${JSON.stringify(ag.nama)},${aid})">${status}</button>`;
}
function agCC(gid,hari){
    const c=document.getElementById(`ag-c-${gid}-${hari}`);if(!c)return;
    c.innerHTML=`<span class="ag-be" onclick="agM(${gid},${hari},'',${JSON.stringify(ag.nama)},null)">+</span>`;
}
function agBL(on){const b=document.getElementById('agSaveBtn');b.disabled=on;b.innerHTML=on?'<span style="opacity:.6">Menyimpan…</span>':'<i class="bi bi-check2"></i> Simpan';}
let _at;
function agT(msg,type='success'){
    clearTimeout(_at);const el=document.getElementById('agToast');
    document.getElementById('agTi').className=type==='success'?'bi bi-check-circle-fill':'bi bi-exclamation-circle-fill';
    document.getElementById('agTm').textContent=msg;
    el.className=`ag-toast ${type} show`;
    _at=setTimeout(()=>{el.className=`ag-toast ${type}`;},3000);
}
document.addEventListener('DOMContentLoaded',()=>{
    const th=document.querySelector('.ag-tbl thead th.ag-td');
    if(th){const sc=document.getElementById('agScroll');sc.scrollLeft=Math.max(0,th.offsetLeft-240);}
});
document.addEventListener('keydown',e=>{if(e.key==='Escape')agMC();});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH S:\PA3\smpn-kutime\resources\views/admin/absensi-guru/index.blade.php ENDPATH**/ ?>
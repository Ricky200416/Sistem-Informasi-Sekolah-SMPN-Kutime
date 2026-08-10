<?php $__env->startSection('title', 'Absensi Guru & Siswa'); ?>

<?php $__env->startPush('styles'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
    * { box-sizing: border-box; }
    body { background: #f0f2f8; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; }

    /* ── Tab Navbar Kecil ── */
    .ag-tabs {
        display: flex;
        gap: 4px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 14px;
        width: fit-content;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .ag-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        transition: all .15s;
        border: none;
        background: transparent;
        cursor: pointer;
        font-family: inherit;
    }
    .ag-tab:hover { background: #f1f5f9; color: #334155; }
    .ag-tab.active {
        background: #4f46e5;
        color: #fff;
        box-shadow: 0 2px 6px rgba(79,70,229,.25);
    }
    .ag-tab.active i { color: #fff; }
    .ag-tab i { font-size: 13px; }

    /* ── Header ── */
    .ag-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px; }
    .ag-title  { font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; margin:0; }
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
    .ag-filter select, .ag-filter input[type="text"], .ag-filter input[type="date"] {
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

    .ag-b { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:5px; font-size:10px; font-weight:700; cursor:pointer; border:none; font-family:'Plus Jakarta Sans',sans-serif; transition:transform .1s,box-shadow .1s; position:relative; }
    .ag-b:hover { transform:scale(1.18); box-shadow:0 2px 8px rgba(0,0,0,.15); }
    .ag-b-P{background:#dcfce7;color:#15803d;} .ag-b-A{background:#fee2e2;color:#b91c1c;}
    .ag-b-S{background:#fef9c3;color:#a16207;} .ag-b-I{background:#e0f2fe;color:#0369a1;}
    .ag-b-L{background:#fce7f3;color:#be185d;} .ag-b-W{background:#f3e8ff;color:#7e22ce;}
    .ag-rc { border-radius:4px; padding:1px 4px; font-size:9px; font-weight:700; display:inline-block; }

    .ag-cam-dot {
        position:absolute; top:-3px; right:-3px; width:9px; height:9px; border-radius:50%;
        background:#4f46e5; border:1.5px solid #fff; display:flex; align-items:center; justify-content:center;
    }
    .ag-cam-dot i { font-size:6px; color:#fff; }

    .ag-legend { display:flex; gap:10px; flex-wrap:wrap; padding:8px 14px; border-top:1px solid #f1f5f9; background:#fafbfd; align-items:center; }
    .ag-li { display:flex; align-items:center; gap:4px; font-size:10px; color:#64748b; font-weight:500; }
    .ag-ld { width:16px; height:16px; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:700; }

    .ag-mov { display:none; position:fixed; inset:0; z-index:1000; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px; }
    .ag-mov.show { display:flex; }
    .ag-mbox { background:#fff; border-radius:14px; padding:20px; width:275px; box-shadow:0 24px 60px rgba(0,0,0,.18); animation:agPop .2s cubic-bezier(.34,1.56,.64,1); max-height:90vh; overflow-y:auto; }
    .ag-mbox.ag-mbox-wide { width:340px; }
    @keyframes agPop { from{transform:scale(.88);opacity:0} to{transform:scale(1);opacity:1} }
    .ag-mh { display:flex; align-items:center; gap:10px; margin-bottom:13px; }
    .ag-mav { width:34px; height:34px; border-radius:9px; background:#e0e7ff; color:#4f46e5; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; }
    .ag-mn  { font-size:13px; font-weight:700; color:#1e293b; line-height:1.2; }
    .ag-ms  { font-size:10px; color:#64748b; margin-top:2px; }

    .ag-photo-block { margin-bottom:14px; }
    .ag-photo-badge {
        display:inline-flex; align-items:center; gap:5px; background:#eef2ff; color:#4338ca;
        border:1px solid #c7d2fe; border-radius:6px; padding:3px 8px; font-size:10px; font-weight:700;
        margin-bottom:8px;
    }
    .ag-photo-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .ag-photo-grid.single { grid-template-columns:1fr; }
    .ag-photo-item { text-align:center; }
    .ag-photo-item img {
        width:100%; height:100px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0;
        cursor:zoom-in; transition:opacity .15s;
    }
    .ag-photo-item img:hover { opacity:.85; }
    .ag-photo-item .lbl { font-size:9px; color:#94a3b8; font-weight:600; margin-top:3px; }
    .ag-photo-empty {
        display:flex; align-items:center; justify-content:center; height:100px; border-radius:8px;
        border:1.5px dashed #e2e8f0; color:#cbd5e1; font-size:9.5px;
    }

    .ag-ma { display:flex; gap:6px; }
    .ag-mb { flex:1; padding:8px; border-radius:7px; border:none; font-size:11.5px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .15s; display:flex; align-items:center; justify-content:center; gap:4px; }
    .ag-mb-c{background:#f1f5f9;color:#64748b;} .ag-mb-c:hover{background:#e2e8f0;}

    .ag-empty { padding:48px; text-align:center; color:#94a3b8; }
    .ag-empty i { font-size:36px; display:block; margin-bottom:10px; color:#cbd5e1; }
    .ag-empty p { font-size:12px; margin:0; line-height:1.7; }

    .ag-lightbox { display:none; position:fixed; inset:0; z-index:1100; background:rgba(0,0,0,.8); align-items:center; justify-content:center; padding:24px; cursor:zoom-out; }
    .ag-lightbox.show { display:flex; }
    .ag-lightbox img { max-width:100%; max-height:100%; border-radius:10px; box-shadow:0 20px 60px rgba(0,0,0,.4); }

    .ag-no-photo {
        text-align:center; padding:14px 4px; color:#94a3b8; font-size:11px;
    }
    .ag-no-photo i { font-size:20px; display:block; margin-bottom:6px; color:#cbd5e1; }

    .ag-viewonly-badge {
        display:inline-flex; align-items:center; gap:5px; background:#fefce8; color:#92400e;
        border:1px solid #fde68a; border-radius:6px; padding:3px 10px; font-size:10px; font-weight:700;
    }

    /* ── Absensi Siswa (Admin View) ── */
    .as-siswa-list { padding: 0; }
    .as-siswa-row {
        display: flex;
        align-items: center;
        gap: 0.615rem;
        padding: 0.615rem 0.769rem;
        transition: background .1s;
        border-bottom: 1px solid #f1f5f9;
        min-height: 3.4rem;
    }
    .as-siswa-row:last-child { border-bottom: none; }
    .as-siswa-row:hover { background: #fafbff; }
    .as-no {
        width: 1.462rem;
        height: 1.462rem;
        border-radius: 0.308rem;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 0.538rem;
        font-weight: 700;
        text-align: center;
        line-height: 1.462rem;
        flex-shrink: 0;
    }
    .as-av {
        width: 2.3rem;
        height: 2.3rem;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 800;
        flex-shrink: 0;
        overflow: hidden;
        border: 1.5px solid #e0e7ff;
    }
    .as-av img { width: 100%; height: 100%; object-fit: cover; }
    .as-info { flex: 1; min-width: 0; }
    .as-nama { font-size: 0.8rem; font-weight: 700; color: #0f172a; line-height: 1.3; }
    .as-nis { font-size: 0.55rem; color: #94a3b8; font-weight: 500; }
    .as-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .as-status-hadir { background:#dcfce7; color:#15803d; }
    .as-status-sakit { background:#fef9c3; color:#a16207; }
    .as-status-izin  { background:#e0f2fe; color:#0369a1; }
    .as-status-alpha { background:#fee2e2; color:#b91c1c; }
    .as-status-belum { background:#f1f5f9; color:#94a3b8; }
    .as-ket-text { font-size: 10px; color: #64748b; margin-top: 2px; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .tab-content { display: none; }
    .tab-content.active { display: block; }
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

    // Data Absensi Siswa (akan diisi controller jika tab siswa aktif)
    $kelasListSiswa   = $kelasListSiswa   ?? collect();
    $kelasIdSiswa     = $kelasIdSiswa     ?? null;
    $tanggalSiswa     = $tanggalSiswa     ?? date('Y-m-d');
    $siswaList        = $siswaList        ?? collect();
    $absensiHariSiswa = $absensiHariSiswa ?? collect();
    $ringkasanSiswa   = $ringkasanSiswa   ?? ['hadir'=>0,'sakit'=>0,'izin'=>0,'alpha'=>0];
    $selectedKelasSiswa = $kelasListSiswa->firstWhere('id', $kelasIdSiswa);
    $activeTab = request('tab', 'guru'); // default tab guru
?>

<div class="container-fluid px-0">

    
    <div class="ag-tabs">
        <a href="<?php echo e(route('admin.absensi-guru.index', ['tab' => 'guru'] + request()->except('tab'))); ?>"
           class="ag-tab <?php echo e($activeTab === 'guru' ? 'active' : ''); ?>">
            <i class="bi bi-person-badge-fill"></i>
            Absensi Guru
        </a>
        <a href="<?php echo e(route('admin.absensi-guru.index', ['tab' => 'siswa'] + request()->except('tab'))); ?>"
           class="ag-tab <?php echo e($activeTab === 'siswa' ? 'active' : ''); ?>">
            <i class="bi bi-people-fill"></i>
            Absensi Siswa
        </a>
    </div>

    
    <div class="tab-content <?php echo e($activeTab === 'guru' ? 'active' : ''); ?>" id="tab-guru">

        
        <div class="ag-header">
            <h5 class="ag-title">
                <i class="bi bi-calendar-check-fill"></i>
                Absensi Guru
                <span class="sub">— <?php echo e($bulanList[$bulan]); ?> <?php echo e($tahun); ?></span>
            </h5>

            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <span class="ag-viewonly-badge">
                    <i class="bi bi-eye-fill"></i> Mode Lihat Saja
                </span>
                <a href="<?php echo e(route('admin.perizinan.index')); ?>" class="ag-btn-kelola">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    Perizinan
                </a>
                <a href="<?php echo e(route('admin.absensi-guru.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun])); ?>"
                   class="ag-btn-kelola" style="background:#ef4444; border-color:#ef4444; color:white;">
                    <i class="bi bi-file-pdf-fill"></i>
                    PDF
                </a>
                <a href="<?php echo e(route('admin.absensi-guru.export-excel', ['bulan' => $bulan, 'tahun' => $tahun])); ?>"
                   class="ag-btn-kelola" style="background:#16a34a; border-color:#16a34a; color:white;">
                    <i class="bi bi-file-earmark-excel-fill"></i>
                    Excel
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success py-2 px-3 mb-2" style="font-size:12px;border-radius:8px;">
                <i class="bi bi-check-circle-fill me-1"></i><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:8px 12px;margin-bottom:10px;font-size:11.5px;color:#1e40af;display:flex;align-items:center;gap:7px;">
            <i class="bi bi-info-circle-fill" style="color:#3b82f6;flex-shrink:0;"></i>
            <span>
                Data absensi di halaman ini <strong>hanya diisi oleh guru</strong> melalui menu Absensi Kehadiran (Foto) pada dashboard masing-masing.
                Admin tidak dapat mengubah atau menghapus data absensi — klik sel untuk melihat bukti foto.
            </span>
        </div>

        <?php if($tanpaProfil > 0): ?>
            <div style="background:#fefce8;border:1px solid #fde68a;border-radius:8px;padding:8px 12px;margin-bottom:10px;font-size:11.5px;color:#92400e;display:flex;align-items:center;gap:7px;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#d97706;flex-shrink:0;"></i>
                <span>
                    <strong><?php echo e($tanpaProfil); ?> guru</strong> belum punya profil — sel absensinya tidak dapat ditampilkan.
                    <a href="<?php echo e(route('admin.users.index', ['tab'=>'guru'])); ?>" style="color:#4f46e5;font-weight:600;">→ Lengkapi profil</a>
                </span>
            </div>
        <?php endif; ?>

        
        <form method="GET" action="<?php echo e(route('admin.absensi-guru.index')); ?>" class="ag-filter">
            <input type="hidden" name="tab" value="guru">
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
                            <?php
                                $abs = ($gid && isset($absensiData[$gid][$d])) ? $absensiData[$gid][$d] : null;
                                $hasFoto = $abs && !empty($abs->foto_masuk);
                                $fotoMasukUrl  = $hasFoto ? Storage::url($abs->foto_masuk) : null;
                                $fotoPulangUrl = ($abs && !empty($abs->foto_pulang)) ? Storage::url($abs->foto_pulang) : null;
                                $jamMasuk  = $abs->jam_masuk  ?? null;
                                $jamPulang = $abs->jam_pulang ?? null;
                                $tipeAbsen = $abs->tipe_absensi ?? null;
                                $kelasNama = $abs?->kelas?->nama ?? null;
                            ?>
                            <td id="ag-c-<?php echo e($gid??'u'.$user->id); ?>-<?php echo e($d); ?>"
                                class="<?php echo e($todayDay===$d?'ag-td':''); ?>">
                                <?php if(!$gid): ?>
                                    <span style="color:#f1f5f9;font-size:10px;" title="Profil belum ada">·</span>
                                <?php elseif($abs): ?>
                                    <button class="ag-b ag-b-<?php echo e($abs->status); ?>"
                                        onclick="agV(<?php echo e($gid); ?>,<?php echo e($d); ?>,'<?php echo e($abs->status); ?>',<?php echo \Illuminate\Support\Js::from($namaTampil)->toHtml() ?>,<?php echo e($hasFoto ? 'true' : 'false'); ?>,<?php echo \Illuminate\Support\Js::from($fotoMasukUrl)->toHtml() ?>,<?php echo \Illuminate\Support\Js::from($fotoPulangUrl)->toHtml() ?>,<?php echo \Illuminate\Support\Js::from($jamMasuk)->toHtml() ?>,<?php echo \Illuminate\Support\Js::from($jamPulang)->toHtml() ?>,<?php echo \Illuminate\Support\Js::from($tipeAbsen)->toHtml() ?>,<?php echo \Illuminate\Support\Js::from($kelasNama)->toHtml() ?>)"
                                        title="<?php echo e($namaTampil); ?> · <?php echo e($d); ?> <?php echo e($bulanList[$bulan]); ?><?php echo e($hasFoto ? ' · Ada foto absensi' : ''); ?>">
                                        <?php echo e($abs->status); ?>

                                        <?php if($hasFoto): ?>
                                            <span class="ag-cam-dot"><i class="bi bi-camera-fill"></i></span>
                                        <?php endif; ?>
                                    </button>
                                <?php else: ?>
                                    <span style="color:#f1f5f9;font-size:10px;" title="Belum absen">—</span>
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
                <span class="ag-li"><span class="ag-ld" style="background:#eef2ff;color:#4f46e5;"><i class="bi bi-camera-fill" style="font-size:8px;"></i></span>Absen via Foto</span>
                <span style="margin-left:auto;font-size:10px;color:#cbd5e1;"><i class="bi bi-info-circle me-1"></i>Klik sel untuk melihat bukti foto</span>
            </div>
        </div>
    </div>

    
    <div class="tab-content <?php echo e($activeTab === 'siswa' ? 'active' : ''); ?>" id="tab-siswa">

        <div class="ag-header">
            <h5 class="ag-title">
                <i class="bi bi-clipboard2-pulse-fill"></i>
                Absensi Siswa
                <span class="sub">— Rekap Kehadiran per Kelas</span>
            </h5>
            <span class="ag-viewonly-badge">
                <i class="bi bi-eye-fill"></i> Mode Lihat Saja
            </span>
        </div>

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:8px 12px;margin-bottom:10px;font-size:11.5px;color:#1e40af;display:flex;align-items:center;gap:7px;">
            <i class="bi bi-info-circle-fill" style="color:#3b82f6;flex-shrink:0;"></i>
            <span>
                Halaman ini menampilkan <strong>rekap kehadiran siswa</strong> yang diisi oleh guru.
                Pilih kelas dan tanggal untuk melihat daftar siswa beserta status kehadirannya.
            </span>
        </div>

        
        <form method="GET" action="<?php echo e(route('admin.absensi-guru.index')); ?>" class="ag-filter">
            <input type="hidden" name="tab" value="siswa">
            <label><i class="bi bi-calendar3 me-1"></i>Tanggal:</label>
            <input type="date" name="tanggal" value="<?php echo e($tanggalSiswa); ?>" max="<?php echo e(date('Y-m-d')); ?>">
            
            <label><i class="bi bi-mortarboard me-1"></i>Kelas:</label>
            <select name="kelas_id">
                <option value="">— Pilih Kelas —</option>
                <?php $__currentLoopData = $kelasListSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($kelas->id); ?>" <?php echo e($kelasIdSiswa == $kelas->id ? 'selected' : ''); ?>>
                        <?php echo e($kelas->nama); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="ag-btn-filter">
                <i class="bi bi-funnel-fill"></i> Tampilkan
            </button>
        </form>

        
        <?php if($kelasIdSiswa && $siswaList->count()): ?>
            <div class="ag-stats">
                <div class="ag-stat"><span class="ag-dot" style="background:#7c3aed;"></span><div><div class="ag-val"><?php echo e($siswaList->count()); ?></div><div class="ag-lbl">Total Siswa</div></div></div>
                <div class="ag-stat"><span class="ag-dot" style="background:#15803d;"></span><div><div class="ag-val"><?php echo e($ringkasanSiswa['hadir'] ?? 0); ?></div><div class="ag-lbl">Hadir</div></div></div>
                <div class="ag-stat"><span class="ag-dot" style="background:#a16207;"></span><div><div class="ag-val"><?php echo e($ringkasanSiswa['sakit'] ?? 0); ?></div><div class="ag-lbl">Sakit</div></div></div>
                <div class="ag-stat"><span class="ag-dot" style="background:#0369a1;"></span><div><div class="ag-val"><?php echo e($ringkasanSiswa['izin'] ?? 0); ?></div><div class="ag-lbl">Izin</div></div></div>
                <div class="ag-stat"><span class="ag-dot" style="background:#b91c1c;"></span><div><div class="ag-val"><?php echo e($ringkasanSiswa['alpha'] ?? 0); ?></div><div class="ag-lbl">Alpha</div></div></div>
            </div>
        <?php endif; ?>

        
        <div class="ag-wrap">
            <div style="padding:10px 14px;border-bottom:1px solid #f1f5f9;background:#fafbff;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <div style="font-size:12px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-list-check" style="color:#4f46e5;"></i>
                    Daftar Siswa
                    <?php if($selectedKelasSiswa): ?>
                        <span style="background:#e0e7ff;color:#4338ca;border-radius:5px;padding:2px 8px;font-size:10px;font-weight:700;">
                            <?php echo e($selectedKelasSiswa->nama); ?>

                        </span>
                    <?php endif; ?>
                </div>
                <?php if($kelasIdSiswa): ?>
                    <span style="font-size:11px;color:#64748b;">
                        <?php echo e(\Carbon\Carbon::parse($tanggalSiswa)->locale('id')->isoFormat('dddd, D MMMM Y')); ?>

                    </span>
                <?php endif; ?>
            </div>

            <?php if(!$kelasIdSiswa): ?>
                <div class="ag-empty">
                    <i class="bi bi-mortarboard"></i>
                    <p>Pilih <strong>kelas</strong> dan <strong>tanggal</strong> di atas untuk melihat rekap kehadiran siswa.</p>
                </div>
            <?php elseif($siswaList->count() === 0): ?>
                <div class="ag-empty">
                    <i class="bi bi-person-x"></i>
                    <p>Kelas ini belum memiliki siswa yang terdaftar.</p>
                </div>
            <?php else: ?>
                <div class="as-siswa-list">
                    <?php $__currentLoopData = $siswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $existing    = $absensiHariSiswa->get($siswa->id);
                            $statusSaved = $existing ? $existing->status : null;
                            $ketSaved    = $existing ? ($existing->keterangan ?? '') : '';
                            $namaTampil  = $siswa->nama ?: ($siswa->user?->name ?? '—');
                            $nis         = $siswa->nis ?? null;
                            $fotoUrl     = ($siswa->user && $siswa->user->photo)
                                           ? Storage::url($siswa->user->photo) : null;
                            $inisial     = strtoupper(mb_substr($namaTampil, 0, 1));

                            $statusClass = match($statusSaved) {
                                'hadir' => 'as-status-hadir',
                                'sakit' => 'as-status-sakit',
                                'izin'  => 'as-status-izin',
                                'alpha' => 'as-status-alpha',
                                default => 'as-status-belum',
                            };
                            $statusLabel = match($statusSaved) {
                                'hadir' => 'Hadir',
                                'sakit' => 'Sakit',
                                'izin'  => 'Izin',
                                'alpha' => 'Alpha',
                                default => 'Belum diisi',
                            };
                        ?>

                        <div class="as-siswa-row">
                            <span class="as-no"><?php echo e($i + 1); ?></span>

                            <div class="as-av">
                                <?php if($fotoUrl): ?>
                                    <img src="<?php echo e($fotoUrl); ?>" alt="<?php echo e($namaTampil); ?>">
                                <?php else: ?>
                                    <?php echo e($inisial); ?>

                                <?php endif; ?>
                            </div>

                            <div class="as-info">
                                <div class="as-nama"><?php echo e($namaTampil); ?></div>
                                <?php if($nis): ?>
                                    <div class="as-nis"><i class="bi bi-person-badge" style="font-size:0.5rem;"></i> <?php echo e($nis); ?></div>
                                <?php endif; ?>
                                <?php if($ketSaved): ?>
                                    <div class="as-ket-text" title="<?php echo e($ketSaved); ?>"><?php echo e($ketSaved); ?></div>
                                <?php endif; ?>
                            </div>

                            <span class="as-status-badge <?php echo e($statusClass); ?>">
                                <?php if($statusSaved === 'hadir'): ?> <i class="bi bi-check2-circle"></i>
                                <?php elseif($statusSaved === 'sakit'): ?> <i class="bi bi-thermometer-half"></i>
                                <?php elseif($statusSaved === 'izin'): ?> <i class="bi bi-envelope-check"></i>
                                <?php elseif($statusSaved === 'alpha'): ?> <i class="bi bi-x-circle"></i>
                                <?php else: ?> <i class="bi bi-dash-circle"></i>
                                <?php endif; ?>
                                <?php echo e($statusLabel); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>


<div class="ag-mov" id="agModal" onclick="if(event.target.id==='agModal')agMC()">
    <div class="ag-mbox" id="agMbox">
        <div class="ag-mh">
            <div class="ag-mav" id="agMav">G</div>
            <div><div class="ag-mn" id="agMn">—</div><div class="ag-ms" id="agMs">—</div></div>
        </div>

        <div class="ag-photo-block" id="agPhotoBlock" style="display:none;">
            <span class="ag-photo-badge" id="agPhotoBadge"><i class="bi bi-camera-fill"></i> Absen via Foto</span>
            <div class="ag-photo-grid" id="agPhotoGrid"></div>
        </div>

        <div class="ag-no-photo" id="agNoPhoto" style="display:none;">
            <i class="bi bi-camera"></i>
            Tidak ada foto untuk absensi ini.
        </div>

        <div class="ag-ma">
            <button class="ag-mb ag-mb-c" style="flex:1;" onclick="agMC()">
                <i class="bi bi-x"></i> Tutup
            </button>
        </div>
    </div>
</div>


<div class="ag-lightbox" id="agLightbox" onclick="agLbC()">
    <img src="" id="agLbImg" alt="">
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const AG_B = <?php echo e((int)$bulan); ?>, AG_Y = <?php echo e((int)$tahun); ?>;
const AG_BN = <?php echo \Illuminate\Support\Js::from($bulanList[$bulan] ?? '')->toHtml() ?>;

/* ── Pencarian nama guru di tabel ── */
function agFil(v){
    const q = v.toLowerCase().trim();
    document.querySelectorAll('.ag-row').forEach(r=>{
        r.style.display = (!q || r.dataset.nama.includes(q)) ? '' : 'none';
    });
}

/**
 * Admin HANYA melihat data absensi guru (view-only).
 */
function agV(gid, hari, status, nama, hasFoto, fotoMasuk, fotoPulang, jamMasuk, jamPulang, tipeAbsen, kelasNama){
    document.getElementById('agMav').textContent = nama.charAt(0).toUpperCase();
    document.getElementById('agMn').textContent  = nama;
    document.getElementById('agMs').textContent  = `${hari} ${AG_BN} ${AG_Y} · Status: ${status}`;

    const photoBlock = document.getElementById('agPhotoBlock');
    const photoGrid  = document.getElementById('agPhotoGrid');
    const badge      = document.getElementById('agPhotoBadge');
    const noPhoto    = document.getElementById('agNoPhoto');
    const mbox       = document.getElementById('agMbox');

    if (hasFoto) {
        mbox.classList.add('ag-mbox-wide');
        photoBlock.style.display = 'block';
        noPhoto.style.display    = 'none';

        let labelTipe = tipeAbsen === 'kantor' ? 'Absen di Kantor' : 'Absen Mengajar';
        if (tipeAbsen !== 'kantor' && kelasNama) {
            labelTipe += ` · ${kelasNama}`;
        }
        badge.innerHTML = `<i class="bi bi-camera-fill"></i> ${labelTipe}`;

        let html = `<div class="ag-photo-item">
                        <img src="${fotoMasuk}" onclick="agLbO('${fotoMasuk}')">
                        <div class="lbl">${tipeAbsen === 'kantor' ? 'Foto Kehadiran' : 'Foto Masuk'}${jamMasuk ? ' · ' + jamMasuk.substring(0,5) : ''}</div>
                    </div>`;

        if (tipeAbsen !== 'kantor') {
            if (fotoPulang) {
                html += `<div class="ag-photo-item">
                            <img src="${fotoPulang}" onclick="agLbO('${fotoPulang}')">
                            <div class="lbl">Foto Pulang${jamPulang ? ' · ' + jamPulang.substring(0,5) : ''}</div>
                        </div>`;
            } else {
                html += `<div class="ag-photo-item">
                            <div class="ag-photo-empty">Belum upload<br>foto pulang</div>
                            <div class="lbl">Foto Pulang</div>
                        </div>`;
            }
        }

        photoGrid.className = 'ag-photo-grid' + (tipeAbsen === 'kantor' ? ' single' : '');
        photoGrid.innerHTML = html;
    } else {
        mbox.classList.remove('ag-mbox-wide');
        photoBlock.style.display = 'none';
        photoGrid.innerHTML = '';
        noPhoto.style.display = 'block';
    }

    document.getElementById('agModal').classList.add('show');
}

function agMC(){
    document.getElementById('agModal').classList.remove('show');
}

/* ── Lightbox zoom foto ── */
function agLbO(url){
    document.getElementById('agLbImg').src = url;
    document.getElementById('agLightbox').classList.add('show');
}
function agLbC(){
    document.getElementById('agLightbox').classList.remove('show');
}

/* ── Auto-scroll ke kolom hari ini saat halaman dimuat ── */
document.addEventListener('DOMContentLoaded', () => {
    const th = document.querySelector('.ag-tbl thead th.ag-td');
    if (th) {
        const sc = document.getElementById('agScroll');
        if (sc) sc.scrollLeft = Math.max(0, th.offsetLeft - 240);
    }
});

/* ── Tutup modal / lightbox dengan tombol Escape ── */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        agMC();
        agLbC();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\smpn-kutime\resources\views/admin/absensi-guru/index.blade.php ENDPATH**/ ?>